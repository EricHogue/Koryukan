$.validator.setDefaults({
    submitHandler: function(form) {
        var url = $("form#userProfile").attr('action');
        var data = $(form).serializeArray();
        
        $.post(url, data, function(data, textStatus, XMLHttpRequest) {
            var displayText = '';
            for (message in data.messages) {
                displayText = displayText + '<p>' + message + '</p>'
            }
            
            var theme = 'default';
            if (!data.success) {
               theme = 'ui-state-error';
            }
            
            $("#messages").jGrowl(displayText, {'header': data.title, 'life': 5000, 'sticky': false, 'theme': theme});
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