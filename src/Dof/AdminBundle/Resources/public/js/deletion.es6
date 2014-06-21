var ajaxDelete = wrapAsync(function* ajaxDelete(url, description, e) {
if (yield dialogConfirm('Voulez-vous vraiment supprimer ' + description + ' ?'))
	 try {
        var { data: response } = yield ajax({ url: url, type: 'DELETE' });

		e.parent().parent().remove();
    } catch (err) {
        // do something with the request error
    }
});

$(function () {
	$('.deletion').on('click', function (ev) {
		ev.preventDefault();
		ajaxDelete(this.getAttribute('data-url'), this.getAttribute('data-description'), this);
	});
});