$(function() {

	// Data de vencimento já calculada
	let dataAtual = new Date;

	// Prazo de dias para vencimento do boleto
	let diasVencimento = 5;
		
	// adicionando prazo
	dataAtual.setDate(dataAtual.getDate() + diasVencimento);       

	// convertendo para string e retornando apenas parte da data yy-mm-dd
	let dataVencimento = dataAtual.toISOString().substring(0, 10);
	
	// adicionando data no formulario
	$('#vencimento').attr('value', dataVencimento);

	// Alerta de boleto
	if ($('.alerta-boleto').length) {
		$('#modalBoleto').modal('show');
	}

	// se o boleto for gerado, abrir nova aba com ele para impressão
	if ($('#boleto-gerado').length) {
		window.open($('#boleto-gerado').attr('href'), '_blank');
	}
});
