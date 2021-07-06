<tr>
  <?='<?
  foreach($this->getRepresentingFields() as $field){
  	?>'?>
	<td><?='<?= Util::formataTexto($data->$field);?>'?></td>
	<?='<?
  }
  ?>'?>
  <td style="text-align:right;">
    <?='<?
	if(Yii::app()->user->obj->group->temPermissaoAction($this->id,\'view\')){
        ?>'?>
        <span><a class="btn " href="<?='<?php echo $this->createUrlRel(\'view\',array(\'id\'=>$data->'.$this->tableSchema->primaryKey.'));?>'?>" title="Visualizar"> <i class="icon-zoom-in"></i> </a></span>
    	<?='<?
    }
    if(Yii::app()->user->obj->group->temPermissaoAction($this->id,\'update\')){
        ?>'?>
        <span><a class="btn " href="<?='<?php echo $this->createUrlRel(\'update\',array(\'id\'=>$data->'.$this->tableSchema->primaryKey.'));?>'?>" title="Editar"> <i class="icon-edit "></i> </a></span>
        <?='<?
    }
    if(Yii::app()->user->obj->group->temPermissaoAction($this->id,\'delete\')){
        ?>'?>
        <span><a class="btn btn-delete" href="<?='<?php echo $this->createUrlRel(\'delete\',array(\'id\'=>$data->'.$this->tableSchema->primaryKey.'));?>'?>" title="Excluir"> <i class="icon-trash"></i> </a></span>
        <?='<?
    }
    ?>'?>
  </td>
</tr>