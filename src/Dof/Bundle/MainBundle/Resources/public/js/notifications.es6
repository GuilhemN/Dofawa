var warningFillStyle = '#EECE00';

jQuery(function () {
    if(localStorage.notificationCheckbox = true)
        jQuery('#notifications #checkbox input').prop('checked', true);
    else
        jQuery('#notifications #checkbox input').prop('checked', false);

    if (!('Notification' in window))
        jQuery('#notifications #checkbox input').remove();

    var unread = jQuery('#notifications span.badge').html();
    majNotificationsTitle(unread);

    jQuery('#notifications').on('show.bs.dropdown', function (){
        jQuery.ajax({
            url: Routing.generate('dof_notifications_ajax_list')
        }).done(function(data) {
            jQuery('#notifications .dropdown-menu li:not(.to-keep)').remove();
            jQuery('#notifications .dropdown-menu').append(data.content);
            jQuery('#notifications span.badge').html(data.unread === 0 ? '' : data.unread);

            delete localStorage.notified;
            majNotificationsTitle(data.unread);
        });
    });

    jQuery('#notifications #checkbox input').on('click', function(){
        localStorage.notificationCheckbox = jQuery(this).is(':checked');
        if(jQuery(this).is(':checked')) {
        	if (Notification.permission === 'denied')
                $(this).prop('checked', false);
            else
            	Notification.requestPermission(function (perm) {
            		if (perm === 'granted')
                        return true;
                    else
                        jQuery('#notifications #checkbox input').prop('checked', false);
            	});
        }
    });
});

function notify(fillStyle, title, text, preference) {
    var hasPreference = arguments.length > 4;
	if (!hasPreference || (preference && preference.checked)) {
		var notif = new Notification(title.replace(/<[^>]+>/gi, ''), { body: text.replace(/<[^>]+>/gi, '') });
		setTimeout(notif.close.bind(notif), 15000);
	}
}

function checkUnreadNotifications(){
    jQuery.ajax({
        url: Routing.generate('dof_notifications_ajax_check_unread')
    }).done(function(data) {
        jQuery('#notifications span.badge').html(data.unread === 0 ? '' : data.unread);
        majNotificationsTitle(data.unread);

        if (document.hidden)
            if(jQuery('#notifications #checkbox input').is(':checked'))
                for (var i = 0; i < data.notifications.length; i++) {
                    var notification = data.notifications[i];
                    if(jQuery.inArray(notification.id, getStoredArray(localStorage, 'notified')) == -1){
                        notify(warningFillStyle, notification.message, notification.createdAt);
                        addToStoredArray(localStorage, 'notified', notification.id);
                    }
                }
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
function getStoredArray(storage, key) {
    if (key in storage) return JSON.parse(storage[key]);
    return [ ];
}
function getStoredObject(storage, key) {
    if (key in storage) return JSON.parse(storage[key]);
    return { };
}
function setStoredObject(storage, key, value) {
    storage[key] = JSON.stringify(value);
}
var setStoredArray = setStoredObject;
function addToStoredArray(storage, key, ...values) {
    setStoredArray(storage, key, getStoredArray(storage, key).concat(values));
}
setInterval(checkUnreadNotifications, 25000)
