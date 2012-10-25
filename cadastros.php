<?php 
require_once "mainframe/autoload.php";

$diags = new diags();

@session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Planejamento Estratégico</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

<script type="text/javascript" src="<?php echo $CFG->addr ?>plugins/jquery/js/jquery-1.5.js"></script>
<script type="text/javascript" src="<?php echo $CFG->addr ?>plugins/jquery/js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="js/funcs.js"></script>
<script type="text/javascript" src="js/eventos.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo $CFG->addr ?>plugins/jquery/ui/css/custom-theme/jquery-ui-1.8.13.custom.css"/>
<link rel="stylesheet" type="text/css" href="css/core.css"/>
<link rel="stylesheet" type="text/css" href="css/comuns.css"/>

<script type="text/javascript">
	$(function() {
    	$( "#acordion" ).accordion();
    });
</script>

</head>
	<body style="background-color: darkcyan;">
		<div id='_all'>
			<div id='_menu'>
				<?php include "menu.php" ?>
			</div> <!-- FIM DA DIV _menu -->	
			<div id='_content'>
				<div id='acordion'>
					<h3>Doenças</h3>
					<div>
						<table class="tblCads">
							<tr>
								<td colspan='2'>
									<label>
										<span>Nome da Doença</span>
										<input type="text" name="doenome" />
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<label>
										<span>Sintomas</span>
										<select name='doe-sinid' id='doe-sinid'>
											<?php
												$rs = $diags->_get("sintomas");
												while(!$rs->EOF){
													echo "<option value='".$rs->Fields("traid")."'>".$rs->Fields("tranome")."</option>";
													$rs->MoveNext();												
												}
											?>
										</select>
									</label>
								</td>								
								<td>
									<label>
										<span>Tratamento</span>
										<select name='doe-traid' id='doe-traid'>
											<?php
												$rs = $diags->_get("tratamentos");
												while(!$rs->EOF){
													echo "<option value='".$rs->Fields("traid")."'>".$rs->Fields("tranome")."</option>";
													$rs->MoveNext();												
												}
											?>
										</select>
									</label>
								</td>								
							</tr>
							<tr>
								<td colspan='2' align="center">
									<button id="cadDoenca">Cadastrar</button>
								</td>
							</tr>
						</table>
						<div id='allDoenca'>
							<?php echo $diags->allDoencas() ?>
						</div>
					</div>

					<h3>Sintomas</h3>
					<div>
						<table class="tblCads">
							<tr>
								<td>
									<label>
										<span>Nome do Sintoma</span>
										<input type="text" name="sinnome" />
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<button id="cadSintoma">Cadastrar</button>
								</td>
							</tr>
						</table>
						<div id='allSintoma'>
							<?php echo $diags->allSintomas() ?>
						</div>
					</div>					

					<h3>Tratamentos</h3>
					<div>
						<table class="tblCads">
							<tr>
								<td>
									<label>
										<span>Nome do Tratamento</span>
										<input type="text" name="traid" />
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<button id="cadTratamento">Cadastrar</button>
								</td>
							</tr>
						</table>
						<div id='allTratameno'>
							<?php echo $diags->allTratamentos() ?>
						</div>
					</div>
					
				</div>
			</div>	<!-- FIM DA DIV _content -->	
		</div>	<!-- FIM DA DIV _all -->	
    </body>
</html>
