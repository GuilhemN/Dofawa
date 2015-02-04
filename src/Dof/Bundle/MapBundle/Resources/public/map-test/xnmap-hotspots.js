var XNMapHotSpotLayer = function XNMapHotSpotLayer(hotSpots) {
  "use strict";
  $traceurRuntime.superCall(this, $XNMapHotSpotLayer.prototype, "constructor", []);
  this.hotSpots = new RangeIndex();
  this.onMapViewportChange = this.onMapViewportChange.bind(this);
  for (var $__1 = hotSpots[Symbol.iterator](),
      $__2; !($__2 = $__1.next()).done; ) {
    var hotSpot = $__2.value;
    this.addHotSpot(hotSpot);
  }
};
var $XNMapHotSpotLayer = XNMapHotSpotLayer;
($traceurRuntime.createClass)(XNMapHotSpotLayer, {
  addHotSpot: function(hotSpot) {
    "use strict";
  },
  removeHotSpot: function(hotSpot) {
    "use strict";
  },
  get hasBounds() {
    "use strict";
    return false;
  },
  attach: function(element) {
    "use strict";
    this.detach();
    this.element = element;
    addCssClass(element, 'XNMapHotSpotLayer');
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
    removeCssClass(element, 'XNMapHotSpotLayer');
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
  }
}, {}, XNMapLayer);
