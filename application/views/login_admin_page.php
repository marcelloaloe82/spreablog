	<form class="form-signin" id="login-form">
        <h2 class="form-signin-heading">Autenticazione</h2>
        <label for="codice_op" class="sr-only">Email</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Email" required autofocus>
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="entra">Entra</button>
    </form>

    </div> <!-- /container -->
	<div id="modale" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"></h4>
		  </div>
		  <div class="modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
		  </div>
		</div>

	  </div>
	</div>
	<div id="caricamento" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<h4 class="modal-title">Verifica in corso...</h4>
		  </div>
		  <div class="modal-body">
			<img src="<?php echo base_url(); ?>static/img/load-icon.gif" style="display: block; margin: auto;" />
		  </div>
		</div>

	  </div>
	</div>

</div>	 

<script type="text/javascript">

	function finestra_messaggio(messaggio, conferma){

	    var html_messaggio = "<p>" + messaggio + "</p>";
	    
	    $("#confirm-modal .modal-body").html(html_messaggio);

	    $("#confirm-modal").modal('show');

    
  	}
	
	$(document).ready( function(){

		$("#entra").click(function(event){

	    	event.preventDefault();

		   	var form = $('#login-form').get(0); 
			var formData = new FormData(form);

		    $.ajax({
				url: "<?php echo base_url(); ?>index.php/api/auth/login",
				type: "POST",
				data: formData, 
				processData: false,
				contentType: false
				
			}).done(function(response){

	                  $("#login-modal").modal('hide');
	                  location.reload();
	                
	      }).fail( function(response){
	        
	        $("#login-modal").modal('hide');
	        
	        finestra_messaggio(response.responseJSON.message);
	        
	      });
	    });
	});
</script>
</body>
</html>