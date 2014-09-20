jQuery(function () {
    var unread = jQuery('#notifications span.badge').html();
    majNotificationsTitle(unread);

    jQuery('#notifications').on('show.bs.dropdown', function (){
        jQuery.ajax({
            url: Routing.generate('dof_notifications_ajax_list')
        }).done(function(data) {
            var html = '';
            for (var i = 0; i < data.notifications.length; i++) {
                var notification = data.notifications[i];

                html += '<li ';
                if(notification.isRead == false)
                    html += 'class="active"';

                html += '>' +
                    '<a href="' + notification.path + '">'
                        + notification.message +
                        '<br>' +
                        '<small>' + notification.createdAt + '</small>' +
                    '</a>' +
                '</li>' +
                '<li class="divider"></li>';
            }
            jQuery('#notifications .dropdown-menu li:not(#checkbox)').remove();
            jQuery('#notifications .dropdown-menu').append(html);
            jQuery('#notifications span.badge').html(data.unread);

            majNotificationsTitle(data.unread);
        });
    });

    jQuery('#notifications #checkbox input').on('click', function(){
        if(jQuery(this).is(':checked')) {
        	if (!('Notification' in window))
                var error = true;
            else
            	Notification.requestPermission(function (perm) {
            		if (perm != 'granted')
            			var error = true;
            		else
            			var error = false;
            	});

            if(error)
                $(this).prop('checked', false);
        }
    });
});


function makeNotificationIcon(fillStyle, icon) { return runTask(function* () {
	var imgTask = fetchImage(icon);
	var canvas = document.createElement('canvas');
	canvas.width = 40;
	canvas.height = 40;
	var ctx = canvas.getContext('2d');
	ctx.fillStyle = (typeof fillStyle == 'function') ? fillStyle(canvas, ctx) : fillStyle;
	ctx.beginPath();
	ctx.arc(20, 20, 20, 0, Math.PI * 2.0, false);
	ctx.closePath();
	ctx.fill();
	try {
		var img = yield imgTask;
		ctx.drawImage(img, 8, 8, 24, 24);
	} catch (e) { }
	return canvas.toDataURL();
}); }

function notify(fillStyle, icon, title, text, preference) {
    var hasPreference = arguments.length > 4; return runTask(function* () {
	if (!hasPreference || (preference && preference.checked)) {
		var img = yield makeNotificationIcon(fillStyle, icon);
		var notif = new Notification(title, { body: text, icon: img });
		setTimeout(notif.close.bind(notif), 8000);
	}
}); }

function checkUnreadNotifications(){
    jQuery.ajax({
        url: Routing.generate('dof_notifications_ajax_check_unread')
    }).done(function(data) {
        jQuery('#notifications span.badge').html(data.unread);
        majNotificationsTitle(data.unread);
    });
}

function majNotificationsTitle(unread){
    if(document.title.match(/^\([0-9]+\)/))
        if(unread > 0)
            document.title = document.title.replace(/^\([0-9]+\)/, '(' + unread + ')');
        else
            document.title = document.title.replace(/^\([0-9]+\)/, '');
    else if(unread > 0)
        document.title = '(' + unread + ') ' + document.title;
}

setInterval(checkUnreadNotifications, 25000)
