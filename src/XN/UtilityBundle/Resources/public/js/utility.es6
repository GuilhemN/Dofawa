// <span> vide pour les détections de fonctionnalités du navigateur
var testSpan = document.createElement('span');

// Un objet vide (et verrouillé comme tel quand c'est possible)
var emptyObject = { };
try {
	Object.freeze(emptyObject);
} catch (e) { }

// Récupère un objet depuis le serveur
var get = wrapAsync(function* get(url) {
	return (yield ajax({ url: url, type: 'GET' })).data;
});

// Fonction qui lève une exception "Pas implémenté", à définir pour les méthodes abstraites ou semi-abstraites
// (pour connaître facilement la liste des méthodes abstraites d'une classe à la lecture de son code)
function notImplemented() {
	throw new Error("Cette fonction n'est pas implémentée.");
}

// Fonction qui ne fait rien
function noop() { }

// Fonction qui renvoie son paramètre
function identity() { }

// Crée une fonction qui renvoie une valeur constante
function constant(value) {
	return function () {
		return value;
	}
}

// Pourquoi c'est pas en standard ?!
jQuery.fn.reverse = Array.prototype.reverse;

// Filtre un ensemble jQuery selon un critère asynchrone (avec conservation d'ordre)
jQuery.fn.filterAsync = wrapAsync(function* (fn) {
	var results = [ ];
	for (var i = 0; i < this.length; ++i)
		results[i] = fn.call(this[i], i, this[i]);
	results = yield all(results);
	return this.filter(function (i) { return results[i]; });
});

// Convertit un ensemble jQuery selon une fonction asynchrone (avec conservation d'ordre)
jQuery.fn.mapAsync = wrapAsync(function* (fn) {
	var results = [ ];
	for (var i = 0; i < this.length; ++i)
		results[i] = fn.call(this[i], i, this[i]);
	results = yield all(results);
	return this.map(function (i) { return results[i]; });
});

// Appelle une fonction asynchrone pour tous les éléments d'un ensemble jQuery
jQuery.fn.eachAsync = wrapAsync(function* (fn) {
	var results = [ ];
	for (var i = 0; i < this.length; ++i)
		results[i] = fn.call(this[i], i, this[i]);
	yield all(results);
	return this;
});

// Crée une fonction qui planifie fn, ms millisecondes après avoir été appelée, et lui transmet ses paramètres
function delay(fn, ms) {
	return function () {
		var args = Array.prototype.slice.call(arguments);
		setTimeout((function () {
			fn.apply(this, args);
		}).bind(this), ms);
	};
}

// Similaire à delay. Si appelée alors qu'un appel à fn est déjà planifié mais pas exécuté, la planification précédente est annulée.
function delayAndLimit(fn, ms) {
	var timer = null;
	return function () {
		var args = Array.prototype.slice.call(arguments);
		if (timer !== null)
			clearTimeout(timer);
		timer = setTimeout((function () {
			timer = null;
			fn.apply(this, args);
		}).bind(this), ms);
	};
}

// Fusionne deux objets
function merge(target, source, skipNew, skipExisting) {
	for (var i in source) {
		if (i in target) {
			if (typeof target[i] == 'object' && typeof source[i] == 'object')
				merge(target[i], source[i], skipNew, skipExisting);
			else if (!skipExisting)
				target[i] = source[i];
		} else if (!skipNew)
			target[i] = source[i];
	}
	return target;
}

// Raccourci pour document.getElementById
function elById(id) {
	return document.getElementById(id);
}

// Raccourci pour document.createTextNode
function mkText(value) {
	return document.createTextNode(value);
}

// Raccourci pour document.createElement + .setAttribute + ...
/** @deprecated Utiliser JSXDOM */
function mkNode(node, attrs, props, children, events) {
	if (typeof node == 'string')
		node = document.createElement(node);
	if (attrs)
		for (var i in attrs)
			node.setAttribute(i, attrs[i]);
	if (props)
		merge(node, props);
	if (children) {
		children = cArray(children);
		var nChildren = children.length;
		for (var i = 0; i < nChildren; ++i) {
			var child = children[i];
			if (typeof child != 'object')
				child = document.createTextNode(cString(child));
			node.appendChild(child);
		}
	}
	if (events) {
		var $node = jQuery(node);
		for (var i in events)
			$node.on(i, events[i]);
	}
	return node;
}

// Rend une URL absolue
function toAbsoluteURL(url) {
	if (!url)
		return url;
	var link = document.createElement('a');
	link.href = url;
	return link.href;
}

// Injecte des paramètres aux emplacements marqués
function inject(into, params) {
	if (typeof into !== 'object')
		return into;
	else if (into instanceof ParameterMarker)
		return params[into.name];
	else if (into instanceof Template)
		return into.instantiate(params);
	else if (into instanceof Raw)
		return into.value;
	else if (into instanceof Array) {
		var result = [];
		for (var i = 0; i < into.length; ++i)
			result[i] = inject(into[i], params);
		return result;
	} else {
		var result = createSimilar(into);
		var names = Object.keys(into);
		var numnames = names.length;
		for (var i = 0; i < numnames; ++i)
			result[names[i]] = inject(into[names[i]], params);
		return result;
	}
}

// Représente un marqueur de paramètre pour inject
function ParameterMarker(name) {
	this.name = name;
}

// Représente une valeur ne devant pas être traitée par inject
function Raw(value) {
	this.value = value;
}

// Représente un modèle (fonction permettant de générer un objet à partir de paramètres) pour inject
function Template(instantiate) {
	this.instantiate = instantiate;
}

// Récupère le dernier élément d'un noeud HTML
var getLastElementChild;
if ('lastElementChild' in testSpan) {
	getLastElementChild = function (elem) {
		return elem.lastElementChild;
	};
} else {
	getLastElementChild = function (elem) {
		for (var cld = elem.lastChild; cld; cld = cld.previousSibling)
			if (cld.nodeType == 1)
				return cld;
		return null;
	};
}

// Supprime les derniers éléments enfants d'un noeud HTML
function rmLastChildren(elem, num) {
	while (num--)
		elem.removeChild(getLastElementChild(elem));
}

// Vide partiellement un noeud HTML
function clearChildren(elem) {
	var cld;
	while ((cld = getLastElementChild(elem)) !== null)
		elem.removeChild(cld);
}

// Vide totalement un noeud HTML
function clearChildNodes(elem) {
	var cld;
	while ((cld = elem.lastChild) !== null)
		elem.removeChild(cld);
}

// Lie une fonction
function bind(fn) {
	return Function.prototype.bind.apply(fn, Array.prototype.slice.call(arguments, 1));
}

// Crée un objet similaire à un autre
function createSimilar(obj) {
	var proto = Object.getPrototypeOf(obj);
	if (proto)
		return Object.create(proto);
	return { };
}

// Mesure les dimensions de chaînes suivant le style de la classe spécifiée
var measureStrings = wrapAsync(function* measureStrings(clazz, strings) {
	var els = cArray(strings).map(function (str) {
		var el = document.createElement('div');
		el.className = 'measurement-helper ' + clazz;
		el.appendChild(document.createTextNode(str));
		document.body.appendChild(el);
		return el;
	});
	while (els.some(function (el) { return Dimension.fromElement(el).isEmpty(); }))
		yield sleep(0);
	return els.map(function (el) {
		var dim = Dimension.fromElement(el);
		el.parentNode.removeChild(el);
		return dim;
	});
});

// Représente un point
function Point(x, y) {
	this.x = x;
	this.y = y;
}
Point.prototype.toString = function () {
	return "(" + this.x + ", " + this.y + ")";
};
Point.fromElement = function (elem) {
	var x = 0, y = 0;
	while (elem) {
		x += elem.offsetLeft;
		y += elem.offsetTop;
		elem = elem.offsetParent;
	}
	return new Point(x, y);
};

// Représente des dimensions
function Dimension(width, height) {
	this.width = width;
	this.height = height;
}
Dimension.prototype.toString = function () {
	return this.width + "x" + this.height;
};
Dimension.prototype.constrain = function (max) {
	var w = this.width;
	var h = this.height;
	if (w > max.width) {
		h *= max.width / w;
		w = max.width;
	}
	if (h > max.height) {
		w *= max.height / h;
		h = max.height;
	}
	return new Dimension(Math.ceil(w), Math.ceil(h));
};
Dimension.prototype.isEmpty = function () {
	return this.width == 0 && this.height == 0;
};
Dimension.fromElement = function (elem) {
	return new Dimension(('width' in elem) ? elem.width : elem.offsetWidth, ('height' in elem) ? elem.height : elem.offsetHeight);
};

// Représente un rectangle
function Rectangle(x, y, width, height) {
	this.x = x;
	this.y = y;
	this.width = width;
	this.height = height;
}
Rectangle.prototype.toString = function () {
	var args = Array.prototype.slice.call(arguments);
	return Point.prototype.toString.apply(this, args) + ", " + Dimension.prototype.toString.apply(this, args);
};
Rectangle.fromElement = function (elem) {
	var pt = Point.fromElement(elem);
	var dim = Dimension.fromElement(elem);
	return new Rectangle(pt.x, pt.y, dim.width, dim.height);
};

// Récupère ou définit le texte d'un élément
var getTextContent, setTextContent;
if ('textContent' in testSpan) {
	getTextContent = function (elem) {
		return elem.textContent;
	};
	setTextContent = function (elem, text, elem2) {
		if (elem.textContent == text)
			return;
		elem.textContent = text;
		updateTitle(elem, text, elem2);
	};
} else {
	getTextContent = function (elem) {
		if (elem.nodeType == 3)
			return elem.nodeValue;
		var text = [];
		for (var cld = elem.firstChild; cld; cld = cld.nextSibling)
			text.push(getTextContent(cld));
		return text.join('');
	};
	setTextContent = function (elem, text, elem2) {
		if (getTextContent(elem) == text)
			return;
		clearChildNodes(elem);
		elem.appendChild(document.createTextNode(text));
		updateTitle(elem, text, elem2);
	};
}

// Ajoute si nécessaire une info-bulle à l'élément
var updateTitle = wrapAsync(function* updateTitle(elem, text, elem2) {
	if (typeof elem2 == 'undefined')
		elem2 = elem;
	var cls = elem2.getAttribute('data-measure-class');
	if (!cls)
		return;
	if (typeof text == 'undefined')
		text = getTextContent(elem);
	var dim = (yield measureStrings(cls, [ text ]))[0];
	if (dim.width > elem.offsetWidth)
		elem2.title = text;
	else
		elem2.title = '';
});

// Met un mot au singulier
function singularize(str) {
	return str.replace(/^(\S+)s(?:$|(?=\s))/i, '$1');
}

// Recherche un élément par dichotomie dans un tableau trié
function binarySearch(haystack, needle, comparer, keySelector, start, end) {
	if (typeof start == 'undefined')
		start = 0;
	if (typeof end == 'undefined')
		end = haystack.length;
	--end;
	if (comparer) {
		while (end >= start) {
			var middle = start + ((end - start) >> 1);
			var value = haystack[middle];
			if (keySelector)
				value = keySelector(value);
			var comp = comparer(needle, value);
			if (comp == 0)
				return middle;
			else if (comp < 0)
				end = middle - 1;
			else
				start = middle + 1;
		}
	} else {
		while (end >= start) {
			var middle = start + ((end - start) >> 1);
			var value = haystack[middle];
			if (keySelector)
				value = keySelector(value);
			if (needle == value)
				return middle;
			else if (needle < value)
				end = middle - 1;
			else
				start = middle + 1;
		}
	}
	return ~start;
}

// Sélectionne une page
function selectPage(page) {
	var $page = jQuery(page);
	if (!$page.length)
		return false;
	jQuery('> .selected', $page.parent()).removeClass('selected').addClass('unselected');
	$page.addClass('selected').removeClass('unselected');
	return true;
}

// Construit un objet en passant un tableau de paramètres (à la .apply)
function applyCtor(ctor, args) {
	var inst = Object.create(ctor.prototype);
	var ret = ctor.apply(inst, args);
	if (typeof ret == 'object' && ret !== null)
		return ret;
	return inst;
}

// Comme applyCtor mais en beaucoup plus moche (new Function est le frère d'eval !)
// (Déconseillé, n'utiliser que si le constructeur voulu n'est pas compatible avec applyCtor)
function applyCtorCompat(ctor, args) {
	return prepareApplyCtorCompat(args.length)(ctor, args);
}
var prepareApplyCtorCompat = (function () {
	var cache = [ ];
	return function (argc) {
		if (typeof argc != 'number')
			return null;
		if (typeof cache[argc] == 'undefined') {
			var args = [ ];
			for (var i = 0; i < argc; ++i)
				args.push('args[' + i + ']');
			cache[argc] = new Function('ctor, args', 'return new ctor(' + args.join(', ') + ');');
		}
		return cache[argc];
	};
})();

// Convertit une valeur en booléen
function parseBoolean(val) {
	if (typeof val == 'boolean')
		return val;
	else if (typeof val == 'string') {
		val = val.trim();
		if (!val)
			return false;
		var numval = parseFloat(val);
		if (numval == numval)
			return !!numval;
		val = val.toLowerCase();
		return val != 'off' && val != 'false' && val != 'no';
	} else
		return !!val;
}
var parseBool = parseBoolean;

// Convertit une valeur en date
var reISODate = /^(\d{4,})-(\d{2})-(\d{2})(?:T(\d{2}):(\d{2}):(\d{2})(?:\.(\d{3}))?)?(?:Z|([+-])(\d{2}):?(\d{2}))?$/i;
function parseDate(val, withTZ) {
	if (val == null || val == '')
		return null;
	if (val instanceof Date)
		return val;
	var expl = reISODate.exec(val);
	if (!expl)
		return new Date(val);
	expl = expl.slice(1).filter(function (val) { return val != null && val != ''; }).map(function (val) {
		if (val == 'Z' || val == '+' || val == '-')
			return val;
		return parseInt(val);
	});
	--expl[1];
	var tz = 0;
	if (expl.length > 2 && expl[expl.length - 3] == '+') {
		tz = -(expl[expl.length - 2] * 60 + expl[expl.length - 1]);
		expl.length -= 3;
	} else if (expl.length > 2 && expl[expl.length - 3] == '-') {
		tz = (expl[expl.length - 2] * 60 + expl[expl.length - 1]);
		expl.length -= 3;
	}
	var dt = applyCtorCompat(Date, expl);
	if (withTZ !== false)
		dt.setTime(dt.getTime() + (tz - dt.getTimezoneOffset()) * 60000);
	return dt;
}

// Convertit une valeur localisée en date
var reLocaleDate = /^([012]?[0-9]|3[01])\/(0?[0-9]|1[0-2])\/(\d+)(?:[ -]([01]?[0-9]|2[0-3]):([0-5]?[0-9])(?::([0-5]?[0-9]|60))?)?$/i;
function parseLocaleDate(val) {
	if (val == null || val == '')
		return null;
	if (val instanceof Date)
		return val;
	var expl = reLocaleDate.exec(val);
	if (!expl)
		return parseDate(val);
	expl = expl.slice(1).filter(function (val) { return val != null && val != ''; }).map(function (val) { return parseInt(val); });
	var year = expl[2];
	if (year < 70)
		year += 2000;
	else if (year < 100)
		year += 1900;
	else if (year < 1000)
		year += 1000;
	expl[2] = expl[0];
	expl[0] = year;
	--expl[1];
	return applyCtorCompat(Date, expl);
}

// Convertit une date en texte
function localeDate(val, withTime) {
	var ds = padString("" + val.getDate(), 2, "0", true) + "/" + padString("" + (val.getMonth() + 1), 2, "0", true) + "/" + padString("" + val.getFullYear(), 4, "0", true);
	if (!withTime)
		return ds;
	return ds + " " + padString("" + val.getHours(), 2, "0", true) + ":" + padString("" + val.getMinutes(), 2, "0", true) + ":" + padString("" + val.getSeconds(), 2, "0", true);
}

// Découpe le chemin d'accès à une propriété profonde (syntaxes a[b] et a.b acceptées)
var rePropertyDelimiters = /[\[\]\.]+/g;
function splitPropertyPath(path) {
	return path.split(rePropertyDelimiters).filter(cBoolean).map(function (value) {
		var numValue;
		if ((numValue = cNumber(value)) == numValue)
			return numValue;
		return value;
	});
}

// Teste l'existence d'une propriété profonde
function hasProperty(object, property) {
	if (!isArray(property))
		property = splitPropertyPath(property);
	var value = object;
	try {
		for (var i = 0; i < property.length; ++i) {
			if (!(property[i] in value))
				return false;
			value = value[property[i]];
		}
	} catch (e) {
		return false;
	}
	return true;
}
// Récupère la valeur d'une propriété profonde
function getProperty(object, property) {
	if (!isArray(property))
		property = splitPropertyPath(property);
	var value = object;
	for (var i = 0; i < property.length && typeof value != 'undefined'; ++i)
		value = value[property[i]];
	return value;
}
// Définit la valeur d'une propriété profonde
function setProperty(object, property, value) {
	if (!isArray(property))
		property = splitPropertyPath(property);
	if (property.length == 0)
		throw new Error("Nom de propriété invalide");
	for (var i = 0; i < property.length - 1; ++i) {
		if (!(property[i] in object)) {
			if (typeof property[i + 1] == 'number')
				object[property[i]] = [ ];
			else
				object[property[i]] = { };
		}
		object = object[property[i]];
	}
	object[property[property.length - 1]] = value;
}
// Supprime une propriété profonde
function removeProperty(object, property, value) {
	if (!isArray(property))
		property = splitPropertyPath(property);
	if (property.length == 0)
		throw new Error("Nom de propriété invalide");
	for (var i = 0; i < property.length - 1; ++i) {
		if (!(property[i] in object))
			return false;
		object = object[property[i]];
	}
	return delete object[property[property.length - 1]];
}

// Casts
function cString(val) { return "" + val; }
function cBoolean(val) { return !!val; }
function cNumber(val) { return +val; }
function cInteger(val) { return val | 0; }
function cArray(val) { return isArray(val) ? val : [ val ]; }
function cThenable(val) { return isThenable(val) ? val : Promise.resolve(val); }
var cBool = cBoolean;

// Vérifie si une valeur est un nombre
var reNumeric = /^[+-]?\d+(?:\.\d+)?(?:[Ee][+-]?\d+)?$/;
function isNumeric(val) {
	return reNumeric.test(val);
}

// Génère des identifiants uniques dans le scope de la page en cours
var luid = (function () {
	var counter = 0;
	return function () {
		return ++counter;
	};
})();

// Répète une chaîne
function repeatString(str, times) {
	if (times <= 0)
		return '';
	var rpt = '';
	for (;;) {
		if ((times & 1) == 1)
			rpt += str;
		times >>>= 1;
		if (times == 0)
			break;
		str += str;
	}
	return rpt;
}

// Complète une chaîne jusqu'à la longueur voulue
function padString(str, length, pad, left) {
	if (str.length >= length)
		return str;
	if (typeof pad == 'undefined')
		pad = ' ';
	else if (pad.length > 1)
		pad = pad.charAt(0);
	pad = repeatString(pad, length - str.length);
	return left ? (pad + str) : (str + pad);
}

// Formate une date
var prettyDate = (function () {
	var months = [ 'janv.', 'fév.', 'mars', 'avril', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.' ];
	return function (date, withTime) {
		date = parseDate(date);
		var str = date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
		if (withTime)
			str += ' ' + padString('' + date.getHour()  , 2, '0', true)
					+  ':' + padString('' + date.getMinute(), 2, '0', true)
					+  ':' + padString('' + date.getSecond(), 2, '0', true);
		return str;
	};
})();

// Formate un mois
var prettyMonth = (function () {
	var months = [ 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre' ];
	return function (date) {
		return months[date.getMonth()] + ' ' + date.getFullYear();
	};
})();

// Récupère le jour d'une date
function day(date) {
	if (typeof date == 'string')
		date = parseDate(date, false);
	if (typeof endDate == 'object')
		date = date.getTime();
	date = new Date(date);
	date.setHours(0);
	date.setMinutes(0);
	date.setSeconds(0);
	date.setMilliseconds(0);
	return date;
}

// Énumère les jours entre deux dates
function listDaysBetween(startDate, endDate) {
	if (typeof startDate == 'string')
		startDate = parseDate(startDate);
	if (typeof startDate == 'object')
		startDate = startDate.getTime();
	if (typeof endDate == 'string')
		endDate = parseDate(endDate);
	if (typeof endDate == 'object')
		endDate = endDate.getTime();
	var dt = new Date(startDate);
	dt.setHours(0);
	dt.setMinutes(0);
	dt.setSeconds(0);
	dt.setMilliseconds(0);
	var days = [ ];
	while (dt.getTime() <= endDate) {
		days.push(new Date(dt.getTime()));
		dt.setDate(dt.getDate() + 1);
	}
	return days;
}

// Récupère le premier jour du mois
function firstDayOfMonth(date) {
	if (typeof date == 'string')
		date = parseDate(date);
	if (typeof date == 'object')
		date = date.getTime();
	var dt = new Date(date);
	dt.setDate(1);
	dt.setHours(0);
	dt.setMinutes(0);
	dt.setSeconds(0);
	dt.setMilliseconds(0);
	return dt;
}

// Récupère le dernier jour du mois
function lastDayOfMonth(date) {
	if (typeof date == 'string')
		date = parseDate(date);
	if (typeof date == 'object')
		date = date.getTime();
	var dt = new Date(date);
	dt.setDate(1);
	dt.setHours(0);
	dt.setMinutes(0);
	dt.setSeconds(0);
	dt.setMilliseconds(0);
	dt.setMonth(dt.getMonth() + 1);
	dt.setDate(dt.getDate() - 1);
	return dt;
}

// Compare deux tableaux, élément à élément
function rowValueCompare(lhs, rhs) {
	var len = Math.min(lhs.length, rhs.length);
	for (var i = 0; i < len; ++i) {
		if (lhs[i] < rhs[i])
			return -1;
		else if (lhs[i] > rhs[i])
			return 1;
	}
	if (lhs.length > len)
		return 1;
	else if (rhs.length > len)
		return -1;
	return 0;
}
function createRowValueComparer(compare) {
	if (!compare)
		return rowValueCompare;
	return function (lhs, rhs) {
		var len = Math.min(lhs.length, rhs.length);
		for (var i = 0; i < len; ++i) {
			var comparison = compare(lhs[i], rhs[i]);
			if (comparison != 0)
				return comparison;
		}
		if (lhs.length > len)
			return 1;
		else if (rhs.length > len)
			return -1;
		return 0;
	}
}

// Représente un ensemble de valeurs triées et uniques
function Set() {
	this.values = [ ];
	this.size = 0;
	this.comparer = null;
}
Set.prototype.clear = function () {
	this.values.length = 0;
	this.size = 0;
};
Set.prototype.add = function (value) {
	var i = binarySearch(this.values, value, this.comparer);
	if (i >= 0)
		return false;
	this.values.splice(~i, 0, value);
	++this.size;
	return true;
};
Set.prototype.has = function (value) {
	return binarySearch(this.values, value, this.comparer) >= 0;
};
Set.prototype.contains = Set.prototype.has;
Set.prototype.remove = function (value) {
	var i = binarySearch(this.values, value, this.comparer);
	if (i < 0)
		return false;
	this.values.splice(i, 1);
	--this.size;
	return true;
};
Set.prototype.forEach = function (fn) {
	if (arguments.length >= 2)
		return this.values.forEach(fn, arguments[1]);
	else
		return this.values.forEach(fn);
};
Set.prototype.toArray = function () {
	return this.values.slice();
};

// Copie une tranche de tableau (fonctionne même si target === source)
function alCopy(target, trgOffset, source, srcOffset, length) {
	if (isArray(trgOffset)) {
		source = trgOffset;
		trgOffset = 0;
	}
	if (srcOffset == null)
		srcOffset = 0;
	if (length == null)
		length = source.length - srcOffset;
	if (target === source && trgOffset == srcOffset || length == 0)
		return;
	if (target === source && trgOffset > srcOffset) {
		for (var i = length; i-- > 0; )
			target[trgOffset + i] = source[srcOffset + i];
	} else {
		for (var i = 0; i < length; ++i)
			target[trgOffset + i] = source[srcOffset + i];
	}
	return target;
}

// Comme Array.prototype.splice mais pas que pour les tableaux, et prend un tableau de valeurs non-éclaté
function alSplice(target, offset, length, values) {
	var tlen = target.length;
    if (length == null)
        length = tlen - offset;
    else
        alCopy(target, offset + values.length, target, offset + length);
    alCopy(target, offset, values, 0);
    target.length = tlen + values.length - length;
}

jQuery(function () {
	jQuery('.tab-strip').on('mousemove', function (ev) {
		var rc = Rectangle.fromElement(this);
		var fix = Math.max(0, (document.documentElement.offsetWidth - document.body.offsetWidth) >> 1);
		var x = (ev.pageX - fix - rc.x - 16) / (rc.width - 32);
		if (x < 0)
			x = 0;
		else if (x > 1)
			x = 1;
		jQuery('ul', this).each(function () {
			var ovf = Dimension.fromElement(this).width - rc.width;
			if (ovf < 0)
				ovf = 0;
			this.style.left = -Math.round(ovf * x) + 'px';
		});
	});
	jQuery('[data-page-id]').each(function () {
		var page = elById(this.getAttribute('data-page-id'));
		if (!page)
			return;
		var selection = [ this, page ];
		jQuery('a', this).on('click', function () {
			selectPage(selection);
		});
	});
	jQuery('.not-implemented').on('click', function (ev) {
		dialogAlert('Cette fonctionnalité n\'est pas encore disponible.');
		try { ev.stopPropagation(); } catch (e) { }
		try { ev.preventDefault(); } catch (e) { }
	});
	jQuery('.multipage > :not(.selected)').addClass('unselected');
});
