var ajaxDelete = wrapAsync(function* ajaxDelete(url, description) {
if (yield dialogConfirm('Voulez-vous vraiment supprimer ' + description + ' ?'))
    yield ajax({ url: url, type: 'DELETE' });
});

$(function () {
	$('.deletion').on('click', function (ev) {
		ev.preventDefault();
		ajaxDelete(this.getAttribute('data-url'), this.getAttribute('data-description'));
	});
});