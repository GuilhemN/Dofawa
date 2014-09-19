jQuery(function () {
    var unread = jQuery('#notifications span.badge').html();
    if(unread > 0)
        document.title = '(' + unread + ') ' + document.title;

    jQuery('#notifications').on('show.bs.dropdown', function (){
        jQuery.ajax({
            url: Routing.generate('dof_notifications_ajax_list')
        }).done(function(data) {
            jQuery('#notifications .dropdown-menu').html(data.html);
            jQuery('#notifications span.badge').html(data.unread);
        });
    });
});

function checkUnreadNotifications(){
    jQuery.ajax({
        url: Routing.generate('dof_notifications_ajax_check_unread')
    }).done(function(data) {
        jQuery('#notifications span.badge').html(data.unread);
    });
}

setTimeout(function() {
    setInterval(checkUnreadNotifications, 25000)
}, 10000);
