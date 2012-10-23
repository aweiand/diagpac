<?php 
require_once "mainframe/autoload.php";
$get = $_GET;

$ut = new Utils();
$usua = new usuario();

//############# Sessão responsável por tratar o acesso do usuario #############
@session_start();
if (isset($_SESSION['usuid'])){
	if ( (( $_SESSION['usuid'] == 176) || ( $_SESSION['usuid'] == 309)) )
		$_SESSION['CKFinder_UserRole'] = 'admin';	
}
	
if (!$ut->_logado())
	header("Location: index.php");
//############# Final da Sessão responsável por tratar o acesso do usuario #############
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Planejamento Estratégico</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

<script type="text/javascript" src="<?php echo $CFG->addr ?>plugins/jquery/js/jquery-1.5.js"></script>
<script type="text/javascript" src="<?php echo $CFG->addr ?>plugins/jquery/js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $CFG->addr ?>plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $CFG->addr ?>plugins/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript" src="js/utils.js"></script>
<script type="text/javascript" src="js/funcs.js"></script>
<script type="text/javascript" src="js/eventos.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo $CFG->addr ?>plugins/jquery/ui/css/custom-theme/jquery-ui-1.8.13.custom.css"/>
<link rel="stylesheet" type="text/css" href="css/core.css"/>
<link rel="stylesheet" type="text/css" href="css/titulos.css"/>
<link rel="stylesheet" type="text/css" href="css/comuns.css"/>
<link rel="stylesheet" type="text/css" href="css/outros.css"/>

<script type="text/javascript">

</script>

</head>
	<body style='background: url("img/main-bg.jpg") repeat-x fixed 0 0 #2D6E98;'>
		<?php require "../tools/pag/menu_full.php"; ?>   

		<div id='_all'>
			<div id='_menu'>
				<img src='img/logo_png.png' style='width: 250px; float: left; margin-right: 50px;' />
			</div> <!-- FIM DA DIV _menu -->	
			<div id='_content'>
				<?php include 'pag/contexto.php' ?>
			</div>	<!-- FIM DA DIV _content -->	
		</div>	<!-- FIM DA DIV _all -->	
    </body>
</html>