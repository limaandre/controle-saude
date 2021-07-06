<?php

Yii::import('application.models._base.BaseMedicine');

class Medicine extends BaseMedicine
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
        if ($this->date_end === '0000-00-00 00:00:00') {
            $this->date_end = null;
        }
        if ($this->date_initial === '0000-00-00 00:00:00') {
            $this->date_initial = null;
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
        if ($data['idMedication']) {
            $model = Medicine::model()->findByPk($data['idMedication']);
            if (is_object($model)) {
                $return['status'] = true;
                $return['data'] = $this->parseModelApp($model);
                return $return;
            } else {
                $return = array(
                    'status' => false,
                    'msg' => Messages::returnMsg($data['language'], 'medicine', 'dontFind'),
                    'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
                    'redirect' => 'professionals'
                );
            }
        } else if (isset($data['search'])) {
            $user = User::model()->getUserByEmail($data['user_email'], $data['provider']);
            $criteria = new CDbCriteria();
            $criteria->addCondition("iduser = :iduser");
            $criteria->order = "idmedicine desc";
            $criteria->params = array(
                ':iduser' => (int)$user->iduser
            );

            $medicine = Medicine::model()->findAll($criteria);
            if (count($medicine)) {
                $return = array();
                $return['status'] = true;
                foreach ($medicine as $key => $value) {
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
        $has_model_data = Medicine::model()->findByPk($data['idMedication']);
        if (!$has_model_data) {
            $model = new Medicine();
            $return = $this->saveModel($model, $data);
        }
        return $this->saveModelReturn($return);
    }

    public function put($data)
    {
        $has_model_data = Medicine::model()->findByPk($data['idMedication']);
        if (!$has_model_data) {
            return array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'medicine', 'dontFind'),
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
                'redirect' => 'medicine'
            );
            return $return;
        }
        
        $schedules = '';
        if (count($data['medicationSchedules'])) {
            $schedules = implode(', ', $data['medicationSchedules']);
        }

        $model->name = $data['name'];
        $model->concentration = $data['concentration'];
        $model->dosage = $data['dosage'];
        $model->prescription = $data['prescription'];
        $model->medication_schedules = $schedules;
        $model->note = $data['note'];
        $model->iddoctor = $data['idDoctor'];
        $model->image = $data['image'];
        $model->iduser = (int)$user->iduser;
        $model->date = date('Y-m-d H:i:s');
        $model->date_end = null;
        $model->date_initial = null;
        if ($data['dateEnd']) {
            $model->date_end = $data['dateEnd'];
        }
        if ($data['dateInitial']) {
            $model->date_initial = $data['dateInitial'];
        }

        if ($model->save()) {
            $return = array(
                'status' => true,
                'data' => array(
                    'medicine' => $this->parseModelApp($model)
                )
            );
        } else {
            $return = array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'medicine', 'dontSave'),
                'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
            );
        }
        return $return;
    }

    private function parseModelApp($model)
    {
        $date_end = explode(' ', $model->date_end);
        $date_initial = explode(' ', $model->date_initial);
        return array(
            'idMedication' => (int)$model['idmedicine'],
            'name' => $model['name'],
            'dateInitial' => $date_initial[0] ? $date_initial[0] : null,
            'dateEnd' => $date_end[0] ? $date_end[0] : null,
            'medicationSchedules' => explode(', ', $model->medication_schedules),
            'concentration' => $model['concentration'],
            'dosage' => $model['dosage'],
            'prescription' => $model['prescription'],
            'note' => $model['note'],
            'doctor' => $model->doctor->name ? $model->doctor->name : '',
            'idDoctor' => $model->doctor->iddoctor ? (int)$model->doctor->iddoctor : null,
            'image' => $model['image'],
            'show' => true,
            'filter' => $model['name'] . 
            $model->medication_schedules . 
            $model['date_initial'] . 
            $model['date_end'] . 
            $model['concentration'] . 
            $model['dosage'] . 
            $model['prescription'] . 
            $model->doctor->name . 
            $model->doctor->iddoctor
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
        $model = Medicine::model()->findByPk($param['id']);
        if (is_object($model)) {
            $model->deleteRecursive();
            return array(
                'status' => true,
                'data' => array(
                    'medicine' => []
                )
            );
        }

        return array(
            'status' => false,
            'msg' => Messages::returnMsg($param['language'], 'medicine', 'dontFind'),
            'header' => Messages::returnMsg($param['language'], 'header', 'attention'),
        );
    }
    
        
}