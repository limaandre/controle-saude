<?='<?php
if(Yii::app()->user->obj->group->temPermissaoAction($this->id,\'index\')){
	$this->breadcrumbs[$model->label(2)] = array(\'index\');
}
else{
	$this->breadcrumbs[] = $model->label(2);
}
if($this->hasRel()){
	$this->breadcrumbs[$model->label(2)] = array(\'rel\'=>$this->getRel());
}
$this->breadcrumbs[] = Yii::t(\'app\',\'Atualizar\');
?>'?>

<div class="row-fluid">
  <div class="span12">
      <div class="w-box">
          <div class="w-box-header">
            <h4><?='<?=Yii::t(\'app\',\'Atualizar\');?>'?></h4>
          </div>
          <div class="w-box-content">
			<?='<?php
			$this->renderPartial(\'_form\', array(\'model\' => $model));
			?>'?>
		 </div>
      </div>
  </div>
</div>
