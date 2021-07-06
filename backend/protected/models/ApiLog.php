<?php

Yii::import('application.models._base.BaseApiLog');

class ApiLog extends BaseApiLog
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
    
    
    public function init(){
		$this->data = date('d/m/Y H:i:s');
  
    }
    
    public function beforeSave(){
		if($this->data != "")
				$this->data= Util::formataDataHoraBanco($this->data);
		//{{beforeSave}}
		return parent::beforeSave();
	}
	
	public function afterFind(){
		if($this->data != "")
				$this->data = Util::formataDataHoraApp($this->data);
		//{{afterFind}}
		return parent::afterFind();
	}
    
    public function behaviors(){
    	return array(
        	//{{behaviors}}
        );
    }
    
        
}