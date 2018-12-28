<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sprea blog Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datatables.min.css">
  <style>

    #menu{
      padding: 10px;
      height: 50px;
    }

    /*.admin-ops{
      position: fixed;
      left: 0;
      top: 300px;
      width: 50px;
      height: 100px;
      z-index: 200;
    }

    .admin-ops > div{
      border: 2px solid black;
      width: 50px;
      height: 50px;
      margin-top: 10px;
    }

    .admin-ops > div > span{

      margin: auto;
      display: block;
      text-align: center;
    }



    #aggiungi-utente{
      display: none;
    }

    #apri-tabella-utenti{

      border: 2px solid black;
      float:right;
      width: 50px;
      height: 50px;
      margin-top: 10px;
    }
    #tabella-utenti-wrapper{
      left:-804px;
      width: 750px;
      height: 750px;
      background-color: white; 
      border: 1px solid;
      position: relative;
    }*/

   

    #utenti_wrapper{

      width: 80%;
      margin: 50px auto;
    }

    #overlayer{
      height: 1200px;
      width: 1400px;
      background: black;
      opacity: 0.7;
      display: none;
      position: fixed;
      z-index: 1;
    }

    .modal-body > img{

      max-width: 150px;
      max-height: 150px;
      display: block;
      margin: auto;
    }

    .publish-draft-button{

      margin-left: 10px;
    }


    .news-buttons{

      margin-top: 20px;
    }
    
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/datatables.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/tinymce/js/tinymce/tinymce.min.js"></script>
  <script>
    tinymce.init({ selector:'textarea',
                  height: 500,
                  theme: 'modern',
                  plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media  charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern',
                  toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
                  //image_advtab: true,
                  templates: [
                    { title: 'Test template 1', content: 'Test 1' },
                    { title: 'Test template 2', content: 'Test 2' }
                  ]});
  </script>
</head>
<body>

<?php if($this->session->user && $ruolo_utente == 'admin'): ?>

<div id="pannello-utenti" class="modal fade" role="dialog">
        
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Elenco utenti</h4>
          </div>
          
          <div class="modal-body">
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
          
        </div>

      </div>
  </div>

  <input type="hidden" id="user_delete" />

<div id="user-modal" class="modal fade" role="dialog">
      
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Dati utente</h4>
          </div>
          
          <div class="modal-body">
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
          
        </div>

      </div>
</div>
<?php endif; ?>


<div class="container">
  <div id="menu">
     <div class="dropdown">
        <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Area riservata
        <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
        <?php if($this->session->user && $ruolo_utente == 'admin'): ?>

          <li id="menu-nuovo-utente" role="presentation"><a role="menuitem" tabindex="-1" href="#">Aggiungi utente</a></li>
          <li id="menu-tabella-utenti" role="presentation"><a role="menuitem" tabindex="-1" href="#">Elenco utenti</a></li>
        <?php endif; ?>
         <?php if(empty($this->session->user)): ?>
          <li id="menu-login" role="presentation"><a role="menuitem" tabindex="-1" href="#">Login</a></li>
        <?php else: ?>
          <li id="menu-logout" role="presentation"><a role="menuitem" tabindex="-1" href="#">Logout</a></li>
        <?php endif; ?>
          
        </ul>
      </div>
    </div>
  
  <!--div class="admin-ops">
    < !--div id="nuovo-utente"><span  class="glyphicon glyphicon-plus"></span></div-->
   
     
      
    
    <!--div id="login-button"  data-target="#login-modal"><span class="glyphicon glyphicon glyphicon-user"></span></div>
    <div id="logout-button"><span class="glyphicon glyphicon-log-out"></span></div-->
  <!--/div-->
  <?php if($this->session->user && $ruolo_utente == 'editor'): ?>
  <div class="row" id="editor">
    <h2>Scrivi articolo</h2>

    <form id="news-form">
    <div class="col-sm-12">
      <div class="form-group">
        <input type="text" name="title" id="title" class="form-control" placeholder="Titolo">
      </div>
    </div>
    <div class="col-sm-12">
      <textarea id="news-text" name="content" class="form-control" rows="5"></textarea>
      <div class="news-buttons">
        <button class="btn btn-default" id="save-draft">Salva bozza</button> 
        <button id="publish" class="btn btn-primary">Pubblica</button>
      </div>
    </form>
    </div>
  
<? endif; ?>

<?php foreach($news as $single_news): ?>

  <?php 

  if($single_news['status'] == 'draft') {
   
    if(!empty($ruolo_utente)){

      if($ruolo_utente != 'editor') continue;
      
      $button_modifica = "<button class='btn btn-primary edit-button'>Modifica</button>";
      $button_pubblica = "<button class='btn btn-primary publish-draft-button'>Pubblica</button>";
      $data_pubblicazione = "";
    
    }
 

  } else{

      $button_modifica = "";
      $button_pubblica = "";
      $data_pubblicazione = "<h4>Pubblicato il: ". @strftime("%d %B %Y ",  strtotime($single_news['last_modified'])) . " alle " . @strftime("%H:%M", $single_news['last_modified']) . "</h4>";
      
  }

  ?>

  <div class="row">
    <div class="col-sm-12">
      <?php echo $button_modifica . $button_pubblica; ?>
      <h2><?php echo $single_news['title']; ?></h2>
      <?php echo $data_pubblicazione; ?>
      <div class="news-content">
      <?php echo $single_news['content']; ?>
      </div>
    </div>
  </div>
  <hr>
<?php endforeach; ?>
</div>


  <div id="login-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Login</h4>
        </div>
        <form id="login-form">
        <div class="modal-body">
          <div class="form-group">
            <input type="text" class="form-control" name="username" id="username" placeholder="username">
          </div>
          <div class="form-group">
            <input type="password" class="form-control" name="password"  placeholder="password">
          </div>
          <div><button class="btn btn-primary" id="login">Login</button></div>
        
        </div>
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
            <h4 class="modal-title">Salvataggio in corso...</h4>
          </div>
          
          <div class="modal-body">
            <img src="<?php echo base_url(); ?>assets/img/load-icon.gif">
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
              <h4 class="modal-title">Finestra di messaggio</h4>
            </div>
            
            <div class="modal-body">
              
            </div>
            <div class="modal-footer">
            
           </div>
          </div>

        </div>
    </div>

    <div id="confirm-cancel-modal" class="modal fade" role="dialog">
        
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
              <div style="width: 25%;margin: 0 auto;"><button class="btn btn-primary" id="butt-ok">OK</button><button class="btn" id="butt-annulla" data-dismiss="modal">Annulla</button></div>
           </div>
          </div>

        </div>
    </div>

    

  
  
</div>    
</body>
<script type="text/javascript">

  function finestra_messaggio(messaggio, conferma){

    var html_messaggio = "<p>" + messaggio + "</p>";
    var html_bottoni = "";

    $("#confirm-modal .modal-body").html(html_messaggio);

    if(conferma){
       html_bottoni = "<div style=\"width: 50%; margin: 0 auto;\">" +
                        "<button class=\"btn btn-primary\" id=\"butt-ok\">OK</button>" +
                        "<button class=\"btn\" id=\"butt-annulla\" data-dismiss=\"modal\">Annulla</button>" +
                      "</div>";  

    }

    else{
      html_bottoni = "<div style=\"width: 50%; float: right;\">" +
                        "<button class=\"btn btn-primary\" id=\"butt-ok\" data-dismiss=\"modal\">OK</button>" +
                      "</div>";
    }

    $("#confirm-modal .modal-footer").html(html_bottoni);

    $("#confirm-modal").modal('show');

    
  }


 

  function aggiungi_pulsanti(json){

    for(i in json.data){
      json.data[i]['operazioni'] = "<span class=\"glyphicon glyphicon-pencil\" style=\"cursor:pointer; margin: 0 10px;\"></span><span class=\"glyphicon glyphicon-remove\" style=\"cursor:pointer; margin: 0 10px;\"></span>";
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

        $("#pannello-utenti").modal('hide');
        $("#user-modal").modal('show');
        //$("#ruolo").val();
    });

    $("#utenti .glyphicon.glyphicon-remove").on('click', function () {
        

        var rowdata = datatable.row( this.parentElement.parentElement ).data();


        $("#user_delete").val(rowdata['id']);
        
        $("#confirm-cancel-modal").modal('show');
        
        
    });
    
  }
  
  $(document).ready(function(){

<?php if($this->session->user && $ruolo_utente == 'admin'): ?>
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
<?php endif; ?>

    $("#utenti").css("width", "100%");

    
   $("#butt-ok").on('click', function(){

          
          $.post("<?php echo base_url(); ?>index.php/api/users/delete", "&userid=" + $("#user_delete").val(),
            function (response) {
              $("#confirm-cancel-modal").modal('hide');
              datatable.ajax.reload();
            });
    });

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
          response = JSON.parse(response.responseText);
          finestra_messaggio(response.message);
        });
    });


    $("#menu-tabella-utenti").click( function(){
      
      $("#pannello-utenti").modal('show');
      
      //$("#pannello-utenti").fadeIn('slow');
    });
    
    

    $("#menu-nuovo-utente").click(function(){

      $("#idutente").val("");
      $("#user-modal").modal('show');

    });



    $("#menu-login").click( function(){

      $("#login-modal").modal('show');
    })


    $("#login").click(function(event){

      event.preventDefault();
      $.post("<?php echo base_url(); ?>index.php/api/auth/login", $("#login-form").serialize(), function(response){
        $("#login-modal").modal('hide');
        location.reload();
      });
    });

    $("#menu-logout").click(function(event){

          event.preventDefault();
          $.post("<?php echo base_url(); ?>index.php/api/auth/logout", function(response){
           
            location.reload();
          });
        });


    $("#publish").click( function(event){

      event.preventDefault();
      $("#salvataggio-news-modal").modal("show");

      $.post("<?php echo base_url(); ?>index.php/api/news/create_news", {"content": tinyMCE.activeEditor.getContent(), "title": $("#title").val()}, function(response){
          
          $("#salvataggio-news-modal").modal("hide");

          var new_entry_content = "<div class='row'><div class='col-sm-12'><h2>" + response.title + "</h2><div  class='news-content'>" + response.content + "</div></div></div>";           
          var new_item = $(new_entry_content).hide();
          $("#editor").after($(new_item).fadeIn(2000));
      })
    });

    $("#save-draft").click( function(event){

          event.preventDefault();
          $("#salvataggio-news-modal").modal("show");

          $.post("<?php echo base_url(); ?>index.php/api/news/create_draft", {"content": tinyMCE.activeEditor.getContent()}, function(response){
              
              $("#salvataggio-news-modal").modal("hide");

              var new_entry_content = "<div class='row'><div class='col-sm-12'><h2>" + response.title + "</h2><div  class='news-content'>" + response.content + "</div></div></div>";        
              var new_item = $(new_entry_content).hide();
              $("#editor").after($(new_item).fadeIn(2000));
          })
    });





  });
</script>
</html>
