<?php
require_once "../../tools/mainframe/plugins/adodb/adodb.inc.php";
require_once "../../tools/mainframe/plugins/phpmailer/class.phpmailer.php";
require_once "../mainframe/autoload.php";

@session_cache_expire(90); // 2 hours
@session_start();

/*
 * Ações para trabalhar com o CRM
 * 
 */
$post = $_POST;
$get = $_GET;

/*
  echo "<pre>";
  print_r($post);
  exit();
*/

/*
if (!isset($_SESSION['usuid'])){
	header("Location: ../index.php");	
}
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

$uti   = new Utils();
$db    = new dataPlano();
$plano = new plano();

//############# Final das Classes padrão para os cases #############
if (isset($post['action'])){
	switch($post['action']){
		case 'NovoConcorrente' : {
			unset($post['action']);
			$post['id'] = $db->seed("concorrentes","id");
			if ($plano->_insrt("concorrentes",$post)){
				echo $post['id'];
			} else {
				echo "-9";
			}
		} break;

		case 'removeConcorrente' : {
			unset($post['action']);
			if ($plano->_del("concorrentes"," id = ".$post['id'])){
				echo "0";
			} else {
				echo "-9";
			}
		} break;		

		case 'NovaAmb' : {
			unset($post['action']);
			$post['id'] = $db->seed($post['tabela'],"id");
			if ($plano->_insrt($post['tabela'],$post)){
				echo $post['id'];
			} else {
				echo "-9";
			}
		} break;
		
		case 'RemoveAmb' : {
			unset($post['action']);
			if ($plano->_del($post['tabela']," id = ".$post['id'])){
				echo "0";
			} else {
				echo "-9";
			}
		} break;		
		
		case 'salvandoInformacoes' : {
			$ck_prdt = explode("#",$post['ck_prdt']);
			
			$post['perplano_ini'] = preg_replace( '/\s*/m', '', $uti->formatDateTime($post['perplano_ini'],'americano'));
			$post['perplano_fim'] = preg_replace( '/\s*/m', '', $uti->formatDateTime($post['perplano_fim'],'americano'));
			$idPlano = $post['idPlano'];
			unset($post['action']);
			unset($post['ck_prdt']);
			if ($plano->_updt('planos',$post,'id = '.$idPlano)){
				$plano->_del("prdt_plano",'plano = '.$idPlano);
				$arr = Array();
				$arr['plano']	= $idPlano;
				for($i=0;$i<count($ck_prdt);$i++){
					if ($ck_prdt[$i] != ""){
						$arr['prdt_srv'] = $ck_prdt[$i];
						$db->_insrt("prdt_plano",$arr);
					};
				};				
				echo "Atualizado com Sucesso!";
			} else
				echo "Desculpe, houve algum problema ao salvar";
		} break;				
		
		case 'CriandoInformacoes' : {
			$ck_prdt = explode("#",$post['ck_prdt']);
			
			$post['perplano_ini'] = preg_replace( '/\s*/m', '', $uti->formatDateTime($post['perplano_ini'],'americano'));
			$post['perplano_fim'] = preg_replace( '/\s*/m', '', $uti->formatDateTime($post['perplano_fim'],'americano'));
			$idPlano = $db->seed("planos","id");
			unset($post['action']);
			unset($post['ck_prdt']);
			if ($plano->_insrt('planos',$post,'id = '.$idPlano)){
				
				$plano->_del("prdt_plano",'plano = '.$idPlano);
				$arr = Array();
				$arr['plano']	= $idPlano;
				for($i=0;$i<count($ck_prdt);$i++){
					if ($ck_prdt[$i] != ""){
						$arr['prdt_srv'] = $ck_prdt[$i];
						$db->_insrt("prdt_plano",$arr);
					};
				};				

				//Adicionando usuario ao plano
				unset($arr['prdt_srv']);
				$arr['user'] = $_SESSION['usuid'];
				$arr['tipo'] = 1;
				$db->_insrt("user_plano",$arr);
				
				//Adiciono o Administrador como Default
				$arr['user'] = 2;
				$db->_insrt("user_plano",$arr);
				//FIM 
				
				echo "Atualizado com Sucesso!";
			} else
			echo "Desculpe, houve algum problema ao salvar";
		} break;
		
		case 'salvandoCenarios' : {
			unset($post['action']);
			if ($plano->_updt("planos",$post,'id='.$post['idPlano']))
				echo "Atualizado com Sucesso!";
			else
				echo "Erro ao atualizar, desculpe-me";
		} break;

		case 'CarregaUsuariosPlano' : {
			unset($post['action']);
			echo $plano->getUsuariosPlano($post['id']);
		} break;		

		case 'PesquisandoUsuario' : {
			unset($post['action']);
			echo $plano->getUsuariosLiFiltro($post['letras']);
		} break;				

		case 'salvandoUsuariosPlano' : {
			unset($post['action']);

			$user = explode("#",$post['users']);
			$arr = Array();
			$arr['plano'] = $post['plano'];
			
			$plano->_del('user_plano','plano='.$post['plano']);
			for($i=0;$i<count($user);$i++){
				if($user[$i] != ''){
					$perm = explode("_",$user[$i]);				
					$arr['user']  = $perm[0];
					$arr['tipo']  = $perm[1];
					$plano->_insrt("user_plano",$arr);			
				}
			};
			echo "Operação Realizada com Sucesso!";
		} break;					
		
		case 'salvandoMatrizSwot' : {
			unset($post['action']);
			
			$dados = array();
			parse_str($post['dado'], $dados);
			
			foreach($dados as $tblId => $nota){
				$tbl = explode("#",$tblId);

				if (strrpos($tbl[1],"_")){
					$ids = explode("_",$tbl[1]);
					$plano->insNotaSwot($tbl[0],$nota,$ids[0],$ids[1]);				
				} else {
					$plano->insNotaSwot($tbl[0],$nota,$tbl[1], false);								
				}
			};	
			echo "Salvo Com Sucesso!";
		} break;		
		
		case 'salvandoPosEstrat' : {
			unset($post['action']);
			
			if ($plano->_get("iniciativa_estrat","id=".$post['id'])->RecordCount() == 0)
				$plano->_insrt('iniciativa_estrat',$post);
			else
				$plano->_updt('iniciativa_estrat',$post,'id='.$post['id']);
			
			unset($post['iniciativa']);
			$post['iniciativa'] = $post['id'];
			
			
			$plano->_del('iniciativa_ameaca','iniciativa='.$post['id']);
			$tipo = explode("#",$post['ameaca']);
			for($i=0;$i<count($tipo);$i++){
				if($tipo[$i] != ''){
					$post['ameaca']  = $tipo[$i];
					$plano->_insrt("iniciativa_ameaca",$post);			
				}
			};					

			$plano->_del('iniciativa_oportunidade','iniciativa='.$post['id']);
			$tipo = explode("#",$post['oportunidade']);
			for($i=0;$i<count($tipo);$i++){
				if($tipo[$i] != ''){
					$post['oportunidade']  = $tipo[$i];
					$plano->_insrt("iniciativa_oportunidade",$post);			
				}
			};
			
			$plano->_del('iniciativa_forca','iniciativa='.$post['id']);
			$tipo = explode("#",$post['forca']);
			for($i=0;$i<count($tipo);$i++){
				if($tipo[$i] != ''){
					$post['forca']  = $tipo[$i];
					$plano->_insrt("iniciativa_forca",$post);			
				}
			};
			
			$plano->_del('iniciativa_fraqueza','iniciativa='.$post['id']);
			$tipo = explode("#",$post['fraqueza']);
			for($i=0;$i<count($tipo);$i++){
				if($tipo[$i] != ''){
					$post['fraqueza']  = $tipo[$i];
					$plano->_insrt("iniciativa_fraqueza",$post);			
				}
			};			
			
			echo "Operação Realizada com Sucesso!";
		} break;		
		
		case 'DeletandoPosEstrat' : {
			unset($post['action']);
		
			$plano->_del('iniciativa_ameaca','iniciativa='.$post['id']);
			$plano->_del('iniciativa_oportunidade','iniciativa='.$post['id']);
			$plano->_del('iniciativa_forca','iniciativa='.$post['id']);
			$plano->_del('iniciativa_fraqueza','iniciativa='.$post['id']);
			
			$plano->_del('iniciativa_estrat','id='.$post['id']);			
			
			echo "Operação Realizada com Sucesso!";
		} break;				
		
		case 'carregaNovoObjetivo' : {
			echo $plano->addObjetivoEstrat($post['id']);
		} break;
		
		case 'SalvandoObjetivosEstrategicos' : {
			unset($post['action']);
			$plano->_insrt('obj_estrat',$post);
			echo $plano->getObjetivoEstrat($post['plano']);
		} break;
		
		case 'DeletandoObjetivosEstrategicos' : {
			unset($post['action']);
			$plano->_del('obj_estrat','id='.$post['id']);
			echo $plano->getObjetivoEstrat($post['plano']);
		} break;		
		
		case 'AtualizandoObjetivosEstrategicos' : {
			unset($post['action']);
			$plano->_updt('obj_estrat',$post, 'id='.$post['id']);
			echo $plano->getObjetivoEstrat($post['plano']);
		} break;		
		
		case 'verDadosObjetivos' : {
			unset($post['action']);
			$dbM = new dataMoodle();
			
			$sg  = $plano->_get('obj_estrat oe','oe.id='.$post['id'],' iniciativa_estrat ie ON (oe.iniciativa_estrat = ie.id) INNER JOIN perspectiva p ON (p.id = oe.perspectiva)');
			$prp = $dbM->getAlunoId($sg->Fields('responsavel'));
			
			$str = "
				<div style='text-align: left;'>
					Perspectiva: <b>".$sg->Fields('nome')."</b><br />
					Objeivo de Contribuição: <b>".$sg->Fields('contribuicao')."</b><br />
					Indicadores: <b>".$sg->Fields('indicador')."</b><br />
					Responsavel: <b>".utf8_encode($prp->Fields('firstname')." ".$prp->Fields('lastname'))."</b>
				</div>";
			echo $str;			
		} break;		
		
		case 'NovaMeta' : {
			unset($post['action']);
			$post['id'] = $db->seed("metas","id");
			if ($plano->_insrt("metas",$post))
				echo $post['id'];
			else
				echo -9;			
		} break;
		
		case 'NovoMapaIndicador' : {
			unset($post['action']);
			
			if ($plano->_updt("mapa_indicadores",$post,"id=".$post['id']))
				echo -9;
			else
				echo 0;			
		} break;		
		
		case 'removeMeta' : {
			unset($post['action']);
			
			if ($plano->_del("metas","id=".$post['id']))
				echo -9;
			else
				echo 0;			
		} break;	

		case 'DeletaObjEstratejico' : {
			unset($post['action']);
			
			if ( ($plano->_del("metas","mapa_indicadores=".$post['id'])) && ($plano->_del("mapa_indicadores","id=".$post['id'])) )
				echo -9;
			else
				echo 0;			
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