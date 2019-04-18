<div>
  <button style="float:right;" class="btn btn-default" id="logout">Logout</button>
</div>

 <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#modera">Modera commenti</a></li>
    <li><a id="scrivi-news" data-toggle="tab" href="#editor">Scrivi news</a></li>
    
  </ul>

  <div class="tab-content">
    
    <div id="modera" class="tab-pane fade in active">
      
      <table id="commenti" class="cell-border compact stripe">
          <thead>
            
            <tr>
              <th>Nome</th>
              <th>Email</th>
              <th>Indirizzo IP</th>
              <th>News</th>
              <th>Vedi commento</th>
              <th>Contenuto</th>
              <th>Cancella</th>
              <th>Rispondi</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
    </div>
    <div id="editor" class="tab-pane fade">

        <h2>Scrivi articolo</h2>

        <form id="news-form">
        
          <div class="form-group">
            <input type="text" name="title" id="title" class="form-control" placeholder="Titolo" value="<?php if(!empty($title)) echo $title; ?>">
          </div>
        
        
          <textarea id="news-text" name="content" class="form-control" rows="5">
            <?php if(!empty($content)) echo $content; ?>
          </textarea>
          <div class="news-buttons">
            <button id="publish" class="btn btn-primary">Pubblica</button>
          </div>
          <input type="hidden" name="id" id="post-id" value="<?php if(!empty($id)) echo $id; ?>">
          <input type="hidden" id="<?php echo $csrf['name']; ?>" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>">
        </form>
        
    </div>
  </div>


<div id="loading" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Operazione in corso...</h4>
      </div>
      <div class="modal-body">
        <img id="load-gif" src="<?php echo base_url(); ?>assets/img/load-icon.gif">
      </div>
      
    </div>

  </div>
</div>
<div id="message-dialog" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Risultato operazione</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
         <button id="close-modal" type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
      </div>
    </div>

  </div>
</div>
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
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/utils.js"></script>   
<script type="text/javascript">

csrf_name = '<?php echo $csrf['name']; ?>';
  user_id = '<?php echo $user_id; ?>';
  messaggio_risposta = "";
  delete_uri = "";
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/editor.js"></script>   
</body>
</html>
