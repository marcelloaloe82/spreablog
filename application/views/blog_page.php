<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sprea blog Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datatables.min.css">
  <style>

   
    
  </style>
  <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/datatables.min.js"></script>
  
  <script src="<?php echo base_url(); ?>assets/js/tinymce/js/tinymce/tinymce.min.js"></script>
  <script>
    tinymce.init({ selector:'#editor textarea',
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


  
</div>    
</body>
</html>
