<?php
if (!function_exists('getallheaders'))
{
	function getallheaders(){
		$headers = '';
		foreach ($_SERVER as $name => $value) {
			if(substr($name, 0, 5) == 'HTTP_'){
				$headers[$name] = $value;
			}
		}
		return $headers;
	}
}

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class RestController extends CController {
	
	public $request_data = array();	
	
    public function beforeAction($action) {
		$header = getallheaders();
		
		if($this->action->id == 'health'){
			return parent::beforeAction($action);
		}

		$valida_header = $this->validateHeader($header);
		if (!$valida_header['is_valid']) {
			$this->sendRestResponse(401,array(
				'status' => false,
				'message' => $valida_header['msg'],
			));
		}
		
		$request_body = file_get_contents('php://input');
		if(!empty($request_body)){
			$this->request_data = CJSON::decode($request_body);
			Utf8::decode($this->request_data);
		}	
		
		return parent::beforeAction($action);
	}

	private function validateHeader($header) {
		$retorno['is_valid'] = true;
		$retorno['msg'] = '';
        if (str_contains($_SERVER['REQUEST_URI'], 'upload_imagem')) {
            return $retorno;
        }

		$token_client = Client::tokenClient($header['Client']);

		if (empty($header['X-Apikey'])) {
			$retorno['is_valid'] = false;
			$retorno['msg'] = 'Informe uma api key válida';
		} else if (!in_array($header['Client'], Client::allClients()) || empty($header['Client']) || count($token_client) === 0) {
			$retorno['is_valid'] = false;
			$retorno['msg'] = 'Não autorizado 1';
		} else if(!in_array($header['X-Apikey'], $token_client)) {
			$retorno['is_valid'] = false;
			$retorno['msg'] = 'Não autorizado';
		}
		return $retorno;
	}

    protected function response($data, $status_code = 200){
		$this->sendRestResponse($status_code, $data);
	}

	protected function sendRestResponse($status, $data){
		$this->sendResponse($status, CJSON::encode($data), $contentType = 'application/json');
	}
	
	protected function sendResponse($status = 200, $body = '', $contentType = 'application/json'){
		ob_end_clean();
		header("Connection: close\r\n");
		header("Content-Encoding: none\r\n");
		$statusHeader = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage($status);
		header($statusHeader);
		header('Content-type: ' . $contentType.'; charset=utf-8');
		ignore_user_abort(true); // optional
		ob_start();
		echo $body;
        exit;
		$size = ob_get_length();
		header("Content-Length: $size");
		ob_end_flush();     // Strange behaviour, will not work
		flush();            // Unless both are called !
		ob_end_clean();
		// session_destroy();
	}
	
	protected function getStatusCodeMessage($status)
	{
	    $codes = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
	    );
	    return (isset($codes[$status])) ? $codes[$status] : '';
	}
	

}
