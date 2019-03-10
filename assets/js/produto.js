$(function() {
			
	// MODAL
	$('.excluir-produto').click(function() {
		
		let idProduto =  $(this).attr("data-id-produto");
		let nomeProduto = $(this).attr("data-nome-produto");

		$("#modalExclusao #excluir-produto").attr("href", `produto/remover/${idProduto}`);
		$('#modalExclusao .modal-body p').html(`<p>${nomeProduto}</p>`);

		
		$('#modalExclusao').modal("show");// this triggers your modal to display
	});

	$("#valor").on('invalid', () => {
		
		var elemento = $("#valor")[0];
		 elemento.setCustomValidity('Utilize separador apenas para as casas decimais. Ex: 2000,50');
	});

	$("#valor").on('input', () => {

		var elemento = $("#valor")[0];
		 
		try {
			elemento.setCustomValidity('');
		} catch (e) {
			console.log('erro')
		}
	});			

	// Datatables
	$('#lista-produtos').DataTable( {
		"order": [],
		"language": {
			"lengthMenu": "Exibindo _MENU_ produtos por página",
			"zeroRecords": "Nenhum produto encontrado",
			"info": "Exibindo página _PAGE_ de _PAGES_",
			"infoEmpty": "Nenhum produto encontrado",
			"infoFiltered": "(Filtrados de _MAX_ registros)",
			"search": "Pesquisar:",
			"paginate": {
				"first": "Primeiro",
				"last": "Último",
				"next": "Próximo",
				"previous": "Anterior"
			},
		},
		"lengthMenu": [[5, 10, 15, 20], [5, 10, 15, 20]],
		"columnDefs": [
			{ 
				"searchable": false, 
				"targets": [2,3,4,5] 
			}
		]
	});

});
