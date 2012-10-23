/**
 * evento de click do mouse no menu principal
 * @param string href no a
 */ 
$("#_menu a").live("click", function(){
	clickMenu($(this).attr("href"));
	return false;
});

/**
 * evento de click do mouse no botao + concorrente, do item de menu informacoes
 * ele cria um subitem em branco para ser preenchido com o novo concorrente
 */ 
$("#addConcorrente").live("click", function(){
	var tem = false;
	$("#concorrentes ul li").each(function(){	
		if ($(this).attr("id") == "new")
			tem = true;
	});
	if (!tem)
		$("#concorrentes ul li").parent().append('<li id="new"><input type="text" id="adicConcor"/><button onclick="saveConcorr()">Salvar</button></li>');
	return false;
});

/**
 * Botão de salvar Informações 
 */ 
$("#saveInformacoes").live("click", function(){
	salvaInformacoes();
	return false;
});

/**
 * Botão de criar novo plano
 *  
 */
$("#adicPlano").live("click", function(){
	$("#novoPlano").slideUp(function(){
		$("#novoPlano").load("pag/novoPlano.php", { "logado" : true }, function(){
			$("#novoPlano").slideDown();
		});
	});
	return false;
});

/**
 * Botão de gravar o novo plano criado
 *  
 */
$("#createInformacoes").live("click", function(){
	criarNovoPlano();
	return false;
});

/**
 * Botão de gravar os cenários
 *  
 */
$("#saveCenarios").live("click", function(){
	savingCenarios();
	return false;
});

/**
 * Botão de salvar participantes do plano
 */
$("#salvaUsuariosPlano").live("click", function(){
	var ids = '';
	$("#ulUser li").each(function(index, element) {
		if ($(this).find("#ck_adm").is(":checked"))
			ids += ($(this).attr("id")) +"_1#";
		else
			ids += ($(this).attr("id")) +"_0#";	
	});
	
	var data = {
		"action" 	: "salvandoUsuariosPlano",
		"users"		: ids,
		"plano"		: $("#sel_plano").attr("value")
	};
	
	$.post("mainframe/actions.php",data, function(result){
		alert(result);
	});
});

/**
 * evento de click do mouse no botao + ameaca, do item de menu ambiente externo
 * ele cria um subitem em branco para ser preenchido com a nova ameaça
 */ 
$("#addAmeaca").live("click", function(){
	var tem = false;
	$("#ameacas ul li").each(function(){	
		if ($(this).attr("id") == "new")
			tem = true;
	});
	if (!tem)
		$("#ameacas ul li").parent().append('<li id="new"><input type="text" id="adicAmb"/><button onclick="saveAmb(\'ameacas\')">Salvar</button></li>');
	return false;
});

/**
 * evento de click do mouse no botao + oportunidade, do item de menu ambiente externo
 * ele cria um subitem em branco para ser preenchido com a nova oportunidade
 */ 
$("#addOportunidade").live("click", function(){
	var tem = false;
	$("#oportunidades ul li").each(function(){	
		if ($(this).attr("id") == "new")
			tem = true;
	});
	if (!tem)
		$("#oportunidades ul li").parent().append('<li id="new"><input type="text" id="adicAmb"/><button onclick="saveAmb(\'oportunidades\')">Salvar</button></li>');
	return false;
});

/**
 * evento de click do mouse no botao + forças, do item de menu ambiente interno
 * ele cria um subitem em branco para ser preenchido com a nova força
 */ 
$("#addForcas").live("click", function(){
	var tem = false;
	$("#forcas ul li").each(function(){	
		if ($(this).attr("id") == "new")
			tem = true;
	});
	if (!tem)
		$("#forcas ul li").parent().append('<li id="new"><input type="text" id="adicAmb"/><button onclick="saveAmb(\'forcas\')">Salvar</button></li>');
	return false;
});

/**
 * evento de click do mouse no botao + fraquezas, do item de menu ambiente interno
 * ele cria um subitem em branco para ser preenchido com a nova fraqueza
 */ 
$("#addFraquezas").live("click", function(){
	var tem = false;
	$("#fraquezas ul li").each(function(){	
		if ($(this).attr("id") == "new")
			tem = true;
	});
	if (!tem)
		$("#fraquezas ul li").parent().append('<li id="new"><input type="text" id="adicAmb"/><button onclick="saveAmb(\'fraquezas\')">Salvar</button></li>');
	return false;
});

/**
 * evento de click do mouse no botao salvar matriz SWOT na página matriz.php
 */ 
$("#btnSalvarSwot").live("click", function(){
	$.post("mainframe/actions.php", { "action" : "salvandoMatrizSwot", "dado" : $("#iTblMatriz").serialize() }, function(result){
		alert(result);
	});
});

/**
 * evento de click do mouse no botao calcular totais, que esta hidden na pagina matriz.php 
 * foi utilizado no desenvolvimento de outros métodos 
 */ 
$("#calculaTotais").live("click", function(){
	calculaTotais();
	return false;
});

/**
 * evento de KEYUP na página matriz.php para calcular automaticamente os totais e subtotais da matriz
 */ 
$("#iTblMatriz input[type=text]").live("keyup", function(){
	calculaTotais();
});

/**
 * evento de CHANGE na página iniciativas.php para carregar a pagina posicionamento com os valores necessários
 */ 
$("#sel_posicionam").live("change", function(){
	$("#posicionamento").slideUp(function(){
		$(this).load("pag/posicionamento.php", { "action" : "existe", "id" : $("#sel_posicionam").attr("value") }, function(){
			$(this).slideDown();
		});
	})
});

/**
 * evento de CHANGE na página iniciativas.php para carregar a pagina posicionamento com os valores necessários
 */ 
$("#addNewPosic").live("click", function(){
	$("#posicionamento").slideUp(function(){
		$(this).load("pag/posicionamento.php", { "action" : "novo" }, function(){
			$(this).slideDown();
		});
	})
});

/**
 * evento de CLICK na página posicionamento.php para salvar os dados do novo ou atualizar o posicionamento
 */ 
$("#saveIniciativaEstrategica").live("click", function(){
	salvandoPosEstrat('Salvar');
});

/**
 * evento de CLICK na página posicionamento.php para Deletar os dados
 */ 
$("#deletarIniciativaEstrategica").live("click", function(){
	if (confirm("Você tem certeza que deseja deletar esta Iniciativa Estratégica?"))
		salvandoPosEstrat('Deletar');
});

$("#addNewPosic").live("click", function(){
	$("#objetivos").slideUp(function(){
		$(this).load("mainframe/actions.php", { "action" : "carregaNovoObjetivo", "id" : $("#idPlano").val() }, function(){
			$(this).slideDown();			
		});
	});
});

$("#SalvaObjetivoEstrat").live("click", function(){
	var dataId = $(this).data("id");
	$("#objetivos").slideUp(function(){
		ManipObjEstrat('SalvandoObjetivosEstrategicos', dataId);
	});
});

$("#deletaObjetivoEstrat").live("click", function(){
	var dataId = $(this).data("id");
	if (confirm("Você tem certeza que deseja deletar este Objetivo Estratégico?"))
		$("#objetivos").slideUp(function(){
			ManipObjEstrat('DeletandoObjetivosEstrategicos', dataId);
		});
});

$("#editaObjetivoEstrat").live("click", function(){
	var dataId = $(this).data("id");
	$("#objetivos").slideUp(function(){
		ManipObjEstrat('AtualizandoObjetivosEstrategicos', dataId);
	});
});

$("#carregaPlanoDeAcao").live("click", function(){
	alert("TODO:");
});

$("#addNewMapaIndic").live("click", function(){
	$("#_content").slideUp(function(){
		$(this).load("pag/novo_indicador.php",{ "id" : $("#idPlano").val() },function(){
			$(this).slideDown();
		});
	});
});

$("#addMeta").live("click", function(){
	var tem = false;
	$("#metas ul li").each(function(){	
		if ($(this).attr("id") == "new")
		tem = true;
	});
	if (!tem)
		$("#metas ul li").parent().append('<li id="new" class="metas"><input type="text" id="periodo" placeholder="periodo" class="numP" /><br /><input type="text" id="previsto" placeholder="previsto" class="numP" /><br /><button onclick="salvaMetas()">Salvar</button></li>');
	return false;	
});

$("#saveMeta").live("click", function(){
	salvaMetas();
	return false;
});

$("#salvaMapa_indicador").live("click", function(){
	salvaMapa_indicadores();
	return false;
});

$("#cancelaMapa_indicador").live("click", function(){
	$.post("mainframe/actions.php", { "action" : "DeletaObjEstratejico", "id" : $("#id").val() }, function(result){
		if (result == -9){
			$("#_content").slideUp(function(){
				$(this).load("pag/mapa_indicadores.php?id="+$("#idPlano").val(), { },function(){
					$(this).slideDown();
				});
			});
		} else
			alert("Houve algum erro...");
	});
});
