<div>
  <button style="float:right;" class="btn btn-default" id="logout">Logout</button>
</div>

 <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#modera">Modera commenti</a></li>
    <li><a id="aggiungi-utente" data-toggle="tab" href="#editor">Scrivi news</a></li>
    
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
              <th>Approva</th>
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
        
      </div>
      <div class="modal-footer">
         <button id="close-modal" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">

  csrf_name = '<?php echo $csrf['name']; ?>';
  user_id = '<?php echo $user_id; ?>';
  messaggio_risposta = "";
  
  function finestra_messaggio(messaggio, conferma){

    var html_messaggio = "<p>" + messaggio + "</p>";

    $("#confirm-modal .modal-body").html(html_messaggio);

    $("#confirm-modal").modal('show');

    
  }

  function aggancia_callback(){

    $("#commenti a").on('click', function (event) {

      event.preventDefault();
      $('#loading').modal('show');

      $.post( $(this).attr('href'), function(){
         $('#loading').modal('hide');
         messaggio_risposta = "Commento approvato";
         datatable.ajax.reload();
      });

    });
    
    $("#commenti glyphicon.glyphicon-remove").on('click', function () {

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
            { "data": "approva" }
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


    $("#loading").on('hidden.bs.modal', function(){

      finestra_messaggio(messaggio_risposta);

    });

    $("#confirm-modal").on('hidden.bs.modal', function(){

        location.reload();

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
