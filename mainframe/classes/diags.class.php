<?php

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

class diags {
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
		$db = new data();
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
		
		$ret = $db->query($cmdSQL);
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
		$db = new data();
		if ($db->_insrt($tabela, $dados))
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
		$db = new data();
        if ($db->_updt($tabela, $dados, $id)) 
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
		$db = new data();
		$cmdSQL = "DELETE FROM ". $tabela . " WHERE ". $id;
        if ($db->command($cmdSQL)) 
			return true;
        else
			return false;
    }    		

	function allDoencas(){
		$ret = $this->_get("doencas");
		$str = "<ul>";
		
		$bgCor = "#FFF";
		while(!$ret->EOF){
			if ($bgCor == "#e6e6e6")
				$bgCor = "#FFF";
			else
				$bgCor = "#e6e6e6";
			
			$str.= '<li data-id="'.$ret->Fields("doeid").'" style="background-color: '.$bgCor.'; padding: 5px;">'.
						'<span>'.$ret->Fields("doenome").'<span>
						<span style="float: right;"><button onclick=\'deletar('.$ret->Fields("doeid").',"doencas", this)\'>Del.</button></span>';

			$sint = $this->_get("sintomas s","doeid=".$ret->Fields("doeid"),"doenca_sintoma sd ON (sd.sinid = s.sinid)");
			if ($sint->RecordCount() != 0){
				$str.= '<ul><li>Sintomas:</li>';
				while(!$sint->EOF){
					$str.= '<li><span>'.$sint->Fields("sinnome").'<span></li>';
					$sint->MoveNext();
				};
				$str.= '</ul>';
			}
			$str.= "<br />";
			$trat = $this->_get("tratamentos t","doeid=".$ret->Fields("doeid"),"doenca_tratamento td ON (td.traid = t.traid) ");
			if ($trat->RecordCount() != 0){
				$str.= '<ul><li>Tratamentos:</li>';
				while(!$trat->EOF){
					$str.= '<li><span>'.$trat->Fields("tranome").'<span></li>';
					$trat->MoveNext();
				};
				$str.= '</ul>';
			}

			$str.= '</li>';
			$ret->MoveNext();
		};
		$str.= "</ul>";
		return $str;
	}

	function allSintomas(){
		$ret = $this->_get("sintomas");
		$str = "<ul>";
		$bgCor = "#FFF";		
		while(!$ret->EOF){
			if ($bgCor == "#e6e6e6")
				$bgCor = "#FFF";
			else
				$bgCor = "#e6e6e6";
						
			$str.= '<li data-id="'.$ret->Fields("sinid").'" class="listaData" style="background-color: '.$bgCor.'; padding: 5px;">'.
						$ret->Fields("sinnome").
"<span style='float: right;'><button onclick='deletar(\"sinid=".$ret->Fields("sinid")."\",\"sintomas\", this)'>Del.</button></span>"
					.'</li>';
			$ret->MoveNext();
		};
		$str.= "</ul>";
		return $str;
	}

	function allTratamentos(){
		$ret = $this->_get("tratamentos");
		$str = "<ul>";
		$bgCor = "#FFF";
		while(!$ret->EOF){
			if ($bgCor == "#e6e6e6")
				$bgCor = "#FFF";
			else
				$bgCor = "#e6e6e6";
						
			$str.= '<li data-id="'.$ret->Fields("traid").'" class="listaData" style="background-color: '.$bgCor.'; padding: 5px;">'.
						$ret->Fields("tranome").
						"<span style='float: right;'><button onclick=\"deletar('traid=".$ret->Fields("traid")."','tratamentos', this)\">Del.</button></span>"
					.'</li>';
			$ret->MoveNext();
		};
		$str.= "</ul>";
		return $str;
	}

	function allPacientes(){
		$ret = $this->_get("pacientes");
		$str = "<ul>";
		while(!$ret->EOF){
			$str.= '<li data-id="'.$ret->Fields("pacid").'" class="listaData">'.
						"Nome: ".$ret->Fields("pacnome")." <br />
						Telefone: ".$ret->Fields("pactel").
						"<span style='float: right;'><button onclick=\"deletar(\'pacid=".$ret->Fields("pacid")."\',\'pacientes\', this)\">Del.</button></span>"
					.'</li>';
			$ret->MoveNext();
		};
		$str.= "</ul>";
		return $str;
	}
	
}
?>
