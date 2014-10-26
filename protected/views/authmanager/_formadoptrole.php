   <?php
/* @var $this AuthItemController */
/* @var $model AuthItem */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'auth-item-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
      <?php
         echo $form->hiddenfield($model, 'parent', array('value'=>$parent));
      ?>
      
	<div class="row">
		<?php echo $form->labelEx($model,'Select Role'); ?>
		<?php 
               $res=Yii::app()->db->createCommand('select name, type, description from AuthItem '.
                  'where type=2')->queryAll();
               $datas=CHtml::listData($res,'name', 'description');
               echo $form->listBox($model,'child',$datas, array()); 
            ?>
		<?php echo $form->error($model,'child'); ?>
	</div>

      
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->