<?php 
setlocale(LC_ALL, 'it_IT.UTF-8');

if(!empty($ruolo_utente) && $ruolo_utente == 'editor'){

      $button_modifica = "<button class='btn btn-primary edit-button' data-post-id='${single_news['id']}'>Modifica</button>";
      $button_elimina  = "<button class='btn btn-primary btn-danger delete-news-button' data-post-id='${single_news['id']}'>Elimina</button>";
    
} else{

      $button_modifica = "";
      $button_elimina  = "";
      
}

$data_pubblicazione = "<h4>Pubblicato il: ". @strftime("%d %B %Y ",  strtotime($single_news['created_at'])) . "</h4>";
  
  ?>

  <div class="row">
    <div class="col-sm-12">
      <h1><?php echo $single_news['title']; ?></h1>
      <?php echo $data_pubblicazione; ?>
      <div class="news-content">
      <?php echo $single_news['content']; ?>
      </div>
      <?php echo $button_modifica .' '. $button_elimina; ?>
      <div class="comments-area">
        <?php if($comments): ?>
            <h3>Commenti alla news</h3>
        <?php endif; ?>
        <?php foreach ($comments as $key => $comment_entry): ?>
          
            <h5><?php echo $comment_entry['display_name']; ?></h5>
            <div class="comment-content">
            
              <?php echo $comment_entry['content']; ?>
                
            </div>
          
          <?php if(count($comment_entry['replies']) > 0): ?>
          <div class="replies">
            
            <?php foreach ($comment_entry['replies'] as $key => $reply): ?>
              
              <h5><?php echo $reply['display_name']; ?></h5>
              <div><?php echo $reply['content']; ?> </div>
        
            <?php endforeach; ?>
          
          </div>
        <?php endif; ?>
        <?php endforeach; ?>
        
      <form class="invia-commento" validate> 
      <div class="post-comment">
        <h3>Commenta</h3>
        <div class="form-group">
          <label for="usr">Nome</label>
          <input type="text" class="form-control" name="display_name" required>
        </div>
        <div class="form-group">
          <label for="indirizzo_email">Email (non sarà visualizzata)</label>
          <input type="email" class="form-control" name="email" required>
        </div>
        <div class="form-group">
          <label for="comment">Scrivi il tuo commento:</label>
          <textarea class="form-control" rows="5" id="comment" name="content" required></textarea>
        </div>
        <div class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_SITEKEY; ?>"></div>
        <div class="form-group">
          <button class="btn btn-default">Invia</button>
        <input type="hidden" name="news_id" value="<?php echo $single_news['id'] ?>">
        <input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>">
      </div>
    </div>
  </form>
  </div>
  <hr>
</div>



<div id="confirm-delete-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Messaggio di conferma</h4>
      </div>
      <div class="modal-body">
        Vuoi davvero cancellare la news?
      </div>
      <div class="modal-footer">
         <button id="butt-ok" type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
         <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
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
        <h4 class="modal-title">Messaggio di conferma</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
         <button id="butt-ok" type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
         
      </div>
    </div>

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

  
</div>    
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/utils.js"></script>   
<?php if(in_array($ruolo_utente, ['admin', 'editor'])): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/editor.js"></script>
<?php endif; ?>
<script type="text/javascript">

  news_offset         = 10;
  no_more_news        = false;
  



  $(document).ready(function(){


    $("#butt-ok").on('click', function(){

       $.post("<?php echo base_url(); ?>index.php/api/news/delete", "&id=" + news_id,
            function (response) {
              
              $("#confirm-delete-modal").modal('hide');
              finestra_messaggio('News cancellata correttamente');
              
            });
    });

    $("#message-dialog").on("hidden.bs.modal", function(){

        location.reload();
    })
      
    $("#loading").on("hidden.bs.modal", function(){

        finestra_messaggio(messaggio_risposta);
    })
    



    $(".invia-commento").on('submit', function(event){

      event.preventDefault();

      $("#loading").modal("show");

      form = $(this).get(0);
      form_data  = new FormData(form);

      $.ajax({
        url: "<?php echo base_url(); ?>index.php/api/comments/save",
        type: "POST",
        data: form_data, 
        processData: false,
        contentType: false

      }).done(function(response){

          $("#loading").modal("hide");
          messaggio_risposta = response.message;
          
          
      }).fail( function(response){

          $("#loading").modal("hide");

          try{
            
            messaggio_risposta = response.responseJSON.message;

          } catch(exc){

            messaggio_risposta = response.responseText;
          }

        
      });
    
    });


  });

</script>
</body>
</html>
