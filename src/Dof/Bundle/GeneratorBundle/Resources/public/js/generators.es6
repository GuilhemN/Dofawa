$(wrapAsync(function* () {
	yield syncData('form');
	$('input').on('change keyup click', wrapAsync(function* () {
		syncData('form');
	}));
}));