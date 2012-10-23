<?php 
require_once "mainframe/autoload.php";
$ut = new Utils();
if ($ut->_logado())
	header("Location: home.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Login Planejamento Estratégico</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

<script type="text/javascript" src="<?php echo $CFG->addr ?>plugins/jquery/js/jquery-1.5.js"></script>
<script type="text/javascript" src="js/utils.js"></script>

<link rel="stylesheet" type="text/css" href="css/login.css"/>
</head>
<body>
	<?php require "../tools/pag/menu_full.php"; ?>
	
		<div class="login">
			<div class="login-in">
				<div class="logo-name">
					<h1>
						<a href = "http://www.cnecvirtual.com.br/" title = "CNEC Virtual">
							<img src="../tools/img/logo.png" alt="CNEC Virtual"  />
						</a>
					</h1>
					<h2>Gerenciador de Planejmaneto Estratégico</h2>
				</div>

				<form id = "loginform" name = "loginform" method="post" action="mainframe/actions.php?action=login"  onsubmit="return validateCompleteForm(this,'input_error');">
					<fieldset>
					<div class="row">
						<label for="username" class="username"></label>
						<input type="text" class="text" name="username" id="username" required = "1" style='height: 23px; width: 165px;' />
					</div>

					<div class="row">
						<label for="pass" class="pass"></label>
						<input type="password" class="text" name="pass" id="pass" style='height: 23px; width: 165px;' />
					</div>


					<div class="row">
						<label for="stay" class="keep" onclick = "toggleClass(this,'keep','keep-active');"><span>Stay logged in</span></label>
						<input type = "checkbox" name = "staylogged" id="stay" value = "1" />
					</div>

					<div class="row">
						<button type="submit" class="loginbutn" title="Login" onfocus="this.blur();"></button>
					</div>
					</fieldset>
				</form>
			</div>
			<?php if (isset($_GET['action']) && $_GET['action']=="erroLogin"){ ?>
			<div class="login-alert timetrack">
				Você não foi Logado
				<br>
				Por favor cheque suas credenciais de acesso.
				<br>
				<br>
			</div>
			<?php } ?>
		</div>
    </body>
</html>