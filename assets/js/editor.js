base_url = "/spreablog/index.php/";

function edit_button_callback(){
      

    var news_id = $(this).data('post-id') ;
       
    location.href = base_url + 'Admin/edit_news/' + news_id;
}

function delete_button_callback(){

	news_id = $(this).data('post-id') ;

	$("#confirm-delete-modal").modal('show');

}

  
  

  function aggancia_callback(){

    $("#commenti a").on('click', function (event) {

      event.preventDefault();
      var href = $(this).attr('href');

      if( href != "#"){

        if(href.indexOf("delete") > 0){
            
            $('#confirm-modal').modal('show');
            delete_uri = href;
        }

        if(href.indexOf("reply") > 0){

          $("#reply-form").attr("action", href);
          $("#reply-modal").modal({backdrop: "static"});
        }


      } else{

          var rowdata = tabella_commenti.row( this.parentElement.parentElement ).data();
          var comment_content = rowdata['content'];

          $("#view-comment .modal-body").text( comment_content );
          $("#view-comment").modal("show");
      }

    });
    
    
  }


  
  $(document).ready( function(){


    tabella_commenti= $("#commenti").DataTable({
      
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
        
        url: base_url + 'api/comments/' + user_id,
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
            { "data": "rispondi" }
        ],
      
      columnDefs: [
        { targets: [4], visible: false}
      ],

      dom: 'Bfrtip',
      pageLength: 30
    });


    tabella_commenti.on("draw.dt", function(){
    
      aggancia_callback();

    });

    
    if(location.pathname.indexOf("/edit_news/") >= 0)
      $("#scrivi-news").trigger("click");


    $("#message-dialog").on("hidden.bs.modal", function(){

      location.reload();
    
    });


    $("#loading").on("hidden.bs.modal", function(){

      finestra_messaggio(messaggio_risposta);
      

    });

    $("#confirm-modal").on("hidden.bs.modal", function(){

      $("#loading").modal("show");

      $.post( delete_uri, function(response){
               
         $('#loading').modal('hide');
         messaggio_risposta = response.message;

      
      }).fail( function(response){

        
        try{
          
          messaggio_risposta = response.responseJSON.message;

        }catch(exc){
          
          messaggio_risposta = response.responseText;
        }

        $('#loading').modal('hide');
        finestra_messaggio(messaggio_risposta);

      }).always(function(){

         $('#loading').modal('hide');

      });
    
    });

    $("#butt-ok").on('click', function(){

      $("#confirm-modal").modal("hide");

    });

    $("#reply-modal").on('hidden.bs.modal', function(){

      if($("#reply-text").val().trim() == ""){

        $("#reply-text").css("border", "1px solid #ccc");
        $("#message-empty-reply").hide();
        return;
      }

      $("#loading").modal("show");

      var form = $("#reply-form").get(0);
      var formData = new FormData(form);

      $.ajax({

        url: $("#reply-form").attr("action"),
        type: "POST",
        data: formData, 
        processData: false,
        contentType: false

      }).done(function(response){

          messaggio_risposta = response.message;
          $("#loading").modal("hide");          
          
      }).fail( function(response){
          
          try{

            messaggio_risposta = response.responseJSON.message;
          
          }catch(exc){

            messaggio_risposta = response.responseText;
          }

          $("#loading").modal("hide");          

      }).always(function(){

         $("#loading").modal("hide"); 

      });

    });

    $("#send-reply").click( function(event){

      $("#reply-modal").modal("hide");
      event.preventDefault();
      

    });

    $("#reply-text").keyup(function(){

      if($("#reply-text").val().trim() != "")
       $("#send-reply").prop("disabled", false);

     else $("#send-reply").prop("disabled", true);
    
    });

    $("#reply-text").blur( function(){
      
      if($("#reply-text").val().trim() == ""){
        $("#reply-text").css("border", "1px solid red");
        $("#message-empty-reply").show();
      

      }else{
        $("#reply-text").css("border", "1px solid green");
        $("#send-reply").prop("disabled", false);
        $("#message-empty-reply").hide();
      }
      
    });


    $("#publish").click( function(event){

      event.preventDefault();

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
        url: base_url + "api/news/" + operazione,
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
        $.post(base_url + "api/auth/logout", function(response){
         
          location.reload();
        });
    });

  });