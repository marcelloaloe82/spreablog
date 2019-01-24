<div class="row" id="editor">
    <div class="col-sm-12">
      
        <h2>Scrivi articolo</h2>

        <form id="news-form">
        
          <div class="form-group">
            <input type="text" name="title" id="title" class="form-control" placeholder="Titolo">
          </div>
        
        
          <textarea id="news-text" name="content" class="form-control" rows="5">
            <?php if(!empty($news_content)) echo $news_content; ?>
          </textarea>
          <div class="news-buttons">
            <button id="publish" class="btn btn-primary">Pubblica</button>
          </div>
          <input type="hidden" name="id" id="post-id" value="<?php if(!empty($id)) echo $id; ?>">
        </form>
        
    </div>
  </div>
</div>

<div id="salvataggio-news-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Salvataggio news</h4>
      </div>
      <div class="modal-body">
        <img id="load-gif" src="<?php echo base_url(); ?>assets/img/load-icon.gif">
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">

  
  function finestra_messaggio(messaggio, conferma){

    var html_messaggio = "<p>" + messaggio + "</p>";

    $("#load-gif").hide();
    
    $("#salvataggio-news-modal .modal-body").html(html_messaggio);

    $("#salvataggio-news-modal").modal('show');

    
  }


  
  $(document).ready( function(){


    $("#salvataggio-news-modal").on('hidden.bs.modal', function(){
      
      location.reload();

    });

    
      
    $("#butt-ok").on('click', function(){

       $.post("<?php echo base_url(); ?>index.php/api/news/delete", "&id=" + $("#post-id").val(),
            function (response) {
              
              $("#confirm-delete-modal").modal('hide');
              finestra_messaggio('News cancellata correttamente');
              
            });
    });

      

      $("#publish").click( function(event){

        event.preventDefault();

        $("#load-gif").show();
        $("#salvataggio-news-modal").modal("show");

        var formData = new FormData();
        formData.append("content", tinyMCE.activeEditor.getContent());
        formData.append("title", $("#title").val());
        
        var post_id = $("#post-id").val();
        var operazione = "";

        if(post_id){
          formData.append('id', post_id);
          operazione = 'update';
        }

        else operazione = 'create';


        $.ajax({
          url: "<?php echo base_url(); ?>index.php/api/news/" + operazione,
          type: "POST",
          data: formData, 
          processData: false,
          contentType: false

        }).done(function(response){

          $("#salvataggio-news-modal").modal("hide");
  
          finestra_messaggio("News salvata con successo");
        

        }).fail(function (response) {

          $("#salvataggio-news-modal").modal("hide");
          finestra_messaggio(response.responseJSON.message);
        
        });

      });

  });
</script>
</body>
</html>
