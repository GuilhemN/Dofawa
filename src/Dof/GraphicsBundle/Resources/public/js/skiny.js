$(function () {
    var DOFAWA_ROOT = 'http://beta.dofawa.fr/';
    var DOFAWA_PATTERNS = {
        flipcard: 'fr/graphics/skins/{slug}/embed/flipcard.js'
    };
    var dipSerial = 0;
    var slice = Array.prototype.slice;
    var reFlipcard = /\[dofawa-([a-z-]+)(?: ([0-9a-z= -]*))?\]([0-9a-z-]+)\[\/dofawa-\1\]/g;
    var reSlugToken = /\{slug\}/g;
    var walk = function () {
        var children = slice.call(this.childNodes);
        for (var i = 0; i < children.length; ++i)
            walk.call(children[i]);
        if (this.nodeType != 3)
            return;
        var match, lastIndex = 0, parts = [ ], input = this.nodeValue;
        while ((match = reFlipcard.exec(input))) {
            if (lastIndex < match.index)
                parts.push(input.substring(lastIndex, match.index));
            parts.push(match);
            lastIndex = match.index + match[0].length;
        }
        if (!parts.length)
            return;
        if (lastIndex < input.length)
            parts.push(input.substring(lastIndex));
        for (var i = 0; i < parts.length; ++i) {
            var node;
            if (typeof parts[i] == "string")
                node = document.createTextNode(parts[i]);
            else {
                var match = parts[i];
                node = document.createElement('div');
                ++dipSerial;
                node.id = 'dofawa-insertion-point-' + dipSerial;
                node.className = 'dofawa-insertion-point';
                node.style.display = 'inline-block';
                if (match[1] in DOFAWA_PATTERNS) {
                    var scriptNode = document.createElement('script');
                    scriptNode.type = 'text/javascript';
                    scriptNode.src = DOFAWA_ROOT + DOFAWA_PATTERNS[match[1]].replace(reSlugToken, match[3]);
                    scriptNode.async = true;
                    scriptNode.defer = true;
                    document.head.appendChild(scriptNode);
                } else {
                    node.style.border = '1px dashed red';
                    node.style.padding = '0 4px';
                    node.style.borderRadius = '4px';
                    node.style.color = 'red';
                    node.appendChild(document.createTextNode('Balise Dofawa non reconnue : [dofawa-' + match[1] + ']'));
                }
            }
            if (node)
                this.parentNode.insertBefore(node, this);
        }
        this.parentNode.removeChild(this);
    };
    $(".postbody").each(walk);
});
