function edit_button_callback(){
      

    var news_id = $(this).data('post-id') ;
       
    location.href ='<?php echo base_url();?>index.php/Admin/edit_news/' + news_id;
}

function delete_button_callback(){

	news_id = $(this).data('post-id') ;

	$("#confirm-delete-modal").modal('show');

}