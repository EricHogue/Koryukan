$(function() {
    $('form#userProfile').submit(function(event) {
        event.preventDefault();
        
        var url = this.action;
        alert(url);
    });
});