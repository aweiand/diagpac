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
		$( "#frmHistorico" ).accordion();
		
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
				<div id='frmHistorico'>
					<?php 
						$rs = $diags->_get("consultas");
						
						while(!$rs->EOF){
							
							echo "<h3>Data da Consulta: ".$rs->Fields("condata")." ".$rs->Fields("conhora")."</h3>";
					?>
							<div>
								<table class="tblCads" cellspacing="10" cellpadding="5">
									<tr>
										<td width="400px;" class="ui-state-highlight ui-corner-all" colspan='2'>
											<label>
												<span>Dados do Paciente:</span><br />
												<?php
													$pac = $diags->_get("pacientes","pacid=".$rs->Fields("pacid"));
													echo "Nome: ".$pac->Fields("pacnome")."<br />";
													echo "End: ".$pac->Fields("pacend")."<br />";
													echo "Tel: ".$pac->Fields("pactel")."<br />";
												?>
											</label>
										</td>
									</tr>
									<tr>
										<td class="ui-state-highlight ui-corner-all">
											<label>
												<span>Sintomas</span>
												<?php
													$sint = $diags->_get("sintomas s","conid=".$rs->Fields("conid"),"consulta_sintoma cs ON (cs.sinid = s.sinid)");
													echo "<ul>";
													while(!$sint->EOF){
														echo "<li>".$sint->Fields("sinnome")."</li>";
														$sint->MoveNext();
													}
													echo "</ul>";
												?>
											</label>
										</td>
										<td class="ui-state-highlight ui-corner-all">
											<label>
												<span>Doenças</span>
												<?php
													$sint = $diags->_get("doencas d","conid=".$rs->Fields("conid"),"consulta_doenca cd ON (cd.doeid = d.doeid)");
													echo "<ul>";
													while(!$sint->EOF){
														echo "<li>".$sint->Fields("doenome")."</li>";
														$sint->MoveNext();
													}
													echo "</ul>";
												?>
											</label>
										</td>
									</tr>
									<tr>
										<td width="400px;" class="ui-state-highlight ui-corner-all" colspan='2'>
											<label>
												<span>Tratamentos</span>
												<?php
													$tra = $diags->_get("tratamentos t","conid=".$rs->Fields("conid"),"consulta_tratamento ct ON (ct.traid = t.traid)");
													echo "<ul>";
													while(!$tra->EOF){
														echo "<li>".$tra->Fields("tranome")."</li>";
														$tra->MoveNext();
													}
													echo "</ul>";
												?>
											</label>
										</td>
									</tr>							
								</table>
							</div>
						<?php
							$rs->MoveNext(); 
						} 
						?>
				</div>
			</div>	<!-- FIM DA DIV _content -->	
		</div>	<!-- FIM DA DIV _all -->	
    </body>
</html>
