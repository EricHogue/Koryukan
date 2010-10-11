$.validator.setDefaults({
    submitHandler: function(form) {
        var url = $("form#userProfile").attr('action');
        var data = $(form).serializeArray();
        
        $.post(url, data, function(data, textStatus, XMLHttpRequest) {
            alert('Success');
        }, 'json');
    }
});
 


$(function() {
    $("form#userProfile").validate({
        rules: {
            firstName: {
                required: true,
                minlength: 2, 
            }, 
            lastName: {
                required: true,
                minlength: 2, 
            }, 
            email: {
                required: true, 
                email: true, 
            }, 
        },
        
    });
    
    $('button').button();
});