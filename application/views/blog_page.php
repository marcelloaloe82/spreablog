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
      /*background-color: blue;*/
      width: 0%;
      height: 0%;
    }

    .modal-body > img{

      max-width: 200px;
      display: block;
      margin: auto;
    }

    .publish-draft-button{

      margin-left: 10px;
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

<div id="aggiungi-utente"><!-- maschera per inserire nuovo utente -->
  <div style="border:1px solid; width: 500px; height: 500px;"></div>
</div>
<div id="pannello-utenti"><!-- tabella con gli utenti approvati -->
  
</div>

<div class="container">
  <div class="admin-ops">
    <div><span  class="glyphicon glyphicon-plus"></span></div>
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
      <div>
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
  $(document).ready(function(){
    
    $("#apri-tabella-utenti").click( function(){
      $("#pannello-utenti").show();
      $("#pannello-utenti").animate({width: "+=" + window.innerWidth + 'px', height: "+=" + window.innerHeight + 'px'}, 500, "swing");
      //$("#pannello-utenti").fadeIn('slow');
    });


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
