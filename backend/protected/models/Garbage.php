<?php

Yii::import('application.models._base.BaseGarbage');

class Garbage extends BaseGarbage
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	public function recuperar(){
		Yii::app()->db->createCommand($this->sql_insert)->execute();
	}
	
	public static function getHash(){
		return md5(time().rand(0,300000));
	}
}