<?php
/* @var $this ItemsController */
/* @var $model Items */
/* @var $form CActiveForm */
?>

<div class="form">
    

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'items-form',
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
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>30,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php 
         echo $form->dropDownList($model, 'type', array(1=>'Beli-Putus', 
            2=>'Konsinyasi'), array('empty'=>'Harap Pilih'));
      ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>40,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'brand'); ?>
            <?php
               //$brands=Yii::app()->db->createCommand()->selectDistinct('brand')->from('items')->queryColumn();

               $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                  'name'=>'Items[brand]',
                  'sourceUrl'=> Yii::app()->createUrl('LookUp/getBrand'),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->brand,
               ));
            ?>
		<?php //echo $form->textField($model,'brand',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'brand'); ?>
      </div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'picture'); ?>
		<?php 
                    //echo CHtml::hiddenField('MAX_FILE_SIZE', 100000);
                    echo $form->fileField($model,'picture', array()); 
                ?>
		<?php echo $form->error($model,'picture'); ?>
	</div>

	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
