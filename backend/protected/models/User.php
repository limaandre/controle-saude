<?php

Yii::import('application.models._base.BaseUser');

class User extends BaseUser
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
    
    public function init(){
		$this->date = date('d/m/Y H:i:s');
    }
    
    public function beforeSave(){		
		//{{beforeSave}}
		return parent::beforeSave();
	}
	
	public function afterFind(){
		//{{afterFind}}
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
        	//{{behaviors}}
        );
    }

    public function get($data) {       
        $return = array(
            'status' => true,
            'data' => array(
                'user' => null
            )
        );

        $user = $this->getUserByEmail($data['email'], $data['provider']);
        if (is_object($user)) {
            $return = array(
                'status' => true,
                'data' => array(
                    'user' => $this->parseUserApp($user)
                )
            );
        }
        return $return;
    }

    public function post($data) {   
        $has_user = $this->getUserByEmail($data['email'], $data['provider']);
        if (!$has_user) {
            $user = new User();
            $return = $this->saveUser($user, $data);
        }

        return $this->saveUserReturn($return);
    }
    
    public function put($data) {
        $user = $this->getUserByEmail($data['email'], $data['provider']);
        if ($user) {
            $return = $this->saveUser($user, $data);
        }
        return $this->saveUserReturn($return);
    }

    private function saveUser($user, $data) {
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->blood_type = $data['bloodType'];
        $user->provider = $data['provider'];
        $user->active = 1;
        $user->date = date('Y-m-d H:i:s');
        $user->gender = $this->parseGender($data['gender']);
        $user->birth_date = $data['birthDate'];
        $user->image = $data['image'];
        if ($user->save()) {
            $return = array(
                'status' => true,
                'data' => array(
                    'user' => $this->parseUserApp($user)
                )
            );
        } else {
            $return = array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'user', 'dontSave'),
                'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
            );
        }
        return $return;
    }

    private function parseUserApp($user) {
        return array(
            'displayName' => $user['name'],
            'email' => $user['email'],
            'phoneNumber' => null,
            'photoURL' => $user['image'],
            'bloodType' => $user['blood_type'],
            'gender' => $user['gender'],
            'birthDate' => $user['birth_date'],
            'active' => $user['active'],
        );
    }

    private function parseGender($gender) {
        return $gender[0];
    }

    public function getUserByEmail($email, $provider) {
        $criteria = new CDbCriteria();
        $criteria->addCondition("email = :email");
        $criteria->addCondition("provider = :provider");
        $criteria->params = array(
            ':email' => $email,
            ':provider' => $provider
        );
        return User::model()->find($criteria);
    }   

    private function saveUserReturn($return) {
        if (!$return) {
            $return = array(
                'status' => false,
                'msg' => Messages::returnMsg($data['language'], 'generic', 'basic'),
                'header' => Messages::returnMsg($data['language'], 'header', 'attention'),
            );
        }
        return $return;
    }
}