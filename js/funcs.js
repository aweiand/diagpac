﻿/**
 * Função carregar o menu e o contexto apos a selecao dele
 * @param integer idplano
 */ 
function sel_plano(idplano){
	$("#_content").slideUp(function(){
		$(this).load("pag/home.php", { "idPlano" : idplano, "logado" : true } , function(){
			$(this).slideDown();
		});
	});
	$("#_menu").slideUp(function(){
		$(this).load("pag/menu.php", { "idPlano" : idplano, "logado" : true } , function(){
			$(this).slideDown();
		});
	});	
}

/**
 * Função carregar a pagina selecionada no menu
 * @param string link
 */ 
function clickMenu(link){
	$("#_content").slideUp(function(){
		$(this).load(link, { "logado" : true }, function(){
			$(this).slideDown();
		});
	});
}

/**
 * Função para removar o concorrente selecionado
 * @param integer idConcorr
 */ 
function delConcorr(idConcorr){
	if (confirm("Você deseja realmente remover este concorrente da lista?")){
		var data = {
			"action" : "removeConcorrente",
			"id"	 : $(idConcorr).attr("id")
		};
		$.post("mainframe/actions.php", data, function(result){
			if (result == 0){
				alert("Concorrente Removido com sucesso!");
				$(idConcorr).remove();
			}
			else
				alert("Desculpe, houve algum erro...");
		})
	}
	return false;
}

/**
 * Função para salvar o novo concorrente, pegando os valores do input
 */ 
function saveConcorr(){
	var data = {
		"action" : "NovoConcorrente",
		"plano"	 : $("#idPlano").val(),
		"nome"	 : $("#concorrentes #adicConcor").val()
	};
	$.post("mainframe/actions.php", data, function(result){
		if (result!=-9){
			var novo = $("#concorrentes #adicConcor").val()
			$("#concorrentes #new").remove();		
			$("#concorrentes ul li").parent().append('<li id="'+result+'" style="cursor: pointer;" onclick="delConcorr(this)">'+novo+'</li>');		
			return false;
		} else {
			alert("Houve algum problema ao salvar.");
			return false;
		}
	});
	return false;
}

/**
 * Função para salvar os dados da pagina informações
 */
function salvaInformacoes(){
	var checks = '';
	$("#ck_prdt:checked").each(function(){
		checks += "#"+($(this).attr("value"));
	});
	var dado = {
		"action"       : "salvandoInformacoes",
		"idPlano"	   : $("#idPlano").val(),
		"perplano_ini" : $("#perplano_ini").val(),
		"perplano_fim" : $("#perplano_fim").val(),
		"id_segmento"  : $("#rd_segmento:checked").attr("value"),
		"ck_prdt"	   : checks,
		"id_respplano" : $("#id_respplano :selected").attr("id"),
		"id_respund"   : $("#id_respund :selected").attr("id"),
		"missao"	   : $("#missao").val(),
		"visao"		   : $("#visao").val(),
		"principios"   : $("#principios").val(),
		"negocio"	   : $("#negocios").val()
	}
	$.post("mainframe/actions.php",dado, function(result){
		alert(result);		
	});
}

/**
 * Função para salvar os dados da pagina informações do novo plano
 */
function criarNovoPlano(){
	var checks = '';
	$("#ck_prdt:checked").each(function(){
		checks += "#"+($(this).attr("value"));
	});
	var dado = {
		"action"       : "CriandoInformacoes",
		"perplano_ini" : $("#perplano_ini").val(),
		"perplano_fim" : $("#perplano_fim").val(),
		"id_segmento"  : $("#rd_segmento:checked").attr("value"),
		"ck_prdt"	   : checks,
		"id_respplano" : $("#id_respplano :selected").attr("id"),
		"id_respund"   : $("#id_respund :selected").attr("id"),
		"id_unidade"   : $("#id_unidade :selected").attr("id")
	};
	$.post("mainframe/actions.php",dado, function(result){
		$("#novoPlano").slideUp(function(){	
			alert(result);		
		});
	});
}

/**
 * Função para gravar os cenários, a partir dos eventos
 */
function savingCenarios(){
	var dado = {
		"action"	 :	"salvandoCenarios",
		"idPlano"	 :	$("#idPlano").val(),
		"cen_social" :	$("#cen_social").val(),
		"cen_polit"	 :	$("#cen_polit").val(),
		"cen_econom" :	$("#cen_econom").val(),
		"cen_tec" 	 :	$("#cen_tec").val()
	};
	$.post("mainframe/actions.php", dado, function(result){
		alert(result);
	});
}

/**
 * Função de troca de plano no menu Usuários do Plano
 */
function altUsuariosPlano(idPlano){
	$("#users_plano").slideUp(function(){
		$("#ulUser").load("mainframe/actions.php", { "action" : "CarregaUsuariosPlano", "id" : idPlano }, function(){
			$("#users_plano").slideDown();
		});
	});
}

/**
 * Ação de KeyUp de Pesquisa de usuários no banco
 */
function pesquisaUsuario(letras){
	$("#ulUserPlano").load("mainframe/actions.php", { "action" : "PesquisandoUsuario", "letras" : letras })
}

/**
 * Função para salvar a nova ameaça, pegando os valores do input
 */ 
function saveAmb(tipo){
	var data = {
		"action" : "NovaAmb",
		"plano"	 : $("#idPlano").val(),
		"nome"	 : $("#"+tipo+" #adicAmb").val(),
		"tabela" : tipo
	};
	$.post("mainframe/actions.php", data, function(result){
		if (result!=-9){
			var novo = $("#"+tipo+" #adicAmb").val()
			$("#"+tipo+" #new").remove();		
			$("#"+tipo+" ul li").parent().append('<li id="'+result+'" style="cursor: pointer;" onclick="delAmb(this,\''+tipo+'\')">'+novo+'</li>');		
			return false;
		} else {
			alert("Houve algum problema ao salvar.");
			return false;
		}
	});
	return false;
}

/**
 * Função para removar a ameaca selecionada
 * @param integer idAmeaca
 */ 
function delAmb(idAmb,tipo){
	if (confirm("Você deseja realmente remover da lista?")){
		var data = {
			"action" : "RemoveAmb",
			"id"	 : $(idAmb).attr("id"),
			"tabela" : tipo
		};
		$.post("mainframe/actions.php", data, function(result){
			if (result == 0){
				alert("Removido com sucesso!");
				$(idAmb).remove();
			}
			else
				alert("Desculpe, houve algum erro...");
		})
	}
	return false;
}

/**
 * Função para calcular os totais da Matriz SWOT automaticamente
 */ 
function calculaTotais(){
	var a = 0;
	
	$("#iTblMatriz tr").each(function(){
		if ($(this).attr("id")){
			$(this).children().each(function(){   
				if($(this).children().hasClass("calc"))
				a+=parseFloat($(this).children().val());
			});
			a = a * ($(this).find(".mot_"+$(this).attr("id")).val());
			$("#tot_motric_"+$(this).attr("id")).html(a);
			a = 0;
		};
	});
	
	var a = [];
	
	$("#iTblMatriz tr .fa").each(function(){
		if(a[$(this).parent().attr("id")]==undefined)
        a[$(this).parent().attr("id")] = 0;
		a[$(this).parent().attr("id")] += parseFloat($(this).val());
	});
	$(a).each(function(i,a){
		$("#iTblMatriz #sub_tot__forca_ameaca__"+i).html(a);
	});
	
	var a = [];
	
	$("#iTblMatriz tr .fop").each(function(){
		if(a[$(this).parent().attr("id")]==undefined)
        a[$(this).parent().attr("id")] = 0;
		a[$(this).parent().attr("id")] += parseFloat($(this).val());
	});
	$(a).each(function(i,a){
		$("#iTblMatriz #sub_tot__forca_oport__"+i).html(a);
	});
	
	var a = [];
	
	$("#iTblMatriz tr .fo").each(function(){
		if(a[$(this).parent().attr("id")]==undefined)
        a[$(this).parent().attr("id")] = 0;
		a[$(this).parent().attr("id")] += parseFloat($(this).val());
	});
	$(a).each(function(i,a){
		$("#iTblMatriz #sub_tot__fraqueza_oport__"+i).html(a);
	});
	
	var a = [];
	
	$("#iTblMatriz tr .fam").each(function(){
		if(a[$(this).parent().attr("id")]==undefined)
        a[$(this).parent().attr("id")] = 0;
		a[$(this).parent().attr("id")] += parseFloat($(this).val());
	});
	$(a).each(function(i,a){
		$("#iTblMatriz #sub_tot__fraqueza_ameaca__"+i).html(a);
	});	
	
	var a =0;
	$("#iTblMatriz #totais td").each(function(){
		if($(this).attr("id")){
			if($(this).children().attr("id") == "tot__oport"){                
				a +=parseFloat($("#iTblMatriz #sub_tot__forca_oport__"+$(this).attr("id")).html());
				a -=parseFloat($("#iTblMatriz #sub_tot__fraqueza_oport__"+$(this).attr("id")).html());        
				$(this).children().html(a);
				a = 0;				
			}       
			if($(this).children().attr("id") == "tot__ameaca"){                
				a +=parseFloat($("#iTblMatriz #sub_tot__forca_ameaca__"+$(this).attr("id")).html());
				a -=parseFloat($("#iTblMatriz #sub_tot__fraqueza_ameaca__"+$(this).attr("id")).html());        
				$(this).children().html(a);
				a = 0;				
			}            
		}
	});
}

/**
 * Função para carregar o help em uma poupup, puxando os dados 
 * do arquivo de help central na pasta docs/help.php 
 *	@param String itemHelp - ID da div no help.php (sem o #)
 */ 
function helper(itemHelp){
	if (!document.getElementById('_help')) {
		var div = document.createElement("div");
		div.id = '_help';
		div.innerHTML = "&nbsp;";
		document.body.appendChild(div);	
		$("#_help").addClass("_help");
	};
	$( "#_help" ).dialog({
		autoOpen: false,
		show: "blind",
		hide: "explode",
		title: "Help"
	});
	
	$("#_help").load("docs/help.php div#"+itemHelp);	
	
	$( "#_help" ).dialog( "open" );
}

function salvandoPosEstrat(modo){
	var ameaca = '', oportunidade = '',forca = '',fraqueza = '';
	$("#ulAmeacasPlano li").each(function(index, element) {
		ameaca += ($(this).attr("id")) + "#";	
	});
	
	$("#ulOportunidadePlano li").each(function(index, element) {
		oportunidade += ($(this).attr("id")) + "#";	
	});
	
	$("#ulForcasPlano li").each(function(index, element) {
		forca += ($(this).attr("id")) + "#";	
	});
	
	$("#ulFraquezasPlano li").each(function(index, element) {
		fraqueza += ($(this).attr("id")) + "#";	
	});	
	
	if (modo == 'Salvar')
		var action = "salvandoPosEstrat";
	else
		var action = "DeletandoPosEstrat";
	
	var data = {
		"action" 		: action,
		"ameaca" 		: ameaca,
		"oportunidade" 	: oportunidade,
		"forca" 		: forca,
		"fraqueza" 		: fraqueza,
		"iniciativa"	: $("#iniciativa").val(),
		"plano"			: $("#idPlano").val(),
		"id"			: $("#iniciativa_estrat").val(),
		"nome"			: $("#nome").val()
	};
	
	$.post("mainframe/actions.php", data, function(result){
		alert(result);
		$("#posicionamento").slideUp();
	});
}

function ManipObjEstrat(tipo, dataId){
	var data = {
		"action" 			: tipo,
		"id"				: dataId,
		"plano"				: $("#idPlano").val(),
		"iniciativa_estrat"	: $("#obj_"+dataId+" #sel_iniciativa_estrat").attr("value"),
		"perspectiva"		: $("#obj_"+dataId+" #sel_perspectiva").attr("value"),
		"contribuicao"		: $("#obj_"+dataId+" #contribuicao").val(),
		"forma_mensura"		: $("#obj_"+dataId+" #forma_mensura").val(),
		"plan_acao_prazo"	: $("#obj_"+dataId+" #plan_acao_prazo").val(),		
		"indicador"			: $("#obj_"+dataId+" #indicador").val(),
		"meta_prazo"		: $("#obj_"+dataId+" #meta_prazo").val(),
		"responsavel"		: $("#obj_"+dataId+" #responsavel").attr("value")
	};
	
	$.post("mainframe/actions.php", data, function(result){
		$("#objetivos").html(result);
		$("#objetivos").slideDown();
	});
}

function verObjetivos(idObj){
	var data = {
		"action" 	: "verDadosObjetivos",
		"id"		: idObj
	};
	$("#obj_dados").slideUp(function(){
		$(this).load("mainframe/actions.php", data, function(){
			$(this).slideDown();
		});
	});
}

function salvaMetas(){
	var data = {
		"action" 			: "NovaMeta",
		"plano"	 			: $("#idPlano").val(),
		"mapa_indicadores"	: $("#id").val(),
		"periodo"			: $("#metas #new #periodo").val(),
		"previsto"			: $("#metas #new #previsto").val()
	};
	$.post("mainframe/actions.php", data, function(result){
		if (result != -9){
			var peri = $("#metas #new #periodo").val();
			var prev = $("#metas #new #previsto").val();
			
			$("#metas #new").remove();		
			$("#metas ul li").parent().append('<li class="metas" id="'+result+'" style="cursor: pointer;" onclick="delMeta(this)">Periodo: '+peri+'<br />Previsão: '+prev+'</li>');		
			return false;
		} else {
			alert("Houve algum problema ao salvar.");
			return false;
		}
	});
	return false;
}

function salvaMapa_indicadores(){
	if ( $("#sel_obj").attr("value") == "Selecione uma Opção" ) {
		alert("Você deve selecionar um Objetivo");
	} else {
		var data = {
			"action" 			: "NovoMapaIndicador",
			"id"	 			: $("#id").val(),
			"plano"	 			: $("#idPlano").val(),
			"obj_estrat"		: $("#sel_obj").attr("value"),
			"qt"				: $("#qt").val(),
			"formula_indicador"	: $("#formula_indicador").val(),
			"periodicidade"		: $("#periodicidade").val(),
			"polaridade"		: $("#polaridade").val(),
			"und_medida"		: $("#und_medida").val(),
			"comentario"		: $("#comentario").val()
		};
		$.post("mainframe/actions.php", data, function(result){
			if (result == -9){
				$("#_content").slideUp(function(){
					$(this).load("pag/mapa_indicadores.php?id="+$('#idPlano').val(), {} ,function(){
						$(this).slideDown();
					});
				});
			} else {
				alert("Houve algum problema ao salvar.");
			}
		});
		return false;
	}
}

function delMeta(idMeta){
	if (confirm("Você deseja realmente remover esta meta da lista?")){
		var data = {
			"action" : "removeMeta",
			"id"	 : $(idMeta).attr("id")
		};
		$.post("mainframe/actions.php", data, function(result){
			if (result == -9){
				alert("Meta Removido com sucesso!");
				$(idMeta).remove();
			}
			else
			alert("Desculpe, houve algum erro...");
		})
	}
	return false;	
}

function sel_perspectivaMapa(idPerspec){
	$("#mapa_indicadores").slideUp(function(){
		$(this).load("pag/altera_mapa_indicador.php", { "id" : idPerspec, "plano" : $("#idPlano").val() }, function(){
			$(this).slideDown();
		});	
	});
}

function deletaMapaIndicador(idMapaIndicador){
	$.post("mainframe/actions.php", { "action" : "DeletaObjEstratejico", "id" : idMapaIndicador }, function(result){
		if (result == -9){
			$("#mapa_indicadores #"+idMapaIndicador).remove();
		} else
			alert("Houve algum erro...");
	});
};

function atualizaMapaIndicador(idMapaIndicador){
	var data = {
		"action" 			: "NovoMapaIndicador",
		"id"	 			: idMapaIndicador,
		"plano"	 			: $("#mapa_indicadores #"+idMapaIndicador+" #idPlano").val(),
		"obj_estrat"		: $("#mapa_indicadores #"+idMapaIndicador+" #sel_obj").attr("value"),
		"qt"				: $("#mapa_indicadores #"+idMapaIndicador+" #qt").val(),
		"formula_indicador"	: $("#mapa_indicadores #"+idMapaIndicador+" #formula_indicador").val(),
		"periodicidade"		: $("#mapa_indicadores #"+idMapaIndicador+" #periodicidade").val(),
		"polaridade"		: $("#mapa_indicadores #"+idMapaIndicador+" #polaridade").val(),
		"und_medida"		: $("#mapa_indicadores #"+idMapaIndicador+" #und_medida").val(),
		"comentario"		: $("#mapa_indicadores #"+idMapaIndicador+" #comentario").val()
	};
	
	$.post("mainframe/actions.php", data, function(result){
		if (result == -9){
			alert("Atualizado com Sucesso!");
		} else
		alert("Houve algum erro...");
	});
}