// Polyfill pour Promise
if (typeof Promise == 'undefined') {
	function Promise(fn) {
		var defer = jQuery.Deferred();
		var prom = defer.promise();
		fn.call(undefined, defer.resolve, defer.reject);
		prom.log = Promise.prototype.log;
		return prom;
	}
}
if (!('resolve' in Promise)) {
	Promise.resolve = function (value) {
		if (isThenable(value))
			return new Promise(bind(value.then, value));
		else
			return new Promise(function (resolve, reject) { resolve(value); });
	};
}
if (!('reject' in Promise)) {
	Promise.reject = function (reason) {
		return new Promise(function (resolve, reject) { reject(reason); });
	};
}
if (!('all' in Promise)) {
	Promise.all = function (proms) {
		return all(proms, true);
	};
}
if (!('race' in Promise)) {
	Promise.race = function (proms) {
		return race(proms, true);
	};
}

// Affiche la valeur d'une Promise lors de sa résolution ou de son rejet
Promise.prototype.log = function () {
	this.then(function (value) { console.info(value); }, function (reason) { console.error(reason); });
	return this;
}

function ThenableJoiner() {
	this.array = [ ];
	this.hasValue = false;
	this.value = undefined;
	this.isImmediate = false;
}
ThenableJoiner.prototype.add = function (value) {
	if (isThenable(value)) {
		this.array.push(value);
		this.isImmediate = false;
	} else if (!this.hasValue) {
		this.hasValue = true;
		this.value = value;
	}
	return value;
};
ThenableJoiner.prototype.join = function (noCancel) {
	return revalue(undefined, this.dangerousAll(noCancel));
};
ThenableJoiner.prototype.dangerousAll = function (noCancel) {
	return all(this.array, noCancel);
};
ThenableJoiner.prototype.race = function (noCancel) {
	if (this.hasValue) {
		if (!noCancel)
			race(this.array, false).cancel();
		return Promise.resolve(this.value);
	}
	return race(this.array, noCancel);
};
var PromiseJoiner = ThenableJoiner;

// Vérifications de types
function isArray(x) {
	return Object.prototype.toString.call(x) == '[object Array]';
}
function isThenable(x) {
	return typeof x == 'object' && x !== null && typeof x.then == 'function';
}

// Lance une tâche asynchrone implémentée au format "itérateur de Promise"
function runAsync(taskGen) {
	if (typeof taskGen == 'function')
		taskGen = taskGen.apply(this, Array.prototype.slice.call(arguments, 1));
	var cancel, prom = new Promise(function (resolve, reject) {
		var done = false, canceled = false, current = null, cookie = 0;
		var go = function (action, toSend) {
			var myCookie = ++cookie;
			current = null;
			if (action == 'next') {
				if (isThenable(toSend))
					current = toSend;
				else if (isArray(toSend) && toSend.some(isThenable))
					current = all(toSend);
				if (current) {
					if (canceled) {
						if ('cancel' in current)
							current.cancel();
						current = null;
						return go('throw', new Error('Opération annulée'));
					}
					current.then(function (value) {
						if (myCookie == cookie)
							go('next', value);
					}, function (reason) {
						if (myCookie == cookie)
							go('throw', reason);
					});
					return;
				}
				if (done) {
					resolve(toSend);
					return;
				}
			}
			var result;
			try {
				result = taskGen[action](toSend);
			} catch (e) {
				done = true;
				reject(e);
				return;
			}
			done = result.done;
			var value = result.value;
			return go('next', value);
		};
		cancel = function () {
			if (canceled || done)
				return;
			canceled = true;
			++cookie;
			reject(new Error('Opération annulée'));
			if (current) {
				if ('cancel' in current)
					current.cancel();
				current = null;
				go('throw', new Error('Opération annulée'));
			}
		};
		go('next');
	});
	prom.cancel = cancel;
	return prom;
}

// Convertit une fonction génératrice en fonction asynchrone
function wrapAsync(genFn, returnValue) {
	if (arguments.length > 1)
		return function () {
			runAsync(genFn.apply(this, Array.prototype.slice.call(arguments)));
			return returnValue;
		};
	else
		return function () {
			return runAsync(genFn.apply(this, Array.prototype.slice.call(arguments)));
		};
}

// Modifie la valeur d'une Promise
function revalue(value, prom, noCancel) {
	var cancel, prom2 = new Promise(function (resolve, reject) {
		if (!noCancel && 'cancel' in prom)
			cancel = function () { prom.cancel(); };
		prom.then(function () { resolve(value); }, reject);
	});
	if (!noCancel && 'cancel' in prom)
		prom2.cancel = cancel;
	return prom2;
}

// Modifie la valeur d'une Promise en utilisant une fonction
function remap(fn, prom, noCancel) {
	var cancel, prom2 = new Promise(function (resolve, reject) {
		if (!noCancel && 'cancel' in prom)
			cancel = function () { prom.cancel(); };
		prom.then(function (value) {
			try {
				resolve(fn(value));
			} catch (e) {
				reject(e);
			}
		}, reject);
	});
	if (!noCancel && 'cancel' in prom)
		prom2.cancel = cancel;
	return prom2;
}

// Combine un tableau de Promises en Promise de tableau
function all(proms, noCancel) {
	var cancel, prom = new Promise(function (resolve, reject) {
		var first = true;
		if (!noCancel)
			cancel = function () {
				if (!first)
					return;
				first = false;
				proms.forEach(function (prom) {
					if (isThenable(prom) && 'cancel' in prom)
						prom.cancel();
				});
			};
		var reject2 = noCancel ? reject : function (reason) {
			reject(reason);
			cancel();
		};
		var values = [ ], missing = 0;
		var resolve2PA = function (i) {
			return function (value) {
				values[i] = value;
				if (--missing == 0)
					resolve(values);
			}
		};
		for (var i = 0; i < proms.length; ++i) {
			if (isThenable(proms[i])) {
				proms[i].then(resolve2PA(i), reject2);
				++missing;
			} else
				values[i] = proms[i];
		}
		if (missing == 0)
			resolve(values);
	});
	if (!noCancel)
		prom.cancel = cancel;
	return prom;
};

// Crée une Promise dont la valeur sera celle de la première Promise résolue
function race(proms, noCancel) {
	if (!proms.length)
		return new Promise(function () { });
	var cancel, prom = new Promise(function (resolve, reject) {
		var first = true;
		if (!noCancel)
			cancel = function () {
				if (!first)
					return;
				first = false;
				proms.forEach(function (prom) {
					if (isThenable(prom) && 'cancel' in prom)
						prom.cancel();
				});
			};
		var resolve2 = noCancel ? resolve : function (value) {
			resolve(value);
			cancel();
		};
		var reject2 = noCancel ? reject : function (reason) {
			reject(reason);
			cancel();
		};
		for (var i = 0; i < proms.length; ++i) {
			if (isThenable(proms[i]))
				proms[i].then(resolve2, reject2);
			else {
				resolve(proms[i]);
				if (!noCancel)
					cancel();
				return;
			}
		}
	});
	prom.cancel = cancel;
	return prom;
};

// Lance une requête AJAX
function ajax(params) {
	var jqXHR = jQuery.ajax(params);
	var cancel, prom = new Promise(function (resolve, reject) {
		jqXHR.then(function (data, textStatus, jqXHR) {
			resolve({ data: data, textStatus: textStatus, jqXHR: jqXHR });
		}, function (jqXHR, textStatus, errorThrown) {
			var e = new Error("Erreur AJAX : " + textStatus);
			e.jqXHR = jqXHR;
			e.textStatus = textStatus;
			e.errorThrown = errorThrown;
			reject(e);
		});
		cancel = function () {
			jqXHR.abort();
			reject(new Error('Opération annulée'));
		};
	});
	prom.cancel = cancel;
	prom.jqXHR = jqXHR;
	return prom;
}

// Attend un délai
function sleep(msec) {
	var cancel, prom = new Promise(function (resolve, reject) {
		var timer = setTimeout(function () {
			resolve();
		}, msec);
		cancel = function () {
			clearTimeout(timer);
			reject(new Error('Opération annulée'));
		};
	});
	prom.cancel = cancel;
	return prom;
}

// Limite le temps alloué à la résolution d'une Promise
function timeout(prom, msec, noCancel) {
	var cancel, prom2 = new Promise(function (resolve, reject) {
		var timer = setTimeout(function () {
			reject(new Error('Délai d\'attente de l\'opération dépassé'));
			if (!noCancel && 'cancel' in prom)
				prom.cancel();
		}, msec);
		prom.then(function (value) {
			clearTimeout(timer);
			resolve(value);
		}, function (reason) {
			clearTimeout(timer);
			reject(reason);
		});
		if (!noCancel && 'cancel' in prom) {
			cancel = function () {
				clearTimeout(timer);
				prom.cancel();
				reject(new Error('Opération annulée'));
			};
		}
	});
	if (!noCancel && 'cancel' in prom)
		prom2.cancel = cancel;
	return prom2;
}

// Attend un évènement HTML
function wait(obj, ev, predicate) {
	var $obj = jQuery(obj);
	var cancel, prom = new Promise(function (resolve, reject) {
		var callback = predicate ? function (e) {
			if (predicate(e)) {
				$obj.off(ev, callback);
				resolve(e);
			}
		} : function (e) {
			$obj.off(ev, callback);
			resolve(e);
		};
		cancel = function () {
			$obj.off(ev, callback);
			reject(new Error('Opération annulée'));
		};
		$obj.on(ev, callback);
	});
	prom.cancel = cancel;
	return prom;
}

// Récupère une image depuis le serveur, un blob, ou un champ fichier
function getImage(url) {
	if (url) {
		var addCleanup = false;
		if (typeof url == 'object') {
			if (url instanceof Image)
				return Promise.resolve(url);
			if (url == window)
				return Promise.reject(new Error("URL ou objet requis"));
			if ('files' in url) {
				if (url.files.length == 0)
					return Promise.reject(new Error("Ce champ ne contient aucun fichier"));
				url = url.files[0];
			}
			if ('slice' in url) {
				url = URL.createObjectURL(url);
				addCleanup = true;
			}
		}
		var cancel, prom = new Promise(function (resolve, reject) {
			var img = new Image();
			var $img = jQuery(img);
			var load = function () {
				$img.off('load', load);
				$img.off('error', error);
				resolve(img);
			};
			var error = function (ev) {
				$img.off('load', load);
				$img.off('error', error);
				var e = new Error("Impossible de charger l'image");
				e.event = ev;
				e.image = img;
				reject(e);
			};
			this.cancel = function () {
				$img.off('load', load);
				$img.off('error', error);
				reject(new Error('Opération annulée'));
			};
			$img.on('load', load);
			$img.on('error', error);
			img.src = url;
			if (addCleanup)
				img.cleanup = function () { URL.revokeObjectURL(url); };
		});
		prom.cancel = cancel;
		return prom;
	} else
		return Promise.reject(new Error("URL ou objet requis"));
}

function barrier(named, noCancel) {
	var cancel, open;
	var bo = {
		promise: null,
		open: null,
		counter: 1
	};
	bo.open = function (value) {
		if (--bo.counter == 0)
			open(value);
	};
	if (named) {
		bo.name = 'barrier$open$' + luid();
		bo.inlineHandlerCode = bo.name + '(event);';
	}
	bo.promise = new Promise(function (resolve, reject) {
		open = named ? function (value) {
			try {
				delete window[bo.name];
			} catch (e) { }
			resolve(value);
		} : resolve;
		if (!noCancel)
			cancel = named ? function () {
				try {
					delete window[name];
				} catch (e) { }
				reject(new Error('Opération annulée'));
			} : function () {
				reject(new Error('Opération annulée'));
			};
	});
	if (!noCancel)
		bo.promise.cancel = cancel;
	if (named)
		window[bo.name] = bo.open;
	return bo;
}
