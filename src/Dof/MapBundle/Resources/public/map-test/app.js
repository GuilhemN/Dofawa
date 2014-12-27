var urls = Object.create(null);
var validityMatrix = [128, 80, 61, 126, 30, 129, 80, 61, 126, 30, 129, 80, 61, 126, 30, 1];
var map = new XNMap([new XNMapTiledLayer(0, 0, 768, 528, 8, 16, [0], 1, function(tileX, tileY, scalePower) {
  if ((validityMatrix[tileY] & (1 << tileX)) == 0)
    return null;
  var key = tileX + "_" + (tileY - 7);
  var url = "/xelorium/maps/" + key + ".jpg";
  return getImage(url);
}), new XNMapGridLayer(0, 0, 768, 528, 1, 'black', [1, 1], 0), new XNMapHotSpotLayer([])]);
function onTwitterLinkClick(ev) {
  if (open(this.href, "_blank", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700"))
    ev.preventDefault();
}
document.addEventListener('DOMContentLoaded', function() {
  if ('ontouchstart' in window) {
    var topBar = document.getElementsByClassName('top-bar')[0];
    if (topBar)
      topBar.firstChild.nodeValue = 'Faites glisser avec un doigt pour défiler. Pincez avec deux doigts pour zoomer.';
  }
  map.attach(document.getElementById('map'));
  var twitterLinks = document.querySelectorAll('a[href^="https://twitter.com/share?"]');
  for (var i = twitterLinks.length; i-- > 0; )
    twitterLinks[i].addEventListener('click', onTwitterLinkClick, false);
}, false);
addEventListener('load', function() {
  map.scrollLeft = 0;
  map.scrollTop = 6336;
  map.scale = 1e-16;
  map.adjustScrollAndScale();
  map.dispatchEvent(new FakeEvent('viewportchange', true, false));
}, false);
addEventListener('resize', function() {
  map.adjustScrollAndScale();
  map.dispatchEvent(new FakeEvent('viewportchange', true, false));
}, false);
