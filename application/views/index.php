<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
	
	<div class="py-5 text-center">
		<img class="d-block mx-auto mb-4" src="https://superlogica.com/wp-content/uploads/2014/10/logo-sl-normal.png" alt="Logo Superlógica" width="120">
	    <h2>Desafio Superlógica</h2>
	    <p class="lead">O seu desafio é criar um CRUD de inventário e emitir um boleto para venda do estoque todo usando a API do PJBank.</p>
	</div>
	
	<div class="row row-lista-produto">
		<div class="col-md-12">
			<h1 class="h1-sl-azul">Inventário</h1>

			<a href="produto/adicionar" class="btn btn-sl btn-sl-azul" title="Adicionar Produto" alt="Adicionar Produto">
				<i class="fas fa-plus"></i> Adicionar Produto
			</a>

			<?php if ($this->session->flashdata('sucesso')) { ?>
				<div class="alert alert-success alert-success-sl alert-dismissible fade show" role="alert">
					<?php echo $this->session->flashdata('sucesso'); ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			<?php } ?>

			<?php if ($this->session->flashdata('erro')) { ?>
				<div class="alert alert-danger alert-danger-sl alert-dismissible fade show" role="alert">
					<?php echo $this->session->flashdata('erro'); ?>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			<?php } ?>
			
			<?php if ($this->session->flashdata('removido')) { ?> 
				<div class="alert alert-danger alert-danger-sl alert-dismissible fade show" role="alert">
					<?php echo $this->session->flashdata('removido'); ?>
				</div>
			<?php } ?>	
			
			<table id="lista-produtos" class="table table-striped">
				<thead>
					<tr>
						<th scope="col">Código</th>
						<th scope="col">Descrição</th>
						<th scope="col">Quantidade</th>
						<th scope="col">Valor Unitário</th>
						<th scope="col">Valor Total</th>
						<th scope="col">Ações</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($produtos as $produto) { ?>
						<tr>
							<th scope="row"><?php echo $produto->codigoProduto; ?></th>
							<td><?php echo $produto->descricaoProduto;?></td>
							<td><?php echo $produto->qtdeProduto;?></td>
							<td>R$ <?php echo $produto->precoProduto;?></td>
							<td>R$ <?php echo $produto->valorParcialProduto;?></td>
							<td>
								<a href="<?php echo site_url() . "/produto/editar/" . $produto->codigoProduto;?>" class="btn btn-sl btn-sl-amarelo" title="Editar Produto" alt="Editar Produto">
									<i class="fas fa-pencil-alt"></i>
								</a>
								<button type="button" class="btn btn-sl btn-sl-vermelho excluir-produto" data-id-produto="<?php echo $produto->codigoProduto;?>" data-nome-produto="<?php echo $produto->descricaoProduto;?>">
									<i class="fas fa-trash-alt"></i>
								</button>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
						
	<div class="row row-gera-boleto">
		<div class="col-md-12">
			<h1 class="h1-sl-verde">Total</h1>
			<h3>R$ <?php echo $total;?></h3>
			
				<?php if (floatval($total)) { ?>
					<button type="button" class="btn btn-sl btn-sl-verde" data-toggle="modal" data-target="#modalBoleto">
						<i class="fas fa-file-invoice-dollar"></i> Gerar Boleto
					</button>
				<?php } ?>
		</div>
	</div>	

</div><!-- /container -->

<!-- Modal Remoção -->
<div class="modal fade" id="modalExclusao" tabindex="-1" role="dialog" aria-labelledby="modalExclusaoLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="modalExclusaoLabel">Exclusão de Produto</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
			  </div>
			  
      		<div class="modal-body">
				Confirma exclusão do produto?
				<p></p>
			</div>
			  
      		<div class="modal-footer">
				<button type="button" class="btn btn-sl btn-sl-azul" data-dismiss="modal">Cancelar</button>
				<a id="excluir-produto" class="btn btn-sl btn-sl-vermelho" href="#">Excluir</a>
      		</div>
    	</div>
  	</div>
</div>

<!-- Modal Geração Boleto -->
<div class="modal fade" id="modalBoleto" tabindex="-1" role="dialog" aria-labelledby="modalBoletoLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="modalBoletoLabel">Informações do Boleto</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
			  </div>
			  
      		<div class="modal-body">
				<form method="post" action="<?php echo site_url() . '/produto/gerar-boleto/';?>">
					<div class="form-row">
						<div class="form-group col-md-6">
							<!-- Informações Boleto -->
							<label for="valorBoleto">Valor:</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">R$</div>
								</div>
								<input type="text" id="valorBoleto" name="valor" class="form-control" value="<?php echo $total;?>" readonly>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label for="vencimento">Vencimento:</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<i class="fas fa-calendar-alt"></i>
									</div>
								</div>
								<input type="date" id="vencimento" name="vencimento" class="form-control" format="dd/mm/yyyy" readonly>
							</div>
						</div>
						<!-- Dados cliente -->
						<div class="form-group col-md-12">
							<label for="nome">Nome:</label>
							<div class="input-group">
								<input type="text" id="nome" name="nome_cliente" class="form-control" placeholder="Nome" value="<?php echo set_value('nome_cliente');?>" required>
							</div>
							<?php echo form_error('nome_cliente'); ?>
						</div>
						<div class="form-group col-md-12">
							<label for="cpf">CPF/CNPJ:</label>
							<div class="input-group">
								<input type="number" id="cpf" name="cpf_cliente" class="form-control" placeholder="CPF/CNPJ" value="<?php echo set_value('cpf_cliente');?>" maxlength="14" required>
							</div>
							<?php echo form_error('cpf_cliente'); ?>
						</div>
						<!-- Dados endereço --> 
						<div class="form-group col-md-10">
							<label for="endereco">Endereço:</label>
							<div class="input-group">
								<input type="text" id="endereco" name="endereco_cliente" class="form-control" placeholder="Endereço" value="<?php echo set_value('endereco_cliente');?>" required>
							</div>
							<?php echo form_error('endereco_cliente'); ?>
						</div>
						<div class="form-group col-md-2">
							<label for="numero">Número:</label>
							<div class="input-group">
								<input type="number" id="numero" name="numero_cliente" class="form-control" min="1" maxlenght="10" placeholder="Número" value="<?php echo set_value('numero_cliente');?>" required>
							</div>
							<?php echo form_error('numero_cliente'); ?>
						</div>
						<div class="form-group col-md-6">
							<label for="complemento">Complemento:</label>
							<div class="input-group">
								<input type="text" id="complemento" name="complemento_cliente" class="form-control" placeholder="Complemento" value="<?php echo set_value('complemento_cliente');?>">
							</div>
							<?php echo form_error('complemento_cliente'); ?>
						</div>
						<div class="form-group col-md-6">
							<label for="bairro">Bairro:</label>
							<div class="input-group">
								<input type="text" id="bairro" name="bairro_cliente" class="form-control" value="<?php echo set_value('bairro_cliente');?>" placeholder="Bairro">
							</div>
							<?php echo form_error('bairro_cliente'); ?>
						</div>
						<div class="form-group col-md-6">
							<label for="cidade">Cidade:</label>
							<div class="input-group">
								<input type="text" id="cidade" name="cidade_cliente" class="form-control" placeholder="Cidade" value="<?php echo set_value('cidade_cliente');?>" required>
							</div>
							<?php echo form_error('cidade_cliente'); ?>
						</div>
						<div class="form-group col-md-2">
							<label for="estado">Estado:</label>
							<div class="input-group">
								<input type="text" id="estado" name="estado_cliente" class="form-control" maxlength="2" placeholder="Estado" value="<?php echo set_value('estado_cliente');?>" required>
							</div>
							<?php echo form_error('estado_cliente'); ?>
						</div>
						<div class="form-group col-md-4">
							<label for="cep">CEP:</label>
							<div class="input-group">
								<input type="number" id="cep" name="cep_cliente" class="form-control" placeholder="CEP" value="<?php echo set_value('cep_cliente');?>" required>
							</div>
							<?php echo form_error('cep_cliente'); ?>
						</div>
					</div>
					<div class="text-right">
						<button type="submit" class="btn btn-sl btn-sl-verde">
							<i class="fas fa-print"></i> Gerar Boleto
						</button>
					</div>
				</form>
			</div>
			  
      		<div class="modal-footer">
				<button type="button" class="btn btn-sl btn-sl-azul" data-dismiss="modal">Cancelar</button>
			</div>
    	</div>
  	</div>
</div>
