<?php

ini_set('display_erros', 'Off');
error_reporting(22519);
if (!function_exists('getallheaders')) {

    function getallheaders()
    {
        $headers = '';
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[$name] = $value;
            }
        }
        return $headers;
    }
}
class ApiController extends RestController
{

    private $_POST;
    private $base_url;
    private $token;
    private $hash;
    private $versao;
    private $app = 0;
    private $usuario;
    private $api_log;
    private $language;
    private $provider;
    private $user_email;

    public function init()
    {
        parent::init();
        Yii::app()->attachEventHandler('onError', array($this, 'handleApiError'));
        Yii::app()->attachEventHandler('onException', array($this, 'handleApiError'));
    }

    public function handleApiError(CEvent $event)
    {
        var_dump($event);
        exit;
        $statusCode = 500;
        //echo "<pre>";
        //print_r($event);
        if ($event instanceof CExceptionEvent) {
            $statusCode = $event->exception->statusCode;
            $body = array(
                'status' => false,
                'code' => $event->exception->getCode(),
                'msg' => $event->exception->getMessage(),
                'file' => YII_DEBUG ? $event->exception->getFile() : '*',
                'line' => YII_DEBUG ? $event->exception->getLine() : '*'
            );
        } else {
            $body = array(
                'status' => false,
                'code' => $event->code,
                'msg' => $event->message,
                'file' => YII_DEBUG ? $event->file : '*',
                'line' => YII_DEBUG ? $event->line : '*'
            );
        }

        $event->handled = true;
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        $this->response($body);
    }

    public function trataHeader()
    {
        $_HEADERS = getallheaders();

        if (!empty($_HEADERS['Versao'])) {
            $this->versao = trim($_HEADERS['Versao']);
        }
        if (!empty($_HEADERS['HTTP_VERSAO'])) {
            $this->versao = trim($_HEADERS['HTTP_VERSAO']);
        }

        if (!empty($_HEADERS['Language'])) {
            $this->language = trim($_HEADERS['Language']);
        }

        if (!empty($_HEADERS['Provider'])) {
            $this->provider = trim($_HEADERS['Provider']);
        }

        if (!empty($_HEADERS['UserEmail'])) {
            $this->user_email = trim($_HEADERS['UserEmail']);
        }

        if (!empty($_HEADERS['App']) && $_HEADERS['App'] == 'true') {
            $this->app = 1;
        }
    }

    public function beforeAction($action)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            echo '1';
            exit;
        }
        header('Content-Type: application/json;charset=iso-8859-1');

        $this->trataHeader();
        $this->trataUrl();
        $this->trataDadosRecebidos();
        $this->registrarLog();

        return parent::beforeAction($action);
    }

    private function registrarLog()
    {
        $this->api_log = new ApiLog();
        $this->api_log->post = $this->_POST;
        $this->api_log->get = $_GET;
        $this->api_log->controller = $this->id;
        $this->api_log->action = $this->action->id;
        $this->api_log->token = $this->token;
        $this->api_log->versao = $this->versao;
        $this->api_log->login = $this->usuario;
        $this->api_log->nome = $this->usuario;
        $this->api_log->status = '0';
        $this->api_log->app = $this->app;
        $this->api_log->save();
    }

    private function trataUrl()
    {
        $_HEADERS = getallheaders();
        $this->hash = 1;
        $protocol = (!empty($_HEADERS['HTTPS']) && $_HEADERS['HTTPS'] !== 'off' || (isset($_HEADERS['SERVER_PORT']) && $_HEADERS['SERVER_PORT'] == 443)) ? "https://" : "http://";
        $this->base_url = $protocol . $_SERVER['HTTP_HOST'] . Yii::app()->baseUrl;
        //$this->base_url = '';
    }

    public function trataDadosRecebidos()
    {
        Utf8::decode($_POST);
        Utf8::decode($_GET);

        $postdata = file_get_contents("php://input");
        if (!empty($postdata)) {
            $this->_POST = json_decode($postdata, true);
            Utf8::decode($this->_POST);
        }
    }

    function actionUpload_imagem()
    {
        $model = $_GET['modulo'];
        if ($model === 'notes') {
            $model = 'annotation';
        }
        $nome_arquivo = $_FILES['file']['name'];
        Utf8::decode($nome_arquivo);
        $nome_arquivo = Util::removerAcentos($nome_arquivo);

        $extensao = explode('.', $_FILES['file']['name']);
        $extensao = end($extensao);

        $arquivo_nome = time() . '_' . $nome_arquivo;
        $arquivo_nome = str_replace($extensao, '', $arquivo_nome);
        $arquivo_nome = $arquivo_nome . '.' . $extensao;
        $caminho_destino_arquivo = "uploads/" . $model . "/" . $arquivo_nome;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $caminho_destino_arquivo)) {
            $this->responseDefault(array(
                'status' => true,
                'data' => array(
                    'url' => $this->base_url . "/uploads/" . $model . "/" . $arquivo_nome
                )
            ));
        } else {
            $this->responseDefault(
                array(
                    'status' => false,
                    'msg' => 'Não foi possível salvar a imagem. Tente novamente mais tarde.'
                )
            );
        }
    }

    function actionDelete_data()
    {
        $model = null;
        if ($_GET['type'] === 'exams') {
            $model = new Exams();
        }  else if ($_GET['type'] === 'professionals') {
            $model = new Doctor();
        }   else if ($_GET['type'] === 'notes') {
            $model = new Annotation();
        }   else if ($_GET['type'] === 'consults') {
            $model = new Consults();
        }   else if ($_GET['type'] === 'diseases') {
            $model = new Disease();
        }           

        if ($model) {
            $response = $this->managerAPI($model);
        } else {
            $response = array(
                'status' => false,
                'msg' => Messages::returnMsg( $this->language, 'generic', 'basic'),
                'header' => Messages::returnMsg($this->language, 'header', 'attention'),
            );
        }
        $this->responseDefault($response);
    }

    function actionUser()
    {
        $model = new User();
        $response = $this->managerAPI($model);
        $this->responseDefault($response);
    }

    function actionDoctor()
    {
        $model = new Doctor();
        $response = $this->managerAPI($model);
        $this->responseDefault($response);
    }
    
    function actionMedication()
    {
        $model = new Medicine();
        $response = $this->managerAPI($model);
        $this->responseDefault($response);
    }
    
    function actionDisease()
    {
        $model = new Disease();
        $response = $this->managerAPI($model);
        $this->responseDefault($response);
    }

    function actionNotes()
    {
        $model = new Annotation();
        $response = $this->managerAPI($model);
        $this->responseDefault($response);
    }

    function actionExam()
    {
        $model = new Exams();
        $response = $this->managerAPI($model);
        $this->responseDefault($response);
    }
    
    function actionConsult()
    {
        $model = new Consults();
        $response = $this->managerAPI($model);
        $this->responseDefault($response);
    }

    private function responseDefault($response)
    {
        if (is_array($response) && $response['status']) {
            $this->response(
                array(
                    'status' => true,
                    'data' => isset($response['data']) ? $response['data'] : $response
                ),
                200
            );
        }

        $this->response(
            array(
                'status' => false,
                'msg' => $response['msg'],
                'header' => $response['header'],
                'redirect' => $response['redirect'] ? $response['redirect'] : null,
            ),
            400
        );
    }

    private function managerAPI($class)
    {
        $response = null;
        $params['language'] = $this->language;
        $params['provider'] = $this->provider;
        $params['user_email'] = $this->user_email;
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $params = array_merge($params, $_GET);
            $response = $class->get($params);
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $params = array_merge($params, $this->_POST);
            $response = $class->post($params);
        } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $params = array_merge($params, $_GET);
            $response = $class->deleteModel($params);
        } else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $params = array_merge($params, $this->_POST);
            $response = $class->put($params);
        }
        return $response;
    }
}
