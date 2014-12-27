var RangeIndex = function RangeIndex() {
  "use strict";
  this.clear();
};
var $RangeIndex = RangeIndex;
($traceurRuntime.createClass)(RangeIndex, {
  add: function(min) {
    "use strict";
    var max = arguments[1] !== (void 0) ? arguments[1] : min;
    for (var elems = [],
        $__4 = 2; $__4 < arguments.length; $__4++)
      elems[$__4 - 2] = arguments[$__4];
    var $__0 = this;
    max = this.separateMax(min, max);
    this.splitAt(min);
    this.splitAt(max);
    this.process(min, max, (function(data) {
      var $__9;
      return ($__9 = $__0).addToSet.apply($__9, $traceurRuntime.spread([data], elems));
    }));
    this.compact(min, max);
  },
  remove: function(min) {
    "use strict";
    var max = arguments[1] !== (void 0) ? arguments[1] : min;
    for (var elems = [],
        $__5 = 2; $__5 < arguments.length; $__5++)
      elems[$__5 - 2] = arguments[$__5];
    var $__0 = this;
    max = this.separateMax(min, max);
    this.splitAt(min);
    this.splitAt(max);
    this.process(min, max, (function(data) {
      var $__9;
      return ($__9 = $__0).removeFromSet.apply($__9, $traceurRuntime.spread([data], elems));
    }));
    this.compact(min, max);
  },
  query: function(min) {
    "use strict";
    var max = arguments[1] !== (void 0) ? arguments[1] : min;
    var result = arguments[2] !== (void 0) ? arguments[2] : [];
    var $__0 = this;
    this.process(min, max, (function(data) {
      var $__9;
      return ($__9 = $__0).addToSet.apply($__9, $traceurRuntime.spread([result], data));
    }));
    return result;
  },
  clear: function() {
    "use strict";
    this.data = [[-Infinity, Infinity, []]];
  },
  process: function(min) {
    "use strict";
    var max = arguments[1] !== (void 0) ? arguments[1] : min;
    var fn = arguments[2];
    max = this.separateMax(min, max);
    var mini = (min[0] == null) ? 0 : this.binarySearch(min[0]);
    var maxi = (max[0] == null) ? (this.data.length - 1) : Math.max(mini, this.binarySearch(previousNumber(max[0])));
    for (var i = mini; i <= maxi; ++i) {
      var entry = this.data[i];
      if (entry[2] instanceof $RangeIndex)
        entry[2].process((i == mini) ? min.slice(1) : [], (i == maxi) ? max.slice(1) : [], fn);
      else
        fn(entry[2]);
    }
  },
  splitAt: function($__8) {
    "use strict";
    var point = $__8[0],
        subpoint = Array.prototype.slice.call($__8, 1);
    if (point == null || !isFinite(point) || isNaN(point))
      return;
    var i = this.binarySearch(point);
    var entry = this.data[i];
    if (entry[0] != point) {
      var newEntry = [entry[0], previousNumber(point), entry[2].slice()];
      this.data.splice(i, 0, newEntry);
      entry[0] = point;
      ++i;
    }
    if (subpoint.length > 0) {
      if (entry[1] != point) {
        var newEntry = [nextNumber(point), entry[1], entry[2].slice()];
        this.data.splice(i + 1, 0, newEntry);
        entry[1] = point;
      }
      if (!(entry[2] instanceof $RangeIndex)) {
        var data = entry[2];
        entry[2] = new $RangeIndex();
        entry[2].data[0][2] = data;
      }
      entry[2].splitAt(subpoint);
    }
  },
  compact: function(min, max) {
    "use strict";
    var mini = (min[0] == null) ? 0 : this.binarySearch(min[0]);
    var maxi = (max[0] == null) ? (this.data.length - 1) : Math.max(mini, this.binarySearch(previousNumber(max[0])));
    for (var i = mini; i <= maxi; ++i) {
      var entry = this.data[i];
      if (entry[2] instanceof $RangeIndex) {
        entry[2].compact((i == mini) ? min.slice(1) : [], (i == maxi) ? max.slice(1) : []);
        if (entry[2].data.length == 1)
          entry[2] = entry[2].data[0][2];
      }
    }
    if (mini == 0)
      ++mini;
    if (maxi < this.data.length - 1)
      ++maxi;
    var entryA = this.data[maxi],
        entryB;
    for (var i = maxi; i >= mini; --i) {
      entryB = this.data[i - 1];
      if (!(entryA[2] instanceof $RangeIndex) && !(entryB[2] instanceof $RangeIndex) && this.equalsSet(entryA[2], entryB[2])) {
        entryB[1] = entryA[1];
        this.data.splice(i, 1);
      }
      entryA = entryB;
    }
  },
  binarySearch: function(point) {
    "use strict";
    var data = this.data;
    var l = 0,
        u = data.length - 1;
    while (l <= u) {
      var m = (l + u) >> 1;
      if (point < data[m][0])
        u = m - 1;
      else if (point > data[m][1])
        l = m + 1;
      else
        return m;
    }
    return ~l;
  },
  separateMax: function(min, max) {
    "use strict";
    if (min.length != max.length)
      return max;
    for (var i = min.length; i-- > 0; )
      if (min[i] != max[i])
        return max;
    if (max.length > 0) {
      max = max.slice();
      max[max.length - 1] = nextNumber(max[max.length - 1]);
    }
    return max;
  },
  addToSet: function(s) {
    "use strict";
    for (var elems = [],
        $__6 = 1; $__6 < arguments.length; $__6++)
      elems[$__6 - 1] = arguments[$__6];
    for (var $__2 = elems[Symbol.iterator](),
        $__3; !($__3 = $__2.next()).done; ) {
      var elem = $__3.value;
      if (s.indexOf(elem) < 0)
        s.push(elem);
    }
  },
  removeFromSet: function(s) {
    "use strict";
    for (var elems = [],
        $__7 = 1; $__7 < arguments.length; $__7++)
      elems[$__7 - 1] = arguments[$__7];
    var i;
    for (var $__2 = elems[Symbol.iterator](),
        $__3; !($__3 = $__2.next()).done; ) {
      var elem = $__3.value;
      if ((i = s.indexOf(elem)) >= 0)
        s.splice(i, 1);
    }
  },
  equalsSet: function(s, t) {
    "use strict";
    if (s.length != t.length)
      return false;
    var f = 0;
    for (var $__2 = s[Symbol.iterator](),
        $__3; !($__3 = $__2.next()).done; ) {
      var e = $__3.value;
      if (t.indexOf(e) >= 0)
        ++f;
    }
    return f == s.length;
  }
}, {});
