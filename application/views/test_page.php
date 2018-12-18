<!DOCTYPE html>
<html>
<head>

	<title>Test Blog Backend</title>
	<style type="text/css">
		body > div{
			
			background-color: lightgrey;
			padding: 20px;
			margin: 20px;
			font-family: arial;
		}


		body > div div{
			width: 400px;
		}

		body > div  label, body > div input{

			display: inline-block;
			margin: 10px;
		}

		body > div  label{
			text-align: right;
			width: 60px;
		}

		body > div  input{
			text-align: left;
		}

		textarea{
			width: 350px;
			height: 300px;
			display: block;
		}
	</style>
</head>
<body>

	<div>
		<h3> crea utente</h3>
		<form id="user-form">
			<div><label>Nome</label><input name="nome" ></div>
			<div><label>Cognome</label><input name="cognome" ></div>
			<div><label>Email</label><input name="email" type="email" ></div>
			<div><label>Password</label><input name="password" ></div>
			<div><label>Ruolo</label><select name="role_id" id="ruolo"><option value="">------</option></select>
			</div>
			<div><button>Crea</button></div>
		</form>

		<textarea id="user-result" readonly="readonly"></textarea>
	</div>

	<div>
		<h3> aggiorna utente</h3>
		<form id="user-update-form">
			<div><label>Nome</label><input name="nome" ></div>
			<div><label>Cognome</label><input name="cognome" ></div>
			<div><label>Email</label><input name="email" type="email" ></div>
			<div><label>Password</label><input name="password" ></div>
			<input type="hidden" name="id" value="2">
			<div><button>Crea</button></div>
		</form>

		<textarea id="user-update-result" readonly="readonly"></textarea>
	</div>
	<div>
			<h3> Elenco utenti</h3>
			<form id="user-list-form">
				
				<div><button>Elenco utenti</button></div>
			</form>

			<textarea id="user-list-result" readonly="readonly"></textarea>
		</div>

	<div>
		<h3> login test</h3>
		<form id="login-form">
			<label>Username</label><input name="username" >
			<label>Password</label><input type="password" name="password" >
			<button>Login</button>

		</form>
		<textarea id="login-result" readonly="readonly">
			
		</textarea>
		<button id="logout">logout</button>
	</div>

	<div>
		<h3>Crea news test</h3>
		<form id="news-form">
			<label>Testo</label>
			<textarea  name="content"></textarea>
			<input name="author_id" id="author_id" type="hidden" value="13">
			<button>Salva</button>
		</form>
	</div>
	<script type="text/javascript" src="/spreablog/assets/js/jquery.js"></script>
	<script type="text/javascript">

		$(document).ready( function(){


			$("#user-form button").click(function(event){
				event.preventDefault();
				$.ajax({
					url: "/spreablog/index.php/api/users/create",
					method: "POST",
					data: $("#user-form").serialize(),
					processData: false
				}).done( function(result){
					$("#user-result").text( result.message )  ;
				}).fail( function(result){
					$("#user-result").text( result.responseJSON.message );
				})
			});
			$("#user-update-form button").click(function(event){
				event.preventDefault();
				$.ajax({
					url: "/spreablog/index.php/api/users/update",
					method: "POST",
					data: $("#user-update-form").serialize(),
					processData: false
				}).done( function(result){
					$("#user-update-result").text( result.message )  ;
				}).fail( function(result){
					$("#user-update-result").text( result.responseJSON.message );
				})
			});
			$("#login-form button").click(function(event){
				event.preventDefault();
				$.ajax({
					url: "/spreablog/index.php/api/auth/login",
					method: "POST",
					data: $("#login-form").serialize(),
					processData: false
				}).done( function(result){
					$("#login-result").text( result.message )  ;

					$.get("/spreablog/index.php/api/users/ruoli", function(result){

						ruoli = JSON.parse(result);

						for(i in ruoli){

							$("#ruolo").append("<option value='" + ruoli[i].id + "'>" + ruoli[i].name + "</option>");
						}
					});

				}).fail( function(result){
					$("#login-result").text( result.responseJSON.message );
				})
			});
			
			$("#news-form button").click(function(event){
				event.preventDefault();
				$.ajax({
					url: "/spreablog/index.php/api/news/create_news",
					method: "POST",
					data: $("#news-form").serialize(),
					processData: false
				}).done( function(result){
					alert( result.message )  ;
				}).fail( function(result){
					alert ( result.responseJSON.message );
				})
			});

			$("#user-list-form button").click(function(event){
				event.preventDefault();
				$.get("/spreablog/index.php/api/users/", function(response){
					$("#user-list-result").text(JSON.stringify(response));
				})
			});
			
			$("#logout").click(function(event){
				event.preventDefault();
				$.post("/spreablog/index.php/api/auth/logout", function(response){
					alert(JSON.stringify(response));
				})
			});

		})

	</script>
</body>
</html>