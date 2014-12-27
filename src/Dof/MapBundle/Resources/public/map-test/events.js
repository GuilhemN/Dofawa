var FakeEvent = function FakeEvent(type, bubbles, cancelable) {
  "use strict";
  this.type = type;
  this.namespaceURI = null;
  this.bubbles = bubbles;
  this.cancelable = cancelable;
  this.target = null;
  this.currentTarget = null;
  this.eventPhase = 0;
  this.defaultPrevented = false;
  this._propagationStopped = false;
  this._immediatePropagationStopped = false;
  this.timeStamp = Date.now();
};
($traceurRuntime.createClass)(FakeEvent, {
  preventDefault: function() {
    "use strict";
    if (this.cancelable)
      this.defaultPrevented = true;
  },
  stopPropagation: function() {
    "use strict";
    this._propagationStopped = true;
  },
  stopImmediatePropagation: function() {
    "use strict";
    this._isImmediatePropagationStopped = true;
  }
}, {});
var FakeEventTarget = function FakeEventTarget() {
  "use strict";
  this._eventParent = null;
  this._eventHandlers = [Object.create(null), Object.create(null)];
};
($traceurRuntime.createClass)(FakeEventTarget, {
  addEventListener: function(name, func, capture) {
    "use strict";
    var allHandlers = this._eventHandlers[capture ? 0 : 1];
    var handlers;
    if (name in allHandlers)
      handlers = allHandlers[name];
    else {
      handlers = [];
      allHandlers[name] = handlers;
    }
    handlers.push(func);
  },
  removeEventListener: function(name, func, capture) {
    "use strict";
    var allHandlers = this._eventHandlers[capture ? 0 : 1];
    var handlers;
    if (name in allHandlers)
      handlers = allHandlers[name];
    else
      return;
    var i = handlers.indexOf(func);
    if (i >= 0)
      handlers.splice(i, 1);
  },
  dispatchEvent: function(ev) {
    "use strict";
    var name = ev.type;
    var chain = [];
    if (ev.bubbles) {
      var ancestor = this;
      while ((ancestor = ancestor._eventParent))
        chain.push(ancestor);
    }
    var chainLength = chain.length;
    var dp = ev.defaultPrevented;
    var ps = ev._propagationStopped;
    var ips = ev._immediatePropagationStopped;
    var t = ev.target;
    var ct = ev.currentTarget;
    var ep = ev.eventPhase;
    try {
      ev.defaultPrevented = false;
      ev._propagationStopped = false;
      ev._immediatePropagationStopped = false;
      ev.target = this;
      ev.eventPhase = 1;
      for (var i = chainLength; i-- > 0; ) {
        var ancestor = chain[i];
        var allHandlers = ancestor._eventHandlers[0];
        if (name in allHandlers) {
          ev.currentTarget = ancestor;
          for (var $__1 = allHandlers[name].slice()[Symbol.iterator](),
              $__2; !($__2 = $__1.next()).done; ) {
            var handler = $__2.value;
            {
              handler.call(ancestor, ev);
              if (ev._immediatePropagationStopped)
                return !ev.defaultPrevented;
            }
          }
          if (ev._propagationStopped)
            return !ev.defaultPrevented;
        }
      }
      ev.eventPhase = 2;
      ev.currentTarget = this;
      for (var $__5 = this._eventHandlers[Symbol.iterator](),
          $__6; !($__6 = $__5.next()).done; ) {
        var allHandlers = $__6.value;
        {
          if (name in allHandlers) {
            for (var $__3 = allHandlers[name].slice()[Symbol.iterator](),
                $__4; !($__4 = $__3.next()).done; ) {
              var handler = $__4.value;
              {
                handler.call(this, ev);
                if (ev._immediatePropagationStopped)
                  return !ev.defaultPrevented;
              }
            }
            if (ev._propagationStopped)
              return !ev.defaultPrevented;
          }
        }
      }
      ev.eventPhase = 3;
      for (var $__9 = chain[Symbol.iterator](),
          $__10; !($__10 = $__9.next()).done; ) {
        var ancestor = $__10.value;
        {
          var allHandlers = ancestor._eventHandlers[1];
          if (name in allHandlers) {
            ev.currentTarget = ancestor;
            for (var $__7 = allHandlers[name].slice()[Symbol.iterator](),
                $__8; !($__8 = $__7.next()).done; ) {
              var handler = $__8.value;
              {
                handler.call(ancestor, ev);
                if (ev._immediatePropagationStopped)
                  return !ev.defaultPrevented;
              }
            }
            if (ev._propagationStopped)
              return !ev.defaultPrevented;
          }
        }
      }
      return !ev.defaultPrevented;
    } finally {
      ev.defaultPrevented = dp;
      ev._propagationStopped = ps;
      ev._immediatePropagationStopped = ips;
      ev.target = t;
      ev.currentTarget = ct;
      ev.eventPhase = ep;
    }
  }
}, {});
