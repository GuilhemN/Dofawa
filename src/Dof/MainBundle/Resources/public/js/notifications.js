jQuery(function () {
    jQuery('#notifications').on('show.bs.dropdown', function (){
            jQuery.ajax({
                url: Routing.generate('dof_notifications_get_notifications')
            }).done(function(data) {
                data = JSON.parse(data);
                jQuery('#notifications .dropdown-menu').html(data.html);
                jQuery('#notifications span.badge').html(data.unread);
            });
    });
});
