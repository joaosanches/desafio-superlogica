<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo site_url();?>">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Produto</li>
			</ol>
			</nav>		
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1 class="h1-sl-roxo"><?php echo $titulo;?></h1>
			<form method="post" action="<?php echo site_url() . "/produto/salvar/" . (isset($codigoProduto) ? $codigoProduto : "");?>" accept-charset="utf-8">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="quantidade">Quantidade:</label>
						<input type="number" id="quantidade" name="quantidade" class="form-control" min="1" value="<?php echo (isset($quantidade) ? $quantidade : set_value('quantidade'));?>" placeholder="Quantidade do Produto" required>
						<?php echo form_error('quantidade'); ?>
					</div>
					<div class="form-group col-md-6">
						<label for="valor">Valor:</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">R$</div>
							</div>
							<input type="number" id="valor" name="valor" class="form-control" min="1" step="any" value="<?php echo (isset($valor) ? $valor : set_value('valor'));?>" placeholder="Valor do Produto" required>
						</div>
						<?php echo form_error('valor'); ?>

					</div>
				</div>
				<div class="form-group">
					<label for="descricao">Descrição</label>
					<textarea id="descricao" name="descricao" class="form-control" rows="6" placeholder="Descrição do Produto" required><?php echo (isset($descricao) ? $descricao : set_value('descricao'));?></textarea>
					<?php echo form_error('descricao'); ?>
					<button type="submit" class="btn btn-sl btn-sl-verde">
						<i class="fas fa-save"></i> Salvar
					</button>
					<a href="<?php echo site_url();?>" class="btn btn-sl btn-sl-azul" title="Cancelar" alt="Cancelar">
						<i class="fas fa-long-arrow-alt-left"></i> Cancelar
					</a>
				</div>
			</form>
		</div>
	</div>
</div>
