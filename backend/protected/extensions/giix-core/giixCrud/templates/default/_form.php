<div class="form">
	
	<?='<?php $form = $this->beginWidget(\'GxActiveForm\', array(
        \'id\' => \''.$this->class2id($this->modelClass).'-form\',
        \'enableAjaxValidation\' => false,
        \'htmlOptions\'=> array (
            \'class\' => \'form-horizontal\',
            \'enctype\' => \'multipart/form-data\',
			\'action\' => $this->createUrlRel($this->action->id),
        )
    ));
    ?>'?>
 

	<?='<? 
    $this->renderPartial("//layouts/erros",array(
        \'model\' => $model,
    ));
    ?>
    '?>
    
    <? 
	Yii::import('system.gii.generators.model.ModelCode'); 
	$model_code = new ModelCode();
	?>
    <?php foreach ($this->tableSchema->columns as $column): ?>
	<?php if (!$column->autoIncrement): ?>
    
    <div class="formSep">
        <dl class="dl-horizontal">
          <dt><?php echo "<?php echo " . $this->generateActiveLabel($this->modelClass, $column) . "; ?>\n"; ?></dt>
          <dd>
		  	<?php echo "<?php " . $this->generateActiveField($this->modelClass, $column) . "; ?>"; ?>
         <?php if ($column->size == 120): ?>
         
         <br/><img style="margin-top:10px;" class="img-polaroid" src="<?='<?php echo Yii::app()->request->baseUrl; ?>/<?=$model->'. $model_code->generateClassName($column->name).'->getAttachment(\'p\');?>'?>" />
		<?='<?
		if(!empty($model->'.$column->name.')){
            ?>
            <div style="margin-top:10px;"><label class="checkbox" for="<?=get_class($model)?>_'.$column->name.'_delete"><?php echo $form->checkbox($model,\''.$column->name.'_delete\'); ?> Excluir imagem</label></div>
            <?
        }?>'?> 
        <?php endif; ?>
        
		 <?php if ($column->size == 130): ?>
         
         <br/><a style="margin-top:10px;" target="_blank" class="btn-link" href="<?='<?php echo Yii::app()->request->baseUrl; ?>/<?=$model->'.$column->name.';?>'?>" ><?='<?=$model->'.$column->name.';?>'?></a>
		 <?='<?
		if(!empty($model->'.$column->name.')){
            ?>
            <div style="margin-top:10px;"><label class="checkbox" for="<?=get_class($model)?>_'.$column->name.'_delete"><?php echo $form->checkbox($model,\''.$column->name.'_delete\'); ?> Excluir arquivo</label></div>
            <?
        }?>'?> 
		 <?php endif; ?>	 
      	</dd>
       </dl>
    </div>
    <!-- row -->
    <?php endif; ?>
    <?php endforeach; ?>
    
    
   
   <div class="formSep">
      <dl class="dl-horizontal">
          <dt>&nbsp;</dt>
          <dd>
          <?='
          <button type="submit" class="btn">
            <?
            if($this->action->id == \'create\'){
                ?>
                <i class="icon-plus"></i>&nbsp;Cadastrar
                <?
            }
            else{
                ?>
                <i class="icon-pencil"></i>&nbsp;Atualizar
                <?
            }
            ?>
          </button>
			<?
            if(Yii::app()->user->obj->group->temPermissaoAction($this->id,\'index\')){
                ?>
                <a class="btn" href="<?php echo $this->createUrlRel(\'index\');?>"><i class="icon-chevron-left"></i> Voltar</a>
                <?
            }
            ?>
            <?
            if($this->action->id == \'update\' && Yii::app()->user->obj->group->temPermissaoAction($this->id,\'delete\')){
                ?>
                <a class="btn btn-delete" href="<?php echo $this->createUrlRel(\'delete\',array(\'id\'=>$model->'.$this->tableSchema->primaryKey.'));?>" style="margin-left:30px;"><i class="icon-trash"></i> Excluir</a>
                <?
            }
            ?>'?>  
        </dd>
       </dl>
   </div>
   
    
    <?='<? 
	$this->endWidget(); 
	?>
	
'?>
</div>
<!-- form -->