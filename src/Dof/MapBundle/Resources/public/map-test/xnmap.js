var XNMap = function XNMap(layers) {
  "use strict";
  $traceurRuntime.superCall(this, $XNMap.prototype, "constructor", []);
  this.layers = layers;
  for (var $__1 = this.layers[Symbol.iterator](),
      $__2; !($__2 = $__1.next()).done; ) {
    var layer = $__2.value;
    layer.map = this;
  }
  this.scale = 1;
  this.scrollLeft = 0;
  this.scrollTop = 0;
  this.onElementWheel = this.onElementWheel.bind(this);
  this.onElementClick = this.onElementClick.bind(this);
  this.onElementMouseDown = this.onElementMouseDown.bind(this);
  this.onDocumentMouseMove = this.onDocumentMouseMove.bind(this);
  this.onDocumentMouseUp = this.onDocumentMouseUp.bind(this);
  this.onElementTouchStart = this.onElementTouchStart.bind(this);
  this.onElementTouchMove = this.onElementTouchMove.bind(this);
  this.onElementTouchEnd = this.onElementTouchEnd.bind(this);
  this.ongoingTouches = [];
  this.panStartX = null;
  this.panStartY = null;
  this.panStartScrollLeft = null;
  this.panStartScrollTop = null;
  this.zoomStartDistance = null;
  this.zoomStartScale = null;
  this._swallowClick = false;
};
var $XNMap = XNMap;
($traceurRuntime.createClass)(XNMap, {
  addLayer: function(layer, index) {
    "use strict";
    if (layer.map)
      layer.map.removeLayer(layer);
    if (typeof index == 'undefined') {
      index = this.layers.length;
      this.layers.push(layer);
    } else
      this.layers.splice(index, 0, layer);
    if (this.element) {
      var element = document.createElement('div');
      this.element.insertBefore(element, this.element.childNodes[index]);
      layer.attach(element);
    }
    layer.map = this;
  },
  removeLayer: function(layer) {
    "use strict";
    var i;
    if (layer.map !== this || (i = this.layers.indexOf(layer)) < 0)
      throw new Error("Cannot remove layer which is not ours");
    if (this.element) {
      this.layers[i].detach();
      this.element.removeChild(this.element.childNodes[i]);
    }
    this.layers.splice(i, 1);
    layer.map = null;
  },
  attach: function(element) {
    "use strict";
    this.detach();
    this.element = element;
    addCssClass(element, 'XNMap');
    element.addEventListener('wheel', this.onElementWheel, false);
    element.addEventListener('click', this.onElementClick, false);
    element.addEventListener('mousedown', this.onElementMouseDown, false);
    element.addEventListener('touchstart', this.onElementTouchStart, false);
    element.addEventListener('touchmove', this.onElementTouchMove, false);
    element.addEventListener('touchend', this.onElementTouchEnd, false);
    element.addEventListener('touchleave', this.onElementTouchEnd, false);
    element.addEventListener('touchcancel', this.onElementTouchEnd, false);
    for (var $__1 = this.layers[Symbol.iterator](),
        $__2; !($__2 = $__1.next()).done; ) {
      var layer = $__2.value;
      {
        var element2 = document.createElement('div');
        element.appendChild(element2);
        layer.attach(element2);
      }
    }
  },
  detach: function() {
    "use strict";
    var element = this.element;
    if (!element)
      return;
    this.element = null;
    for (var $__1 = this.layers[Symbol.iterator](),
        $__2; !($__2 = $__1.next()).done; ) {
      var layer = $__2.value;
      {
        layer.detach();
        element.removeChild(element.firstChild);
      }
    }
    element.removeEventListener('wheel', this.onElementWheel, false);
    element.removeEventListener('click', this.onElementClick, false);
    element.removeEventListener('mousedown', this.onElementMouseDown, false);
    element.removeEventListener('touchstart', this.onElementTouchStart, false);
    element.removeEventListener('touchmove', this.onElementTouchMove, false);
    element.removeEventListener('touchend', this.onElementTouchEnd, false);
    element.removeEventListener('touchleave', this.onElementTouchEnd, false);
    element.removeEventListener('touchcancel', this.onElementTouchEnd, false);
    removeCssClass(element, 'XNMap');
  },
  getOngoingTouchIndexById: function(id) {
    "use strict";
    for (var i = 0; i < this.ongoingTouches.length; ++i)
      if (this.ongoingTouches[i].id == id)
        return i;
    return -1;
  },
  onElementWheel: function(ev) {
    "use strict";
    ev.preventDefault();
    var oldScale = this.scale;
    switch (ev.deltaMode) {
      case 0:
        this.scale *= Math.pow(0.9, ev.deltaY / 100);
        break;
      case 1:
        this.scale *= Math.pow(0.9, ev.deltaY / 3);
        break;
      default:
        console.warn("Unsupported wheel delta mode " + ev.deltaMode);
        break;
    }
    this.adjustScrollAndScale();
    if (oldScale !== this.scale) {
      var elemX = ev.clientX,
          elemY = ev.clientY;
      var elemRect = this.element.getBoundingClientRect();
      elemX -= elemRect.left;
      elemY -= elemRect.top;
      this.scrollLeft += elemX / oldScale - elemX / this.scale;
      this.scrollTop += elemY / oldScale - elemY / this.scale;
      if (this.panStartScrollLeft !== null) {
        this.panStartScrollLeft = this.scrollLeft - (this.panStartX - ev.clientX) / this.scale;
        this.panStartScrollTop = this.scrollTop - (this.panStartY - ev.clientY) / this.scale;
      }
      this.adjustScrollAndScale();
    }
    this.dispatchEvent(new FakeEvent('viewportchange', true, false));
  },
  onElementClick: function(ev) {
    "use strict";
    if (this._swallowClick)
      return;
    var ev2 = new FakeEvent('click', true, true);
    ev2.clientX = ev.clientX;
    ev2.clientY = ev.clientY;
    ev2.pageX = ev.pageX;
    ev2.pageY = ev.pageY;
    ev2.layerX = ev.layerX;
    ev2.layerY = ev.layerY;
    var elemX = ev.clientX,
        elemY = ev.clientY;
    var elemRect = this.element.getBoundingClientRect();
    elemX -= elemRect.left;
    elemY -= elemRect.top;
    ev2.elementX = elemX;
    ev2.elementY = elemY;
    ev2.mapX = this.scrollLeft + elemX / this.scale;
    ev2.mapY = this.scrollTop + elemY / this.scale;
    ev2.nativeEvent = ev;
    if (!this.dispatchEvent(ev2))
      ev.preventDefault();
  },
  onElementMouseDown: function(ev) {
    "use strict";
    if (ev.button != 0 && ev.button != 1)
      return;
    ev.preventDefault();
    document.addEventListener('mousemove', this.onDocumentMouseMove, false);
    document.addEventListener('mouseup', this.onDocumentMouseUp, false);
    this.panStartX = ev.clientX;
    this.panStartY = ev.clientY;
    this.panStartScrollLeft = this.scrollLeft;
    this.panStartScrollTop = this.scrollTop;
    this._swallowClick = false;
  },
  onDocumentMouseMove: function(ev) {
    "use strict";
    ev.preventDefault();
    if (Math.abs(ev.clientX - this.panStartX) >= 5 || Math.abs(ev.clientY - this.panStartY) >= 5)
      this._swallowClick = true;
    this.element.style.cursor = "move";
    this.scrollLeft = this.panStartScrollLeft + (this.panStartX - ev.clientX) / this.scale;
    this.scrollTop = this.panStartScrollTop + (this.panStartY - ev.clientY) / this.scale;
    this.adjustScrollAndScale();
    this.dispatchEvent(new FakeEvent('viewportchange', true, false));
  },
  onDocumentMouseUp: function(ev) {
    "use strict";
    if (ev.button != 0 && ev.button != 1)
      return;
    ev.preventDefault();
    this.element.style.cursor = "";
    document.removeEventListener('mousemove', this.onDocumentMouseMove, false);
    document.removeEventListener('mouseup', this.onDocumentMouseUp, false);
    this.panStartX = null;
    this.panStartY = null;
    this.panStartScrollLeft = null;
    this.panStartScrollTop = null;
  },
  updateTouchStartParams: function(oldETC, newETC) {
    "use strict";
    if (newETC === null) {
      this.panStartX = null;
      this.panStartY = null;
      this.panStartScrollLeft = null;
      this.panStartScrollTop = null;
      this.zoomStartDistance = null;
      this.zoomStartScale = null;
    } else {
      if (oldETC === null) {
        this.panStartX = newETC.x;
        this.panStartY = newETC.y;
        this.panStartScrollLeft = this.scrollLeft;
        this.panStartScrollTop = this.scrollTop;
      } else {
        this.panStartX += newETC.x - oldETC.x;
        this.panStartY += newETC.y - oldETC.y;
      }
      if (newETC.distance === null) {
        this.zoomStartDistance = null;
        this.zoomStartScale = null;
      } else if (oldETC.distance === null) {
        this.zoomStartDistance = newETC.distance;
        this.zoomStartScale = this.scale;
      } else
        this.zoomStartDistance *= newETC.distance / oldETC.distance;
    }
  },
  onElementTouchStart: function(ev) {
    "use strict";
    ev.preventDefault();
    var oldETC = this.getEffectiveTouchCoordinates();
    for (var $__1 = $traceurRuntime.spread(ev.changedTouches)[Symbol.iterator](),
        $__2; !($__2 = $__1.next()).done; ) {
      var touch = $__2.value;
      {
        var touch2 = {
          id: touch.identifier,
          x: touch.clientX,
          y: touch.clientY
        };
        var index = this.getOngoingTouchIndexById(touch2.id);
        if (index >= 0)
          this.ongoingTouches[index] = touch2;
        else
          this.ongoingTouches.push(touch2);
      }
    }
    var newETC = this.getEffectiveTouchCoordinates();
    this.updateTouchStartParams(oldETC, newETC);
    this._swallowClick = this.ongoingTouches.length != 1;
  },
  onElementTouchMove: function(ev) {
    "use strict";
    ev.preventDefault();
    var oldETC = this.getEffectiveTouchCoordinates();
    if (oldETC === null)
      return;
    for (var $__1 = $traceurRuntime.spread(ev.changedTouches)[Symbol.iterator](),
        $__2; !($__2 = $__1.next()).done; ) {
      var touch = $__2.value;
      {
        var index = this.getOngoingTouchIndexById(touch.identifier);
        if (index >= 0)
          this.ongoingTouches[index] = {
            id: touch.identifier,
            x: touch.clientX,
            y: touch.clientY
          };
      }
    }
    var newETC = this.getEffectiveTouchCoordinates();
    if (newETC === null || oldETC.x === newETC.x && oldETC.y === newETC.y && oldETC.distance === newETC.distance)
      return;
    if (Math.abs(newETC.x - this.panStartX) >= 5 || Math.abs(newETC.y - this.panStartY) >= 5)
      this._swallowClick = true;
    var oldScale = this.scale;
    this.scrollLeft = this.panStartScrollLeft + (this.panStartX - newETC.x) / oldScale;
    this.scrollTop = this.panStartScrollTop + (this.panStartY - newETC.y) / oldScale;
    if (this.zoomStartScale !== null && newETC.distance !== null)
      this.scale = this.zoomStartScale * newETC.distance / this.zoomStartDistance;
    this.adjustScrollAndScale();
    if (this.zoomStartScale !== null && newETC.distance !== null && oldScale !== this.scale) {
      var elemX = newETC.x,
          elemY = newETC.y;
      var elemRect = this.element.getBoundingClientRect();
      elemX -= elemRect.left;
      elemY -= elemRect.top;
      this.scrollLeft += elemX / oldScale - elemX / this.scale;
      this.scrollTop += elemY / oldScale - elemY / this.scale;
      this.panStartScrollLeft = this.scrollLeft - (this.panStartX - newETC.x) / this.scale;
      this.panStartScrollTop = this.scrollTop - (this.panStartY - newETC.y) / this.scale;
      this.adjustScrollAndScale();
    }
    this.dispatchEvent(new FakeEvent('viewportchange', true, false));
  },
  onElementTouchEnd: function(ev) {
    "use strict";
    ev.preventDefault();
    var oldETC = this.getEffectiveTouchCoordinates();
    for (var $__1 = $traceurRuntime.spread(ev.changedTouches)[Symbol.iterator](),
        $__2; !($__2 = $__1.next()).done; ) {
      var touch = $__2.value;
      {
        var index = this.getOngoingTouchIndexById(touch.identifier);
        if (index >= 0)
          this.ongoingTouches.splice(index, 1);
      }
    }
    var newETC = this.getEffectiveTouchCoordinates();
    this.updateTouchStartParams(oldETC, newETC);
    if (newETC !== null || ev.type != 'touchend' || this._swallowClick)
      return;
    var ev2 = new FakeEvent('click', true, false);
    ev2.clientX = oldETC.x;
    ev2.clientY = oldETC.y;
    ev2.pageX = oldETC.x + pageXOffset;
    ev2.pageY = oldETC.y + pageYOffset;
    ev2.layerX = null;
    ev2.layerY = null;
    var elemX = oldETC.x,
        elemY = oldETC.y;
    var elemRect = this.element.getBoundingClientRect();
    elemX -= elemRect.left;
    elemY -= elemRect.top;
    ev2.elementX = elemX;
    ev2.elementY = elemY;
    ev2.mapX = this.scrollLeft + elemX / this.scale;
    ev2.mapY = this.scrollTop + elemY / this.scale;
    ev2.nativeEvent = ev;
    this.dispatchEvent(ev2);
  },
  getEffectiveTouchCoordinates: function() {
    "use strict";
    var touches = this.ongoingTouches,
        a,
        b,
        dx,
        dy;
    switch (touches.length) {
      case 0:
        return null;
      case 1:
        a = touches[0];
        return {
          x: a.x,
          y: a.y,
          distance: null
        };
      default:
        a = touches[0];
        b = touches[1];
        dx = a.x - b.x;
        dy = a.y - b.y;
        return {
          x: (a.x + b.x) / 2,
          y: (a.y + b.y) / 2,
          distance: Math.sqrt(dx * dx + dy * dy)
        };
    }
  },
  adjustScrollAndScale: function() {
    "use strict";
    if (this.layers.length == 0) {
      this.scrollLeft = 0;
      this.scrollTop = 0;
      this.scale = 1;
    } else {
      var left = Infinity,
          top = Infinity,
          right = -Infinity,
          bottom = -Infinity;
      for (var $__1 = this.layers[Symbol.iterator](),
          $__2; !($__2 = $__1.next()).done; ) {
        var layer = $__2.value;
        {
          if (layer.hasBounds) {
            left = Math.min(left, layer.left);
            top = Math.min(top, layer.top);
            right = Math.max(right, layer.left + layer.width);
            bottom = Math.max(bottom, layer.top + layer.height);
          }
        }
      }
      var width = right - left,
          height = bottom - top;
      if (this.scale > 1)
        this.scale = 1;
      var ewidth = this.elementWidth,
          eheight = this.elementHeight;
      var minScale = Math.max(ewidth / width, eheight / height);
      if (this.scale < minScale)
        this.scale = minScale;
      this.scrollLeft = Math.max(left, Math.min(right - ewidth / this.scale, Math.round(this.scrollLeft)));
      this.scrollTop = Math.max(top, Math.min(bottom - eheight / this.scale, Math.round(this.scrollTop)));
    }
  },
  createSimilar: function() {
    "use strict";
    return new $XNMap(this.layers.map((function(layer) {
      return layer.createSimilar();
    })));
  },
  get elementWidth() {
    "use strict";
    if (this.element)
      return this.element.clientWidth;
    return 0;
  },
  get elementHeight() {
    "use strict";
    if (this.element)
      return this.element.clientHeight;
    return 0;
  },
  get mapScrollLeft() {
    "use strict";
    return this.scrollLeft;
  },
  get mapScrollTop() {
    "use strict";
    return this.scrollTop;
  },
  get mapElementWidth() {
    "use strict";
    return this.elementWidth / this.scale;
  },
  get mapElementHeight() {
    "use strict";
    return this.elementHeight / this.scale;
  }
}, {}, FakeEventTarget);
var XNMapLayer = function XNMapLayer() {
  "use strict";
  $traceurRuntime.superCall(this, $XNMapLayer.prototype, "constructor", []);
};
var $XNMapLayer = XNMapLayer;
($traceurRuntime.createClass)(XNMapLayer, {
  get map() {
    "use strict";
    return this._eventParent;
  },
  set map(value) {
    "use strict";
    this._eventParent = value;
  },
  get hasBounds() {
    "use strict";
    return false;
  }
}, {}, FakeEventTarget);
var XNMapTiledLayer = function XNMapTiledLayer(left, top, tileWidth, tileHeight, tileXCount, tileYCount, scalePowers, loadOffScreen, getTile) {
  "use strict";
  $traceurRuntime.superCall(this, $XNMapTiledLayer.prototype, "constructor", []);
  this.left = left;
  this.top = top;
  this.tileWidth = tileWidth;
  this.tileHeight = tileHeight;
  this.tileXCount = tileXCount;
  this.tileYCount = tileYCount;
  this.scalePowers = scalePowers;
  this.loadOffScreen = loadOffScreen;
  this.getTile = getTile;
  this.onMapViewportChange = this.onMapViewportChange.bind(this);
  this._images = [];
  this._imagesByKey = Object.create(null);
  this.effectiveScalePower = 0;
  this._mapLeft = 0;
  this._mapTop = 0;
  this._mapRight = 0;
  this._mapBottom = 0;
};
var $XNMapTiledLayer = XNMapTiledLayer;
($traceurRuntime.createClass)(XNMapTiledLayer, {
  get hasBounds() {
    "use strict";
    return true;
  },
  get width() {
    "use strict";
    return this.tileWidth * this.tileXCount;
  },
  get height() {
    "use strict";
    return this.tileHeight * this.tileYCount;
  },
  attach: function(element) {
    "use strict";
    this.detach();
    this.element = element;
    addCssClass(element, 'XNMapTiledLayer');
    map.addEventListener('viewportchange', this.onMapViewportChange, false);
    this.updateViewport();
  },
  detach: function() {
    "use strict";
    var element = this.element;
    if (!element)
      return;
    this.element = null;
    map.removeEventListener('viewportchange', this.onMapViewportChange, false);
    while (element.lastChild)
      element.removeChild(element.lastChild);
    this._images.length = 0;
    this._imagesByKey = Object.create(null);
    removeCssClass(element, 'XNMapTiledLayer');
  },
  onMapViewportChange: function(ev) {
    "use strict";
    if (this.element)
      this.updateViewport();
  },
  updateViewport: function() {
    "use strict";
    if (!this.element)
      return;
    var realScalePower = Math.ceil(Math.log(this.map.scale) / Math.log(2));
    var effectiveScalePower = Infinity;
    for (var $__1 = this.scalePowers[Symbol.iterator](),
        $__2; !($__2 = $__1.next()).done; ) {
      var scalePower = $__2.value;
      if (scalePower >= realScalePower && scalePower < effectiveScalePower)
        effectiveScalePower = scalePower;
    }
    if (effectiveScalePower == Infinity)
      effectiveScalePower = Math.max.apply(Math, this.scalePowers);
    if (!isFinite(effectiveScalePower))
      effectiveScalePower = 0;
    this.effectiveScalePower = effectiveScalePower;
    this.element.style.transform = "scale(" + this.map.scale + ")";
    this.element.style.WebkitTransform = "scale(" + this.map.scale + ")";
    var effectiveTileWidth = lrShift(this.tileWidth, -effectiveScalePower);
    var effectiveTileHeight = lrShift(this.tileHeight, -effectiveScalePower);
    var effectiveTileXCount = lrShift(this.tileXCount, effectiveScalePower);
    var effectiveTileYCount = lrShift(this.tileYCount, effectiveScalePower);
    var tileLeft = Math.max(0, Math.floor((this.map.mapScrollLeft - this.left) / effectiveTileWidth) - this.loadOffScreen);
    var tileTop = Math.max(0, Math.floor((this.map.mapScrollTop - this.top) / effectiveTileHeight) - this.loadOffScreen);
    var tileRight = Math.min(effectiveTileXCount, Math.ceil((this.map.mapScrollLeft + this.map.mapElementWidth - this.left) / effectiveTileWidth) + this.loadOffScreen);
    var tileBottom = Math.min(effectiveTileYCount, Math.ceil((this.map.mapScrollTop + this.map.mapElementHeight - this.top) / effectiveTileHeight) + this.loadOffScreen);
    var mapLeft = this.left + tileLeft * effectiveTileWidth;
    var mapTop = this.top + tileTop * effectiveTileHeight;
    var mapRight = this.left + tileRight * effectiveTileWidth;
    var mapBottom = this.top + tileBottom * effectiveTileHeight;
    this._mapLeft = mapLeft;
    this._mapTop = mapTop;
    this._mapRight = mapRight;
    this._mapBottom = mapBottom;
    this.element.style.left = Math.round((mapLeft - this.map.mapScrollLeft) * this.map.scale) + "px";
    this.element.style.top = Math.round((mapTop - this.map.mapScrollTop) * this.map.scale) + "px";
    for (var i = this._images.length; i-- > 0; ) {
      var image = this._images[i];
      if (image.right <= mapLeft || image.bottom <= mapTop || image.left >= mapRight || image.top >= mapBottom)
        this._destroyImage(image, i);
      else {
        if (image.element)
          this._updateImageBounds(image);
      }
    }
    for (var y = tileTop; y < tileBottom; ++y) {
      for (var x = tileLeft; x < tileRight; ++x) {
        var key = effectiveScalePower + ":" + y + ":" + x;
        if (!(key in this._imagesByKey)) {
          var image = {
            layer: this,
            left: this.left + x * effectiveTileWidth,
            top: this.top + y * effectiveTileHeight,
            right: this.left + (x + 1) * effectiveTileWidth,
            bottom: this.top + (y + 1) * effectiveTileHeight,
            scalePower: effectiveScalePower,
            key: key,
            promise: Promise.resolve(this.getTile(x, y, effectiveScalePower)),
            obscures: []
          };
          for (var $__3 = this._images[Symbol.iterator](),
              $__4; !($__4 = $__3.next()).done; ) {
            var image2 = $__4.value;
            {
              if (Math.max(image.left, image2.left) < Math.min(image.right, image2.right) && Math.max(image.top, image2.top) < Math.min(image.bottom, image2.bottom)) {
                image.obscures.push(image2.key);
                image2.obscures.push(image.key);
              }
            }
          }
          this._images.push(image);
          this._imagesByKey[key] = image;
          image.promise.then((function(element) {
            if (this.layer._imagesByKey[this.key] !== this)
              return;
            this.element = element;
            if (element) {
              element.draggable = false;
              element.setAttribute('data-key', this.key);
              element.width = effectiveTileWidth;
              element.height = effectiveTileHeight;
              this.layer._updateImageBounds(this);
            }
            if (this.element)
              this.layer.element.appendChild(element);
          }).bind(image), function() {});
        }
      }
    }
  },
  _updateImageBounds: function(image) {
    "use strict";
    image.visibleLeft = Math.max(this._mapLeft, image.left);
    image.visibleTop = Math.max(this._mapTop, image.top);
    image.visibleRight = Math.min(this._mapRight, image.right);
    image.visibleBottom = Math.min(this._mapBottom, image.bottom);
    image.visibleArea = (image.visibleRight - image.visibleLeft) * (image.visibleBottom - image.visibleTop);
    image.element.style.left = (image.left - this._mapLeft) + "px";
    image.element.style.top = (image.top - this._mapTop) + "px";
    if (image.scalePower == this.effectiveScalePower) {
      for (var $__1 = image.obscures.slice()[Symbol.iterator](),
          $__2; !($__2 = $__1.next()).done; ) {
        var key = $__2.value;
        {
          var im = this._imagesByKey[key];
          if (im)
            this._destroyImageIfFullyObscured(im);
        }
      }
    } else
      this._destroyImageIfFullyObscured(image);
  },
  _destroyImageIfFullyObscured: function(image) {
    "use strict";
    var area = image.visibleArea;
    for (var $__1 = image.obscures[Symbol.iterator](),
        $__2; !($__2 = $__1.next()).done; ) {
      var key = $__2.value;
      {
        var im = this._imagesByKey[key];
        if (im && im.scalePower == this.effectiveScalePower) {
          area -= im.visibleArea;
          if (area <= 0) {
            this._destroyImage(image);
            return;
          }
        }
      }
    }
  },
  _destroyImage: function(image, index) {
    "use strict";
    var i;
    if (image.promise && 'cancel' in image.promise)
      image.promise.cancel();
    if (image.element) {
      if (image.element.parentNode)
        this.element.removeChild(image.element);
      image.element = null;
    }
    for (var $__1 = image.obscures[Symbol.iterator](),
        $__2; !($__2 = $__1.next()).done; ) {
      var key = $__2.value;
      {
        var im = this._imagesByKey[key];
        if (im) {
          var i = im.obscures.indexOf(image.key);
          if (i >= 0)
            im.obscures.splice(i, 1);
        }
      }
    }
    image.obscures = [];
    if (typeof index == 'undefined')
      index = this._images.indexOf(image);
    if (index >= 0)
      this._images.splice(index, 1);
    delete this._imagesByKey[image.key];
  },
  createSimilar: function() {
    "use strict";
    return new $XNMapTiledLayer(this.left, this.top, this.tileWidth, this.tileHeight, this.tileXCount, this.tileYCount, this.scalePowers, this.loadOffScreen, this.getTile);
  }
}, {}, XNMapLayer);
var XNMapGridLayer = function XNMapGridLayer(offsetX, offsetY, cellWidth, cellHeight, lineWidth, strokeStyle, lineDash, lineDashOffset) {
  "use strict";
  $traceurRuntime.superCall(this, $XNMapGridLayer.prototype, "constructor", []);
  this.offsetX = offsetX;
  this.offsetY = offsetY;
  this.cellWidth = cellWidth;
  this.cellHeight = cellHeight;
  this.lineWidth = lineWidth;
  this.strokeStyle = strokeStyle;
  this.lineDash = lineDash;
  this.lineDashOffset = lineDashOffset;
  this.onMapViewportChange = this.onMapViewportChange.bind(this);
  this._canvas = document.createElement('canvas');
  this.scale = 1;
};
var $XNMapGridLayer = XNMapGridLayer;
($traceurRuntime.createClass)(XNMapGridLayer, {
  attach: function(element) {
    "use strict";
    this.detach();
    this.element = element;
    addCssClass(element, 'XNMapGridLayer');
    map.addEventListener('viewportchange', this.onMapViewportChange, false);
    element.appendChild(this._canvas);
    this.updateViewport();
  },
  detach: function() {
    "use strict";
    var element = this.element;
    if (!element)
      return;
    this.element = null;
    map.removeEventListener('viewportchange', this.onMapViewportChange, false);
    element.removeChild(this._canvas);
    removeCssClass(element, 'XNMapGridLayer');
  },
  onMapViewportChange: function(ev) {
    "use strict";
    if (this.element)
      this.updateViewport();
  },
  updateViewport: function() {
    "use strict";
    if (!this.element)
      return;
    var cwidth = this.cellWidth * this.map.scale;
    var cheight = this.cellHeight * this.map.scale;
    var wantedWidth = this.map.elementWidth + cwidth + cwidth - 1;
    wantedWidth -= wantedWidth % cwidth;
    wantedWidth = Math.ceil(Math.max(cwidth + cwidth, wantedWidth));
    var wantedHeight = this.map.elementHeight + cheight + cheight - 1;
    wantedHeight -= wantedHeight % cheight;
    wantedHeight = Math.ceil(Math.max(cheight + cheight, wantedHeight));
    if (this._canvas.width != wantedWidth || this._canvas.height != wantedHeight) {
      this._canvas.width = wantedWidth;
      this._canvas.height = wantedHeight;
      this.scale = this.map.scale;
      this.redraw();
    } else if (this.scale != this.map.scale) {
      this.scale = this.map.scale;
      this.redraw();
    }
    var left = ((this.map.mapScrollLeft - this.offsetX) % this.cellWidth) * this.scale;
    var top = ((this.map.mapScrollTop - this.offsetY) % this.cellHeight) * this.scale;
    if (left < 0)
      left += cwidth;
    if (top < 0)
      top += cheight;
    this.element.style.left = -Math.round(left) + 'px';
    this.element.style.top = -Math.round(top) + 'px';
  },
  redraw: function() {
    "use strict";
    var width = this._canvas.width,
        height = this._canvas.height;
    var cwidth = this.cellWidth * this.scale,
        cheight = this.cellHeight * this.scale;
    var context = this._canvas.getContext('2d');
    context.clearRect(0, 0, width, height);
    context.lineWidth = (typeof this.lineWidth == 'function') ? this.lineWidth(context) : this.lineWidth;
    context.strokeStyle = (typeof this.strokeStyle == 'function') ? this.strokeStyle(context) : this.strokeStyle;
    if (this.lineDash != null && 'setLineDash' in context) {
      context.setLineDash((typeof this.lineDash == 'function') ? this.lineDash(context) : this.lineDash);
      context.lineDashOffset = (typeof this.lineDashOffset == 'function') ? this.lineDashOffset(context) : this.lineDashOffset;
    }
    var xlat = (context.lineWidth & 1) / 2;
    context.beginPath();
    for (var x = 0; x <= width; x += cwidth) {
      context.moveTo(Math.round(x) + xlat, 0);
      context.lineTo(Math.round(x) + xlat, height);
    }
    for (var y = 0; y <= height; y += cheight) {
      context.moveTo(0, Math.round(y) + xlat);
      context.lineTo(width, Math.round(y) + xlat);
    }
    context.stroke();
  }
}, {}, XNMapLayer);
