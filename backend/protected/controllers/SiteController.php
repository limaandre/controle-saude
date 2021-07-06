<?php

class SiteController extends RestController {


	public function getPageTitle() {
        return $pageTitle = 'Minha Saúde';
    }
	
	public function actionHealth(){
		
	}
	
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
		echo "Documentação em desenvolvimento";
		//$this->sendRestResponse(200,array('status'=>''));
	}

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
			$this->sendRestResponse(500,array(
				'status' => false,
				'message' => $error['message'],
				'data' => $error,
			));			
        }
	}
	
	public function actionDebug(){
		phpinfo();	
	}

}