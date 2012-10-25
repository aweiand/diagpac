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
			if ($diags->_insrt($post['tabelaInsere'],$post))
				echo $post['id'];
			else
				echo 0;
		} break;

		case 'RemoveItem' : {
			unset($post['action']);
			if ($diags->_del($post['tabelaRemove'], $post['idRemove'])){
				echo 1;
			} else {
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
		
	}
}

if (isset($get['action'])){
	switch($get['action']){
		case 'teste' : 
		break;
		
	}
}

?>
