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

   .row{

    margin-top: 20px;
   }

   #editor{
    padding-bottom: 20px;
    box-shadow: 0px 0px 13px 10px rgba(199,197,199,1);
   }

   .edit-button{
      margin-right: 5px;
   }

    #utenti_wrapper{

      width: 80%;
      margin: 50px auto;
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

    .post-comment{

      margin-top: 20px;
      width: 80%;
    }
    
  </style>
  <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/datatables.min.js"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script src="<?php echo base_url(); ?>assets/js/tinymce/js/tinymce/tinymce.min.js"></script>
  <script>
    tinymce.init({ selector:'textarea',
                  height: 500,
                  language: 'it',
                  theme: 'modern',
                  plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media  charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern',
                  toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
                  automatic_uploads: true,
                  file_picker_types: 'image', 
                  // and here's our custom image picker
                  file_picker_callback: function(cb, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    
                    // Note: In modern browsers input[type="file"] is functional without 
                    // even adding it to the DOM, but that might not be the case in some older
                    // or quirky browsers like IE, so you might want to add it to the DOM
                    // just in case, and visually hide it. And do not forget do remove it
                    // once you do not need it anymore.

                    input.onchange = function() {
                      var file = this.files[0];
                      
                      var reader = new FileReader();
                      reader.onload = function () {
                        // Note: Now we need to register the blob in TinyMCEs image blob
                        // registry. In the next release this part hopefully won't be
                        // necessary, as we are looking to handle it internally.
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        // call the callback and populate the Title field with the file name
                        cb(blobInfo.blobUri(), { title: file.name });
                      };
                      reader.readAsDataURL(file);
                    };
                    
                    input.click();
                  },
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
    <div class="col-sm-12">
      
        <h2>Scrivi articolo</h2>

        <form id="news-form">
        
          <div class="form-group">
            <input type="text" name="title" id="title" class="form-control" placeholder="Titolo">
          </div>
        
        
          <textarea id="news-text" name="content" class="form-control" rows="5"></textarea>
          <div class="news-buttons">
            <button id="publish" class="btn btn-primary">Pubblica</button>
          </div>
          <input type="hidden" name="id" id="post-id">
        </form>
        
    </div>
  </div>
  
<?php endif; ?>

<?php 

if(!empty($ruolo_utente) && $ruolo_utente == 'editor'){

      $button_modifica = "<button class='btn btn-primary edit-button'>Modifica</button>";
      $button_elimina  = "<button class='btn btn-primary btn-danger delete-news-button'>Elimina</button>";
    
} else{

      $button_modifica = "";
      $button_elimina  = "";
      
}

foreach($news as $single_news): 


  $data_pubblicazione = "<h4>Pubblicato il: ". @strftime("%d %B %Y ",  strtotime($single_news['created_at'])) . "</h4>";
  
  ?>

  <div class="row">
    <div class="col-sm-12">
      <h2><?php echo $single_news['title']; ?></h2>
      <?php echo $data_pubblicazione; ?>
      <div class="news-content" data-post-id="<?php echo $single_news['id']; ?>">
      <?php echo $single_news['content']; ?>
      </div>
      <?php echo $button_modifica .' '. $button_elimina; ?>
      <div class="comment-area">
        <?foreach ($comments as $key => $comment_entry):?>
          <h4><?php echo $comment_entry['display_name']; ?></h4>
          <div class="comment-content"><?php echo $comment_entry['content']; ?></div>
      </div>
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
<?php endforeach; ?>
</div>


  <div id="login-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">

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
              <div style="width: 25%;margin: 0 auto;"><button class="btn btn-primary" id="butt-ok">OK</button><button class="btn" id="butt-annulla" data-dismiss="modal">Annulla</button></div>
           </div>
          </div>

        </div>
    </div>

    

  
  
</div>    
</body>
<script type="text/javascript">

  news_offset         = 10;
  no_more_news        = false;
  confirm_caller      = '';
  

  function finestra_messaggio(messaggio, conferma){

    var html_messaggio = "<p>" + messaggio + "</p>";
    
    $("#confirm-modal .modal-body").html(html_messaggio);

    $("#confirm-modal").modal('show');

    
  }


  function edit_button_callback(){
        
        $(this).parent().children().each( function(index, elem){
          
          if($(elem).attr('class') == 'news-content'){
            tinyMCE.activeEditor.setContent( $(elem).html() );
            $('#post-id').val( $(elem).data('post-id') );
          }

          if($(elem).prop('tagName').toLowerCase() == 'h2')
            $('#title').val( $(elem).text() );
        });

        $('html, body').animate({scrollTop: 0}, 500);
  }

  function delete_button_callback(){

      $(this).parent().children().each( function(index, elem){
         
        if($(elem).attr('class') == 'news-content'){
              
            $('#post-id').val( $(elem).data('post-id') );
        }

      });

      confirm_caller = 'news';

      $("#confirm-delete-modal .modal-body").text("Vuoi davvero cancellare questa news?");
      $("#confirm-delete-modal").modal('show');


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

        $("#pannello-utenti").modal('hide');
        $("#user-modal").modal('show');
        //$("#ruolo").val();
    });

    $("#utenti .glyphicon.glyphicon-remove").on('click', function () {
        

        var rowdata = datatable.row( this.parentElement.parentElement ).data();

        confirm_caller = 'user';

        $("#user_delete").val(rowdata['id']);

        $("#confirm-delete-modal .modal-body").text("Vuoi davvero cancellare questo utente?");
        
        $("#confirm-delete-modal").modal('show');
        
        
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

  $("#confirm-modal").on('hidden.bs.modal', function(){
    
    if(confirm_caller == 'news')
      location.reload();
  });

  $("#user-modal").on('shown.bs.modal', function(){

    $("#nome").focus();
    
  });
  
  $("#login-modal").on('shown.bs.modal', function(){

    $("#username").focus();

  });
    
  $("#butt-ok").on('click', function(){

      if(confirm_caller == 'user'){

        $.post("<?php echo base_url(); ?>index.php/api/users/delete", "&userid=" + $("#user_delete").val(),
          function (response) {
            $("#confirm-delete-modal").modal('hide');
            datatable.ajax.reload();
          });
        
      }

      if(confirm_caller == 'news'){

        $.post("<?php echo base_url(); ?>index.php/api/news/delete", "&id=" + $("#post-id").val(),
          function (response) {
            
            $("#confirm-delete-modal").modal('hide');
            finestra_messaggio('News cancellata correttamente');
            
          });
      }          

      

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



    $("#menu-login").click( function(){

      $("#login-modal").modal('show');
      $("#username").focus();
    })


    $("#login").click(function(event){

      event.preventDefault();

      $.post("<?php echo base_url(); ?>index.php/api/auth/login", 
             $("#login-form").serialize(), 
             function(response){

                  $("#login-modal").modal('hide');
                  location.reload();
                
      }).fail( function(response){
        
        $("#login-modal").modal('hide');
        
        finestra_messaggio(response.responseJSON.message);
        
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

      var data = {"content": tinyMCE.activeEditor.getContent(), "title": $("#title").val()};
      var post_id = $("#post-id").val();
      var operazione = "";

      confirm_caller = 'news';


      if(post_id){
        data.id = post_id;
        operazione = 'update';
      }

      else operazione = 'create';

      $.post("<?php echo base_url(); ?>index.php/api/news/" + operazione, data , function(response){
          
          $("#salvataggio-news-modal").modal("hide");

          
          finestra_messaggio("News salvata con successo");
      

      }).fail(function (response) {

        $("#salvataggio-news-modal").modal("hide");
        finestra_messaggio(response.responseJSON.message);
      
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
</html>
