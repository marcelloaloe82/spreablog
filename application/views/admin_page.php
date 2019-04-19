<div>
  <button style="float:right;" class="btn btn-default" id="logout">Logout</button>&nbsp;
  <button style="float:right;" class="btn btn-default" id="blog">Vai al blog</button>
</div>
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#users">Utenti</a></li>
    <li><a id="aggiungi-utente" data-toggle="tab" href="#add-user">Aggiungi/Modifica utente</a></li>
    <li><a data-toggle="tab" href="#modera">Modera commenti</a></li>
    <li><a id="scrivi-news" data-toggle="tab" href="#editor">Scrivi news</a></li>
    
  </ul>

  <div class="tab-content">
    <div id="users" class="tab-pane fade in active">
      <h3>Utenti</h3>
      <table id="utenti" class="cell-border compact stripe">
        <thead>
          
          <tr>
            
            <th>id</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Email</th>
            <th>Ruolo</th>
            <th>Operazioni</th>
            
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>

    <div id="add-user" class="tab-pane fade">
      <h3>Aggiungi/Modifica utente</h3>
      <form id="user-form" validate>
        <div class="form-group">
          <input type="text" class="form-control" name="name" id="nome" placeholder="nome" required autofocus="">
        </div>
        <div class="form-group">
          <input type="text" class="form-control" name="surname" id="cognome" placeholder="cognome"  required>
        </div> 
        <div class="form-group">
          <input type="email" class="form-control" name="email_address" id="email" placeholder="email" required>
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="passwd" id="password" placeholder="password" required>
        </div>
        <div class="form-group">
          <input type="password" class="form-control" placeholder="conferma password" id="conferma-passw" required>
        </div>
        <div id="passw-error" class="form-group" style="display: none;">
          <span  style="color: red; font-weight: bold;">Le password non coincidono</span>
        </div>
        <div class="form-group">
          <select class="form-control" name="ruolo" id="ruolo">
            <option value="">---- Seleziona il ruolo -------</option>
            <?php foreach($ruoli as $ruolo): ?>
              <option value="<?php echo $ruolo['id']; ?>"><?php echo $ruolo['name']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <input type="hidden" name="userid" id="userid">
        <input type="hidden" name="<?php echo $csrf['name']; ?>" id="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>">
        <div><button class="btn btn-primary" id="invia-dati-utente">Invia</button></div>
      </form>
      
    </div>

    <?php echo $tab_editor; ?>
  <!--end tab content -->
  </div>

      
 <?php echo $comments_modals; ?>
  
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
              <h4 class="modal-title">Finestra di messaggio</h4>
            </div>
            
            <div class="modal-body">
              
            </div>
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
            </div>
          </div>

        </div>
    </div>

    <div id="confirm-delete-modal" class="modal fade" role="dialog">
        
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Finestra di messaggio</h4>
            </div>
            
            <div class="modal-body">
              Vuoi davvero cancellare l'utente?
            </div>
            <div class="modal-footer">
              <div class="confirm-buttons-footer">
                <button class="btn btn-primary" id="butt-ok">OK</button>
                <button class="btn" id="butt-annulla" data-dismiss="modal">Annulla</button>
              </div>
           </div>
          </div>

        </div>
    </div>

</div>    
</body>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/utils.js"></script>  
<script type="text/javascript">

  csrf_name = '<?php echo $csrf['name']; ?>';
  user_id = '<?php echo $user_id; ?>';
  messaggio_risposta = "";
  delete_uri = "";
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/editor.js"></script>    
<script type="text/javascript">

  operazione = "";


  function aggiungi_pulsanti(json){

    for(i in json.data){
      json.data[i]['operazioni'] = "<span title=\"modifica dati\" class=\"glyphicon glyphicon-pencil\" style=\"cursor:pointer; margin: 0 10px;\"></span><span title=\"cancella utente\" class=\"glyphicon glyphicon-remove\" style=\"cursor:pointer; margin: 0 10px;\"></span>";
    }

    return json.data;
  }

  function aggancia_callback_tabella_utenti(){

    $("#utenti .glyphicon.glyphicon-pencil").on('click', function () {
      

        var rowdata = datatable.row( this.parentElement.parentElement ).data();

        $("#userid").val(rowdata['id']);
        $("#nome").val(rowdata['nome']);
        $("#cognome").val(rowdata['cognome']);
        $("#email").val(rowdata['email']);

        //switch su tab aggiungi utente
        $("#aggiungi-utente").trigger('click');

    });

    
    $("#utenti .glyphicon.glyphicon-remove").on('click', function () {
        

        var rowdata = datatable.row( this.parentElement.parentElement ).data();

        $("#userid").val(rowdata['id']);

        $("#confirm-delete-modal .modal-body").text("Vuoi davvero cancellare questo utente?");
        
        $("#confirm-delete-modal").modal('show');
        
        
    });
    
  }

  
  $(document).ready(function(){

    datatable= $("#utenti").DataTable({
      
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
        
        url: '<?php echo base_url(); ?>index.php/api/users',
        contentType: false,
        processData: false,
        dataSrc: aggiungi_pulsanti
        
      },

      columns:  [
            { "data": "id" },
            { "data": "nome" },
            { "data": "cognome" },
            { "data": "email" },
            { "data": "ruolo" },
            { "data": "operazioni" }
        ],
      
      columnDefs: [
        { targets: [0], visible: false}
      ],

      dom: 'Bfrtip',
      pageLength: 30
    });


    datatable.on("draw.dt", function(){
    
      aggancia_callback_tabella_utenti();

    });

  $("#utenti").css("width", "100%");

  $("#message-dialog").on('hidden.bs.modal', function(){
    
      location.reload();
  });


  $("#conferma-passw").blur( function(){

    if($("#conferma-passw").val() != $("#password").val()){

      $("#conferma-passw").css("border", "1px solid red");
      $("#password").css("border", "1px solid red");
      $("#passw-error").show();
      $("#invia-dati-utente").prop('disabled', true);
    
    } else{
      $("#conferma-passw").css("border", "1px solid #2eb92e");
      $("#password").css("border", "1px solid #2eb92e");
      $("#passw-error").hide();
      $("#invia-dati-utente").prop('disabled', false);
    }

  });

 

  $("#invia-dati-utente").click(function(event){

      event.preventDefault();

      if($("#userid").val())
        operazione = 'update';
      else operazione = 'create';

      var form = $('#user-form').get(0); 
      var formData = new FormData(form);
      //formData.append(csrf_name, $("#"+csrf_name).val());

      $.ajax({
        url: "<?php echo base_url(); ?>index.php/api/users/" + operazione,
        type: "POST",
        data: formData, 
        processData: false,
        contentType: false

      }).done(function(response){

          finestra_messaggio(response.message);
          
      }).fail( function(response){
          
          finestra_messaggio(response.responseJSON.message);

        });
    });


    $("#menu-tabella-utenti").click( function(){
      
      $("#pannello-utenti").modal('show');
      
      //$("#pannello-utenti").fadeIn('slow');
    });
    
    

    $("#menu-nuovo-utente").click(function(){

      $("#user-form").trigger('reset');
      $("#user-modal").modal('show');
      $("#userid").val('');

    });


    $("#blog").click(function(){
        location.href = '<?php echo base_url(); ?>index.php/Blog';
    });
    

    $("#logout").click(function(event){

        $.post("<?php echo base_url(); ?>index.php/api/auth/logout", function(response){
         
          location.reload();
        });
    });


    $("#butt-ok").on('click', function(){

      var form = $('#user-form').get(0); 
      var formData = new FormData(form);
      //formData.append(csrf_name, $("#"+csrf_name).val());

      $.ajax({
        url: "<?php echo base_url(); ?>index.php/api/users/delete",
        type: "POST",
        data: formData, 
        processData: false,
        contentType: false

      }).done(function(response){
      
          $("#confirm-delete-modal").modal('hide');
          datatable.ajax.reload();
      
      }).fail( function(response){
          
          finestra_messaggio(response.responseJSON.message);

      });
        
    });

  });
</script>
</html>
