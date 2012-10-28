var action = "mainframe/actions.php";

/**
 * Ação de KeyUp de Pesquisa de usuários no banco
 */
function pesquisaSintoma(letras){
	$("#listSintoma").load("mainframe/actions.php", { "action" : "PesquisandoSintomas", "letras" : letras })
}

function dadosPaciente(pacid){
	var dados = {
		"action"	: "dadosPaciente",
		"pacid"		: pacid
	};
	
	$("#dadoPaciente").slideUp(function(){
		$(this).load(action, dados, function(){
			$(this).slideDown();
		});
	});
}

function deletar(idObjeto, tabela, obj){
	var data = {
		"action" 		: "RemoveItem",
		"tabelaRemove" 	: tabela,
		"idRemove"		: idObjeto
	};
	
	$.post(action, data, function(result){
		if (result == 1){
			$(obj).closest("li").remove();
		} else { 
			alert("Erro ao Deletar..."); 
		}			
	});	
}