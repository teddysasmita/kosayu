<?php
/* @var $this ItembatchController */
/* @var $model Itembatch */
/* @var $form CActiveForm */
?>

<div class="form">
    

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'itembatch-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php
		echo $form->hiddenField($model, 'id');
		echo $form->hiddenField($model, 'userlog');
		echo $form->hiddenField($model, 'datetimelog');
    ?>

    <div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
            <?php
               //$brands=Yii::app()->db->createCommand()->selectDistinct('brand')->from('itembatch')->queryColumn();

               $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                  'name'=>'Itembatch[iditem]',
                  'sourceUrl'=> Yii::app()->createUrl('LookUp/getItemAll'),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->iditem,
               ));
            ?>
		<?php //echo $form->textField($model,'brand',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'iditem'); ?>
      </div>
      
	<div class="row">
		<?php echo $form->labelEx($model,'batch'); ?>
		<?php echo $form->textField($model,'batchcode',array('size'=>30,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'batchcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'buyprice'); ?>
		<?php echo $form->textField($model,'buyprice'); ?>
		<?php echo $form->error($model,'buyprice'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'baseprice'); ?>
		<?php echo $form->textField($model,'baseprice'); ?>
		<?php echo $form->error($model,'baseprice'); ?>
	</div>
	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
