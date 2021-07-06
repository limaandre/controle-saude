<?php

Yii::import('application.models._base.BaseAnnotation');

class Annotation extends BaseAnnotation
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
			'Image' => array(
				  'class' => 'ext.behaviors.AttachmentBehavior',
				  'attribute' => 'image',
				  'fallback_image' => 'img/imagem_nao_cadastrada.png',
				  'attribute_delete' => 'image_delete',
				  /*
				  'attribute_size' => 'image_tamanho',
				  'attribute_type' => 'image_tipo',
				  'attribute_ext' => 'image_ext',
				  */
				  'path' => "uploads/:model/image_:id_".time().".:ext",
				  'styles' => array(
					  'p' => '200x200',
					  'g' => '800x800',
				)          
			),
			'File' => array(
				  'class' => 'ext.behaviors.AttachmentBehavior',
				  'attribute' => 'file',
				  'fallback_image' => 'img/imagem_nao_cadastrada.png',
				  'attribute_delete' => 'file_delete',
				  /*
				  'attribute_size' => 'file_tamanho',
				  'attribute_type' => 'file_tipo',
				  'attribute_ext' => 'file_ext',
				  */
				  'path' => "uploads/:model/file_:id_".time().".:ext",          
			),
        	//{{behaviors}}
        );
    }

    public function get($data) {   

        if ($data['idAnnotation']) {
            $model = Annotation::model()->findByPk($data['idAnnotation']);
            if (is_object($model)) {
                $return['status'] = true;
                $return['data'] = $this->parseModelApp($model);
                return $return;
            } else {
                $return = array(
                    'status' => false,
                    'msg' => Messages::returnMsg($data['language'], 'annotation', 'dontFind'),
                    'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
                    'redirect' => 'notes'
                );
            }
        } else if (isset($data['search'])) {
            $user = User::model()->getUserByEmail($data['user_email'], $data['provider']);
            $criteria = new CDbCriteria();
            $criteria->addCondition("iduser = :iduser");
            $criteria->order = "idAnnotation desc";
            $criteria->params = array(
                ':iduser' => (int)$user->iduser
            );

            $annotations = Annotation::model()->findAll($criteria);
            if (count($annotations)) {
                $return = array();
                $return['status'] = true;
                foreach ($annotations as $key => $value) {
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
        $has_model_data = Annotation::model()->findByPk($data['idAnnotation']);
        if (!$has_model_data) {
            $model = new Annotation();
            $return = $this->saveModel($model, $data);
        }
        return $this->saveModelReturn($return);
    }
    
    public function put($data) {   
        $has_model_data = Annotation::model()->findByPk($data['idAnnotation']);
        if (!$has_model_data) {
            return array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'annotation', 'dontFind'),
                'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
                'redirect' => 'notes'
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
                'redirect' => 'notes'
            );
            return $return;

        }
        
        $model->note = $data['note'];
        $model->title = $data['title'];
        $model->image = $data['image'];
        $model->iduser = (int)$user->iduser;
        $model->date = date('Y-m-d H:i:s');
        if ($model->save()) {
            $return = array(
                'status' => true,
                'data' => array(
                    'notes' => $this->parseModelApp($model)
                )
            );
        } else {
            $return = array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'annotation', 'dontSave'),
                'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
            );
        }
        return $return;
    }

    private function parseModelApp($model) {
        return array(
            'idAnnotation' => (int)$model['idannotation'],
            'title' => $model['title'],            
            'note' => $model['note'],            
            'image' => $model['image'],
            'show' => true,
            'filter' => $model['title'] . $model['note'] 
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
        $model = Annotation::model()->findByPk($param['id']);
        if (is_object($model)) {
            $model->deleteRecursive();
            return array(
                'status' => true,
                'data' => array(
                    'doctor' => []
                )
            );
        }

        return array(
            'status' => false,
            'msg' => Messages::returnMsg($param['language'], 'annotation', 'dontFind'),
            'header' => Messages::returnMsg($param['language'], 'header', 'attention'),
        );
    }
    
        
}