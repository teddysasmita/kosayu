<?php
/* @var $this DetailstockentriesController */
/* @var $model Detailstockentries */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailstockentries-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

      <?php
         echo $form->hiddenField($model,'iddetail');
         echo $form->hiddenField($model,'id');
      ?>

	<div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php 
             $res=Yii::app()->db->createCommand('select id, name from items '.
                  'where type=1')->queryAll();
             $datas=CHtml::listData($res,'id', 'name');
             echo $form->dropDownList($model,'iditem',$datas, array()); 
            ?>
		<?php echo $form->error($model,'iditem'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'qty'); ?>
		<?php echo $form->textField($model,'qty'); ?>
		<?php echo $form->error($model,'qty'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->