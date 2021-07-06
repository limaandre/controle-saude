<?php

/**
 * This is the model base class for the table "medicine".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Medicine".
 *
 * Columns in table "medicine" available as properties of the model,
 * followed by relations of table "medicine" available as properties of the model.
 *
 * @property integer $idmedicine
 * @property string $name
 * @property string $concentration
 * @property string $dosage
 * @property string $medication_schedules
 * @property string $date_initial
 * @property string $date_end
 * @property string $prescription
 * @property string $note
 * @property string $notify
 * @property string $date
 * @property string $image
 * @property string $file
 * @property integer $iduser
 * @property integer $iddoctor
 *
 * @property Disease[] $diseases
 * @property Doctor $doctor
 * @property User $user
 */
abstract class BaseMedicine extends GxActiveRecord {
	
    
    public $image_delete;
	public $file_delete;
	    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'medicine';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Medicine|Medicines', $n);
	}

	public static function representingColumn() {
		return array('name');
	}

	public function rules() {
		return array(
			array('name, date, iduser', 'required'),
			array('iduser, iddoctor', 'numerical', 'integerOnly'=>true),
			array('name, concentration, dosage, prescription', 'length', 'max'=>100),
			array('notify', 'length', 'max'=>1),
			array('image', 'length', 'max'=>120),
			array('file', 'length', 'max'=>130),
			array('medication_schedules, date_initial, date_end, note', 'safe'),
			array('image', 'file', 'types'=>'jpg,png', 'allowEmpty'=>true),
			array('image_delete', 'length', 'max'=>1),
			// array('file', 'allowEmpty'=>true),
			// array('file_delete', 'length', 'max'=>1),
			array('concentration, dosage, medication_schedules, date_initial, date_end, prescription, note, notify, image, file, iddoctor', 'default', 'setOnEmpty' => true, 'value' => null),
			array('idmedicine, name, concentration, dosage, medication_schedules, date_initial, date_end, prescription, note, notify, date, image, file, iduser, iddoctor', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'diseases' => array(self::MANY_MANY, 'Disease', 'disease_medicine(idmedicine, iddisease)'),
			'doctor' => array(self::BELONGS_TO, 'Doctor', 'iddoctor'),
			'user' => array(self::BELONGS_TO, 'User', 'iduser'),
		);
	}

	public function pivotModels() {
		return array(
			'diseases' => 'DiseaseMedicine',
		);
	}

	public function attributeLabels() {
		return array(
			'idmedicine' => Yii::t('app', 'Idmedicine'),
			'name' => Yii::t('app', 'Name'),
			'concentration' => Yii::t('app', 'Concentration'),
			'dosage' => Yii::t('app', 'Dosage'),
			'medication_schedules' => Yii::t('app', 'Medication Schedules'),
			'date_initial' => Yii::t('app', 'Date Initial'),
			'date_end' => Yii::t('app', 'Date End'),
			'prescription' => Yii::t('app', 'Prescription'),
			'note' => Yii::t('app', 'Note'),
			'notify' => Yii::t('app', 'Notify'),
			'date' => Yii::t('app', 'Date'),
			'image' => Yii::t('app', 'Image'),
			'file' => Yii::t('app', 'File'),
			'iduser' => null,
			'iddoctor' => null,
			'diseases' => null,
			'doctor' => null,
			'user' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('idmedicine', $this->idmedicine);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('concentration', $this->concentration, true);
		$criteria->compare('dosage', $this->dosage, true);
		$criteria->compare('medication_schedules', $this->medication_schedules, true);
		$criteria->compare('date_initial', $this->date_initial, true);
		$criteria->compare('date_end', $this->date_end, true);
		$criteria->compare('prescription', $this->prescription, true);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('notify', $this->notify, true);
		$criteria->compare('date', $this->date, true);
		$criteria->compare('image', $this->image, true);
		$criteria->compare('file', $this->file, true);
		$criteria->compare('iduser', $this->iduser);
		$criteria->compare('iddoctor', $this->iddoctor);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}