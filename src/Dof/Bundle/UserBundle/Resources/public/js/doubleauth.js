$(function(){
    $('#doubleAuth').on('click', function(){
        $.get( Routing.generate("dof_user_account_security_doubleAuth"), function(data) {
            $('#doubleAuthModal .modal-body').html(data);
            var qrcode = $('#doubleAuthModal .modal-body #qr-code');
            qrcode.qrcode(qrcode.attr('data-key'));
            $('#doubleAuthModal').modal('show');
        })
        .fail(function() {
            alert('Une erreur s\'est produite, rééssayez plus tard');
        });
        return false;
    });
});

function checkTotp(){
    $.post(Routing.generate('dof_user_account_security_doubleAuth_check'), { "_totp": $('input[name="_totp"]').val()}, function( data ) {
        if(data.success){
            $('#doubleAuthModal').modal('hide');
            location.reload();
        }
        else{
            var message;
            if(data.error == 'not_connected')
                message = 'Vous n\'êtes plus connecté.';
            else if(data.error == 'bad_totp')
                message = 'Vous avez rentrer un code incorrect ou expiré.';
            else if(data.error == 'not_set')
                message = 'Une erreur s\'est produite, rééssayez plus tard.';
            $('#totp-errors').append('<div class="alert alert-warning" role="alert">' + message + '</div>');
        }
    }, "json");
    return false;
}
