$.validator.setDefaults({
    submitHandler: function(form) {
        var url = $("form#userProfile").attr('action');
        //alert(url);
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