<?php 
require_once "mainframe/autoload.php";

$diags = new diags();

@session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>DiagPac - Diagnóstico de Pacientes</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

<script type="text/javascript" src="<?php echo $CFG->addr ?>plugins/jquery/js/jquery-1.5.js"></script>
<script type="text/javascript" src="<?php echo $CFG->addr ?>plugins/jquery/js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $CFG->addr ?>plugins/jquery/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo $CFG->addr ?>plugins/jquery/jquery.multiselect.filter.js"></script>        
<script type="text/javascript" src="js/funcs.js"></script>
<script type="text/javascript" src="js/eventos.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo $CFG->addr ?>plugins/jquery/ui/css/custom-theme/jquery-ui-1.8.13.custom.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->addr ?>plugins/jquery/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->addr ?>plugins/jquery/jquery.multiselect.filter.css" />     
<link rel="stylesheet" type="text/css" href="css/core.css"/>
<link rel="stylesheet" type="text/css" href="css/comuns.css"/>


<script type="text/javascript">
	$(function() {

    });
</script>

</head>
	<body style="background-color: darkcyan;">
		<div id='_all'>
			<div id='_menu'>
				<?php include "menu.php" ?>
			</div> <!-- FIM DA DIV _menu -->	
			<div id='_content'>
				<div id='fmrCadPaciente'>
					<table class="tblCads ui-state-highlight ui-corner-all">
						<tr>
							<td>
								<label>
									<span>Nome</span><br />
									<input type='text' name='pacnome' id='pacnome'>
								</label>
							</td>
							<td>
								<label>
									<span>Telefone</span><br />
									<input type='text' name='pactel' id='pactel'>
								</label>
							</td>
						</tr>
						<tr>
							<td colspan='2'>
								<label>
									<span>Endereço</span><br />
									<input type='text' name='pacend' id='pacend'>
								</label>
							</td>
						</tr>
						<tr>
							<td colspan='2' align='center'>
								<button id='cadPaciente'>Salvar</button>
							</td>
						</tr>
					</table>
					<div class='ui-state-highlight ui-corner-all' style='margin-top: 10px'>
						<?php echo $diags->allPacientes() ?>
					</div>
				</div>
			</div>	<!-- FIM DA DIV _content -->	
		</div>	<!-- FIM DA DIV _all -->	
    </body>
</html>
