function ConfirmDialog(titre, message, action) {
    $('<div></div>').appendTo('body').html('<div><h5>' + message + '?</h5></div>').dialog({
        modal: true,
        title: titre,
        zIndex: 10000,
        autoOpen: true,
        width: 'auto',
        resizable: false,
        buttons: {
            Yes: function () {
                action();
                $(this).dialog("close");
            },
            No: function () {
                $(this).dialog("close");
            }
        },
        close: function () {
            $(this).remove();
        }
    });
}
