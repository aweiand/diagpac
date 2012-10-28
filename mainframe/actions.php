<?php
require_once "autoload.php";

@session_cache_expire(90); // 2 hours
@session_start();

$post = $_POST;
$get = $_GET;

/*
  echo "<pre>";
  print_r($post);
  exit();
*/


/**
 * Login no Sistema, realiza o login no sistema
 *   
 * 	@param String login 
 * 	@param String senha 
 */
if (isset($post["username"]) && (isset($post['pass']))) {
    $u = new Utils();
    if (!$u->badWords($post)) {
        return "Não Logado";
        break;
    }

    $l = new dataMoodle();
    $log = $l->_login($post['username'], $post['pass']);
    if (isset($log['usuid'])) {
		@session_cache_expire(90); // 2 hours
		@session_start();
        $_SESSION['usuid'] = $log['usuid'];
        $_SESSION['usu']   = $log['usu'];
        $_SESSION['email'] = $log['email'];
        $_SESSION['autenticado'] = true;

        if (!$_SESSION['autenticado']) {
            session_destroy();
            header("Location: ../index.php?action=erroLogin");
        } else {
            header("Location: ../home.php");
			exit();
        }
    } else {
        session_destroy();
        header("Location: ../index.php?action=erroLogin");
    }
}

//############# Classes padrão para os cases #############

$uti   = new utils();
$db    = new data();
$diags = new diags();

//############# Final das Classes padrão para os cases #############
if (isset($post['action'])){
	switch($post['action']){
		case 'NovoItem' : {

			if ($post['tabelaInsere'] == "doencas"){
				$post['doeid'] = $db->seed("doencas","doeid");
				if ($diags->_insrt('doencas',$post)){

					$arr = explode("#",$post['traid']);
					foreach($arr as $ar => $val)
						$diags->_insrt('doenca_tratamento', array("doeid" => $post['doeid'], "traid" => $val));

					$arr = explode("#",$post['sinid']);
					foreach($arr as $ar => $val)
						$diags->_insrt('doenca_sintoma', array("doeid" => $post['doeid'], "sinid" => $val));

				echo 1;
				} else
					echo 0;
			} else { 
				if ($diags->_insrt($post['tabelaInsere'],$post))
					echo 1;
				else
					echo 0;
			}
		} break;

		case 'RemoveItem' : {
			unset($post['action']);
			
			if ($post['tabelaRemove'] == 'doencas'){
				$diags->_del('doenca_tratamento', 'doeid='.$post['idRemove']);
				$diags->_del('doenca_sintoma', 'doeid='.$post['idRemove']);
				if ($diags->_del('doencas', 'doeid='.$post['idRemove']))
					echo 1;
				else
					echo 0;
			} else {
				if ($diags->_del($post['tabelaRemove'], $post['idRemove']))
					echo 1;
				else
					echo 0;
			}
		} break;
		
		case 'AtualizaItem' : {
			unset($post['action']);
			if ($diags->_updt($post['tabelaAtualiza'], $psot['dados'], $post['idAtualizar'])){
				echo 1;
			} else {
				echo 0;
			}
		} break;		
		
		case 'PesquisandoSintomas' : {
			unset($post['action']);
			$cmdSQL = "SELECT * FROM sintomas WHERE sinnome LIKE '%".$post['letras']."%'";
			$sg = $db->query($cmdSQL);

			$str 	= '';
			while(!$sg->EOF){
				$str.="	<li class='ui-state-default ' id='".$sg->Fields("sinid")."' 
							style='background-color: #EFF2A9; max-width: 300px;'>".$sg->Fields("sinnome")."
						</li>";
				$sg->MoveNext();
			}
			echo $str;			
		} break;

		case 'carregaDoencas' : {
			unset($post['action']);
			
			$post['sints'] = substr($post['sints'],1,$post['sints'].lenght-1);
			
			$sg = $db->query("SELECT * FROM doenca_sintoma ds
								INNER JOIN doencas d ON (ds.doeid = d.doeid)
 							WHERE ds.sinid IN (".$post['sints'].") ");

			$str 	= '';
			while(!$sg->EOF){
				$str.="	<li class='ui-state-default ' id='".$sg->Fields("doeid")."' style='background-color: #EFF2A9; max-width: 250px;'>"
							.$sg->Fields("doenome").
							"<span style='float: right;'><input type='checkbox' id='btn-chkDoenca' value='".$sg->Fields("doeid")."' /></span>".
						"</li>";
				$sg->MoveNext();
			}
			echo $str;						
		} break;

		case 'carregaTratamentos' : {
			unset($post['action']);
			
			//$post['doeid'] = substr($post['doeid'],0,$post['doeid'].lenght-1);
			
			$sg = $db->query("SELECT * FROM doenca_tratamento dt
								INNER JOIN tratamentos t ON (dt.traid = t.traid)
 							WHERE dt.doeid IN (".$post['doeid'].") ");

			$str 	= '';
			while(!$sg->EOF){
				$str.="	<li class='ui-state-default ' id='".$sg->Fields("traid")."' style='background-color: #EFF2A9; max-width: 250px;'>"
							.$sg->Fields("tranome").
							"<span style='float: right;'><input type='checkbox' id='btn-chkTratamento' value='".$sg->Fields("traid")."' /></span>".
						"</li>";
				$sg->MoveNext();
			}
			echo $str;						
		} break;

		case "dadosPaciente" : {
			$rs = $diags->_get("pacientes","pacid=".$post['pacid']);
			$str = "<span>Endereço: ".$rs->Fields("pacend")."<span><br />
					<span>Telefone: ".$rs->Fields("pactel")."<span>";	
			echo $str;
		} break;
		
		case "gravaConsulta" : {
			$post['conid']   = $db->seed("consultas", "conid");
			$post['condata'] = @date("Y-m-d");
			$post['conhora'] = @date("h:m");
			
			$post['sintomas'] 	= explode(",",$post['sintomas']);
			$post['doencas'] 	= explode(",",$post['doencas']);
			$post['tratamentos']= explode(",",$post['tratamentos']);
			
			if ($diags->_insrt("consultas", $post)){			
				foreach($post['sintomas'] as $sinid => $val)
					$diags->_insrt("consulta_sintoma", array("conid" => $post['conid'], "sinid" => $val));
				
				foreach($post['doencas'] as $doeid => $val)
					$diags->_insrt("consulta_doenca", array("conid" => $post['conid'], "doeid" => $val));					

				foreach($post['tratamentos'] as $traid => $val)
					$diags->_insrt("consulta_tratamento", array("conid" => $post['conid'], "traid" => $val));
				
				echo 1;
			} else { echo 0; }
		} break;
		
	}
}

if (isset($get['action'])){
	switch($get['action']){
		case 'teste' : 
		break;
		
	}
}

?>
