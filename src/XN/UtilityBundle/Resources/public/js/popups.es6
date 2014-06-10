(function () {
	var preventUserHidePopup = true;
	var hideResolve = null, hideReject = null;
	
	this.hidePopup = function hidePopup(useCancelSemantics) {
		var previousResolve = hideResolve, previousReject = hideReject;
		jQuery('#popup-overlay').removeClass('visible');
		jQuery('.popup').removeClass('visible');
		preventUserHidePopup = true;
		hideResolve = null;
		hideReject = null;
		if (useCancelSemantics === true) {
			if (previousReject)
				previousReject(new Error('Opération annulée'));
		} else {
			if (previousResolve)
				previousResolve(undefined);
		}
	}
	
	this.showPopup = function showPopup(id, preventCancel) {
		var previousReject = hideReject;
		jQuery('#popup-overlay').addClass('visible');
		jQuery('.popup').removeClass('visible');
		if (typeof id != 'object')
			id = elById(id);
		jQuery(id).addClass('visible');
		preventUserHidePopup = cBoolean(preventCancel);
		hideResolve = null;
		hideReject = null;
		if (previousReject)
			previousReject(new Error('Opération annulée'));
		id.focus();
		return new Promise(function (resolve, reject) {
			hideResolve = resolve;
			hideReject = reject;
			this.cancel = function () {
				if (hideReject === reject)
					hidePopup(true);
			};
		});
	}
	
	jQuery(function () {
		jQuery(document).on('keypress', function (e) {
			if (e.keyCode == 27 && !preventUserHidePopup)
				hidePopup(true);
		});
		jQuery('#popup-overlay, .popup-hide').on('click', function () {
			if (!preventUserHidePopup)
				hidePopup(true);
		});
		jQuery('.popup-show').on('click', function () {
			showPopup(this.getAttribute('data-popup-id'));
		});
	});
}).call(window);

var dialog = wrapAsync(function* dialog(options) {
	var node = mkNode('form', null, {
		id: 'task-dialog',
		className: 'measuring popup'
	}, null, {
		'submit': function () { return false; }
	});
	if ('className' in options)
		node.className += ' ' + options;
	if ('title' in options)
		node.appendChild(mkNode('h2', null, null, cArray(options.title)));
	if ('label' in options)
		cArray(options.label).forEach(function (part) { node.appendChild(mkNode('p', null, null, cArray(part))); });
	if ('fields' in options) {
		options.fields.forEach(function (field) {
			var htField, first = false;
			switch (field.type) {
			case 'select':
				var selectedIndex = -1;
				htField = mkNode('select', {
					'name': field.name
				}, ('className' in field) ? {
					className: field.className
				} : null, field.options.map(function (option, i) {
					if (option.selected && selectedIndex == -1)
						selectedIndex = i;
					return new Option(option.text, option.value);
				}));
				htField.selectedIndex = selectedIndex;
				break;
			case 'radio':
			case 'checkbox':
				htField = mkNode('input', {
					'type': field.type,
					'name': field.name,
					'value': ('value' in field) ? field.value : ''
				}, ('className' in field) ? {
					className: field.className,
					checked: cBoolean(field.checked)
				} : {
					checked: cBoolean(field.checked)
				});
				first = true;
				break;
			default:
				htField = mkNode('input', {
					'type': ('type' in field) ? field.type : 'text',
					'name': field.name,
					'value': ('value' in field) ? field.value : ''
				}, ('className' in field) ? {
					className: field.className
				} : null);
				break;
			}
			if ('label' in field) {
				var labelSpan = mkNode('span', null, null, cArray(field.label));
				htField = mkNode('label', null, null, first ? [ htField, labelSpan ] : [ labelSpan, htField ]);
			} else {
				if (!first)
					jQuery(htField).addClass('block');
			}
			node.appendChild(htField);
		});
	}
	var proms;
	if ('buttons' in options) {
		var btnRow = null;
		var values = [ ];
		var buttons = options.buttons.map(function (button, i) {
			var htButton;
			if (typeof button == 'string')
				button = { block: false, label: button };
			else if (isArray(button))
				button = { block: true, label: button };
			if (('block' in button) ? button.block : isArray(button.label)) {
				htButton = mkNode('button', null, {
					className: 'block' + (('className' in button) ? (' ' + button.className) : '')
				}, button.label);
				node.appendChild(htButton);
			} else {
				htButton = mkNode('input', {
					'type': 'button',
					'value': button.label
				}, ('className' in button) ? { className: button.className } : null);
				if (btnRow === null) {
					btnRow = mkNode('div', null, {
						className: 'buttons'
					});
					node.appendChild(btnRow);
				} else
					btnRow.appendChild(mkText(' '));
				btnRow.appendChild(htButton);
			}
			if ('value' in button)
				values[i] = button.value;
			else
				values[i] = i;
			return htButton;
		});
		proms = buttons.map(function (button, i) {
			jQuery(button).on('click', hidePopup);
			return revalue(values[i], wait(button, 'click'));
		});
	} else
		proms = [ ];
	if ('defaultButton' in options)
		proms.push(revalue(options.defaultButton, wait(document, 'keypress', function (e) {
			if (e.keyCode == 13) {
				hidePopup();
				return true;
			} else
				return false;
		})));
	var cancelButton = null;
	if ('cancelButton' in options)
		cancelButton = options.cancelButton;
	if ('timeout' in options)
		proms.push(revalue(('timeoutButton' in options) ? options.timeoutButton : cancelButton, sleep(options.timeout)));
	if ('scriptControls' in options)
		formScriptControls['task-dialog'] = options.scriptControls;
	if ('data' in options)
		yield importData(node, options.data);
	document.body.appendChild(node);
	var dim;
	while ((dim = Dimension.fromElement(node)).isEmpty())
		yield sleep(0);
	node.style.width = dim.width + 'px';
	node.style.height = dim.height + 'px';
	node.style.marginLeft = '-' + (dim.width >> 1) + 'px';
	node.style.marginTop = '-' + (dim.height >> 1) + 'px';
	node.className = node.className.replace(/^measuring /, (dim.width < 380) ? "narrow " : "");
	var waitClose;
	proms.push(new Promise(function (resolve, reject) {
		waitClose = runAsync.call(this, function* () {
			var popup = showPopup(node, options.preventCancel);
			this.cancel = popup.cancel;
			try {
				yield popup;
			} catch (e) {
				resolve(cancelButton);
			}
			delete formScriptControls['task-dialog'];
			yield race([ wait(node, 'transitionend'), sleep(300) ]);
			document.body.removeChild(node);
		});
	}));
	var button = yield race(proms);
	yield waitClose;
	return exportData(node, { button: button });
});

function dialogAlert(label, title, button, className) {
	if (!button)
		button = 'OK';
	var options = {
		buttons: [ { value: null, block: isArray(button), label: button } ],
		defaultButton: null,
		cancelButton: null
	};
	if (label)
		options.label = label;
	if (title)
		options.title = title;
	if (className)
		options.className = className;
	return revalue(undefined, dialog(options));
}

function dialogConfirm(label, title, trueLabel, falseLabel, className, cancelFirst) {
	if (!trueLabel)
		trueLabel = 'OK';
	trueLabel = { value: true, block: isArray(trueLabel), label: trueLabel };
	if (!falseLabel)
		falseLabel = 'Annuler';
	falseLabel = { value: false, block: isArray(falseLabel), label: falseLabel };
	var options = {
		buttons: [ cancelFirst ? falseLabel : trueLabel, cancelFirst ? trueLabel : falseLabel ],
		defaultButton: true,
		cancelButton: false
	};
	if (label)
		options.label = label;
	if (title)
		options.title = title;
	if (className)
		options.className = className;
	return remap(function (value) { return value.button; }, dialog(options));
}

function dialogPrompt(label, defaultText, title, okLabel, cancelLabel, className, cancelFirst) {
	if (!okLabel)
		okLabel = 'OK';
	okLabel = { value: true, block: isArray(okLabel), label: okLabel };
	if (!cancelLabel)
		cancelLabel = 'Annuler';
	cancelLabel = { value: false, block: isArray(cancelLabel), label: cancelLabel };
	var options = {
		buttons: [ cancelFirst ? cancelLabel : okLabel, cancelFirst ? okLabel : cancelLabel ],
		fields: [ { name: 'text', value: defaultText ? defaultText : '' } ],
		defaultButton: true,
		cancelButton: false
	};
	if (label)
		options.label = label;
	if (title)
		options.title = title;
	if (className)
		options.className = className;
	return remap(function (value) {
		if (value.button)
			return value.text;
		return null;
	}, dialog(options));
}

dialog.test = function () {
	return dialog({
		label: [
			'Votre session a expiré.',
			'Veuillez vous reconnecter.'
		],
		title: 'Session expirée',
		buttons: [
			{
				value: true,
				label: [
					'Se reconnecter',
					mkNode('br'),
					'Lorem ipsum dolor sit amet'
				],
				block: true
			}, {
				value: false,
				label: 'Annuler',
				block: false
			}
		],
		defaultButton: true,
		cancelButton: false,
		fields: [
			{
				name: 'username',
				label: 'Nom d\'utilisateur'
			}, {
				name: 'password',
				type: 'password',
				label: 'Mot de passe'
			}, {
				name: 'remember_me',
				type: 'checkbox',
				label: 'Se souvenir de moi'
			}
		]
	}).log();
};