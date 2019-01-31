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
  

  function aggancia_callback(){

    $("#commenti a").on('click', function (event) {

      event.preventDefault();
      var href = $(this).attr('href');

      if( href != "#"){

        if(href.indexOf("delete") > 0){
            
            $('#confirm-modal').modal('show');
            delete_uri = href;
        }

        if(href.indexOf("reply") > 0){

          $("#reply-form").attr("action", href);
          $("#reply-modal").modal('show');
        }


      } else{

          var rowdata = datatable.row( this.parentElement.parentElement ).data();
          var comment_content = rowdata['content'];

          $("#view-comment .modal-body").text( comment_content );
          $("#view-comment").modal("show");
      }

    });
    
    
  }


  
  $(document).ready( function(){


    datatable= $("#commenti").DataTable({
      
      language: {

        "sEmptyTable":     "Nessun dato presente nella tabella",
        "sInfo":           "Vista da _START_ a _END_ di _TOTAL_ elementi",
        "sInfoEmpty":      "Vista da 0 a 0 di 0 elementi",
        "sInfoFiltered":   "(filtrati da _MAX_ elementi totali)",
        "sInfoPostFix":    "",
        "sInfoThousands":  ".",
        "sLengthMenu":     "Visualizza _MENU_ elementi",
        "sLoadingRecords": "Caricamento...",
        "sProcessing":     "Elaborazione...",
        "sSearch":         "Cerca:",
        "sZeroRecords":    "La ricerca non ha portato alcun risultato.",
        "oPaginate": {
          "sFirst":      "Inizio",
          "sPrevious":   "Precedente",
          "sNext":       "Successivo",
          "sLast":       "Fine"
        }
      },

      ajax: {
        
        url: '<?php echo base_url(); ?>index.php/api/comments/' + user_id,
        contentType: false,
        processData: false,
        data: '',
        dataSrc: ''
        
      },



      columns:  [
            { "data": "name" },
            { "data": "email" },
            { "data": "ip_address" },
            { "data": "news" },
            { "data": "content" },
            { "data": "view_comment" },
            { "data": "cancella" },
            { "data": "rispondi" }
        ],
      
      columnDefs: [
        { targets: [4], visible: false}
      ],

      dom: 'Bfrtip',
      pageLength: 30
    });


    datatable.on("draw.dt", function(){
    
      aggancia_callback();

    });

    
    if(location.pathname.indexOf("/edit_news/") >= 0)
      $("#scrivi-news").trigger("click");


    $("#message-modal").on("hidden.bs.modal", function(){

      location.reload();
    
    });


    $("#loading").on("hidden.bs.modal", function(){

      finestra_messaggio(messaggio_risposta);
      datatable.ajax.reload();

    });

    $("#confirm-modal").on("hidden.bs.modal", function(){

      $("#loading").modal("show");

      $.post( delete_uri, function(response){
               
         $('#loading').modal('hide');
         messaggio_risposta = response.message;

      
      }).fail( function(response){

        
        try{
          
          messaggio_risposta = response.responseJSON.message;

        }catch(exc){
          
          messaggio_risposta = response.responseText;
        }

        $('#loading').modal('hide');
        finestra_messaggio(messaggio_risposta);

      }).always(function(){

         $('#loading').modal('hide');

      });
    
    });

    $("#butt-ok").on('click', function(){

      $("#confirm-modal").modal("hide");

    });

    $("#reply-modal").on('hidden.bs.modal', function(){

      if($("#reply-text").val().trim() == ""){

        $("#reply-text").css("border", "1px solid #ccc");
        $("#message-empty-reply").hide();
        return;
      }

      $("#loading").modal("show");

      var form = $("#reply-form").get(0);
      var formData = new FormData(form);

      $.ajax({

        url: $("#reply-form").attr("action"),
        type: "POST",
        data: formData, 
        processData: false,
        contentType: false

      }).done(function(response){

          messaggio_risposta = response.message;
          $("#loading").modal("hide");          
          
      }).fail( function(response){
          
          try{

            messaggio_risposta = response.responseJSON.message;
          
          }catch(exc){

            messaggio_risposta = response.responseText;
          }

          $("#loading").modal("hide");          

      }).always(function(){

         $("#loading").modal("hide"); 

      });

    });

    $("#send-reply").click( function(event){

      $("#reply-modal").modal("hide");
      event.preventDefault();
      

    });

    $("#reply-text").keyup(function(){

      if($("#reply-text").val().trim() != "")
       $("#send-reply").prop("disabled", false);

     else $("#send-reply").prop("disabled", true);
    
    });

    $("#reply-text").blur( function(){
      
      if($("#reply-text").val().trim() == ""){
        $("#reply-text").css("border", "1px solid red");
        $("#message-empty-reply").show();
      

      }else{
        $("#reply-text").css("border", "1px solid green");
        $("#send-reply").prop("disabled", false);
        $("#message-empty-reply").hide();
      }
      
    });


    $("#publish").click( function(event){

      event.preventDefault();

      $("#load-gif").show();
      $("#loading").modal("show");

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
        $("#loading").modal("hide");

        
      }).fail(function (response) {

        $("#loading").modal("hide");

        try{

          messaggio_risposta = response.responseJSON.message;

        }catch(exc){

          messaggio_risposta = response.responseText;          
        }
      
      });

    });


    $("#logout").click(function(event){

        event.preventDefault();
        $.post("<?php echo base_url(); ?>index.php/api/auth/logout", function(response){
         
          location.reload();
        });
    });

  });
</script>
</body>
</html>
