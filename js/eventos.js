$("#cadDoenca").live("click", function(){
	var data = {
		"action"		: "NovoItem",
		"tabelaInsere"	: "doenca",
		"doenome"		: $("#doenome").val(),
		"traid"			: $("#doe-traid").val(),
		"sinid"			: $("#doe-sinid").val()
	};

	$.post(action, data, function(result){
		if (result !=0 ){
			var dados = {
				"action"		: "NovoItem",
				"tabelaInsere"	: "doenca_sintoma",
				"doeid"			: result,
				"sinid"			: $("#doe-sinid").val()
			};
			$.post(action, data, function(result){
				var dados = {
					"action"		: "NovoItem",
					"tabelaInsere"	: "doenca_tratamento",
					"doeid"			: result,
					"traid"			: $("#doe-traid").val()
				};		
			});
		} else
			alert("Puts deu merda");
	});
});

$("#cadTratamento").live("click", function(){
	var data = {
		"action"		: "NovoItem",
		"tabelaInsere"	: "tratamentos",
		"tranome"		: $("#tranome").val()		
	};

	$.post(action, data, function(result){
		alert(result);
	});
});

$("#cadSintoma").live("click", function(){
	var data = {
		"action"		: "NovoItem",
		"tabelaInsere"	: "sintomas",
		"sinnome"		: $("#sinnome").val()		
	};

	$.post(action, data, function(result){
		alert(result);
	});
});
