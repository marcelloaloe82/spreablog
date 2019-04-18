<div id="confirm-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Operazione in corso...</h4>
      </div>
      <div class="modal-body">
        Cancellare il commento?
      </div>
      <div class="modal-footer">
         <button id="butt-ok" type="button" class="btn btn-default btn-primary" data-dismiss="modal">OK</button>
         <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
      </div>
    </div>

  </div>
</div>

<div id="view-comment" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Operazione in corso...</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
         <button id="close-modal" type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
      </div>
    </div>

  </div>
</div>
<div id="reply-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Rispondi al commento</h4>
      </div>
      <form id="reply-form" validate>
      <div class="modal-body">
          
          <div class="form-group">
            <textarea rows="5" class="form-control" name="content" id="reply-text" required autofocus></textarea>
          </div>
          <div class="form-group" id="message-empty-reply" style="display: none;">
            <span style="color: red;">Ricorda di digitare il testo!</span>
          </div>

          <input type="hidden" name="approved" value="1">
          <input type="hidden" name="display_name" value="<?php echo $this->session->user['nome'] . ' ' . $this->session->user['cognome'] ; ?>">
          <input type="hidden" name="email" value="<?php echo $this->session->user['email']; ?>">
          
      </div>
      </form>
      <div class="modal-footer">
         <button id="send-reply" type="button" class="btn btn-default" disabled>Invia</button>
      </div>
    </div>
  </div>