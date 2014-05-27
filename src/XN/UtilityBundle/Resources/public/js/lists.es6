function AbstractList() {
	throw new Error('Cette classe est abstraite et ne peut être instanciée directement.');
}

AbstractList.prototype.source = null;

AbstractList.prototype.getSource = function () {
	return this.source;
}
AbstractList.prototype.setSource = function (source, noRefresh) {
	this.source = source;
	if (!noRefresh) {
		this.clear();
		this.refresh();
	}
};

AbstractList.prototype.clear = notImplemented;
AbstractList.prototype.refresh = notImplemented;

function FilterableList(elem, source) {
	if (typeof elem === 'string')
		elem = elById(elem);
	else if (elem === null) {
		elem = mkNode('div', null, {
			className: 'composite-list'
		}, [
			mkNode('h2'),
			mkNode('div', null, null, [
				mkNode('ul')
			]),
			mkNode('input')
		]);
	}
	this.root = elem;
	this.header = jQuery('h2', elem)[0];
	this.list = jQuery('ul', elem)[0];
	this.filter = jQuery('input:not([type]), input[type="text"]', elem)[0];
	this.header.setAttribute('data-measure-class', 'composite-list-header');
	this.filter.placeholder = 'Filtre ...';
	this.source = source;
	this.selectedValue = null;
	this.selectedItem = null;
	this.selectedIndex = null;
	this.currentFilter = this.filter.value;
	this.acceptCookie = 0;
	this.onSelectionChange = null;
	this.onSelectionNoChange = null;
	this.onElementClick = bind(this.onElementClick, this);
	jQuery(this.filter).on('keyup paste change', delayAndLimit(bind(function () {
		if (this.filter.value != this.currentFilter) {
			this.currentFilter = this.filter.value;
			this.refresh();
		}
	}, this), 300));
	this.refresh();
}

FilterableList.prototype = Object.create(AbstractList.prototype);
FilterableList.prototype.constructor = FilterableList;

FilterableList.prototype.getTitle = function () {
	return getTextContent(this.header);
};
FilterableList.prototype.setTitle = function (title) {
	setTextContent(this.header, title);
};

FilterableList.prototype.onElementClick = function (ev) {
	var target = ev.target ? ev.target : ev.srcElement;
	var newSel = target.getAttribute('data-value');
	var selChg = newSel !== this.selectedValue;
	this.selectedValue = newSel;
	this.selectedIndex = parseInt(target.getAttribute('data-index'));
	if (this.selectedItem)
		jQuery(this.selectedItem).removeClass('selected');
	jQuery(target).addClass('selected');
	this.selectedItem = target;
	if (selChg && this.onSelectionChange)
		this.onSelectionChange({ target: this });
	else if (!selChg && this.onSelectionNoChange)
		this.onSelectionNoChange({ target: this });
};

FilterableList.prototype.deselect = function () {
	var hasSel = this.selectedValue !== null;
	this.selectedIndex = null;
	this.selectedValue = null;
	if (this.selectedItem)
		jQuery(this.selectedItem).removeClass('selected');
	this.selectedItem = null;
	if (hasSel && this.onSelectionChange)
		this.onSelectionChange({ target: this });
	else if (!hasSel && this.onSelectionNoChange)
		this.onSelectionNoChange({ target: this });
};

FilterableList.prototype.clear = function () {
	clearChildNodes(this.list);
};
FilterableList.prototype.refresh = wrapAsync(function* () {
	var acceptCookie = ++this.acceptCookie;
	if (!this.source) {
		this.clear();
		return;
	}
	var $root = jQuery(this.root);
	$root.addClass('loading');
	var data;
	try {
		data = yield this.source.getData(this.currentFilter);
	} catch (e) {
		console.error(e);
		if (acceptCookie !== this.acceptCookie)
			return;
		$root.removeClass('loading');
		this.clear();
		return;
	}
	if (acceptCookie !== this.acceptCookie)
		return;
	$root.removeClass('loading');
	this.selectedItem = null;
	this.selectedIndex = null;
	for (var i = 0; i < data.length; ++i) {
		var cld = this.list.children[i];
		if (!cld) {
			cld = mkNode('li', {
				'data-index': i,
				'data-measure-class': 'composite-list-item'
			}, {
				className: (i & 1) ? 'even' : 'odd'
			}, null, {
				click: this.onElementClick
			});
			this.list.appendChild(cld);
		}
		cld.setAttribute('data-value', data[i].value);
		setTextContent(cld, data[i].text);
		if (data[i].value == this.selectedValue) {
			jQuery(cld).addClass('selected');
			this.selectedItem = cld;
			this.selectedIndex = i;
		} else
			jQuery(cld).removeClass('selected');
	}
	rmLastChildren(this.list, this.list.children.length - data.length);
});

function PartitionedList(elem, source) {
	if (typeof elem === 'string')
		elem = elById(elem);
	else if (elem === null) {
		elem = mkNode('div', null, {
			className: 'partitioned-list'
		}, [
			mkNode('ul'),
			mkNode('div', null, null, [
				mkNode('input', {
					type: 'button',
					value: '>>'
				}),
				mkNode('input', {
					type: 'button',
					value: '<<'
				})
			]),
			mkNode('ul')
		]);
	}
	this.root = elem;
	var lists = jQuery('ul', elem);
	this.leftList = lists[0];
	this.rightList = lists[1];
	var buttons = jQuery('input', elem);
	this.addButton = buttons[0];
	this.removeButton = buttons[1];
	this.value = [ ];
	this.source = source;
	this.leftSelectedValue = null;
	this.leftSelectedItem = null;
	this.leftSelectedIndex = null;
	this.rightSelectedValue = null;
	this.rightSelectedItem = null;
	this.rightSelectedIndex = null;
	this.acceptCookie = 0;
	this.acceptedData = [ ];
	this.onElementClick = bind(this.onElementClick, this);
	jQuery(this.addButton).on('click', bind(function () {
		if (this.leftSelectedValue === null)
			return;
		this.value.push(this.leftSelectedValue);
		this.rightSelectedValue = this.leftSelectedValue;
		this.leftSelectedValue = null;
		this.redisplay();
	}, this));
	jQuery(this.removeButton).on('click', bind(function () {
		if (this.rightSelectedValue === null)
			return;
		var idx = this.value.indexOf(this.rightSelectedValue);
		if (idx >= 0)
			this.value.splice(idx, 1);
		this.leftSelectedValue = this.rightSelectedValue;
		this.rightSelectedValue = null;
		this.redisplay();
	}, this));
	this.refresh();
}

PartitionedList.prototype = Object.create(AbstractList.prototype);
PartitionedList.prototype.constructor = PartitionedList;

PartitionedList.prototype.getValue = function () {
	return this.value;
};
PartitionedList.prototype.setValue = function (value) {
	if (value === null)
		this.value = [ ];
	else
		this.value = value;
	this.redisplay();
};

PartitionedList.prototype.onElementClick = function (ev) {
	var target = ev.target ? ev.target : ev.srcElement;
	var newSel = target.getAttribute('data-value');
	var inValue = target.parentNode == this.rightList;
	var side = inValue ? 'right' : 'left';
	var selChg = newSel !== this[side + 'SelectedValue'];
	this[side + 'SelectedValue'] = newSel;
	this[side + 'SelectedIndex'] = parseInt(target.getAttribute('data-index'));
	if (this[side + 'SelectedItem'])
		jQuery(this[side + 'SelectedItem']).removeClass('selected');
	jQuery(target).addClass('selected');
	this[side + 'SelectedItem'] = target;
};

PartitionedList.prototype.clear = function () {
	clearChildNodes(this.leftList);
	clearChildNodes(this.rightList);
};
PartitionedList.prototype.refresh = wrapAsync(function* () {
	var acceptCookie = ++this.acceptCookie;
	if (!this.source) {
		this.clear();
		return;
	}
	var $root = jQuery(this.root);
	$root.addClass('loading');
	var data;
	try {
		data = yield this.source.getData(this.currentFilter);
	} catch (e) {
		console.error(e);
		if (acceptCookie !== this.acceptCookie)
			return;
		$root.removeClass('loading');
		this.clear();
		return;
	}
	if (acceptCookie !== this.acceptCookie)
		return;
	this.acceptedData = data;
	$root.removeClass('loading');
	this.redisplay();
});
PartitionedList.prototype.redisplay = function () {
	var data = this.acceptedData;
	this.leftSelectedItem = null;
	this.leftSelectedIndex = null;
	this.rightSelectedItem = null;
	this.rightSelectedIndex = null;
	var lCount = 0, rCount = 0;
	for (var i = 0; i < data.length; ++i) {
		var inValue = this.value.indexOf(data[i].value) >= 0;
		var side = inValue ? 'right' : 'left';
		var j = inValue ? rCount++ : lCount++;
		var cld = this[side + 'List'].children[j];
		if (!cld) {
			cld = mkNode('li', {
				'data-index': j,
				'data-measure-class': 'composite-list-item'
			}, {
				className: (j & 1) ? 'even' : 'odd'
			}, null, {
				click: this.onElementClick
			});
			this[side + 'List'].appendChild(cld);
		}
		cld.setAttribute('data-logical-index', i);
		cld.setAttribute('data-value', data[i].value);
		setTextContent(cld, data[i].text);
		if (data[i].value == this[side + 'SelectedValue']) {
			jQuery(cld).addClass('selected');
			this[side + 'SelectedItem'] = cld;
			this[side + 'SelectedIndex'] = j;
		} else
			jQuery(cld).removeClass('selected');
	}
	rmLastChildren(this.leftList, this.leftList.children.length - lCount);
	rmLastChildren(this.rightList, this.rightList.children.length - rCount);
};

function StaticDataSource(data) {
	this.data = data ? data : [];
}
StaticDataSource.prototype.isImmediate = true;
StaticDataSource.prototype.getData = function (filter) {
	if (!filter)
		return this.data;
	filter = filter.trim().toLowerCase().split(/\s+/g);
	if (filter.length == 1 && !filter[0])
		return this.data;
	return this.data.filter(function (row) {
		var text = row.text.toLowerCase();
		return filter.every(function (word) {
			return text.indexOf(word) >= 0;
		});
	});
};
StaticDataSource.empty = new StaticDataSource();

function AjaxDataSource(settings) {
	this.settings = settings;
	this.running = { };
}
AjaxDataSource.filterMarker = new ParameterMarker('filter');
AjaxDataSource.prototype.isImmediate = false;
AjaxDataSource.prototype.getData = wrapAsync(function* (filter) {
	var prom;
	if (filter in this.running)
		prom = this.running[filter];
	else {
		var settings = inject(this.settings, { filter: filter });
		prom = ajax(settings);
		this.running[filter] = prom;
		prom.jqXHR.always(bind(function () { delete this.running[filter]; }, this));
	}
	return (yield prom).data;
});

function CompositeDataSource(combine) {
	this.combine = combine ? combine : bind(Function.prototype.call, Array.prototype.concat);
	this.sources = Array.prototype.slice.call(arguments, 1);
	this.isImmediate = this.sources.every(function (src) { return src.isImmediate; });
}
CompositeDataSource.prototype.isImmediate = false;
CompositeDataSource.prototype.getData = function (filter) {
	var combine = this.combine;
	if (this.isImmediate)
		return combine.apply(this, this.sources.map(function (src) { return src.getData(filter); }));
	else {
		return runAsync.call(this, function* () {
			return combine.apply(this, yield this.sources.map(function (src) { return src.getData(filter); }));
		});
	}
};

function ProjectionDataSource(base, project) {
	this.base = base;
	this.project = bind(project, this);
	this.isImmediate = base.isImmediate;
}
ProjectionDataSource.prototype.isImmediate = false;
ProjectionDataSource.prototype.getData = function (filter) {
	var project = this.project;
	if (this.isImmediate)
		return this.base.getData(filter).map(project, this);
	else {
		return runAsync.call(this, function* () {
			return (yield this.base.getData(filter)).map(project, this);
		});
	}
};

function DeferredDataSource(base) {
	if (!base.isImmediate)
		return base;
	this.base = base;
}
DeferredDataSource.prototype.isImmediate = false;
DeferredDataSource.prototype.getData = wrapAsync(function* (filter) {
	return this.base.getData(filter);
});
DeferredDataSource.empty = new DeferredDataSource(StaticDataSource.empty);
