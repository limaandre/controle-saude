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
$this->breadcrumbs[] = Yii::t(\'app\',\'Visualizar\');
?>'?>
<div class="row-fluid">
  <div class="span12">
      <div class="w-box">
          <div class="w-box-header">
            <h4>Visualizar</h4>
          </div>
          <div class="w-box-content">
          
		  <?='<? 
          $this->renderPartial("//layouts/sucesso",array(
              \'success\' => $_GET[\'success\'],
          ));
          ?>'?>
      
<?php foreach ($this->tableSchema->columns as $column): ?>
<?php if (!$column->autoIncrement): ?>
        <div class="formSep">
          <dl class="dl-horizontal">
            <dt><?='<?=Util::formataTexto($model->getAttributeLabel(\''.$column->name.'\'));?>'?></dt>
            <dd><?=$this->generateDetailView($this->modelClass, $column);?></dd>
          </dl>
        </div>
<?php endif; ?>
<?php endforeach; ?>
     
     
<?php foreach (GxActiveRecord::model($this->modelClass)->relations() as $relationName => $relation): ?>
<?php if ($relation[0] == GxActiveRecord::HAS_MANY || $relation[0] == GxActiveRecord::MANY_MANY): ?>

<?php echo "	<?\n";?>
	if(Yii::app()->user->obj->group->temPermissaoAction('<?=strtolower($relation[1]);?>','index')){
        <?php echo "?>\n"; ?>
        <div class="formSep">
            <dl class="dl-horizontal">
                <dt><?php echo '<?php'; ?> echo GxHtml::encode($model->getRelationLabel('<?php echo $relationName; ?>')); ?></dt>
                <dd>
                <?php echo "<?php\n"; ?>
                if(count($model-><?php echo $relationName; ?>) > 0){
                            echo GxHtml::openTag('ul');
                    foreach($model-><?php echo $relationName; ?> as $relatedModel) {
                        echo GxHtml::openTag('li');
                        echo GxHtml::link(GxHtml::encode(GxHtml::valueEx($relatedModel)), array('<?php echo strtolower($relation[1][0]) . substr($relation[1], 1); ?>/view', 'id' => GxActiveRecord::extractPkValue($relatedModel, true)));
                        echo GxHtml::closeTag('li');
                    }
                    echo GxHtml::closeTag('ul');
                }
                else{
                    echo '<i>Nenhum registro encontrado</i>';
                }
                <?php echo "?>\n"; ?>
                </dd>
            </dl>
        </div>
<?php echo "		<?\n"; ?>
    }
<?php echo '	?>'; ?>
<?php endif; ?>

<?php endforeach; ?>
     
     
         <div class="formSep">
              <dl class="dl-horizontal">
                  <dt>&nbsp;</dt>
                  <dd>
                  	<?='<?
                    if(Yii::app()->user->obj->group->temPermissaoAction($this->id,\'update\')){
                        ?>
                        <a class="btn" href="<?php echo $this->createUrlRel(\'update\',array(\'id\'=>$model->'.$this->tableSchema->primaryKey.'));?>"><i class="icon-edit "></i> Editar</a>
                        <?
                    }
                    ?>          
                    <?
                    if(Yii::app()->user->obj->group->temPermissaoAction($this->id,\'index\')){
                        ?>
                        <a class="btn" href="<?php echo $this->createUrlRel(\'index\');?>"><i class="icon-chevron-left"></i> Voltar</a>
                        <?
                    }
                    ?>
                    <?
                    if(Yii::app()->user->obj->group->temPermissaoAction($this->id,\'delete\')){
                        ?>
                        <a class="btn btn-delete" href="<?php echo $this->createUrlRel(\'delete\',array(\'id\'=>$model->'.$this->tableSchema->primaryKey.'));?>" style="margin-left:30px;"><i class="icon-trash"></i> Excluir</a>
                        <?
                    }
                    ?>'?>           
                  </dd>
               </dl>
           </div>
          
		</div>
      </div>
  </div>
</div>