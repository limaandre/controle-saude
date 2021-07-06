<?php

Yii::import('application.models._base.BaseConsults');

class Consults extends BaseConsults
{
	public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function init()
    {
        $this->date = date('d/m/Y H:i:s');
    }

    public function beforeSave()
    {
        return parent::beforeSave();
    }

    public function afterFind()
    {
        if ($this->date_consultation === '0000-00-00 00:00:00') {
            $this->date_consultation = null;
        }
        return parent::afterFind();
    }

    public function behaviors()
    {
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
                'path' => "uploads/:model/image_:id_" . time() . ".:ext",
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
                'path' => "uploads/:model/file_:id_" . time() . ".:ext",
            ),
            //{{behaviors}}
        );
    }

    public function get($data)
    {
        if ($data['idConsult']) {
        
                $return['status'] = true;    $model = Consults::model()->findByPk($data['idConsult']);
            if (is_object($model)) {
                $return['data'] = $this->parseModelApp($model);
                return $return;
            } else {
                $return = array(
                    'status' => false,
                    'msg' => Messages::returnMsg($data['language'], 'consults', 'dontFind'),
                    'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
                    'redirect' => 'professionals'
                );
            }
        } else if (isset($data['search'])) {
            $user = User::model()->getUserByEmail($data['user_email'], $data['provider']);
            $criteria = new CDbCriteria();
            $criteria->addCondition("iduser = :iduser");
            $criteria->order = "idconsults desc";
            $criteria->params = array(
                ':iduser' => (int)$user->iduser
            );

            $consults = Consults::model()->findAll($criteria);
            if (count($consults)) {
                $return = array();
                $return['status'] = true;
                foreach ($consults as $key => $value) {
                    $return['data'][] = $this->parseModelApp($value);
                }
            } else {
                $return['status'] = true;
                $return['data'] = array();
            }
        }
        return $return;
    }

    public function post($data)
    {
        $has_model_data = Consults::model()->findByPk($data['idConsult']);
        if (!$has_model_data) {
            $model = new Consults();
            $return = $this->saveModel($model, $data);
        }
        return $this->saveModelReturn($return);
    }

    public function put($data)
    {
        $has_model_data = Consults::model()->findByPk($data['idConsult']);
        if (!$has_model_data) {
            return array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'consults', 'dontFind'),
                'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
                'redirect' => 'professionals'
            );
        }
        $return = $this->saveModel($has_model_data, $data);
        return $this->saveModelReturn($return);
    }

    private function saveModel($model, $data)
    {
        $user = User::model()->getUserByEmail($data['user_email'], $data['provider']);
        if (!$user) {
            $return = array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'generic', 'basic'),
                'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
                'redirect' => 'consults'
            );
            return $return;
        }
        $model->local = $data['locale'];
        $model->note = $data['note'];
        $model->iddoctor = $data['idDoctor'];
        $model->image = $data['image'];
        $model->iduser = (int)$user->iduser;
        $model->date = date('Y-m-d H:i:s');
        $model->date_consultation = null;
        if ($data['date'] && $data['hour']) {
            $model->date_consultation = $data['date'] . ' ' . $data['hour'];
        }

        if ($model->save()) {
            $return = array(
                'status' => true,
                'data' => array(
                    'consults' => $this->parseModelApp($model)
                )
            );
        } else {
            $return = array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'consults', 'dontSave'),
                'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
            );
        }
        return $return;
    }

    private function parseModelApp($model)
    {
        $date_consultation = explode(' ', $model->date_consultation);
        return array(
            'idconsult' => (int)$model['idconsults'],
            'date' => $date_consultation[0] ? $date_consultation[0] : null,
            'hour' => $date_consultation[1] ? $date_consultation[1] : null,
            'locale' => $model['local'],
            'note' => $model['note'],
            'doctor' => $model->doctor->name ? $model->doctor->name : '',
            'idDoctor' => $model->doctor->iddoctor ? (int)$model->doctor->iddoctor : null,
            'image' => $model['image'],
            'show' => true,
            'filter' => $model->date_consultation . $model['local'] . $model['note'] . $model->doctor->name . $model->doctor->iddoctor
        );
    }

    private function saveModelReturn($return)
    {
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
        $model = Consults::model()->findByPk($param['id']);
        if (is_object($model)) {
            $model->deleteRecursive();
            return array(
                'status' => true,
                'data' => array(
                    'consults' => []
                )
            );
        }

        return array(
            'status' => false,
            'msg' => Messages::returnMsg($param['language'], 'consults', 'dontFind'),
            'header' => Messages::returnMsg($param['language'], 'header', 'attention'),
        );
    }
    
        
}