<?php

class Produto_model extends CI_Model {

	public function retornaProduto($codigoProduto = NULL) {
		$this->db->select('codigoProduto, descricaoProduto, qtdeProduto, precoProduto');
		
		if ($codigoProduto) {
			$this->db->where('codigoProduto', $codigoProduto);
		}

		$this->db->order_by('codigoProduto', 'DESC');

		$query = $this->db->get('produtos');
		$produto = $query->result();
			
		return $produto;
	}

	public function adicionar($produto) {
		$data = array(
			'qtdeProduto' => $produto['quantidade'],
			'precoProduto' => $produto['valor'],
			'descricaoProduto' => $produto['descricao'],
		);
	
		if ($this->db->insert('produtos', $data)) {
			return true;
		} else {
			return false;
		}
	}

	public function editar($produto, $codigoProduto) {
		
		$data = array(
			'qtdeProduto' => $produto['quantidade'],
			'precoProduto' => $produto['valor'],
			'descricaoProduto' => $produto['descricao'],
		);
		$this->db->set($data);
		$this->db->where('codigoProduto', $codigoProduto);
		
		if ($this->db->update('produtos')) {
			return true;
		} else {
			return false;
		}
	}

	public function remover($codigoProduto) {
		$this->db->where('codigoProduto', $codigoProduto);

		if($this->db->delete('produtos')) {
			return true;
		} else {
			return false;
		}
	}
}
