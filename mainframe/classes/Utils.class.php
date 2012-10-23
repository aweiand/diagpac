<?php

/**
 * Esta Classe prove métodos úteis para utilização Geral
 *  
 * @author Augusto Weiand <guto.weiand@gmail.com>
 * @version 0.2  
 * @access public
 * @name Utils
 * @category utilitarios  
 * @package utils
 */

class Utils {

    var $db;

    function Base() {
        global $CONN;
        $this->db = $CONN;
    }

	/**
	 * Funcao para verificar se existem letras nao permitidas na sintaxe enviada
	 * @access public 
	 * @param $post array - variaves vindas por $_POST ou $_GET
	 * @return bool - se ha valores nao permitidos ou nao
	 */
    function badWords($post) {
        $badwords = array("#", "'", "*", "=", " union ", " insert ", " update ", " drop ", " select ");
        foreach ($post as $value)
            foreach ($badwords as $word)
                if (substr_count($value, $word) > 0)
                    return false;
                else
                    return true;
    }

	/**
	 * Funcao para testar se a pessoa esta logada no sistema
	 * @access public 
	 * @param $_SESSION['usuid'] String - Sessao que guarda o ID do usuario
	 * @param $_SESSION['autenticado'] String - Sessao que define se o usuario esta autenticado ou nao 
	 * @return bool - se ha valores nao permitidos ou nao
	 */
    function _logado() {
        @session_start();
        if (isset($_SESSION['usuid'])) 
            return true;
        else
            return false;
    }

	/**
	 * Funcao para verificar as permissoes do usuario
	 * @access public 
	 * @param $codusuario String - ID do usuario
	 * @deprecated - novo método desenvolvido 
	 * @return Array com as informações dos aplicativos permitidos
	 */
    function getPermissoes($codusuario) {
        $ret['cadUser'] = 1;
        $ret['altUser'] = 1;
        $ret['delUser'] = 1;

        $ret['cadAluno'] = 1;
        $ret['altAluno'] = 1;
        $ret['delAluno'] = 1;

        $ret['aut'] = 1;

        return $ret;
    }

	/**
	 * Funcao para envio de e-mails
	 * @access public 
	 * @param $assunto String - Assunto da Mensagem
	 * @param $arq String - Localização do template do E-mail
	 * @param $dadosAdic Array - Dados do E-mail  
	 * @return bool - com status de envio
	 */
    function envEmail($assunto, $arq, $dadosAdic) {
        if ((isset($dadosAdic['nomeFrom'])) && (isset($dadosAdic['emailFrom'])))
            $from = $dadosAdic['nomeFrom'] . "<" . $dadosAdic['emailFrom'] . ">";
        else
            $from = "CNEC Virtual<cead@facos.edu.br>";

        $to = $dadosAdic['nome'] . "<" . $dadosAdic['email'] . ">";
        $mt = new mail($arq);

        $mt->setConfig("smtp.facos.edu.br", "cead@facos.edu.br", "2012plur", "587");

        if ($dadosAdic != '') {
            foreach ($dadosAdic as $pos => $valor)
                $mt->campos[$pos] = $valor;
        }

        $mt->assunto = $assunto;
        $mt->cabecalhos["From"] = $from;
        $mt->cabecalhos["To"] = $to;
        $mt->campos["momento_envio"] = date("d-m-Y");

        $mt->parse();
        if ($mt->send("phpmailer"))
            return true;
        else
            return false;  ////use "view" to debug ou "phpmailer" para envio
    }

    /**
     * Funcao para gerar o proximo autoincremento de um campo
	 *	@access public
	 *	@param $tab String - tabela do banco de dados. Ex.: 'Usuario'
	 *  @param $campo String - string com o nome da coluna. Ex.: 'codUsuario'
     *  @return Integer - valor do próximo autoincremento
     * 
     */
    function seed($tab, $campo) {
        $db = new data();

        $sql = "SELECT NEXTVAL('" . $tab . "_" . $campo . "_seq')";
        $ret = $db->query($sql);

        return $ret->Fields('nextval');
    }

    /**
     * Funcao que monta o array para entregar para o flexigrid e ja o converte para array json
	 *	@access public
	 *	@param $bdtab String - tabela do banco de dados. Ex.: 'Usuario'
	 *  @param $post $_POST
	 *  @param $chave - os camposs que deverao ser selecionado
	 *  @param $join - um ou mais join a ser feito para utilizacao de mais tabelas
     *  @return Integer - valor do próximo autoincremento
     */
    function montaSqlGrid($bdtab, $post, $chave, $join = '') {
        $db = new data();

        $post = $_POST;

        $page = isset($post['page']) ? $post['page'] : 1;
        $rp = isset($post['rp']) ? $post['rp'] : 10;
        $sortname = isset($post['sortname']) ? $post['sortname'] : 'nome';
        $sortorder = isset($post['sortorder']) ? $post['sortorder'] : 'asc';
        $query = isset($post['query']) ? $post['query'] : false;
        $qtype = isset($post['qtype']) ? $post['qtype'] : false;

        $sort = "ORDER BY $sortname $sortorder";
        $start = (($page - 1) * $rp);
        $limit = "LIMIT $rp OFFSET $start";
        $where = "";

        if ($query)
            $where = "WHERE UPPER($qtype) LIKE UPPER('%" . $query . "%')";

        $campos = implode($chave, ',');

        $sql = "SELECT $campos FROM $bdtab $join $where $sort $limit";

        $ret = $db->query($sql);

        $lin = $db->query("SELECT * FROM $bdtab ");

        $jsonData = array(
            'page' => $page,
            'total' => $lin->RecordCount(),
            'rows' => array()
        );

        while (!$ret->EOF) {

            $arr = array();
            foreach (array_keys($chave) as $a) {
                $arr[] = $ret->Fields($a);
            }

            $entry = array(
                'id' => $arr[0],
                'cell' => $arr,
            );
            $jsonData['rows'][] = $entry;
            $ret->MoveNext();
        }

        echo json_encode($jsonData);
    }

    /**
     * Funcao que converte uma senha para um padrão utilizado por LDAP
	 *	@access public
	 *	@param $senha String
	 *  @deprecated - Muito velha e sem atualização e utilização
     *  @return String - Senha codificada
     */
    function str2pwd($senha) {
        $passwd = "{SHA}" . base64_encode(pack("H*", sha1($senha)));

        return $passwd;
    }

    /**
     * Funcao que retorna um array com todos arquivos do diretorio
	 *	@access public
	 *	@param $caminho String - Caminho do diretorio
	 *  @param $mask String - Tipo de Arquivo
     *  @return Array com arquivos
     */
    function getFilesFromDir($caminho, $mask = "*") {
        $dir = @ dir("$caminho");

        //List files in images directory 
        while (($file = $dir->read()) !== false) {
            if ($file != "." && $file != ".." && fnmatch($mask, $file))
                $l_vdir[] = $file;
        }

        $dir->close();

        array_multisort($l_vdir);

        return($l_vdir);
    }

    /**
     * Funcao que converte as datas de acordo com o modo
	 *	@access public
	 *	@param $datahora date - Data a ser convertida
	 *  @param $modo String - Modo de conversao
     *  @return String
     */
    function formatDateTime($datahora, $modo = "") {
        if ($datahora != "") {
            // Separa data e hora.
            $dh = explode(" ", $datahora);
            $data = $dh[0];
            if (isset($dh[1]) && $dh[1] != '00:00:00')
                $hora = $dh[1];
            else
                $hora = '';
            // Separa a data.
            $d = explode("-", $data);
            @$ano = $d[0];
            @$mes = $d[1];
            @$dia = $d[2];
            @$data = $d[2] . "/" . $d[1] . "/" . $d[0];
            if ($hora != "")
                $h = explode(":", $hora);
            else
                $h = array();

            if ($modo == "")
                return $data . ' ' . $hora;
            else
            if ($modo == "dia_mes")
                return $d[2] . "/" . $d[1];
            else
            if ($modo == "dia_mes_escrito")
                return $d[2] . " de " . Utils::getNomeMes(intval($d[1]));
            else
            if ($modo == "data_traco_hora")
                return $data . " - " . $hora;
            else
            if ($modo == "dia_mes_traco_hora_min")
                return $d[2] . "/" . $d[1] . " - " . $h[0] . "h" . $h[1] . "min";
            else
            if ($modo == "data_hora")
                return $data . ' ' . $h[0] . ":" . $h[1];
            else
            if ($modo == "data")
                return $data;
            else
            if ($modo == "americano") {
                //retorna a data no formato americano yyyy-mm-dd, recebendo
                // por valor data dd/mm/yyyy
                $d = explode('/', $datahora);
                return $d[2] . "-" . $d[1] . "-" . $d[0];
            }
            else
            if ($modo == "americanoFull"){
	            //retorna a data no formato americano yyyy-mm-dd H:M:S, recebendo
	            // por valor data dd/mm/yyyy H:M:S
	            $d = explode('/', $datahora);
	            $ano = explode(" ",$d[2]);
	            return $ano[0] . "-" . $d[1] . "-" . $d[0] . " ". $ano[1];            	
            }
            else
            if ($modo == "brasComData"){
	            //retorna a data no formato brasileira dd-mm-yyyy H:M:S, recebendo
	            // por valor data yyyy/mm/dd H:M:S - 2012-07-31 14:00:00
	            $d = explode('-', $datahora);
	            $ano = explode(" ",$d[2]);
	            return $ano[0] . "-" . $d[1] . "-" . $d[0] . " ". $ano[1];            	
            }			
			else
			if ($modo == "dia_escrito_mes_ano"){
				setlocale (LC_ALL, 'pt_BR');
			    $tstamp=mktime(0,0,0,$mes,$dia,$ano);   
				$Tdate = getdate($tstamp);			   
				return $Tdate['weekday']. ' | '. $dia . '-' . $mes . '-' . $ano;
			}
        }
        else
            return "";
    }

    /**
     * Funcao que retorna um array com o numero e meses por extenso 
	 *	@access public
     *  @return Array
     */
    function getMeses() {
        $mesext = array('1' => 'Janeiro', '2' => 'Fevereiro', '3' => 'MarÃ§o', '4' => 'Abril',
            '5' => 'Maio', '6' => 'Junho', '7' => 'Julho', '8' => 'Agosto',
            '9' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro');
        return $mesext;
    }

    /**
     * Funcao que retorna o nome do mes 
	 *	@access public
	 *	@param $mes String - Numero do Mes
     *  @return String - Nome do Mes
     */
    function getNomeMes($mes) {
        $mesext = Utils::getMeses();
        return $mesext[$mes];
    }

    /**
     * Funcao que retorna o numero de dias de um mes 
	 *	@access public
	 *	@param $mes String - Numero do Mes
     *  @return Integer - Numero de dias do Mes
     */
    function getDiasMes($mes) {
        $ts = Utils::iso2unix(date("Y") . "-" . $mes . "-1");
        return date("t", $ts);
    }

    /**
     * Funcao que retorna o salt das senhas do Moodle 
	 *	@access public
	 *	@param $arquivo String com local do config do Moodle
     *  @return String - salt Utilizado
     */
    function getSaltFromMoodle($arquivo) {
        /*
          $str = file_get_contents($arquivo);
          $procurar = "/(?<=passwordsaltmain = ').*'/";

          preg_match_all($procurar, $str, $arr);
          print_r($arr);
          $achou = $arr[0][0];
          $achou = explode("'", $achou);
          $achou = $achou[0];
          if ($achou)
          return $achou;
         */
        return 'Nq2IIMl?nom.~GJ&]wS,T7LMEvRz';
    }

    /**
     * Função para retirar acentos e caracteres especiais
     * 	@access public
     * 	@param String palavra
     * 	@return String
     */
    function tiraAcento($palavra){
    	$string = str_replace(" ", '_', $palavra);
		$string = iconv('UTF-8', 'ASCII//IGNORE', $palavra);		
    	return $string;
    }
    
}

?>
