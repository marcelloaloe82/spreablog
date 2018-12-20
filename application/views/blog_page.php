<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sprea blog Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datatables.min.css">
  <style>
    .admin-ops{
      position: fixed;
      right: 0;
      top: 300px;
      width: 50px;
      height: 100px;
      z-index: 200;
    }

    .admin-ops > div{
      border: 2px solid black;
      width: 50px;
      height: 50px;
    }

    .admin-ops > div > span{

      margin: auto;
      display: block;
      text-align: center;
    }



    #aggiungi-utente{
      display: none;
    }

    #pannello-utenti{

      display: none;
      position: fixed;
      z-index: 100;
      border: 1px solid;
      background-color: white;
      
      width: 0;
      height: 0;
    }

    .modal-body > img{

      max-width: 200px;
      display: block;
      margin: auto;
    }

    .publish-draft-button{

      margin-left: 10px;
    }

    #utenti_wrapper{

      width: 80%;
      margin: 50px auto;
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
<div id="aggiungi-utente"><!-- maschera per inserire nuovo utente -->
  <div style="border:1px solid; width: 500px; height: 500px;"></div>
</div>

<div id="pannello-utenti"><!-- tabella con gli utenti approvati -->
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


<div id="user-modal" class="modal fade" role="dialog">
      
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Dati utente</h4>
          </div>
          
          <div class="modal-body">
            <form>
              <div class="form-group">
                <input type="text" class="form-control" name="nome" id="nome" placeholder="nome" required="required">
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="cognome" id="cognome" placeholder="cognome"  required="required">
              </div> 
              <div class="form-group">
                <input type="email" class="form-control" name="email" id="email" placeholder="email" required="required">
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
              <div><button class="btn btn-primary btn-lg" id="invia-dati-utente">Invia</button></div>
             </form>
          </div>
          
        </div>

      </div>
</div>
<?php endif; ?>

<div class="container">
  <div class="admin-ops">
    <div id="nuovo-utente"><span  class="glyphicon glyphicon-plus"></span></div>
    <div id="apri-tabella-utenti"><span class="glyphicon glyphicon-th-list"></span></div>
    <div id="login-button"  data-target="#login-modal"><span class="glyphicon glyphicon glyphicon-user"></span></div>
    <div id="logout-button"><span class="glyphicon glyphicon-log-out"></span></div>
  </div>
  <?php if($this->session->user && $ruolo_utente == 'editor'): ?>
  <div class="row" id="editor">
    <h2>Scrivi articolo</h2>

    <form id="news-form">
    <div class="col-sm-12">
      <div class="form-group"><input type="text" name="title" class="form-control" placeholder="Titolo"></div>
    </div>
    <div class="col-sm-12">
      <textarea id="news-text" name="content" class="form-control" rows="5"></textarea>
      <div class="news-buttons">
        <button class="btn btn-default" id="save-draft">Salva bozza</button> 
        <button id="publish" class="btn btn-primary">Pubblica</button>
      </div>
    </form>
    </div>
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
      $data_pubblicazione = "<h4>Pubblicato il: ". strftime("%d %B %Y %H:%M",  strtotime($single_news['last_modified'])) . "</h4>";
      
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
            <input type="password" class="form-control" name="password" id="password" placeholder="password">
          </div>
          <div><button class="btn btn-primary btn-lg" id="login">Login</button></div>
        </form>
        </div>
        
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

  <div id="delete-confirm-modal" class="modal fade" role="dialog">
        
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Conferma cancellazione</h4>
            </div>
            
            <div class="modal-body">
              <p>Sei sicuro di voler cancellare questo utente?</p>
            </div>
            <div>
             <div style="width: 50%; margin: 0 auto;">
              <button class="btn btn-primary btn-lg" id="canc-ok">OK</button>
              <button class="btn btn-lg" id="canc-annulla">Annulla</button>
            </div>
           </div>
          </div>

        </div>
    </div>


  <ul class="pagination">
    <li class="active"><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
  </ul>
  
</div>    
</body>
<script type="text/javascript">

  function aggiungi_pulsanti(json){

    for(i in json.data){
      json.data[i]['operazioni'] = "<span class=\"glyphicon glyphicon-pencil\"></span><span class=\"glyphicon glyphicon-remove\"></span>";
    }

    return json.data;
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

    $("#utenti").css("width", "100%");

    $("#apri-tabella-utenti").click( function(){
      $("#pannello-utenti").show();
      $("#pannello-utenti").animate({width: "+=" + window.innerWidth + 'px', height: "+=" + window.innerHeight + 'px'}, 500, "swing");
      //$("#pannello-utenti").fadeIn('slow');
    });


    $("#nuovo-utente").click(function(){

      $("#user-modal").modal('show');
    });


    $("#utenti .glyphicon.glyphicon-pencil").on('click', function () {
      

        var rowdata = datatable.row( this.parentElement.parentElement ).data();

        $("#idutente").val(rowdata[0]);
        $("#nome").val(rowdata[1]);
        $("#cognome").val(rowdata[2]);
        $("#email").val(rowdata[3]);

        $("#user-modal").modal('show');
        //$("#ruolo").val();
    });

    $("#utenti .glyphicon.glyphicon-remove").on('click', function () {
      
        $("#delete-confirm-modal").modal('show');
        //$("#ruolo").val();
    });
  
  <?php endif; ?>

    $("#login-button").click( function(){

      $("#login-modal").modal('show');
    })


    $("#login").click(function(event){

      event.preventDefault();
      $.post("<?php echo base_url(); ?>index.php/api/auth/login", $("#login-form").serialize(), function(response){
        $("#login-modal").modal('hide');
        location.reload();
      });
    });

    $("#logout-button").click(function(event){

          event.preventDefault();
          $.post("<?php echo base_url(); ?>index.php/api/auth/logout", function(response){
           
            location.reload();
          });
        });


    $("#publish").click( function(event){

      event.preventDefault();
      $("#salvataggio-news-modal").modal("show");

      $.post("<?php echo base_url(); ?>index.php/api/news/create_news", {"content": tinyMCE.activeEditor.getContent()}, function(response){
          
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
