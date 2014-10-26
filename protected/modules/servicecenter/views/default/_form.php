<?php
/* @var $this ServicecentersController */
/* @var $model Servicecenters */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'servicecenters-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php
            echo $form->hiddenfield($model, 'id');
            echo $form->hiddenfield($model, 'userlog');
            echo $form->hiddenfield($model, 'datetimelog');
        ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'brandname'); ?>
            <?php
               //$brandnames=Yii::app()->db->createCommand()->selectDistinct('brandname')->from('items')->queryColumn();

               $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                  'name'=>'Servicecenters[brandname]',
                  'sourceUrl'=> Yii::app()->createUrl('LookUp/getBrand'),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->brandname,
               ));
            ?>
		<?php //echo $form->textField($model,'brandname',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'brandname'); ?>
      </div>
      
	<div class="row">
		<?php echo $form->labelEx($model,'companyname'); ?>
		<?php echo $form->textField($model,'companyname',array('size'=>50,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'companyname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>50,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>50,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->