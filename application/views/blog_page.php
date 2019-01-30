<?php 


foreach($news as $single_news): 


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
      <h2><a href="<?php echo base_url() . "index.php/Blog/view/${single_news['slug']}"; ?>"><?php echo $single_news['title']; ?></a></h2>
      <?php echo $data_pubblicazione; ?>
      <div class="news-content">
      <?php echo $single_news['content']; ?>
      </div>
      <?php echo $button_modifica .' '. $button_elimina; ?>
      <div class="comment-area">
        <?php if($comments): ?>
        <h4>Commenti alla news</h4>
        <?php endif; ?>
        <?php foreach ($comments as $key => $comment_entry): ?>
          
          <?php if($comment_entry['news_id'] == $single_news['id']): ?>
          <h5><?php echo $comment_entry['display_name']; ?></h5>
          <div class="comment-content">
          
            <?php echo $comment_entry['content']; ?>
              
          </div>
          <?php endif; ?>
          
          <?php if(!empty($comment_entry['replies'])): ?>
          <div class="replies">
            
            <?php foreach ($comment_entry['replies'] as $key => $value): ?>
              
              <h5><?php echo $comment_entry['replies']['display_name']; ?></h5>
              <div><?php echo $comment_entry['replies']['content']; ?> </div>
        
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
        <div class="g-recaptcha" data-sitekey="6LffHIwUAAAAABALRFsTKSgkBPFjTCzLNzScE0cR"></div>
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
<?php endforeach; ?>


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
         <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
      </div>
    </div>

  </div>
</div>

  
</div>    
<script type="text/javascript">

  news_offset         = 10;
  no_more_news        = false;
  

  function finestra_messaggio(messaggio, conferma){

    var html_messaggio = "<p>" + messaggio + "</p>";
    
    $("#message-dialog .modal-body").html(html_messaggio);

    $("#message-dialog").modal('show');

    
  }
  
  function edit_button_callback(){
      

      var news_id = $(this).data('post-id') ;
       
      location.href ='<?php echo base_url();?>index.php/Admin/edit_news/' + news_id;
  }

  function delete_button_callback(){

    news_id = $(this).data('post-id') ;

    $("#confirm-delete-modal").modal('show');

  }


  $(document).ready(function(){


    $("#message-dialog").on("hidden.bs.modal", function(){

      location.reload();
    });


    $("#butt-ok").on('click', function(){

       $.post("<?php echo base_url(); ?>index.php/api/news/delete", "&id=" + news_id,
            function (response) {
              
              $("#confirm-delete-modal").modal('hide');
              finestra_messaggio('News cancellata correttamente');
              
            });
    });

      

    $(".edit-button").click( edit_button_callback );


    $(".delete-news-button").click( delete_button_callback );


    $(".invia-commento").on('submit', function(event){

      event.preventDefault();

      form = $(this).get(0);
      form_data  = new FormData(form);

      $.ajax({
        url: "<?php echo base_url(); ?>index.php/api/comments/save",
        type: "POST",
        data: form_data, 
        processData: false,
        contentType: false

      }).done(function(response){

          finestra_messaggio(response.message);
          $(".invia-commento").reset();
          
      }).fail( function(response){

          var dati_messaggio = "";

          try{
            
            dati_messaggio = response.responseJSON.message;

          } catch(exc){

            dati_messaggio = response.responseText;
          }

        
          finestra_messaggio(dati_messaggio);

      });
    
    });


  });

</script>
</body>
</html>
