<?php

/**
 * This is the model base class for the table "user".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "User".
 *
 * Columns in table "user" available as properties of the model,
 * followed by relations of table "user" available as properties of the model.
 *
 * @property integer $iduser
 * @property string $name
 * @property string $email
 * @property string $provider
 * @property string $blood_type
 * @property string $active
 * @property string $date
 * @property string $gender
 * @property string $birth_date
 * @property string $image
 *
 * @property Annotation[] $annotations
 * @property Consults[] $consults
 * @property Disease[] $diseases
 * @property Doctor[] $doctors
 * @property Exams[] $exams
 * @property Medicine[] $medicines
 */
abstract class BaseUser extends GxActiveRecord {
	
    
    public $image_delete;
	    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'user';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'User|Users', $n);
	}

	public static function representingColumn() {
		return array('name');
	}

	public function rules() {
		return array(
			array('name, email, provider, date, gender, birth_date', 'required'),
			array('name, email, provider', 'length', 'max'=>100),
			array('blood_type', 'length', 'max'=>5),
			array('active, gender', 'length', 'max'=>1),
			array('image', 'length', 'max'=>120),
			array('image', 'file', 'types'=>'jpg,png', 'allowEmpty'=>true),
			array('image_delete', 'length', 'max'=>1),
			array('blood_type, active, image', 'default', 'setOnEmpty' => true, 'value' => null),
			array('iduser, name, email, provider, blood_type, active, date, gender, birth_date, image', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'annotations' => array(self::HAS_MANY, 'Annotation', 'iduser'),
			'consults' => array(self::HAS_MANY, 'Consults', 'iduser'),
			'diseases' => array(self::HAS_MANY, 'Disease', 'iduser'),
			'doctors' => array(self::HAS_MANY, 'Doctor', 'iduser'),
			'exams' => array(self::HAS_MANY, 'Exams', 'iduser'),
			'medicines' => array(self::HAS_MANY, 'Medicine', 'iduser'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'iduser' => Yii::t('app', 'Iduser'),
			'name' => Yii::t('app', 'Name'),
			'email' => Yii::t('app', 'Email'),
			'provider' => Yii::t('app', 'Provider'),
			'blood_type' => Yii::t('app', 'Blood Type'),
			'active' => Yii::t('app', 'Active'),
			'date' => Yii::t('app', 'Date'),
			'gender' => Yii::t('app', 'Gender'),
			'birth_date' => Yii::t('app', 'Birth Date'),
			'image' => Yii::t('app', 'Image'),
			'annotations' => null,
			'consults' => null,
			'diseases' => null,
			'doctors' => null,
			'exams' => null,
			'medicines' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('iduser', $this->iduser);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('provider', $this->provider, true);
		$criteria->compare('blood_type', $this->blood_type, true);
		$criteria->compare('active', $this->active, true);
		$criteria->compare('date', $this->date, true);
		$criteria->compare('gender', $this->gender, true);
		$criteria->compare('birth_date', $this->birth_date, true);
		$criteria->compare('image', $this->image, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}