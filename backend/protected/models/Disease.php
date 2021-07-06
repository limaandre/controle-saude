<?php

Yii::import('application.models._base.BaseDisease');

class Disease extends BaseDisease
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
    
    public function init(){
		$this->date = date('d/m/Y H:i:s');
    }
    
    public function beforeSave(){
		return parent::beforeSave();
	}
	
	public function afterFind(){	
		return parent::afterFind();
	}
    
    public function behaviors(){ 
        return array(
        	//{{behaviors}}
        );
    }

    public function get($data) {   
        if ($data['idDiseases']) {
            $model = Disease::model()->findByPk($data['idDiseases']);
            if (is_object($model)) {
                $return['status'] = true;
                $return['data'] = $this->parseModelApp($model);
                return $return;
            } else {
                $return = array(
                    'status' => false,
                    'msg' => Messages::returnMsg($data['language'], 'disease', 'dontFind'),
                    'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
                    'redirect' => 'diseases'
                );
            }
        } else if (isset($data['search'])) {
            $user = User::model()->getUserByEmail($data['user_email'], $data['provider']);
            $criteria = new CDbCriteria();
            $criteria->addCondition("iduser = :iduser");
            $criteria->order = "iddisease desc";
            $criteria->params = array(
                ':iduser' => (int)$user->iduser
            );
            
            $diseases = Disease::model()->findAll($criteria);
            if (count($diseases)) {
                $return = array();
                $return['status'] = true;
                foreach ($diseases as $key => $value) {
                    $return['data'][] = $this->parseModelApp($value);
                }
            } else {
                $return['status'] = true;
                $return['data'] = array();
            }
        }
        return $return;
    }

    public function post($data) {   
        $has_model_data = Disease::model()->findByPk($data['idDiseases']);
        if (!$has_model_data) {
            $model = new Disease();
            $return = $this->saveModel($model, $data);
        }
        return $this->saveModelReturn($return);
    }
    
    public function put($data) {   
        $has_model_data = Disease::model()->findByPk($data['idDiseases']);
        if (!$has_model_data) {
            return array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'disease', 'dontFind'),
                'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
                'redirect' => 'diseases'
            );
        }
        $return = $this->saveModel($has_model_data, $data);
        return $this->saveModelReturn($return);
    }

    private function saveModel($model, $data) {
        $user = User::model()->getUserByEmail($data['user_email'], $data['provider']);
        if (!$user) {
            $return = array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'generic', 'basic'),
                'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
                'redirect' => 'diseases'
            );
            return $return;

        }
        $model->name = $data['name'];
        $model->note = $data['note'];
        $model->iduser = (int)$user->iduser;
        $model->date = date('Y-m-d H:i:s');
        if ($model->save()) {
            $return = array(
                'status' => true,
                'data' => array(
                    'disease' => $this->parseModelApp($model)
                )
            );
        } else {
            $return = array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'disease', 'dontSave'),
                'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
            );
        }
        return $return;
    }

    private function parseModelApp($model) {
        return array(
            'idDisease' => (int)$model['iddisease'],
            'name' => $model['name'],
            'note' => $model['note'],
            'show' => true,
            'filter' => $model['name'] . $model['note'] 
        );
    }

    private function saveModelReturn($return) {
        if (!$return) {
            $return = array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'generic', 'basic'),
                'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
            );
        }
        return $return;
    }

    public function deleteModel($param)
    {
        $model = Disease::model()->findByPk($param['id']);
        if (is_object($model)) {
            $model->deleteRecursive();
            return array(
                'status' => true,
                'data' => array(
                    'disease' => []
                )
            );
        }

        return array(
            'status' => false,
            'msg' => Messages::returnMsg($param['language'], 'disease', 'dontFind'),
            'header' => Messages::returnMsg($param['language'], 'header', 'attention'),
        );
    }
    
        
}