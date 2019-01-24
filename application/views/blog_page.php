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
      <h2><?php echo $single_news['title']; ?></h2>
      <?php echo $data_pubblicazione; ?>
      <div class="news-content">
      <?php echo $single_news['content']; ?>
      </div>
      <?php echo $button_modifica .' '. $button_elimina; ?>
      <div class="comment-area">
        <?php foreach ($single_news['comments'] as $key => $comment_entry): ?>
          <h4><?php echo $comment_entry['display_name']; ?></h4>
          <div class="comment-content"><?php echo $comment_entry['content']; ?></div>
      </div>
      <?php endforeach; ?>
      <form>
      <div class="post-comment">
        <h3>Commenta</h3>
        <div class="form-group">
          <label for="usr">Nome</label>
          <input type="text" class="form-control" name="display_name">
        </div>
        <div class="form-group">
          <label for="indirizzo_email">Email (non sarà visualizzata)</label>
          <input type="email" class="form-control" name="indirizzo_email">
        </div>
        <div class="form-group">
          <label for="comment">Scrivi il tuo commento:</label>
          <textarea class="form-control" rows="5" id="comment"></textarea>
        </div>
        <div class="g-recaptcha" data-sitekey="6LffHIwUAAAAABALRFsTKSgkBPFjTCzLNzScE0cR"></div>
        <div class="form-group">
          <button class="btn btn-default">Invia</button>
        <input type="hidden" name="comment_id" value="<?php echo $single_news['id'] ?>">
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
  
</div>    
<script type="text/javascript">

  news_offset         = 10;
  no_more_news        = false;
  
  
  function edit_button_callback(){
      

      var news_id = $(this).data('post-id') ;
       
      location.href ='<?php echo base_url();?>index.php/Admin/edit_news/' + news_id;
  }

  function delete_button_callback(){

    news_id = $(this).data('post-id') ;

    $("#confirm-delete-modal").modal('show');

  }


  $(document).ready(function(){


    $("#butt-ok").on('click', function(){

       $.post("<?php echo base_url(); ?>index.php/api/news/delete", "&id=" + news_id,
            function (response) {
              
              $("#confirm-delete-modal").modal('hide');
              finestra_messaggio('News cancellata correttamente');
              
            });
    });

      

    $(".edit-button").click( edit_button_callback );


    $(".delete-news-button").click( delete_button_callback );


  });

</script>
</body>
</html>
