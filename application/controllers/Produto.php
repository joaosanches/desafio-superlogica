<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produto extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->library('session');
				
		$this->load->model('Produto_model', 'produtoModel');		
	}

	/**
	 * view principal
	 */
	public function index()	{
		$produtos = $this->retornaProdutos();

		$this->load->view('partials/header');
		$this->load->view('index', $produtos);
		$this->load->view('partials/footer');
	}
	
	/**
	 * Retorna lista de produtos com valor parcial e total calculados
	 * @return array $produtosComValor
	 */
	public function retornaProdutos() {

		$produtos = $this->produtoModel->retornaProduto();
		
		$produtosComValor = array();
		
		$valorTotal = 0;
		foreach ($produtos as $key => $produto) {
			$valorParcial = $this->calculaValorProduto($produto->precoProduto, $produto->qtdeProduto);
			
			$produtos[$key]->valorParcialProduto = $valorParcial;
			
			$valorTotal += $valorParcial;
		}

		$produtosComValor['produtos'] = $produtos;
		$produtosComValor['total'] = number_format($valorTotal, 2, '.', '');
				
		return $produtosComValor;
	}

	/**
	 * Retorna valor total de cada produto,
	 * de acordo com a quantidade x valor
	 * @param float $valor
	 * @param integer $quantidade
	 * @return float $valor * $quantidade
	 */
	public function calculaValorProduto($valor, $quantidade) {
		return $valor * $quantidade;
	}

	/**
	 * view para adicionar produtos
	 */
	public function adicionar() {
		$data['titulo'] = "Novo Produto";

		$this->load->view('partials/header');
		$this->load->view('produto', $data);
		$this->load->view('partials/footer');
	}

	/**
	 * view para editar produtos
	 * @param integer $codigoProduto
	 */
	public function editar($codigoProduto) {
			
		$produto = $this->produtoModel->retornaProduto($codigoProduto);
		
		if (empty($produto)) {
			redirect('produto', $this->session->set_flashdata('erro', 'Produto não encontrado'));
		}
		
		$data = array(
			'codigoProduto' => $produto[0]->codigoProduto, 
			'valor' => $produto[0]->precoProduto,
			'quantidade' => $produto[0]->qtdeProduto,
			'descricao' => $produto[0]->descricaoProduto
		);
		
		$data['titulo'] = "Editar Produto";
				
		$this->load->view('partials/header');
		$this->load->view('produto', $data);
		$this->load->view('partials/footer');
	}

	/**
	 * remove produto, guardando referencia para possível restauração
	 * @param integer $codigoProduto
	 */
	public function remover($codigoProduto) {
		
		$mensagemRemovido = 'Produto removido com sucesso!
			<a href="' . site_url() . '/produto/restaurar" alt="Desfazer remoção?" title="Desfazer remoção?">
				<strong>
					Desfazer? <i class="fas fa-undo-alt"></i>
				</strong>
			</a>';

		$referenciaProdutoRemovido = $this->produtoModel->retornaProduto($codigoProduto);

		$removido = $this->produtoModel->remover($codigoProduto);
		if ($removido) {
			$this->session->set_userdata('produtoRemovido', $referenciaProdutoRemovido[0]);
			redirect('produto', $this->session->set_flashdata('removido', $mensagemRemovido));
		} else {
			redirect('produto', $this->session->set_flashdata('erro', 'Erro ao remover o produto. Tente mais tarde!'));
		}
	}

	/**
	 * Salva alterações do produto (inclusão/alteração)
	 * @param integer $codigoProduto (optional)
	 */
	public function salvar($codigoProduto = NULL) {
		
		$camposObrigatorios =  array(
			array(
				'field' => 'quantidade',
				'label' => 'Quantidade',
				'rules' => 'required|trim'
			),
			array(
				'field' => 'valor',
				'label' => 'Valor',
				'rules' => 'required|trim'
			),
			array(
				'field' => 'descricao',
				'label' => 'Descrição',
				'rules' => 'required|trim'
			)
		);

		$this->form_validation->set_rules($camposObrigatorios);
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        if ($this->form_validation->run() == FALSE) {
			if ($codigoProduto) {
				$this->editar($codigoProduto);
			} else {
				$this->adicionar();
			}
		} else {

			if ($codigoProduto) {

				if ($this->produtoModel->editar($this->input->post(), $codigoProduto)) {
					redirect('produto', $this->session->set_flashdata('sucesso', 'Produto alterado com sucesso!'));
				} else {
					redirect('produto', $this->session->set_flashdata('erro', 'Erro ao alterar o produto. Tente mais tarde!'));
				}
			} else {
				if ($this->produtoModel->adicionar($this->input->post())) {
					redirect('produto', $this->session->set_flashdata('sucesso', 'Produto cadastrado com sucesso!'));
				} else {
					redirect('produto', $this->session->set_flashdata('erro', 'Erro ao cadastrar o produto. Tente mais tarde!'));
				}
			}
		}
	}

	/**
	 * Restaura último produto removido
	 */
	public function restaurar() {

		if ($this->session->has_userdata('produtoRemovido')) {
			
			$produtoRestaurar = array(
				'quantidade' => $this->session->produtoRemovido->qtdeProduto,
				'valor' => $this->session->produtoRemovido->precoProduto,
				'descricao' => $this->session->produtoRemovido->descricaoProduto,
			);

			if ($this->produtoModel->adicionar($produtoRestaurar)) {

				$this->session->unset_userdata('produtoRemovido');

				redirect('produto', $this->session->set_flashdata('sucesso', 'Produto restaurado com sucesso!'));
			} else {
				redirect('produto', $this->session->set_flashdata('erro', 'Erro ao restaurar o produto. Tente mais tarde!'));
			}
		}
	}

	/**
	 * Gera boleto com os dados fornecidos
	 */
	public function gerarBoleto() {
		
		$camposObrigatorios =  array(
			array(
				'field' => 'nome_cliente',
				'label' => 'Nome',
				'rules' => 'required|trim|min_length[3]|max_length[80]'
			),
			array(
				'field' => 'cpf_cliente',
				'label' => 'CPF',
				'rules' => 'required|trim|callback_validaCPFCNPJ'
			),
			array(
				'field' => 'endereco_cliente',
				'label' => 'Endereço',
				'rules' => 'required|trim|min_length[3]|max_length[80]'
			),
			array(
				'field' => 'numero_cliente',
				'label' => 'Número',
				'rules' => 'required|trim|min_length[1]|max_length[10]'
			),
			array(
				'field' => 'complemento_cliente',
				'label' => 'Complemento',
				'rules' => 'trim|max_length[80]'
			),
			array(
				'field' => 'bairro_cliente',
				'label' => 'Bairro',
				'rules' => 'required|trim|min_length[3]|max_length[80]'
			),
			array(
				'field' => 'cidade_cliente',
				'label' => 'Cidade',
				'rules' => 'required|trim|min_length[3]|max_length[80]'
			),
			array(
				'field' => 'estado_cliente',
				'label' => 'Estado',
				'rules' => 'required|trim|exact_length[2]'
			),
			array(
				'field' => 'cep_cliente',
				'label' => 'CEP',
				'rules' => 'required|trim|min_length[8]|max_length[10]'
			)
		);

		$this->form_validation->set_rules($camposObrigatorios);
		$this->form_validation->set_error_delimiters('<div class="alerta-boleto alert alert-danger" role="alert">', '</div>');
		

        if ($this->form_validation->run() == FALSE) {
			$this->index();
		} else {

			$camposBoleto = $this->input->post();
						
			$this->load->library('PJBank/recebimento');
			$PJBankRecebimentos = new Recebimento();
			
			$boleto = $PJBankRecebimentos->Boletos->NovoBoleto();

			$boleto->setNomeCliente($camposBoleto['nome_cliente'])
					->setCpfCliente($camposBoleto['cpf_cliente'])
					->setValor($camposBoleto['valor'])
					->setVencimento(date('m/d/Y', strtotime($camposBoleto['vencimento'])))
					->setJuros(0)
					->setMulta(0)
					->setDesconto("")
					->setLogoUrl("https://pjbank.com.br/assets/images/pj-logo.png")
					->setTexto("Geração de Boleto para Desafio PJBank")
					->setEnderecoCliente($camposBoleto['endereco_cliente'])
					->setNumeroCliente($camposBoleto['numero_cliente'])
					->setComplementoCliente($camposBoleto['complemento_cliente'])
					->setBairroCliente($camposBoleto['bairro_cliente'])
					->setCidadeCliente($camposBoleto['cidade_cliente'])
					->setEstadoCliente($camposBoleto['estado_cliente'])
					->setCepCliente($camposBoleto['cep_cliente'])
					->gerar();

			if ($boleto->getLink()) {

				$mensagemSucessoBoleto = 'Boleto gerado com sucesso. <a id="boleto-gerado" href="' . $boleto->getLink(). '" target="_blank"><i class="fas fa-print"></i> Imprimir novamente.</a>';
				
				redirect('produto', $this->session->set_flashdata('sucesso', $mensagemSucessoBoleto));
			} else {
				redirect('produto', $this->session->set_flashdata('erro', 'Erro ao gerar o boleto. Tente mais tarde.'));
			}	
		}
	}

	/**
	 * Callback auxiliar para validação do campo cpf_cliente
	 * @param string $campo
	 * @return boolean
	 */
	public function validaCPFCNPJ($campo) {
		$this->load->library('ValidaDocumentos');

		if (!$this->validadocumentos->validateCpfCnpj($campo)) {

			$this->form_validation->set_message('validaCPFCNPJ', 'CPF/CNPJ inválido.');
			return false;
		}

		return true;
	}
}
