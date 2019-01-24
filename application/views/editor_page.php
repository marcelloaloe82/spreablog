<div class="row" id="editor">
    <div class="col-sm-12">
      
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
         <button id="close-modal" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div><div id="confirm-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Salvataggio news</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
         <button id="close-modal" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">

  csrf_name = '<?php echo $csrf['name']; ?>';
  
  function finestra_messaggio(messaggio, conferma){

    var html_messaggio = "<p>" + messaggio + "</p>";

    $("#confirm-modal .modal-body").html(html_messaggio);

    $("#confirm-modal").modal('show');

    
  }


  
  $(document).ready( function(){

    $("#salvataggio-news-modal").on('hidden.bs.modal', function(){

      finestra_messaggio(messaggio_risposta);

    });

    $("#confirm-modal").on('hidden.bs.modal', function(){

        location.reload();

    });

    $("#publish").click( function(event){

      event.preventDefault();

      $("#load-gif").show();
      $("#salvataggio-news-modal").modal("show");

      var formData = new FormData();
      formData.append("content", tinyMCE.activeEditor.getContent());
      formData.append("title", $("#title").val());
      formData.append(csrf_name, $("#"+csrf_name).val());

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

        messaggio_risposta = response.message;
        $("#salvataggio-news-modal").modal("hide");

        
      }).fail(function (response) {

        $("#salvataggio-news-modal").modal("hide");
        try{

          messaggio_risposta = response.responseJSON.message;

        }catch(exc){
          messaggio_risposta = response.responseText;          
        }
      
      });

    });

  });
</script>
</body>
</html>
