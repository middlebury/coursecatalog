import $ from 'jquery';

$('document').ready(function() {

    /*********************************************************
     * Email controls
     *********************************************************/
    $(".email_dialog").each(function() {
        var button = $(this).siblings(".email_button");
        var emailDialog = $(this).dialog({
                autoOpen: false,
                width:  700,
                modal: true
            });

        button.data("emailDialog", emailDialog);
    });
    $(".email_button").click(function(){
        var emailDialog = $(this).data('emailDialog');
        emailDialog.dialog("open");

        // Store the original subject.
        var field = emailDialog.find('input[name=subject]');
        if (!field.data('orig')) {
            field.data('orig', field.val());
        }

        // Store the original message.
        var field = emailDialog.find('textarea[name=message]');
        if (!field.data('orig')) {
            field.data('orig', field.val());
        }

        return false;
    });

    $(".email_dialog form").submit(function() {
        var to = $(this).find("input[name=to]").val();
        to = to.replace(/^\s*/, '').replace(/\s*$/, '');

        if (!to.length) {
            if (!confirm("You didn't specify a recipient.\n\nClick 'Cancel' to add one or 'Ok' to send only to yourself.")) {
                return false;
            }
        }
        if (to.length && !validateEmail(to)) {
            alert("It doesn't look like you specified a valid email address in the 'To:' field.");
            return false;
        }

        // Submit the form asynchronously, clear the to & body, then close.
        var form = $(this);
        var emailDialog = form.parents('.email_dialog');

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            success: function (data, textStatus, req) {
                if (data.length) {
                    alert(data);
                }

                // Clear the form.
                form.find('input[name=to]').val('');

                var field = form.find('input[name=subject]');
                field.val(field.data('orig'));

                var field = form.find('textarea[name=message]');
                field.val(field.data('orig'));
            },
            error: function(req, textStatus, errorThrown) {
                emailDialog.dialog('open');
                alert("Couldn't send email.\n\n" + req.responseText);
            }
        });

        // Close the dialog.
        emailDialog.dialog('close');
        return false;
    });

});

/**
* Validate the email field
*
* @param string emailString
* @return boolean
*/
function validateEmail (emailString) {
    var addresses = emailString.split(",");
    for (var i = 0; i < addresses.length; i++) {
        var address = addresses[i].replace(/^\s*/, '').replace(/\s*$/, '');
        if (!validateEmailAddress(address)) {
            return false;
        }
    }
    return true;
}

/**
* Validate an email address.
* By "sectrean" at http://stackoverflow.com/questions/46155/validate-email-address-in-javascript/46181#46181
*
* @param string email
* @return boolean
*/
function validateEmailAddress (email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return email.match(re);
}
