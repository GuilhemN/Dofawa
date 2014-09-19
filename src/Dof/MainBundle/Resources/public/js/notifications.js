jQuery(function () {
    var unread = jQuery('#notifications span.badge').html();
    if(unread > 0)
        majNotificationsTitle(unread);

    jQuery('#notifications').on('show.bs.dropdown', function (){
        jQuery.ajax({
            url: Routing.generate('dof_notifications_ajax_list')
        }).done(function(data) {
            jQuery('#notifications .dropdown-menu').html(data.html);
            jQuery('#notifications span.badge').html(data.unread);

            majNotificationsTitle(data.unread);
        });
    });
});

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
        document.replace(/^\([0-9]+\)/, '(' + unread + ')');
    else
        document.title = '(' + unread + ') ' + document.title;
}

setInterval(checkUnreadNotifications, 25000)
