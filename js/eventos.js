$("#cadDoenca").live("click", function(){
	var sinid = '';
	$("#doe-sinid :selected").each(function(){ 
		sinid+= $(this).val()+"#";
	});
	var traid = '';
	$("#doe-traid :selected").each(function(){ 
		traid+= $(this).val()+"#";
	});

	var data = {
		"action"		: "NovoItem",
		"tabelaInsere"	: "doencas",
		"doenome"		: $("#doenome").val(),
		"sinid"			: sinid,
		"traid"			: traid
	};

	$.post(action, data, function(result){
		if (result != 0)
			alert("Inserido com Sucesso!");
		else
			alert("Houve algum problema no cadastro do tratamento...");
	});
});

$("#cadTratamento").live("click", function(){
	var data = {
		"action"		: "NovoItem",
		"tabelaInsere"	: "tratamentos",
		"tranome"		: $("#tranome").val()		
	};

	$.post(action, data, function(result){
		if (result != 0)
			alert("Inserido com Sucesso!");
		else
			alert("Houve algum erro...");
	});
});

$("#cadSintoma").live("click", function(){
	var data = {
		"action"		: "NovoItem",
		"tabelaInsere"	: "sintomas",
		"sinnome"		: $("#sinnome").val()		
	};

	$.post(action, data, function(result){
		if (result != 0)
			alert("Inserido com Sucesso!");
		else
			alert("Houve algum erro...");
	});
});

$("#cadPaciente").live("click", function(){
	var data = {
		"action"		: "NovoItem",
		"tabelaInsere"	: "pacientes",
		"pacnome"		: $("#pacnome").val(),
		"pactel"		: $("#pactel").val(),
		"pacend"		: $("#pacend").val()		
	};

	$.post(action, data, function(result){
		if (result != 0)
			alert("Inserido com Sucesso!");
		else
			alert("Houve algum erro...");
	});
});

$("#atualizaDoencas").live("click", function(){
	var sints = '';
	$("#sintomasPaciente li").each(function(){
		sints+= $(this).attr("id") + ",";
	});

	var data = {
		"action"	: "carregaDoencas",
		"sints"		: sints
	};
	
	$("#doencasPaciente").load(action, data);
});

$("#atualizaTratamentos").live("click", function(){
	var doeids = '';
	$("#btn-chkDoenca:checked").each(function(){
		doeids+= $(this).val() + ",";
	});

	var doi ='';
	for (var i=0; i<(doeids.length)-1; i++)
		doi+= doeids[i];

	var data = {
		"action"	: "carregaTratamentos",
		"doeid"		: doi
	};
	
	$("#tratamentosPaciente").load(action, data);
});

$("#cadConsulta").live("click", function(){
	var sints = '';
	$("#sintomasPaciente li").each(function(){
		if ($(this).attr("id") != "")
			sints+= $(this).attr("id") + ",";
	});

	var sintomas = '';
	for (var i=0; i<(sints.length)-1; i++)
		sintomas+= sints[i];

	
	
	var doiid = '';
	$("#btn-chkDoenca:checked").each(function(){
		doiid+= $(this).val() + ",";
	});

	var doencas ='';
	for (var i=0; i<(doiid.length)-1; i++)
		doencas+= doiid[i];	
	
	
	
	var traid = '';
	$("#btn-chkTratamento:checked").each(function(){
		traid+= $(this).val() + ",";
	});

	var tratamentos ='';
	for (var i=0; i<(traid.length)-1; i++)
		tratamentos+= traid[i];
	
	
	
	var data = {
		"action"		: "gravaConsulta",
		"pacid"			: $("#pacid").val(),
		"sintomas"		: sintomas,
		"doencas"		: doencas,
		"tratamentos" 	: tratamentos
	};
	
	$.post(action, data, function(result){
		if (result != 0 )
			alert("Cadastrado com Suuceso!");
		else
			alert("Houve algum problema ao cadastrar");
	});
	
});

