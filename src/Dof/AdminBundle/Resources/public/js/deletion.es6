var ajaxDelete = wrapAsync(function* ajaxDelete(url, description, e) {
if (yield dialogConfirm('Voulez-vous vraiment supprimer ' + description + ' ?'))
	 try {
        var { data: response } = yield ajax({ url: url, type: 'DELETE' });


		var el = e.parentNode.parentNode;
		el.parentNode.removeChild(el);
    } catch (err) {
    	$('#errorpanel').append('<div class="alert alert-danger alert-dismissable">'+
    		'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
	      	'<strong>Erreur !</strong> '+ description + ' n\'a pas pu être supprimé. Ceci peut-être dû à une erreur réseau ou du serveur.'+
    		'</div>');
    }
});

$(function () {
	$('.deletion').on('click', function (ev) {
		ev.preventDefault();
		ajaxDelete(this.getAttribute('data-url'), this.getAttribute('data-description'), this);
	});
});