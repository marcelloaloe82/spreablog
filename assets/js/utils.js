function finestra_messaggio(messaggio, conferma){

    var html_messaggio = "<p>" + messaggio + "</p>";
    
    $("#message-dialog .modal-body").html(html_messaggio);

    $("#message-dialog").modal('show');

    
}
  