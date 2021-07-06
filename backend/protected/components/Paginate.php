<?
class Paginate {	
	public static function generate($page, $model, $primary_key, $condition = null) {
		if (!$page) {
            $page = 1;
        }
        $criteria = new CDbCriteria;
        $criteria->addCondition($condition);
        $criteria->order = $primary_key . ' desc';
        $dataProvider = new CActiveDataProvider($model, array(
            'criteria'=>$criteria,           
			'pagination' => array(
				'pageSize'=> 10,
                'pageVar' => 'pagina',
			),
    	));

        $pagination = $dataProvider->pagination;
        $data = $dataProvider->getData();
        return array(
            strtolower($model) => $data,
            'pagination' => array(
                'total_paginas' => (int)$pagination->pageCount,
                'pagina' => (int)$page
            ),
        );
	}
}