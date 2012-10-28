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
		$( "#listSintoma, #sintomasPaciente" ).sortable({
			connectWith: ".connectedSortable",
			dropOnEmpty: true
		}).disableSelection();
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
					<table class="tblCads ui-state-highlight ui-corner-all" cellspacing="10" cellpadding="5">
						<tr>
							<td width="400px;" class="ui-state-highlight ui-corner-all">
								<label>
									<span>Paciente</span>
									<select name='pacid' id='pacid' style='width: 200px' onchange='dadosPaciente(this.value)'>
										<option>Selecione um Paciente</option>
										<?php
											$rs = $diags->_get("pacientes");
											while(!$rs->EOF){
												echo "<option value='".$rs->Fields("pacid")."'>".$rs->Fields("pacnome")."</option>";
												$rs->MoveNext();												
											}
										?>
									</select>
								</label>
								<div id='dadoPaciente'>
								</div>
							</td>
							<td class="ui-state-highlight ui-corner-all">
								<label>
									<span>Sintomas</span><br />
									<input type='text' name='procSintoma' id='procSintoma' onkeyup="pesquisaSintoma(this.value)">
									<ul id='listSintoma' class="connectedSortable">
										<li style='height: 10px; display: none;'></li>
									</ul>
									<span>Sintomas do Paciente:</span>
									<ul id='sintomasPaciente' class="connectedSortable">
										<li style='height: 10px; width: 10px; list-style: none'>&nbsp;</li>
									</ul>								
								</label>
							</td>
						</tr>
						<tr>
							<td class="ui-state-highlight ui-corner-all">
								<label>
									<button id='atualizaDoencas'>Carregar Doenças</button><br />
									<span>Doenças Possíveis</span>
									<ul id='doencasPaciente'>
									</ul>								
								</label>
							</td>
							<td class="ui-state-highlight ui-corner-all">
								<label>
									<button id='atualizaTratamentos'>Carregar Tratamentos</button><br />
									<span>Tratamentos Possíveis</span>
									<ul id='tratamentosPaciente'>
									</ul>								
								</label>
							</td>
						</tr>
						<tr>
							<td colspan='2' align='center'>
								<button id='cadConsulta'>Salvar</button>
							</td>
						</tr>
					</table>
				</div>
			</div>	<!-- FIM DA DIV _content -->	
		</div>	<!-- FIM DA DIV _all -->	
    </body>
</html>
