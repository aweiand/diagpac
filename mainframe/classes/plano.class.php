<?php

/**
 * Esta Classe prove métodos para manipulação de dados nas tabelas do planejador,
 * relativos ao projeto do planejamento estratégico 
 *  
 * @author Augusto Weiand <guto.weiand@gmail.com>
 * @version 0.1
 * @access public
 * @name aluno
 * @category tablesManipulate  
 * @package plano
 */

class plano {
	function plano(){
	
	}
	
	/**
	 * Método que retorna as informações do plano e da unidade 
	 * do usuário passado no parâmetro
	 *
	 *	@access public	 
	 *	@param Integer $codusuario - id do usuario
	 *	@return recordset
	 */
	function getContexto($codusuario = false){
		$db = new dataPlano();
		$cmdSQL = "SELECT p.*, up.*, u.nome FROM user_plano up
						INNER JOIN planos p ON (p.id = up.plano)
						INNER JOIN unidades u ON (p.id_unidade = u.id)";
		if ($codusuario)
			$cmdSQL.=" WHERE up.user = " . $codusuario;
		$cmdSQL.= " ORDER BY u.nome ASC";
		$ret = $db->query($cmdSQL);
		return $ret;
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
		$db = new dataPlano();
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
		$db = new dataPlano();
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
		$db = new dataPlano();
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
		$db = new dataPlano();
		$cmdSQL = "DELETE FROM ". $tabela . " WHERE ". $id;
        if ($db->command($cmdSQL)) 
			return true;
        else
			return false;
    }    		
	
    /**
     * Metodo que retorna as permissoes do usuario passado no idusuario ou todos
     * @access public
     * @param Integer $idusuario - ID do usuario
     * @return Recordset - com o(s) registro(s) encontrado(s)
     */
    function getPermissoes($idusuario = '') {
        $db = new dataPlano();
        $cmdSQL = "SELECT * FROM permissoes ";
        if ($idusuario != '')
			$cmdSQL.=" WHERE codusuario=" . $idusuario;
        $cmdSQL.=" ORDER BY codusuario ASC";
        $ret = $db->query($cmdSQL);
        return $ret;
    }
	
    /**
     * Metodo que grava as permissoes passadas no parametro
     * @access public
     * @param Array $arrayJava - array com as permissoes
     * @param Integer $codusuario - ID do usuario 
     * @return bool
     */
    function setPermissoes($arrayJava, $codusuario) {
        $db = new dataPlano();
		
        for ($i = 0; $i < count($arrayJava); $i++) {
            $arrjson = explode('@', $arrayJava[$i]);
            $app = $arrjson[0];
            $arrkey = explode('=', $arrjson[1]);
            $key = $arrkey[0];
            if ($arrkey[1] == 'true')
				$val = true;
            else
				$val = false;
            $arr[$app][$key] = $val;
        }
		
        $perm['codusuario'] = $codusuario;
        $perm['permissoes'] = json_encode($arr);
        $a = usuario::getPermissoes($codusuario);
        if ($a->RecordCount() == 0) {
            if ($db->_insrt('permissoes', $perm))
				return true;
            else
				return false;
        } else {
            unset($perm['codusuario']);
            if ($db->_updt('permissoes', $perm, 'codusuario=' . $codusuario))
				return true;
            else
				return false;
        }
    }
	
    /**
     * Metodo que retorna as permissoes passadas no parametro
     * @access public
     * @param Array $permissoes - array com as permissoes
     * @param Integer $codusuario - ID do usuario
     * @return String com as permissoes 
     */
    function permissoesUsuario($codusuario, $permissoes) {
        $db = new dataPlano();
        $cmdSQL = "SELECT * FROM permissoes WHERE codusuario=" . $codusuario;
        $qusu = $db->query($cmdSQL);
        if ($qusu->RecordCount() != 0) {
            $arrAtual = json_decode($qusu->Fields('permissoes'), true);
            $atualiza = false;
            foreach ($permissoes as $perm => $a) {
                foreach ($a as $key => $v) {
                    if (!isset($permissoes[$perm][$key], $arrAtual[$perm][$key])) {
                        $atualiza = true;
                        $arrAtual[$perm][$key] = false;
                        $permissoes[$perm][$key] = false;
                    } else
					$permissoes[$perm][$key] = $arrAtual[$perm][$key];
                }
            }
            if ($atualiza) {
                $arr['permissoes'] = json_encode($arrAtual);
                $db->_updt('permissoes', $arr, 'codusuario=' . $codusuario);
                return $permissoes;
            } else
			return $permissoes;
        }
        else {
            foreach ($permissoes as $perm => $a)
				foreach ($a as $key => $v){
					$permissoes[$perm][$key] = false;
				}
            $arr['codusuario'] = $codusuario;
            $arr['permissoes'] = json_encode($permissoes);
            $db->_insrt('permissoes', $arr);
            return $permissoes;
        }
    }	
	
    /**
     * Metodo que retorna os usuarios associados ao plano já em uma lista li
     * @access public
     * @param Integer plano - ID do plano
     * @return recordset
     */
    function getUsuariosPlano($plano) {
		$db 	= new dataPlano();
		$cmdSQL = "SELECT * FROM user_plano up
						INNER JOIN moodle.moodle_user mu ON (mu.id = up.user)
					WHERE up.plano = ". $plano;
		$sg 	= $db->query($cmdSQL);
		$str 	= '';
		while(!$sg->EOF){
			if ($sg->Fields("id") == 2)
				$str.="<li class='ui-state-highlight ui-state-disabled' id='".$sg->Fields("id")."'>".substr(utf8_encode($sg->Fields("firstname")." ".$sg->Fields("lastname")),0,22)."...
					<span style='float: right;'><input type='checkbox' id='ck_adm' value='1' checked='checked'/></span>
					</li>";
			else
				$str.="<li class='ui-state-highlight ' id='".$sg->Fields("id")."'>".substr(utf8_encode($sg->Fields("firstname")." ".$sg->Fields("lastname")),0,22)."
					<span style='float: right;'><input type='checkbox' id='ck_adm' value='1' /></span>
					</li>";
			$sg->MoveNext();
		}
		return $str;
	}	
	
    /**
     * Metodo que retorna os usuarios associados ao plano já em uma lista li, com filtro de nome
     * @access public
     * @param String letras - partes do nome
     * @return recordset
     */
    function getUsuariosLiFiltro($letras = '') {
		$db 	= new dataMoodle();
		$sg = $db->getAluno($letras, '0,10');
		$str 	= '';
		while(!$sg->EOF){
			$str.="<li class='ui-state-default ' id='".$sg->Fields("id")."' style='background-color: #EFF2A9;'>".substr(utf8_encode($sg->Fields("firstname")." ".$sg->Fields("lastname")),0,22)."...
				<span style='float: right;'><input type='checkbox' id='ck_adm' value='1' /></span>
				</li>";
			$sg->MoveNext();
		}
		return $str;
	}		
	
    /**
     * Metodo que insere os dados na tabela de acordo com as notas da matriz SWOT
     * @access public
     * @param String tbl - Tabela
     * @param Float nota - Nota
     * @param Integer id1 - id da tabela
     * @param Integer id2 - id da tabela
     * @return Null
     */
    function insNotaSwot($tbl,$nota,$id1,$id2) {
		$db  = new dataPlano();	
		//echo " tbl->".$tbl." nota->".$nota." id1->".$id1." id2->".$id2;		
		
		switch($tbl){
			case 'oportunidades'  : $cmdSQL = "SELECT * FROM oportunidades WHERE id=".$id1;
									 $wh = 'id'; $wh1 = false;
									 $this->querySwot($tbl,$cmdSQL,$nota,$wh,$wh1,$id1);
				break;
			case 'forcas' 		   : $cmdSQL = "SELECT * FROM forcas WHERE id=".$id1;
									 $wh = 'id'; $wh1 = false;
									 $this->querySwot($tbl,$cmdSQL,$nota,$wh,$wh1,$id1);
				break;				
			case 'fraquezas' 	   : $cmdSQL = "SELECT * FROM fraquezas WHERE id=".$id1;
									 $wh = 'id'; $wh1 = false;			
									 $this->querySwot($tbl,$cmdSQL,$nota,$wh,$wh1,$id1);
				break;
			case 'ameacas'        : $cmdSQL = "SELECT * FROM ameacas WHERE id=".$id1;
									 $wh = 'id'; $wh1 = false;			
									 $this->querySwot($tbl,$cmdSQL,$nota,$wh,$wh1,$id1);
				break;			
				
			case 'forca_ameaca'   : $cmdSQL = "SELECT * FROM forca_ameaca WHERE forca=".$id1." AND ameaca=".$id2;
									 $wh = 'forca'; $wh1 = 'ameaca';			
									 $this->querySwot($tbl,$cmdSQL,$nota,$wh,$wh1,$id1,$id2);
				break;
			case 'forca_oport'    : $cmdSQL = "SELECT * FROM forca_oport WHERE forca=".$id1." AND oportunidade=".$id2;
									 $wh = 'forca'; $wh1 = 'oportunidade';			
									 $this->querySwot($tbl,$cmdSQL,$nota,$wh,$wh1,$id1,$id2);
				break;				
			case 'fraqueza_ameaca': $cmdSQL = "SELECT * FROM fraqueza_ameaca WHERE fraqueza=".$id1." AND ameaca=".$id2;
									 $wh = 'fraqueza'; $wh1 = 'ameaca';			
									 $this->querySwot($tbl,$cmdSQL,$nota,$wh,$wh1,$id1,$id2);
				break;
			case 'fraqueza_oport' : $cmdSQL = "SELECT * FROM fraqueza_oport WHERE fraqueza=".$id1." AND oportunidade=".$id2;
									 $wh = 'fraqueza'; $wh1 = 'oportunidade';			
									 $this->querySwot($tbl,$cmdSQL,$nota,$wh,$wh1,$id1,$id2);
				break;							
		};		
	}

    /**
     * Metodo auxiliar do insNotaSwot que realiza as inserções ou updates de acordo com od dados
     * @access public
     * @param String tbl - Tabela
     * @param String cmdSQL - comando SQL
     * @param Float nota - Nota
     * @param String wh - campo da tabela
     * @param String wh1 - campo da tabela
     * @param Integer id - id da tabela
     * @param Integer id1 - id da tabela
     * @return Null
     */	
	function querySwot($tbl,$cmdSQL,$nota,$wh,$wh1 = false,$id,$id1 = false){
		$db = new dataPlano();
		$sg = $db->query($cmdSQL);
		if ($sg->RecordCount()!=0){
			$nt['nota'] = $nota;
			if ($wh1)
				$ids = $wh."=".$id." AND ".$wh1."=".$id1;
			else
				$ids = $wh."=".$id;
			$db->_updt($tbl,$nt,$ids);
		} else {
			$nt['nota'] = $nota;
			$nt[$wh] = $id;
			if ($wh1)
				$nt[$wh1] = $id1;
			$db->_insrt($tbl,$nt);
		};
	}
	
    /**
     * Metodo que retorna dados associados ao plano já em uma lista li, da tabela relacionada
     * @access public
     * @param String letras - partes do nome
     * @return recordset
     */
    function getLi($tabela, $idPlano = false) {
		$db 	= new dataMoodle();
		if (!$idPlano)
			$sg = $this->_get($tabela);
		else
			$sg = $this->_get($tabela, 'plano='.$idPlano);
		$str 	= '';
		while(!$sg->EOF){
			$str.="<li class='ui-state-default ' id='".$sg->Fields("id")."' style='background-color: #EFF2A9;'>
						".substr(($sg->Fields("nome")),0,22)."...
				   </li>";
			$sg->MoveNext();
		}
		return $str;
	}

    function getLiIniciativasPlano($idIniciativa,$tabela) {
		$db 	= new dataMoodle();
		$sg = $this->_get('iniciativa_'.$tabela,'iniciativa = '.$idIniciativa,$tabela."s ON ($tabela".'s'.".id = iniciativa_$tabela.$tabela)" );
		$str 	= '';
		while(!$sg->EOF){
			$str.="<li class='ui-state-default ' id='".$sg->Fields("id")."' style='background-color: #EFF2A9;'>
			".substr(($sg->Fields("nome")),0,22)."...
			</li>";
			$sg->MoveNext();
		}
		return $str;
	}
	
	function getObjetivoEstrat($idPlano){
		$db  = new dataMoodle();
		$dbP = new dataPlano();
		
		$sg = $this->_get("obj_estrat oe", "oe.plano=".$idPlano);
		if ($sg->RecordCount() == 0)
			return "<li>Desculpe, não encontrei nenhum objetivo para este plano.</li>";
		
		$str = '';
		
		while(!$sg->EOF){
			$str.= "
			<div id='obj_".$sg->Fields('id')."' style='margin: 10px; width: 100%;' class='ui-state-highlight ui-corner-all'>
				<table class='tblObjetivos'>
					<tr>
						<td>
							<label>Iniciativa</label><br />
							<select name='sel_iniciativa_estrat' id='sel_iniciativa_estrat'>";
							
							$prp = $this->_get("iniciativa_estrat");
							while(!$prp->EOF){
								if ($sg->Fields('iniciativa_estrat') == $prp->Fields('id'))
									$str.="<option value='".$prp->Fields('id')."' selected='selected'>".$prp->Fields('nome')."</option>";
								else
									$str.="<option value='".$prp->Fields('id')."'>".$prp->Fields('nome')."</option>";
								$prp->MoveNext();
							};
							$str.= "</select>
						</td>
						<td>
							<label>Perspectiva</label><br />
							<select name='sel_perspectiva' id='sel_perspectiva'>";

							$prp = $this->_get("perspectiva");
							while(!$prp->EOF){
									if ($sg->Fields('perspectiva') == $prp->Fields('id'))
								$str.="<option value='".$prp->Fields('id')."' selected='selected'>".$prp->Fields('nome')."</option>";
								else
									$str.="<option value='".$prp->Fields('id')."'>".$prp->Fields('nome')."</option>";
								$prp->MoveNext();
							};
							$str.= "</select>
						</td>";
				
				$str.= "<td>
							<label>Objetivo de Estratégico</label><br />
							<textarea name='contribuicao' id='contribuicao'>".$sg->Fields('contribuicao')."</textarea>
						</td>";
				
				$str.= "<td>
							<label>Forma de mensuração definda pela Empresa </label><br />
							<textarea name='forma_mensura' id='forma_mensura'>".$sg->Fields('forma_mensura')."</textarea>
						</td>";			
				
				$str.= "<td>
							<label>Indicador</label><br />		
							<input type='text' name='indicador' id='indicador' value='".$sg->Fields('indicador')."' />
						<br />";								
				
				$str.= "
							<label>Meta + Prazo</label><br />		
							<input type='text' name='meta_prazo' id='meta_prazo' value='".$sg->Fields('meta_prazo')."' />
						</td>";						
						
				$str.= "<td>
							<label>Responsável</label><br />		
							<select name='responsavel' id='responsavel' style='width: 200px;'>
								<option>Selecione um Usuario</option>";
								$prp = $db->getAluno();
								while(!$prp->EOF){
									if ($sg->Fields("responsavel") == $prp->Fields("id"))
										$str.= "<option value='".$prp->Fields("id")."' selected='selected'>".utf8_encode($prp->Fields("firstname")." ".$prp->Fields("lastname"))."</option>";
									else
										$str.= "<option value='".$prp->Fields("id")."'>".utf8_encode($prp->Fields("firstname")." ".$prp->Fields("lastname"))."</option>";
									$prp->MoveNext();
								}
							$str.= "</select>
							<br />
							<label>Projeto / Ação + Prazo</label><br />		
							<input type='text' name='plan_acao_prazo' id='plan_acao_prazo' value='".$sg->Fields('plan_acao_prazo')."' />
							</td>								
					</tr>
					<tr>";
						
				$str.= "<td colspan='6' align='center'>
							<button data-id='".$sg->Fields('id')."' id='editaObjetivoEstrat' class='btVerde-grande'>Salvar Edicao</button>
							<button data-id='".$sg->Fields('id')."' id='deletaObjetivoEstrat' class='btVermelho'>Deletar</button>
							<button data-id='".$sg->Fields('id')."' id='carregaPlanoDeAcao' class='btCinza'>Plano de Ação</button>
						</td>";					
			$str.= "</tr>
				</table>
			</div>";	
			
			$sg->MoveNext();
		};
		return $str;
	}
	
	function addObjetivoEstrat($idPlano){
		$db  = new dataMoodle();
		$dbP = new dataPlano();
		
		$arr['id'] = $dbP->seed("obj_estrat", "id");
		
		$str = '';
		
		$str.= "
		<div id='obj_".$arr['id']."' style='background-color:#e6e6e6; margin: 10px; width: 100%;'>
			<table class='tblObjetivos'>
				<tr>
					<td>
						<label>Iniciativa</label><br />
						<select name='sel_iniciativa_estrat' id='sel_iniciativa_estrat'>";
						
						$prp = $this->_get("iniciativa_estrat", "plano=".$idPlano);
						while(!$prp->EOF){
								$str.="<option value='".$prp->Fields('id')."'>".$prp->Fields('nome')."</option>";
							$prp->MoveNext();
						};
						$str.= "</select>
					</td>
					<td>
						<label>Perspectiva</label><br />
						<select name='sel_perspectiva' id='sel_perspectiva'>";
						
						$prp = $this->_get("perspectiva");
						while(!$prp->EOF){
								$str.="<option value='".$prp->Fields('id')."'>".$prp->Fields('nome')."</option>";
							$prp->MoveNext();
						};
						$str.= "</select>
					</td>";
					
			$str.= "<td>
						<label>Objetivo Estratégico</label><br />
						<textarea name='contribuicao' id='contribuicao'></textarea>
					</td>";
					
			$str.= "<td>
						<label>Forma de mensuração definda pela Empresa </label><br />
						<textarea name='forma_mensura' id='forma_mensura'></textarea>
					</td>";			
					
			$str.= "<td>
						<label>Indicador</label><br />		
						<input type='text' name='indicador' id='indicador' />
					<br />";								
					
						$str.= "
						<label>Meta + Prazo</label><br />		
						<input type='text' name='meta_prazo' id='meta_prazo' />
					</td>";						
					
			$str.= "<td>
						<label>Responsável</label><br />		
						<select name='responsavel' id='responsavel' style='width: 200px;'>
						<option>Selecione um Usuario</option>";
						$prp = $db->getAluno();
						while(!$prp->EOF){
								$str.= "<option value='".$prp->Fields("id")."'>".utf8_encode($prp->Fields("firstname")." ".$prp->Fields("lastname"))."</option>";
							$prp->MoveNext();
						}
						$str.= "</select>
						<br />
						<label>Projeto / Ação + Prazo</label><br />		
						<input type='text' name='plan_acao_prazo' id='plan_acao_prazo' />
					</td>								
				</tr>
				<tr>";
					
			$str.= "<td colspan='6' align='center'>
						<button data-id='".$arr['id']."' id='SalvaObjetivoEstrat' class='btVerde-grande'>Salvar</button>
					</td>";					
		$str.= "</tr>
			</table>
		</div>";	
			
		return $str;
	}	
	
	function getMapaObj($perspectiva, $plano){
		$db = new dataPlano();
		
		$cmdSQL = "
			SELECT mi.*, p.nome as pnome, 
					oe.responsavel, oe.indicador, oe.perspectiva, oe.contribuicao
				FROM mapa_indicadores  mi
					INNER JOIN obj_estrat oe ON ( mi.obj_estrat  = oe.id )
					INNER JOIN perspectiva p ON ( oe.perspectiva = p.id  )
			WHERE oe.plano=".$plano." AND p.id=".$perspectiva." ORDER BY mi.id ASC
				";
		$ret = $db->query($cmdSQL);
		return $ret;
	}
	
}
?>
