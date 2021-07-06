<?php
/**
 * @var $this GalleryManager
 * @var $model GalleryPhoto
 *
 * @author Bogdan Savluk <savluk.bogdan@gmail.com>
 */
?>
<?php echo CHtml::openTag('div', $this->htmlOptions); ?>
    <!-- Gallery Toolbar -->
    <div class="btn-toolbar gform">
        <span class="btn btn-success fileinput-button">
            <i class="fa fa-plus fa fa-white"></i>
            <?php echo Yii::t('galleryManager.main', 'Add…');?>
            <input type="file" name="image" class="afile" accept="image/*" multiple/>
        </span>

        <div class="btn-group">
            <label class="btn">
                <input type="checkbox" style="margin: 0;" class="select_all"/>
                <?php echo Yii::t('galleryManager.main', 'Select all');?>
            </label>
            <span class="btn btn-default disabled edit_selected"><i class="fa fa-pencil"></i> <?php echo Yii::t('galleryManager.main', 'Edit');?></span>
            <span class="btn btn-default disabled remove_selected"><i class="fa fa-remove"></i> <?php echo Yii::t('galleryManager.main', 'Remove');?></span>
        </div>
    </div>
    <hr/>
    <!-- Gallery Photos -->
    <div class="sorter">
        <div class="images"></div>
        <br style="clear: both;"/>
    </div>

    <!-- Modal window to edit photo information -->
    
    <?php /*?><div class="modal hide editor-modal">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h3></h3>
        </div>
        <div class="modal-body">
            <div class="form"></div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-primary save-changes">
                <?php echo Yii::t('galleryManager.main', 'Save changes')?>
            </a>
            <a href="#" class="btn" data-dismiss="modal"><?php echo Yii::t('galleryManager.main', 'Close')?></a>
        </div>
    </div><?php */?>
    
    
    <!-- Modal -->
    <div class="modal fade editor-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo Yii::t('galleryManager.main', 'Edit information')?></h4>
          </div>
          <div class="modal-body">
            <div class="form"></div>
          </div>
          <div class="modal-footer">
          	<a href="#" class="btn btn-primary save-changes">
                <?php echo Yii::t('galleryManager.main', 'Save changes')?>
            </a>
            <a href="#" class="btn btn-default" data-dismiss="modal"><?php echo Yii::t('galleryManager.main', 'Close')?></a>
          </div>
        </div>
      </div>
    </div>
    
    
    <div class="overlay">
        <div class="overlay-bg">&nbsp;</div>
        <div class="drop-hint">
            <span class="drop-hint-info"><?php echo Yii::t('galleryManager.main', 'Drop Files Here…')?></span>
        </div>
    </div>
    <div class="progress-overlay">
        <div class="overlay-bg">&nbsp;</div>
        <!-- Upload Progress Modal-->
        <div class="modal progress-modal">
            <div class="modal-header">
                <h3><?php echo Yii::t('galleryManager.main', 'Uploading images…')?></h3>
            </div>
            <div class="modal-body">
                <div class="progress progress-striped active">
                    <div class="bar upload-progress"></div>
                </div>
            </div>
        </div>
    </div>
<?php echo CHtml::closeTag('div'); ?>