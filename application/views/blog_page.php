<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sprea blog Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
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

    
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="/spreablog/assets/js/tinymce/js/tinymce/tinymce.min.js"></script>
  <script>
    tinymce.init({ selector:'textarea',
                  height: 500,
                  theme: 'modern',
                  plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media  charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern',
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
  </div>
  <?php if($this->session->user && $ruolo_utente == 'editor'): ?>
  <div class="row" id="editor">
    <h2>Scrivi articolo</h2>
    <div class="col-sm-12">
      <textarea class="form-control" rows="5"></textarea>
      <div><button class="btn btn-default" >Salva bozza</button> <button id="publish" class="btn btn-primary">Pubblica</button></div>
    </div>
  </div>
<? endif; ?>
  <div class="row">
    <div class="col-sm-12">
      <h2>Titolo articolo</h2>
      <h4>Pubblicato da: autore il 1 gennaio 2018</h4>
      <div class="news-content">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
      </div>
    </div>
  </div>

  <div class="row">
      <div class="col-sm-12">
        <h2>titolo articolo</h2>
        <h4>Pubblicato da: autore il 1 gennaio 2018</h4>
        <div class="news-content">
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
        </div>
      </div>
  </div>
  
  <div class="row">
      <div class="col-sm-12">
        <h2>titolo articolo</h2>
        <h4>Pubblicato da: autore il 1 gennaio 2018</h4>
        <div class="news-content">
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
        </div>
      </div>
  </div>
 
  <div class="row">
      <div class="col-sm-12">
        <h2>titolo articolo</h2>
        <h4>Pubblicato da: autore il 1 gennaio 2018</h4>
        <div class="news-content">
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
        </div>
      </div>
  </div>
 
  <div class="row">
      <div class="col-sm-12">
        <h2>titolo articolo</h2>
        <h4>Pubblicato da: autore il 1 gennaio 2018</h4>
        <div class="news-content">
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
        </div>
      </div>
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
            <input type="password" class="form-control" name="password" id="password" placeholder="password">
          </div>
          <div><button class="btn btn-primary btn-lg" id="login">Login</button></div>
        </form>
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
    var fake_content = "<div class=\"news-content\"><div class=\"row\">" +
                          "<div class=\"col-sm-12\">"+
                          
                          "<h2>titolo articolo</h2>"+
                      "<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p></div>";
    $("#publish").click( function(){
      var new_item =$(fake_content).hide();
      $("#editor").after($(new_item).fadeIn('slow'));
    });


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
      $.post("/spreablog/index.php/api/auth/login", $("#login-form").serialize(), function(response){
        $("#login-modal").modal('hide');
      });
    })


  });
</script>
</html>
