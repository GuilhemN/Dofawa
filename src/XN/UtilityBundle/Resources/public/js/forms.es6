var formHooks = { };
var formScriptControls = { };

function isThisSubformPA(form) {
	return function () {
		var closest = jQuery(this).closest('form, .subform');
		return closest.length == 1 && closest[0] === form;
	};
}

function shouldUseReadOnlyAttribute(type) {
	return type == 'text' || type == 'number' || type == 'password' || type == 'textarea';
}

function isReadOnly(form) {
	return jQuery(form).hasClass('rdonly');
}
function setReadOnly(form, ro) {
	var $form = jQuery(form);
	if ($form.hasClass('rdonly') == ro)
		return;
	$form[ro ? 'addClass' : 'removeClass']('rdonly');
	var isThisSubform = isThisSubformPA(form);
	jQuery('input, textarea, select', form).filter(isThisSubform).each(function () {
		propagateReadOnly(this, ro);
	});
	jQuery('.subform input[type="button"], .subform input[type="submit"], .subform input[type="reset"]', form).each(function () {
		if (ro) {
			this.setAttribute('data-readonly', this.disabled ? 'yes' : 'no');
			this.disabled = true;
		} else {
			this.disabled = this.getAttribute('data-readonly') == 'yes';
			this.removeAttribute('data-readonly');
		}
	});
	jQuery('.modify', form).filter(isThisSubform)[ro ? 'removeClass' : 'addClass']('hidden');
	jQuery('.stop-modify, input[type="submit"], input[type="reset"]', form).filter(isThisSubform)[ro ? 'addClass' : 'removeClass']('hidden');
	if (ro)
		jQuery('.subform', form).each(function () { setReadOnly(this, true); });
}
function propagateReadOnly(ctl, ro) {
	if (typeof ro == 'undefined')
		ro = true;
	var type = ctl.nodeName.toLowerCase();
	if (type == 'input')
		type = ctl.type.toLowerCase();
	if (type == 'button' || type == 'submit' || type == 'reset')
		return;
	var key = shouldUseReadOnlyAttribute(type) ? 'readOnly' : 'disabled';
	if (ro) {
		ctl.setAttribute('data-readonly', ctl[key] ? 'yes' : 'no');
		ctl[key] = true;
	} else {
		ctl[key] = ctl.getAttribute('data-readonly') == 'yes';
		ctl.removeAttribute('data-readonly');
	}
}

function updateParentCheckbox($ul) {
	if ($ul.hasClass('tree'))
		return;
	var $li = $ul.closest('li');
	var indet = false, check = null;
	jQuery('> ul > li > label > input[type="checkbox"]', $li).each(function () {
		if (indet)
			return;
		if (this.indeterminate)
			indet = true;
		else if (this.checked !== check) {
			if (check === null)
				check = this.checked;
			else
				indet = true;
		}
	});
	var box = jQuery('> label > input[type="checkbox"]', $li)[0];
	if (indet) {
		box.indeterminate = true;
		box.checked = false;
	} else {
		box.indeterminate = false;
		box.checked = (check === null) ? false : check;
	}
	updateParentCheckbox($li.closest('ul'));
}

function getHooks(form) {
	if (typeof form != 'string')
		form = form.hasAttribute('data-hook-id') ? form.getAttribute('data-hook-id') : form.id;
	if (!form)
		return emptyObject;
	if (!(form in formHooks))
		formHooks[form] = { };
	return formHooks[form];
}

function callHook(form, hook) {
	var hooks = getHooks(form);
	if (hooks[hook])
		return hooks[hook].apply(form, Array.prototype.slice.call(arguments, 2));
}
function getErrors(field) {
	var errors = [ ];
	var minLength = field.hasAttribute('data-min-length') ? parseInt(field.getAttribute('data-min-length')) : 0;
	var maxLength = field.hasAttribute('data-max-length') ? parseInt(field.getAttribute('data-max-length')) : Infinity;

	if (maxLength != maxLength)
		maxLength = Infinity;
	var pattern = field.hasAttribute('data-pattern') ? new RegExp(field.getAttribute('data-pattern'), field.hasAttribute('data-pattern-flags') ? field.getAttribute('data-pattern-flags') : '') : null;
	if (minLength > 0 || maxLength < Infinity || pattern !== null) {
		var value = field.value;
		if (value.length < minLength)
			errors.push('doit contenir au moins ' + minLength + ' caractères');
		else if (value.length > maxLength)
			errors.push('ne doit pas dépasser ' + maxLength + ' caractères');
		if (pattern !== null && !pattern.test(value))
			errors.push('est malformé(e)');
	}
	var requiredState = field.hasAttribute('data-required-state') ? field.getAttribute('data-required-state') : null;
	if (requiredState !== null) {
		var state = field.indeterminate ? 'indeterminate' : (field.checked ? 'checked' : 'unchecked');
		if (state != requiredState)
			errors.push('doit être ' + ((requiredState == 'indeterminate') ? 'indéterminé(e)' : ((requiredState == 'checked') ? 'coché(e)' : 'décoché(e)')));
	}
	return errors;
}
function validate(form, report, async) {
	var allErrors = { };
	var hasErrors = false;
	jQuery('input[name]', form).each(function () {
		var errors = getErrors(this);
		jQuery(this)[(report && errors.length > 0) ? 'addClass' : 'removeClass']('invalid');
		if (errors.length > 0) {
			if (this.name in allErrors)
				allErrors[this.name] = allErrors[this.name].concat(errors);
			else
				allErrors[this.name] = errors;
			hasErrors = true;
		}
	});
	var retval = callHook(form, 'validate', allErrors, report, async);
	if (typeof retval != 'undefined') {
		if (!async && isThenable(retval)) {
			if ('cancel' in retval)
				retval.cancel();
			return !hasErrors;
		}
		return retval;
	}
	return !hasErrors;
}
jQuery.fn.validate = function (report, async) {
	var forms = this.filter('form');
	var ok = true;
	var joiner = new ThenableJoiner();
	forms.each(function () {
		var result = validate(this, report, async);
		joiner.add(result);
		if (!result)
			ok = false;
	});
	if (joiner.isImmediate)
		return ok;
	if (ok)
		return remap(function (all) { return all.every(cBoolean); }, joiner.dangerousAll());
	else
		return revalue(false, joiner.dangerousAll());
}
jQuery.fn.autoValidate = function () {
	this.filter('form').on('submit', function () {
		return validate(this, true, false);
	}).each(function () {
		validate(this, false, true);
	});
	return this;
}

function setupForm(form) {
	var $form = jQuery(form);
	// Pour contourner une soi-disant "feature" de Firefox
	$form.filter('form.autocomplete[autocomplete="off"]').each(function () {
		this.autocomplete = 'on';
	});
	$form.filter('form.ajax').on('submit', function () {
		if (this.action) {
			runAsync.call(this, function* () {
				var hooks = getHooks(this);
				if ((yield validate(this, true, true)) === false)
					return;
				var method = (this.hasAttribute('data-method') ? this.getAttribute('data-method') : this.method).toUpperCase();
				if (method == 'DELETE') {
					if (!(yield dialogConfirm([ 'Voulez-vous vraiment supprimer ' + (this.hasAttribute('data-this-object') ? this.getAttribute('data-this-object') : 'cet objet') + ' ?', mkNode('br'), 'Cette opération est irréversible !' ], 'Suppression', 'Oui, supprimer', 'Non')))
						return;
				}
				if (hooks.preSubmit && hooks.preSubmit.call(this) === false)
					return;
				if (jQuery('.modify', this).filter(isThisSubformPA(this)).length)
					setReadOnly(this, true);
				var $this = jQuery(this);
				$this.addClass('submitting');
				var data, jqXHR;
				try {
					{ data, jqXHR } = yield ajax({
						url: this.action,
						data: JSON.stringify(yield exportData(this)),
					    contentType: 'application/json; charset=utf-8',
						type: method
					});
				} catch (e) {
					$this.removeClass('submitting');
					if (hooks.postSubmit)
						hooks.postSubmit.call(this, false, e.jqXHR, null);
					return;
				}
				$this.removeClass('submitting');
				switch (jqXHR.status) {
				case 200:
					var action = this.action;
					jQuery('form').each(function () {
						if (this.action == action)
							importData(this, data);
					});
					break;
				case 201:
					this.action = jqXHR.getResponseHeader('Location');
					importData(this, data);
					break;
				}
				if (hooks.postSubmit)
					hooks.postSubmit.call(this, true, jqXHR, data);
			});
		}
		return false;
	}).each(function () {
		validate(this, false, true);
	});

	$form.filter('form, .subform').each(function () {
		var isThisSubform = isThisSubformPA(this);
		var $modify = jQuery('.modify', this).filter(isThisSubform);
		if (!$modify.length)
			return;
		setReadOnly(this, true);
		$modify.on('click', bind(function () {
			setReadOnly(this, false);
		}, this));
		jQuery('.stop-modify', this).filter(isThisSubform).on('click', bind(function () {
			setReadOnly(this, true);
		}, this));
	});

	jQuery('.tree input[type="checkbox"]', $form).on('click', function () {
		if (this.indeterminate) {
			this.indeterminate = false;
			this.checked = true;
			return;
		}
		var $li = jQuery(this).closest('li');
		jQuery('input[type="checkbox"]', $li).each(this.checked ?
			function () { this.checked = true; } :
			function () { this.checked = false; });
		updateParentCheckbox($li.closest('ul'));
	});

	jQuery('[data-filter-selector]', $form).each(function () {
		var currentFilter = this.value;
		jQuery(this).on('keyup paste change', delayAndLimit(bind(function () {
			if (this.value != currentFilter) {
				currentFilter = this.value;
				filter = currentFilter.trim().toLowerCase().split(/\s+/g);
				var $targets = jQuery(this.getAttribute('data-filter-selector'));
				if (filter.length == 1 && !filter[0])
					$targets.removeClass('hidden');
				else
					$targets.each(function () {
						var text = getTextContent(this).toLowerCase();
						if (filter.every(function (word) {
								return text.indexOf(word) >= 0;
							}))
							jQuery(this).removeClass('hidden');
						else
							jQuery(this).addClass('hidden');
					});
			}
		}, this), 300));
	});
	jQuery('img[data-preview-for]', $form).each(function () {
		var $this = jQuery(this);
		var previewFor = $this.attr('data-preview-for');
		var $field = jQuery(elById(previewFor));
		var previewStyle = $this.attr('data-preview-style');
		var maxWidth = parseInt($this.attr('data-preview-max-width'));
		if (maxWidth !== maxWidth)
			maxWidth = Infinity;
		var maxHeight = parseInt($this.attr('data-preview-max-height'));
		if (maxHeight !== maxHeight)
			maxHeight = Infinity;
		var maxDim = new Dimension(maxWidth, maxHeight);
		var defaultStyle = $this.attr('style');
		var defaultSource = $this.attr('src');
		var defaultWidth = $this.attr('width');
		var defaultHeight = $this.attr('height');
		var currentImage = null;
		var showPreview = wrapAsync(function* () {
			var image;
	 		try {
	 			image = yield getImage(this);
	 		} catch (e) {

	 			$this.attr('style', defaultStyle);
	 			$this.attr('src', defaultSource);
	 			$this.attr('width', defaultWidth);
	 			$this.attr('height', defaultHeight);

		 		if (currentImage && 'cleanup' in currentImage)
		 			currentImage.cleanup();
		 		currentImage = null;

		 		return;
	 		}

	 		var dim = Dimension.fromElement(image).constrain(maxDim);

 			$this.attr('style', previewStyle);
	 		$this.attr('src', image.src);
	 		$this.attr('width', dim.width);
	 		$this.attr('height', dim.height);

	 		if (currentImage && 'cleanup' in currentImage)
	 			currentImage.cleanup();
	 		currentImage = image;
		});
		$field.on('change', showPreview);
		showPreview.call($field[0]);
	});
}

jQuery(function () {
	setupForm(jQuery('form, .subform'));
});

var exportData = wrapAsync(function* exportData(form, data) {
	if (!data)
		data = { };
	if (!form)
		return data;
	var $form = yield jQuery(form).filterAsync(wrapAsync(function* () {
		return (yield callHook(this, 'preExport', data)) !== false;
	}));
	if ($form.length == 0)
		return data;
	jQuery('input[name], select[name], textarea[name]', $form).each(function () {
		switch (this.nodeName.toLowerCase()) {
		case 'input':
			switch ((this.hasAttribute('type') ? this.getAttribute('type') : 'text').toLowerCase()) {
			case 'text':
			case 'password':
				setProperty(data, this.name, this.value);
				break;
			case 'hidden':
				if (jQuery(this).hasClass('json'))
					setProperty(data, this.name, JSON.parse(this.value));
				else
					setProperty(data, this.name, this.value);
				break;
			case 'number':
				var numVal;
				if (this.value.length == 0)
					numVal = null;
				else
					numVal = parseFloat(this.value);
				if (numVal != numVal)
					numVal = this.value;
				setProperty(data, this.name, numVal);
				break;
			case 'radio':
				if (this.checked)
					setProperty(data, this.name, this.value);
				else if (!hasProperty(data, this.name))
					setProperty(data, this.name, null);
				break;
			case 'checkbox':
				var name = this.name;
				var nameMarker = name.length - 2;
				if (nameMarker >= 0 && name.substring(nameMarker) == '[]') {
					name = name.substring(0, nameMarker);
					var array;
					if (hasProperty(data, name))
						array = getProperty(data, name);
					else
						setProperty(data, name, (array = [ ]));
					if (this.checked) {
						var idx = binarySearch(array, this.value);
						if (idx < 0)
							array.splice(~idx, 0, this.value);
					}
				} else
					setProperty(data, name, this.checked);
				break;
			case 'date':
				var date;
				if (this.type == 'date')
					date = new Date(this.value);
				else
					date = parseLocaleDate(this.value);
				setProperty(data, this.name, date.toISOString());
				break;
			case 'datetime-local':
				var date;
				if (this.type == 'datetime-local')
					date = new Date(this.value);
				else
					date = parseLocaleDate(this.value);
				setProperty(data, this.name, date.toISOString());
				break;
			case 'button':
			case 'submit':
			case 'reset':
			case 'image':
				// skip
				break;
			default:
				console.warn('Unknown field type : ' + (this.hasAttribute('type') ? this.getAttribute('type') : 'text') + ', taking default action (result may not be the same as expected)');
				setProperty(data, this.name, this.value);
				break;
			}
			break;
		case 'select':
			if (this.multiple)
				console.warn('Multiple selects not supported (for now), treating like a simple select (result may not be the same as expected)');
			if (this.selectedIndex == -1)
				setProperty(data, this.name, null);
			else
				setProperty(data, this.name, this.value);
			break;
		case 'textarea':
			if (jQuery(this).hasClass('json'))
				setProperty(data, this.name, JSON.parse(this.value));
			else
				setProperty(data, this.name, this.value);
			break;
		}
	});
	yield $form.eachAsync(wrapAsync(function* (form) {
		if (this.id in formScriptControls) {
			var controls = formScriptControls[this.id];
			if (!controls)
				return;
			var joiner = new ThenableJoiner();
			for (var name in controls) {
				var control = controls[name];
				if (!control)
					continue;
				if ('getValue' in control) {
					value = control.getValue();
					if (isThenable(value)) {
						joiner.add(remap(function (value) { setProperty(data, name, value); }, value));
					} else
						setProperty(data, name, value);
				} else if ('value' in control)
					setProperty(data, name, control.value);
			}
			return joiner.join();
		}
	}));
	yield $form.eachAsync(function () {
		return callHook(this, 'postExport', data);
	});

	return data;
});

var importData = wrapAsync(function* importData(form, data, force) {
	if (!form)
		return data;
	var $form = yield jQuery(form).filterAsync(wrapAsync(function* () {
		return (yield callHook(this, 'preImport', data, force)) !== false;
	}));
	jQuery('input[name], select[name], textarea[name]', $form).each(function () {
		switch (this.nodeName.toLowerCase()) {
		case 'input':
			switch ((this.hasAttribute('type') ? this.getAttribute('type') : 'text').toLowerCase()) {
			case 'text':
			case 'password':
				if (hasProperty(data, this.name) && getProperty(data, this.name) !== null)
					this.value = getProperty(data, this.name);
				else if (force)
					this.value = '';
				break;
			case 'hidden':
				if (hasProperty(data, this.name) && getProperty(data, this.name) !== null) {
					if (jQuery(this).hasClass('json'))
						this.value = JSON.stringify(getProperty(data, this.name));
					else
						this.value = getProperty(data, this.name);
				} else if (force)
					this.value = jQuery(this).hasClass('json') ? 'null' : '';
				break;
			case 'number':
				if (hasProperty(data, this.name) && getProperty(data, this.name) !== null)
					this.value = getProperty(data, this.name);
				else if (force)
					this.value = this.hasAttribute('min') ? this.getAttribute('min') : 0;
				break;
			case 'radio':
				if (hasProperty(data, this.name))
					this.checked = getProperty(data, this.name) == this.value;
				else if (force)
					this.checked = false;
				break;
			case 'checkbox':
				var name = this.name;
				var nameMarker = name.length - 2;
				if (nameMarker >= 0 && name.substring(nameMarker) == '[]') {
					name = name.substring(0, nameMarker);
					if (hasProperty(data, name))
						this.checked = getProperty(data, name).indexOf(this.value) >= 0;
					else if (force)
						this.checked = false;
				} else if (hasProperty(data, name))
					this.checked = parseBoolean(getProperty(data, name));
				else if (force)
					this.checked = false;
				break;
			case 'date':
				if (hasProperty(data, this.name) && getProperty(data, this.name) !== null) {
					var date = getProperty(data, this.name);
					if (typeof date != 'object')
						date = new Date(date);
					if (this.type == 'date')
						this.value = date.toISOString().substring(0, 10);
					else
						this.value = date.toLocaleDateString();
				} else if (force)
					this.value = '';
				break;
			case 'datetime-local':
				if (hasProperty(data, this.name) && getProperty(data, this.name) !== null) {
					var date = getProperty(data, this.name);
					if (typeof date != 'object')
						date = new Date(date);
					if (this.type == 'date')
						this.value = date.toISOString().substring(0, 19);
					else
						this.value = date.toLocaleString();
				} else if (force)
					this.value = '';
				break;
			case 'button':
			case 'submit':
			case 'reset':
			case 'image':
				// skip
				break;
			default:
				console.warn('Unknown field type : ' + (this.hasAttribute('type') ? this.getAttribute('type') : 'text') + ', taking default action (result may not be the same as expected)');
				if (hasProperty(data, this.name) && getProperty(data, this.name) !== null)
					this.value = getProperty(data, this.name);
				else if (force)
					this.value = '';
				break;
			}
			break;
		case 'select':
			if (this.multiple)
				console.warn('Multiple selects not supported (for now), treating like a simple select (result may not be the same as expected)');
			if (hasProperty(data, this.name) && getProperty(data, this.name) !== null)
				this.value = getProperty(data, this.name);
			else if (force)
				this.selectedIndex = -1;
			break;
		case 'textarea':
			if (hasProperty(data, this.name) && getProperty(data, this.name) !== null)
				this.value = getProperty(data, this.name);
			else if (force)
				this.value = '';
			break;
		}
	});
	jQuery('.output[data-name]', $form).each(function () {
		var value = getProperty(data, this.getAttribute('data-name'));
		if (typeof value == 'undefined')
			setTextContent(this, '');
		else {
			switch (this.getAttribute('data-type')) {
			default:
				if (typeof value == 'object')
					setTextContent(this, JSON.stringify(value));
				else
					setTextContent(this, value);
			}
		}
	});
	jQuery('.tree ul', $form).reverse().each(function () {
		updateParentCheckbox(jQuery(this));
	});
	yield $form.eachAsync(wrapAsync(function* () {
		if (this.id in formScriptControls) {
			var controls = formScriptControls[this.id];
			if (!controls)
				return;
			var joiner = new ThenableJoiner();
			for (var name in controls) {
				var control = controls[name];
				if (!control)
					continue;
				if ('setValue' in control) {
					if (hasProperty(data, name))
						joiner.add(control.setValue(getProperty(data, name)));
					else if (force)
						joiner.add(control.setValue(null));
				} else if ('value' in control) {
					if (hasProperty(data, name))
						control.value = getProperty(data, name);
					else if (force)
						control.value = null;
				}
			}
			return joiner.join();
		}
	}));
	yield $form.eachAsync(wrapAsync(function* () {
		yield callHook(this, 'postImport', data, force);
		return validate(this, false, true);
	}));
	return data;
});

function clearData(form) {
	return importData(form, { }, true);
}
var syncData = wrapAsync(function* syncData(form) {
	return importData(form, yield exportData(form));
});

var bindToEntity = wrapAsync(function* bindToEntity(form, url, init, force, data) {
	url = toAbsoluteURL(url);
	var $form = jQuery(form).filter('.ajax');
	force = cBoolean(force);
	if (!force)
		$form = $form.filter(function () {
			return this.action != url;
		});
	$form = yield $form.filterAsync(wrapAsync(function* () {
		return (yield callHook(this, 'preBind', url, init, force, data)) !== false;
	}));
	if ($form.length == 0)
		return null;
	$form.each(function () {
		this.action = url;
	});
	if (init == 'asis') {
		yield $form.eachAsync(function () {
			return callHook(this, 'postBind', url, init, force, true, null);
		});
		return null;
	} else if (init == 'custom') {
		yield importData(form, data, true);
		yield $form.eachAsync(function () {
			return callHook(this, 'postBind', url, init, force, true, data);
		});
		return data;
	}
	yield clearData(form);
	if (init == 'clear' || !url) {
		yield $form.eachAsync(function () {
			return callHook(this, 'postBind', url, init, force, true, { });
		});
		return { };
	}
	$form.addClass('loading');
	try {
		data = yield get(url);
	} catch (e) {
		$form.removeClass('loading');
		yield $form.eachAsync(function () {
			return callHook(this, 'postBind', url, init, force, false, null);
		});
		return null;
	}
	$form.removeClass('loading');
	yield importData(form, data);
	yield $form.eachAsync(function () {
		return callHook(this, 'postBind', url, init, force, true, data);
	});
	return data;
});
