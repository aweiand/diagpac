﻿<?php

/**
 * Esta Classe prove métodos para manipulação de dados nas tabelas do planejador,
 * relativos ao projeto do planejamento estratégico 
 *  
 * @author Augusto Weiand <guto.weiand@gmail.com>
 * @version 0.1
 * @access public
 * @name diags
 * @category tablesManipulate  
 * @package diagpac
 */

class diags extends data {
	function diags(){
	
	}
    
	/**
	 * Método generico que retorna as informações da tabela
	 * passada, juntamente com os joins ou where necessarios
	 *
	 *	@access public	 
	 *	@param String $tabela - tabela ( Ex: usuario u )	 
	 *	@param String $join - parâmetro de join ( Ex: plano on (outra.id = outro.id) )	 
	 *	@param String $where - parâmetro de where ( Ex: codusuario = 1 )	 
	 *	@param String $order - parâmetro de ordenacao ( Ex: codusuario ASC )	 
	 *	@return recordset
	 */
	function _get($tabela, $where = false, $join = false, $order = false){
		$cmdSQL = "SELECT * FROM ".$tabela;
		if ($join)
			$cmdSQL.=" INNER JOIN ".$join;
		if ($where)
			$cmdSQL.=" WHERE ".$where;
		if ($order)
			$cmdSQL.=" ORDER BY ".$order;
		else
			$cmdSQL.=" ORDER BY 1 ASC, 2 ASC";
			
		//echo $cmdSQL;
		
		$ret = parent::query($cmdSQL);
		//$db->gravaLog('_query',$cmdSQL);
		return $ret;
	}	
	
    /**
     * Funcao para inserir registros sem sql
     * @access public
     * @param String $rs - Tabela do Banco de dados a ser utilizada. Ex.: 'Usuario'
     * @param Array $record - Array com dados a serem inseridos. Ex.: $arr['nome'] = 'Augusto' ; $arr['e-mail'] = 'guto.weiand@gmail.com'
     * @return Recordset - com dados da Inserção Realizada.
     */   	
	function _insrt($tabela, $dados){
		$db = new dataPlano();
		if (parent::_insrt($tabela, $dados))
			return true;
		else
			return false;
	}
	
    /**
     * Funcao para atualizar registros de uma tabela
     * @access public
     * @param String $tabela - tabela do banco de dados. Ex.: 'Usuario'
     * @param Array $dados - Array com dados a serem inseridos. Ex.: $arr['nome'] = 'Augusto' ; $arr['e-mail'] = 'guto.weiand@gmail.com'
     * @param String $cod - codigo de referencia para atualizacao. Ex.: id = 1
     * @return recordset - com dados da Consulta Realizada
     */    
    function _updt($tabela, $dados, $id) { 
		$db = new dataPlano();
        if (parent::_updt($tabela, $dados, $id)) 
			return true;
        else
			return false;
    }    	
	
    /**
     * Funcao para remover registros de uma tabela
     * @access public
     * @param String $tabela - tabela do banco de dados. Ex.: 'Usuario'
     * @param String $cod - codigo de referencia para atualizacao. Ex.: id = 1
     * @return recordset - com dados da Consulta Realizada
     */    
    function _del($tabela, $id) { 
		$db = new dataPlano();
		$cmdSQL = "DELETE FROM ". $tabela . " WHERE ". $id;
        if (parent::command($cmdSQL)) 
			return true;
        else
			return false;
    }    		
	
}
?>