<?php
GLOBAL $CFG;
$CFG = new stdClass();

$CFG->path = $_SERVER['DOCUMENT_ROOT'] . "/tools/mainframe/";
$CFG->addr = "http://" . $_SERVER['SERVER_NAME'] . "/tools/mainframe/";
$CFG->docs = $_SERVER['DOCUMENT_ROOT'];

require_once($CFG->path . "/plugins/adodb/adodb.inc.php");
/**
 * Função para autocarregar as classes conforme a necessidade de uso
*
*  Uso:
*  - Deve-se somente declarar a classe que a função a executada
*    automaticamente pelo sistema, porÃ©m deve-se seguir a seguinte
*    nomenclatura:
*      - Nome da classe deve ter o mesmo nome do arquivo e do construtor
*  @param String $classe -> caminho para o diretório principal
*  @access public
*  @author Augusto Weiand <guto.weiand@gmail.com>
*  @version 1.0
*  @package autoload
*  @category autoload
*  @copyright Augusto Weiand <guto.weiand@gmail.com>
*
*/
function __autoload($classe) { 
	$path = ($_SERVER['DOCUMENT_ROOT'] . "/tools/mainframe/classes/");
	if (file_exists($path . $classe . '.class.php')) {
		require_once $path . $classe . '.class.php';
	} else 
		if (file_exists($path . $classe . '.php')) {
			require_once $path . $classe . '.php';
		}
}