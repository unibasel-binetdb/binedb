import './bootstrap';

$(document).ready(function() {
    //bind cancel button alert
    var cancelButton = $('#saveActions > a.btn-secondary');
    cancelButton.on('click', function(e) {
        var confirmAction = confirm("Achtung, es gehen alle ungespeicherten Änderungen verloren. Möchten Sie fortfahren?");
        if (!confirmAction)
            event.preventDefault();
    });
});