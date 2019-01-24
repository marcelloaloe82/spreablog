


  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#users">Utenti</a></li>
    <li><a data-toggle="tab" href="#add-user">Aggiungi/Modifica utente</a></li>
    <li><a data-toggle="tab" href="#comments">Moderazione commenti</a></li>
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
      <form id="user-form">
        <div class="form-group">
          <input type="text" class="form-control" name="name" id="nome" placeholder="nome" required="required">
        </div>
        <div class="form-group">
          <input type="text" class="form-control" name="surname" id="cognome" placeholder="cognome"  required="required">
        </div> 
        <div class="form-group">
          <input type="email" class="form-control" name="email_address" id="email" placeholder="email" required="required">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="passwd" placeholder="password" required="required">
        </div>
        <div class="form-group">
          <select class="form-control" name="ruolo" id="ruolo">
            <option value="">-----------</option>
            <?php foreach($ruoli as $ruolo): ?>
              <option value="<?php echo $ruolo['id']; ?>"><?php echo $ruolo['name']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <input type="hidden" name="idutente" id="idutente">
        <div><button class="btn btn-primary" id="invia-dati-utente">Invia</button></div>
      </form>
      
    </div>

    
    <div id="comments" class="tab-pane fade">
      <h3>Moderazione commenti</h3>
      <table id="comments-table" class="cell-border compact stripe">
        <thead>
          
          <tr>
            
            <th>id</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Indirizzo IP</th>
            <th>Titolo news</th>
            <th>Operazioni</th>
            
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>

      
 

  <div id="confirm-modal" class="modal fade" role="dialog">
        
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
              <div style="width: 25%;margin: 0 auto;">
                <button class="btn btn-primary" id="butt-ok">OK</button>
                <button class="btn" id="butt-annulla" data-dismiss="modal">Annulla</button>
              </div>
           </div>
          </div>

        </div>
    </div>

</div>    
</body>
<script type="text/javascript">

  
  function finestra_messaggio(messaggio, conferma){

    var html_messaggio = "<p>" + messaggio + "</p>";
    
    $("#confirm-modal .modal-body").html(html_messaggio);

    $("#confirm-modal").modal('show');

    
  }


  function aggiungi_pulsanti(json){

    for(i in json.data){
      json.data[i]['operazioni'] = "<span title=\"modifica dati\" class=\"glyphicon glyphicon-pencil\" style=\"cursor:pointer; margin: 0 10px;\"></span><span title=\"cancella utente\" class=\"glyphicon glyphicon-remove\" style=\"cursor:pointer; margin: 0 10px;\"></span>";
    }

    return json.data;
  }

  function aggancia_callback(){

    $("#utenti .glyphicon.glyphicon-pencil").on('click', function () {
      

        var rowdata = datatable.row( this.parentElement.parentElement ).data();

        $("#idutente").val(rowdata['id']);
        $("#nome").val(rowdata['nome']);
        $("#cognome").val(rowdata['cognome']);
        $("#email").val(rowdata['email']);

        //switch su tab aggiungi utente

    });

    $("#utenti .glyphicon.glyphicon-remove").on('click', function () {
        

        var rowdata = datatable.row( this.parentElement.parentElement ).data();

        $("#user_delete").val(rowdata['id']);

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
    
      aggancia_callback();

    });

  $("#utenti").css("width", "100%");

 

    $("#invia-dati-utente").click(function(event){

      event.preventDefault();

      var operazione;
      
      if($("#idutente").val())
        operazione = 'update';
      else operazione = 'create';

      $.post("<?php echo base_url(); ?>index.php/api/users/" + operazione, $("#user-form").serialize(), 
        
        function(response){
          
          $("#user-modal").modal('hide');
          finestra_messaggio(response.message);
          datatable.ajax.reload();
          datatable.draw();

        })
        .fail( function(response){
          
          $("#user-modal").modal('hide');
          
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
      $("#idutente").val('');

    });


    

    $("#menu-logout").click(function(event){

        event.preventDefault();
        $.post("<?php echo base_url(); ?>index.php/api/auth/logout", function(response){
         
          location.reload();
        });
    });


    $("#butt-ok").on('click', function(){

      $.post("<?php echo base_url(); ?>index.php/api/users/delete", "&userid=" + $("#user_delete").val(),
          function (response) {
            $("#confirm-delete-modal").modal('hide');
            datatable.ajax.reload();
          });
        
      }



  });
</script>
</html>
