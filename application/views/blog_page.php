<?php 
setlocale(LC_ALL, 'it_IT.UTF-8');

if($news):

$comment_flag = false;

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
      <h1><a href="<?php echo base_url() . "index.php/Blog/view/${single_news['slug']}"; ?>"><?php echo $single_news['title']; ?></a></h1>
        <?php echo $data_pubblicazione; ?>
        <div class="news-content">
        <?php echo $single_news['content']; ?>
        </div>
        <?php echo $button_modifica .' '. $button_elimina; ?>
        <div class="comments-area">
        
        <h3>Commenti alla news</h3>
          
        <?php foreach ($comments as $key => $comment_entry): ?>
          
          <?php if($comment_entry['news_id'] == $single_news['id']): 

              $comment_flag = true;
          ?>
            <h5><?php echo $comment_entry['display_name']; ?></h5>
            <div class="comment-content">
            
              <?php echo $comment_entry['content']; ?>
                
            </div>
            
            <?php if(!empty($comment_entry['replies'])): ?>
            <div class="replies">
              
              <?php foreach ($comment_entry['replies'] as $key => $value): ?>
                
                <h5><?php echo $value['display_name']; ?></h5>
                <div><?php echo $value['content']; ?> </div>
          
              <?php endforeach; ?>
            
            </div>
            <?php endif; ?>
         
          <?php endif; ?>
         
        <?php endforeach; ?>

        <?php if(!$comment_flag): ?>
          <p> Nessun commento per questa news </p>
        <?php endif; ?>
        
        
        </div>
        <div class="comment-btn-wrapper">
          <button class="btn btn-success"><a href="<?php echo base_url() . "index.php/Blog/view/${single_news['slug']}"; ?>">Commenta</a></button>
        </div>
    </div>
  </div> 
  <hr>
<?php endforeach; ?>
<?php else: ?>
   <div class="row">
    <div class="col-sm-12">
      <div>Ancora nessuna news inserita</div>
    </div>
  </div>
<?php endif; ?>


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

  
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/utils.js"></script>   

<script type="text/javascript">

  news_offset         = 10;
  no_more_news        = false;
  

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

    $(window).scroll( function (event) {
      
      if ( ($(document).height() <= $(window).scrollTop() + $(window).height()) && !no_more_news){
        $.get("<?php echo base_url(); ?>index.php/api/news/nextpage/" + news_offset, function(data){
            if(data.length === 0) no_more_news = true;
            
            else{
              $('.container').append( data );
              $('.edit-button').on('click', edit_button_callback);
              $('.delete-news-button').on('click', delete_button_callback);
            } 
        });
        news_offset += 10;
     } 
            
    });


  });

</script>
</body>
</html>
