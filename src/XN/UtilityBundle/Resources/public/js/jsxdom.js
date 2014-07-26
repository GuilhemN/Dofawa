// https://github.com/vjeux/jsxdom/blob/master/jsxdom.js
// Modifié par Exter-N
(function(global) {
var tags = ['a', 'abbr', 'acronym', 'address', 'applet', 'area', 'article', 'aside', 'audio', 'b', 'base', 'basefont', 'bdi', 'bdo', 'big', 'blockquote', 'body', 'br', 'button', 'canvas', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'command', 'datalist', 'dd', 'del', 'details', 'dfn', 'dialog', 'dir', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'figcaption', 'figure', 'font', 'footer', 'form', 'frame', 'frameset', 'h1', 'h2' /* XN */, 'h3' /* XN */, 'h4' /* XN */, 'h5' /* XN */, 'h6' /* XN */, 'head', 'header', 'hgroup', 'hr', 'html', 'i', 'iframe', 'img', 'input', 'ins', 'kbd', 'keygen', 'label', 'legend', 'li', 'link', 'map', 'mark', 'menu', 'meta', 'meter', 'nav', 'noframes', 'noscript', 'object', 'ol', 'optgroup', 'option', 'output', 'p', 'param', 'pre', 'progress', 'q', 'rp', 'rt', 'ruby', 's', 'samp', 'script', 'section', 'select', 'small', 'source', 'span', 'strike', 'strong', 'style', 'sub', 'summary', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'time', 'title', 'tr', 'track', 'tt', 'u', 'ul', 'var', 'video', 'wbr'];

function processChildren(dom, children) {
  if (typeof children === 'string') {
    dom.appendChild(document.createTextNode(children));
  } else if (Object.prototype.toString.call(children) === '[object Array]') {
    for (var i = 0; i < children.length; ++i) {
      processChildren(dom, children[i]);
    }
  } else if (children) {
    dom.appendChild(children);
  }
}

var JSXDOM = {};
tags.forEach(function(tag) {
  JSXDOM[tag] = function(attributes) {
    var dom = document.createElement(tag);
    var $dom;
    for (var name in attributes) {
      if (attributes[name] != null) { // XN
        if (name.length >= 2 && name.substring(0, 2) == 'on' && typeof attributes[name] == 'function') { // XN
          if (!$dom) // XN
            $dom = jQuery(dom); // XN
          $dom.on(name.substring(2), attributes[name]); // XN
        } else if (name == 'class' && Object.prototype.toString.call(attributes[name]) === '[object Array]') // XN
          dom.setAttribute('class', attributes[name].join(' ')); // XN
        else if (name == 'style' && typeof attributes[name] == 'object') // XN
          merge(dom.style, attributes[name]);
        else // XN
          dom.setAttribute(name, attributes[name]);
      } // XN
    }
    processChildren(dom, Array.prototype.slice.call(arguments, 1));
    return dom;
  }
});

global.JSXDOM = JSXDOM;
})(this);
