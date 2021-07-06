<?php

/**
 * This is the model base class for the table "annotation".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Annotation".
 *
 * Columns in table "annotation" available as properties of the model,
 * followed by relations of table "annotation" available as properties of the model.
 *
 * @property integer $idannotation
 * @property string $date
 * @property string $title
 * @property string $note
 * @property integer $iduser
 * @property string $image
 * @property string $file
 *
 * @property User $user
 */
abstract class BaseAnnotation extends GxActiveRecord {
	
    
    public $image_delete;
	public $file_delete;
	    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'annotation';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Annotation|Annotations', $n);
	}

	public static function representingColumn() {
		return array('date');
	}

	public function rules() {
		return array(
			array('date, title, note, iduser', 'required'),
			array('iduser', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('image', 'length', 'max'=>120),
			array('file', 'length', 'max'=>130),
			array('image', 'file', 'types'=>'jpg,png', 'allowEmpty'=>true),
			array('image_delete', 'length', 'max'=>1),
			// array('file', 'allowEmpty'=>true),
			// array('file_delete', 'length', 'max'=>1),
			array('image, file', 'default', 'setOnEmpty' => true, 'value' => null),
			array('idannotation, date, title, note, iduser, image, file', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'iduser'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'idannotation' => Yii::t('app', 'Idannotation'),
			'date' => Yii::t('app', 'Date'),
			'title' => Yii::t('app', 'Title'),
			'note' => Yii::t('app', 'Note'),
			'iduser' => null,
			'image' => Yii::t('app', 'Image'),
			'file' => Yii::t('app', 'File'),
			'user' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('idannotation', $this->idannotation);
		$criteria->compare('date', $this->date, true);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('iduser', $this->iduser);
		$criteria->compare('image', $this->image, true);
		$criteria->compare('file', $this->file, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}