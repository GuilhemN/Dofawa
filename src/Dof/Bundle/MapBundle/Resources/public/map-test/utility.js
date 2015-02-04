function addCssClass(node, clazz) {
  var classes = node.className.trim();
  if (classes)
    classes = classes.split(/ +/g);
  else
    classes = [];
  classes.push(clazz);
  node.className = classes.join(' ');
}
function removeCssClass(node, clazz) {
  var classes = node.className.trim();
  if (classes)
    classes = classes.split(/ +/g);
  else
    classes = [];
  var i = classes.indexOf(clazz);
  if (i >= 0) {
    classes.splice(i, 1);
    node.className = classes.join(' ');
  }
}
function hasCssClass(node, clazz) {
  return (' ' + node.className + ' ').indexOf(' ' + clazz + ' ') >= 0;
}
function toggleCssClass(node, clazz) {
  var classes = node.className.trim();
  if (classes)
    classes = classes.split(/ +/g);
  else
    classes = [];
  var i = classes.indexOf(clazz);
  if (i >= 0)
    classes.splice(i, 1);
  else
    classes.push(clazz);
  node.className = classes.join(' ');
}
function lrShift(value, shift) {
  if (shift >= 0)
    return value << shift;
  else
    return value >> -shift;
}
function nextNumber(num) {
  if (num !== num)
    return num;
  if (num < 0)
    return -previousNumber(-num);
  if (num === Infinity)
    return num;
  var ilog = Math.floor(Math.log2(num));
  return num + Math.max(Number.MIN_VALUE, Math.pow(2, ilog - 52));
}
function previousNumber(num) {
  if (num !== num)
    return num;
  if (num <= 0)
    return -nextNumber(-num);
  if (num === Infinity)
    return Number.MAX_VALUE;
  var log = Math.log2(num);
  var ilog = Math.floor(log);
  if (log == ilog)
    --ilog;
  return num - Math.max(Number.MIN_VALUE, Math.pow(2, ilog - 52));
}
function multiplyTransform(l, r) {
  return [l[0] * r[0] + l[1] * r[2], l[0] * r[1] + l[1] * r[3], l[2] * r[0] + l[3] * r[2], l[2] * r[1] + l[3] * r[3], l[4] * r[0] + l[5] * r[2] + r[4], l[4] * r[1] + l[5] * r[3] + r[5]];
}
function invertTransform(t) {
  var det = t[0] * t[3] - t[1] * t[2];
  return [t[3] / det, -t[1] / det, -t[2] / det, t[0] / det, (t[2] * t[5] - t[3] * t[4]) / det, (t[1] * t[4] - t[0] * t[5]) / det];
}
function transformPoint(pt, t) {
  return {
    x: pt.x * t[0] + pt.y * t[2] + t[4],
    y: pt.x * t[1] + pt.y * t[3] + t[5]
  };
}
function transformVector(pt, t) {
  return {
    x: pt.x * t[0] + pt.y * t[2],
    y: pt.x * t[1] + pt.y * t[3]
  };
}
function getTransform(elem) {
  return parseTransform(elem.style.transform);
}
var parseTransform = (function() {
  var reTransformFunction = /([A-Za-z0-9]+)\s*\(\s*([A-Za-z0-9,.\s-]+)\)/g;
  var reComma = /,/g;
  var parseAngle = function parseAngle(value) {
    value = value.trim().toLowerCase();
    var len = value.length;
    var factor;
    if (len >= 4 && value.substring(len - 4) == "grad") {
      factor = Math.PI / 200;
      value = value.substring(0, len - 4);
    } else if (len >= 4 && value.substring(len - 4) == "turn") {
      factor = Math.PI * 2;
      value = value.substring(0, len - 4);
    } else if (len >= 3 && value.substring(len - 3) == "deg") {
      factor = Math.PI / 180;
      value = value.substring(0, len - 3);
    } else if (len >= 3 && value.substring(len - 3) == "rad") {
      factor = 1;
      value = value.substring(0, len - 3);
    } else
      factor = 1;
    return parseFloat(value) * factor;
  };
  return function parseTransform(xfrm) {
    var matrix = [1, 0, 0, 1, 0, 0];
    if (!xfrm)
      return matrix;
    var fn,
        fnArgs,
        fnMatrix;
    while ((fn = reTransformFunction.exec(xfrm)) !== null) {
      var fnArgs = fn[2].split(reComma).map((function(x) {
        return x.trim();
      }));
      switch (fn[1].toLowerCase()) {
        case 'matrix':
          fnMatrix = fnArgs.map((function(x) {
            return parseFloat(x);
          }));
          break;
        case 'scale':
          fnMatrix = [parseFloat(fnArgs[0]), 0, 0, parseFloat(fnArgs[fnArgs.length - 1]), 0, 0];
          break;
        case 'scalex':
          fnMatrix = [parseFloat(fnArgs[0]), 0, 0, 1, 0, 0];
          break;
        case 'scaley':
          fnMatrix = [1, 0, 0, parseFloat(fnArgs[0]), 0, 0];
          break;
        case 'rotate':
          var angle = parseAngle(fnArgs[0]);
          var cos = Math.cos(angle),
              sin = Math.sin(angle);
          fnMatrix = [cos, sin, -sin, cos, 0, 0];
          if (fnArgs.length > 1) {
            var xlat = [parseFloat(fnArgs[1]), parseFloat(fnArgs[2])];
            fnMatrix = multiplyTransform([1, 0, 0, 1, -xlat[0], -xlat[1]], fnMatrix);
            fnMatrix = multiplyTransform(fnMatrix, [1, 0, 0, 1, xlat[0], xlat[1]]);
          }
          break;
        case 'skew':
          fnMatrix = [1, Math.tan(parseAngle(fnArgs[1])), Math.tan(parseAngle(fnArgs[0])), 1, 0, 0];
          break;
        case 'skewx':
          fnMatrix = [1, 0, Math.tan(parseAngle(fnArgs[0])), 1, 0, 0];
          break;
        case 'skewy':
          fnMatrix = [1, Math.tan(parseAngle(fnArgs[0])), 0, 1, 0, 0];
          break;
        case 'translate':
          fnMatrix = [1, 0, 0, 1, parseFloat(fnArgs[0]), parseFloat(fnArgs[1])];
          break;
        case 'translatex':
          fnMatrix = [1, 0, 0, 1, parseFloat(fnArgs[0]), 0];
          break;
        case 'translatey':
          fnMatrix = [1, 0, 0, 1, 0, parseFloat(fnArgs[0])];
          break;
        default:
          fnMatrix = [1, 0, 0, 1, 0, 0];
          break;
      }
      matrix = multiplyTransform(fnMatrix, matrix);
    }
    return matrix;
  };
})();
function weaveBits(x, y) {
  return (((x & 0x8000) << 15) | ((y & 0x8000) << 16) | ((x & 0x4000) << 14) | ((y & 0x4000) << 15) | ((x & 0x2000) << 13) | ((y & 0x2000) << 14) | ((x & 0x1000) << 12) | ((y & 0x1000) << 13) | ((x & 0x800) << 11) | ((y & 0x800) << 12) | ((x & 0x400) << 10) | ((y & 0x400) << 11) | ((x & 0x200) << 9) | ((y & 0x200) << 10) | ((x & 0x100) << 8) | ((y & 0x100) << 9) | ((x & 0x80) << 7) | ((y & 0x80) << 8) | ((x & 0x40) << 6) | ((y & 0x40) << 7) | ((x & 0x20) << 5) | ((y & 0x20) << 6) | ((x & 0x10) << 4) | ((y & 0x10) << 5) | ((x & 0x8) << 3) | ((y & 0x8) << 4) | ((x & 0x4) << 2) | ((y & 0x4) << 3) | ((x & 0x2) << 1) | ((y & 0x2) << 2) | (x & 0x1) | ((y & 0x1) << 1)) >>> 0;
}
function weaveHighBits(x, y) {
  return weaveBits(x >>> 16, y >>> 16) ^ 0x40000000;
}
function weaveBitsHex(x, y) {
  var lobits = weaveBits(x, y).toString(16);
  var hibits = ((weaveHighBits(x, y) ^ 0x80000000) >>> 0).toString(16);
  return '0'.repeat(8 - hibits.length) + hibits + ':' + '0'.repeat(8 - lobits.length) + lobits;
}
function unweaveBits(l, h) {
  return ((l & 0x40000000) >> 15) | (((h ^ 0x40000000) & 0x40000000) << 1) | ((l & 0x10000000) >> 14) | ((h & 0x10000000) << 2) | ((l & 0x4000000) >> 13) | ((h & 0x4000000) << 3) | ((l & 0x1000000) >> 12) | ((h & 0x1000000) << 4) | ((l & 0x400000) >> 11) | ((h & 0x400000) << 5) | ((l & 0x100000) >> 10) | ((h & 0x100000) << 6) | ((l & 0x40000) >> 9) | ((h & 0x40000) << 7) | ((l & 0x10000) >> 8) | ((h & 0x10000) << 8) | ((l & 0x4000) >> 7) | ((h & 0x4000) << 9) | ((l & 0x1000) >> 6) | ((h & 0x1000) << 10) | ((l & 0x400) >> 5) | ((h & 0x400) << 11) | ((l & 0x100) >> 4) | ((h & 0x100) << 12) | ((l & 0x40) >> 3) | ((h & 0x40) << 13) | ((l & 0x10) >> 2) | ((h & 0x10) << 14) | ((l & 0x4) >> 1) | ((h & 0x4) << 15) | (l & 0x1) | ((h & 0x1) << 16);
}
function unweaveOtherBits(l, h) {
  return unweaveBits(l >>> 1, (h ^ 0x80000000) >>> 1);
}
var bind = Function.prototype.call.bind(Function.prototype.bind);
