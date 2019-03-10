<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PJBank {

	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function gerarBoleto($dadosBoleto)
	{
		$credencial = $this->CI->config->item('credencialPJBank');
		$chave = $this->CI->config->item('chavePJBank');
		
		$url = "https://api.pjbank.com.br/contadigital/{$credencial}/recebimentos/transacoes";
		
		$corpo = json_encode($dadosBoleto);

		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $corpo,
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}
}
