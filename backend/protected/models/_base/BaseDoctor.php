<?php

/**
 * This is the model base class for the table "doctor".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Doctor".
 *
 * Columns in table "doctor" available as properties of the model,
 * followed by relations of table "doctor" available as properties of the model.
 *
 * @property integer $iddoctor
 * @property string $name
 * @property string $medical_specialization
 * @property string $address
 * @property string $phone_number
 * @property string $email
 * @property string $image
 * @property string $file
 * @property string $date
 * @property integer $iduser
 *
 * @property Consults[] $consults
 * @property User $user
 * @property Exams[] $exams
 * @property Medicine[] $medicines
 */
abstract class BaseDoctor extends GxActiveRecord {
	
    
    public $image_delete;
	public $file_delete;
	    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'doctor';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Doctor|Doctors', $n);
	}

	public static function representingColumn() {
		return array('name');
	}

	public function rules() {
		return array(
			array('name, date, iduser', 'required'),
			array('iduser', 'numerical', 'integerOnly'=>true),
			array('name, medical_specialization, email', 'length', 'max'=>100),
			array('address', 'length', 'max'=>200),
			array('phone_number', 'length', 'max'=>20),
			array('image', 'length', 'max'=>120),
			array('file', 'length', 'max'=>130),
			array('image', 'file', 'types'=>'jpg,png', 'allowEmpty'=>true),
			array('image_delete', 'length', 'max'=>1),
			// array('file', 'allowEmpty'=>true),
			// array('file_delete', 'length', 'max'=>1),
			array('medical_specialization, address, phone_number, email, image, file', 'default', 'setOnEmpty' => true, 'value' => null),
			array('iddoctor, name, medical_specialization, address, phone_number, email, image, file, date, iduser', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'consults' => array(self::HAS_MANY, 'Consults', 'iddoctor'),
			'user' => array(self::BELONGS_TO, 'User', 'iduser'),
			'exams' => array(self::HAS_MANY, 'Exams', 'iddoctor'),
			'medicines' => array(self::HAS_MANY, 'Medicine', 'iddoctor'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'iddoctor' => Yii::t('app', 'Iddoctor'),
			'name' => Yii::t('app', 'Name'),
			'medical_specialization' => Yii::t('app', 'Medical Specialization'),
			'address' => Yii::t('app', 'Address'),
			'phone_number' => Yii::t('app', 'Phone Number'),
			'email' => Yii::t('app', 'Email'),
			'image' => Yii::t('app', 'Image'),
			'file' => Yii::t('app', 'File'),
			'date' => Yii::t('app', 'Date'),
			'iduser' => null,
			'consults' => null,
			'user' => null,
			'exams' => null,
			'medicines' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('iddoctor', $this->iddoctor);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('medical_specialization', $this->medical_specialization, true);
		$criteria->compare('address', $this->address, true);
		$criteria->compare('phone_number', $this->phone_number, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('image', $this->image, true);
		$criteria->compare('file', $this->file, true);
		$criteria->compare('date', $this->date, true);
		$criteria->compare('iduser', $this->iduser);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}