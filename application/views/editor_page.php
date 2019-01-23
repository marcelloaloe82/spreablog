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
</div>
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

  $(document).ready( function(){


    $("#confirm-modal").on('hidden.bs.modal', function(){
      
      if(confirm_caller == 'news')
        location.reload();
    });

    
      
    $("#butt-ok").on('click', function(){

       $.post("<?php echo base_url(); ?>index.php/api/news/delete", "&id=" + $("#post-id").val(),
            function (response) {
              
              $("#confirm-delete-modal").modal('hide');
              finestra_messaggio('News cancellata correttamente');
              
            });
    });

      

      $("#publish").click( function(event){

        event.preventDefault();
        $("#salvataggio-news-modal").modal("show");

        var data = {"content": tinyMCE.activeEditor.getContent(), "title": $("#title").val()};
        var post_id = $("#post-id").val();
        var operazione = "";

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
</body>
</html>
