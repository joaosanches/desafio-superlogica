<?php

use PJBank\Boleto\BoletosManager;
use PJBank\Cartao\CartaoManager;
use PJBank\Extrato\ExtratoManager;

/**
 * Class Recebimento
 * @author Matheus Fidelis
 * @email matheus.fidelis@superlogica.com
 */
class Recebimento
{

    /**
     * Credencial informada na criação da conta
     * @var string
     */
    private $credencial;

    /**
     * Chave informada na criação da conta
     * @var null
     */
    private $chave;

    /**
     * Boleto Manager SDK
     * @var Boleto
     */
    public $Boletos;

    /**
     * Cartões Manager SDK
     * @var
     */
    public $Cartoes;

    /**
     * ExtratoManager SDK
     * @var
     */
    public $Extratos;

    /**
     * Conta Digital Manager SDk
     * @var
     */
    public $ContaDigital;

	/**
	 * Instância do CodeIgniter
	 * @var 
	 */
	protected $CI;

    /**
     * PJBank constructor
     * Parâmetros removidos. 
	 * As configurações serão armazenadas 
	 * no arquivo de configuração do CodeIgniter
     */
    public function __construct()
    {
		$this->CI =& get_instance();
		$credencial = $this->CI->config->item('credencialPJBank');
		$chave = $this->CI->config->item('chavePJBank');

        $this->credencial = $credencial;
		$this->chave = $chave;

        $this->constructorCartao();
        $this->constructorBoletos();
        $this->constructorExtrato();
    }

    /**
     * Constructor de Transacoes de Cartão
     */
    private function constructorCartao() {
        $this->Cartoes = new CartaoManager($this->credencial, $this->chave);
    }

    /**
     * Constructor Boletos
     */
    private function constructorBoletos() {
        $this->Boletos = new BoletosManager($this->credencial, $this->chave);
    }

    /**
     * Constructor Extrato
     */
    private function constructorExtrato() {
        $this->Extratos = new ExtratoManager($this->credencial, $this->chave);
    }



}
