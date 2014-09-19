jQuery(function () {
    jQuery('#notifications').on('show.bs.dropdown', function (){
            jQuery.ajax({
                url: Routing.generate('dof_notifications_get_notifications')
            }).done(function(data) {
                jQuery('#notifications .dropdown-menu').html(data)
            });
    });
    jQuery('#notifications').on('hidden.bs.dropdown', function () {
        var notificationsBadge = jQuery('#notifications span.badge');
        if(notificationsBadge.html() != '0'){
            jQuery.ajax({
                url: Routing.generate('dof_notifications_mark_as_read')
            }).done(function() {
                notificationsBadge.html('0')
                jQuery('#notifications li' ).removeClass( "active" );
            });
        }
    });
});
