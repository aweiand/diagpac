<?php

/**
 * Esta Classe prove uma API de Manipulação do Banco de Dados porem ela necessita de que seja instanciada juntamente com ela
 *  o pacote ADOBD for PHP que pode ser baixado em:	http://adodb.sourceforge.net/ e tambpem a classe Utils
 *  
 * @author Augusto Weiand <guto.weiand@gmail.com>
 * @version 1.0
 * @access public
 * @name dataGestao
 * @category DatabaseManipulate  
 * @package dataGestao
 * @subpackage Utils
 */

class data{
    
	var $db;
    var $debug		= false;
	var	$server		= "localhost";
	var	$database	= "planestrat";
	var	$user		= "root";
	var	$password	= "root";
        
    function data(){
        @define('ADODB_FORCE_IGNORE',0);
        @define('ADODB_FORCE_NULL',1);
        @define('ADODB_FORCE_EMPTY',2);
        @define('ADODB_FORCE_VALUE',3);

        $this->db = $this->startDB();
    }

	/**
	 * Funcao que define e prepara a conexao com o BD
	    * @access public 
	    * @return mixed - Conexao
	 */    
    function startDB(){
            $CONN = ADONewConnection('mysql');
            $CONN->Connect($this->server, $this->user, $this->password, $this->database);
            $CONN->Execute("SET NAMES utf-8;");
            $CONN->debug = $this->debug;
            return $CONN;
    }
    
	/**
	 * Funcao para execucao de comandos DML
	    * @access public 
	    * @param String $cmdSQL - Query a ser executada, somente DML (Select, Insert, Update, Delete)
	    * @return Recordset - com dados da Consulta Realizada
	 */
    function query($cmdSQL) {
        $rsQ = $this->db->Execute($cmdSQL) or $this->showErro($this->db->ErrorMsg(), $cmdSQL);
        return $rsQ;
    }

    /**
     * Funcao para execucao de comandos DDL
     * @access public
     * @param String $cmdSQL - Query a ser executada, somente DDL (Create, Alter, Drop)
     * @return Recordset - com dados do resultado obtido
     */   
    function command($cmdSQL) {
        $ret = $this->query($cmdSQL) or $this->gravaLog('_command', $this->db->ErrorMsg() . " ####SQL#### " . $cmdSQL);
        return $ret;
    }

    /**
     * Funcao para capturar erros de execução
     * @access public
     * @param String $msg - mensagem capturada
     * @param String $cmdSQL - Query executada 
     * @return String - echo com erro encontrado
     */    
    function showErro($msg, $cmdSQL = "") {
        if ($cmdSQL != "")
            trigger_error($msg . " comando SQL: <strong><pre>" . $cmdSQL . "</pre></strong>");
    }

    /**
     * Funcao que retorna uma SQL de Inserção de Dados
     * @access public
     * @param String $rs - Tabela do Banco de Dados. Ex.: 'Usuario'
     * @param String $data - Array com Dados a serem processados. Ex.: $arr['nome'] = 'Augusto' ; $arr['e-mail'] = 'guto.weiand@gmail.com'
     * @return String - query de insert com os dados passados nos parametros
     */    
    function getInsertSQL($rs, $data) {
        $a = $this->db->GetInsertSQL($rs, $data);
        return $a;
    }

    /**
     * Funcao para inserir registros sem sql
     * @access public
     * @param String $rs - Tabela do Banco de dados a ser utilizada. Ex.: 'Usuario'
     * @param Array $record - Array com dados a serem inseridos. Ex.: $arr['nome'] = 'Augusto' ; $arr['e-mail'] = 'guto.weiand@gmail.com'
     * @return Recordset - com dados da Inserção Realizada.
     */    
    function _insrt($rs, $record) {
        //$this->db->debug=true;
        $ret = $this->db->AutoExecute($rs, $record, "INSERT") or $this->gravaLog('_insrt', $this->db->ErrorMsg());
        return $ret;
    }

    /**
     * Funcao para atualizar registros de uma tabela
     * @access public
     * @param String $rs - tabela do banco de dados. Ex.: 'Usuario'
     * @param String $record - Array com dados a serem inseridos. Ex.: $arr['nome'] = 'Augusto' ; $arr['e-mail'] = 'guto.weiand@gmail.com'
     * @param String $cod - codigo de referencia para atualizacao. Ex.: id = 1
     * @return Recordset - com dados da Consulta Realizada
     */    
    function _updt($rs, $record, $cod) {
        //$this->db->debug=true;
        $ret = $this->db->AutoExecute($rs, $record, 'UPDATE', $cod) or $this->gravaLog('_updt', $this->db->ErrorMsg() . " ###COD### " . $cod . " ###TABLE### " . $rs);
        return $ret;
    } 
    

    /**
     * Funcao para gerar o proximo autoincremento de um campo
     *	@access public
     *	@param String $tab - tabela do banco de dados. Ex.: 'Usuario'
     *  @param String $campo - string com o nome da coluna. Ex.: 'codUsuario'
     *  @return Integer valor do próximo autoincremento
     *
     */    
    function seed($tab, $campo) {
    	$sql = "SHOW TABLE STATUS LIKE '". $tab ."'";
    	$ret = $this->query($sql)->Fields("Auto_increment");
    	$ret = $ret+1;
    	$sql = "ALTER TABLE diagpac.". $tab ." AUTO_INCREMENT = ". $ret;
    	$alt = $this->query($sql);
    	return $ret;
    }
    
    /**
     * Funcao para gravar logs na tabela
     * @access public
     * @param String $action - acao executada pelo usuario
     * @param String $dado - dados a serem gravados
     * @return bool
     */
    function gravaLog($action, $dado) {
    	$dado = str_replace('\'', ' ', $dado);
    	$cmdSQL = "INSERT INTO log VALUES(" . $this->seed('log', 'cod_log') . ",'" . $action . "','" . $dado . "',NOW())";
    	if ($this->query($cmdSQL))
    		return true;
    	else
    		return false;
    }    
    
    /**
     * Funcao para efetuar verificacao do Login / Senha com base nas tabelas do Moodle
     * @access public
     * @param String $user - Usuario do Aluno
     * @param String $passw - Senha do Aluno
     * @return Recordset - com dados da Consulta Realizada
     */
    function _login($user, $passw) {
        $ut = new Utils;
        $cmdSQL = "SELECT * FROM moodle_user
					WHERE username='" . $user . "'
					AND password=md5('" . $passw.$ut->getSaltFromMoodle($_SERVER['SERVER_NAME']."/moodle/config.php")."') and deleted=0";
        $rs = $this->query($cmdSQL);
        if ($rs) { 
            if ($rs->RecordCount() != 0)
                if ($rs->Fields("deleted") == 0) {
                    $ret['usuid'] = $rs->Fields("id");
                    $ret['usu'] = $rs->Fields("firstname")." ".$rs->Fields("lastname");
                    $ret['email'] = $rs->Fields("email");
                } else
                    $ret = array();
            else
                $ret = array();
        } else
            $ret = array();
        return $ret;
    }    
    
}
?>
