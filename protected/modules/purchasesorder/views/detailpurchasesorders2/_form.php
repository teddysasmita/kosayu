<?php
/* @var $this Detailpurchasesorders2Controller */
/* @var $model Detailpurchasesorders2 */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
 
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailpurchasesorders2-form',
	'enableAjaxValidation'=>true,
   ));
 ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
        <?php 
         echo $form->hiddenField($model,'iddetail');
         echo $form->hiddenField($model,'id');
         echo $form->hiddenField($model,'userlog');
         echo $form->hiddenField($model,'datetimelog');
        ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'vouchername'); ?>
		<?php echo $form->textField($model,'vouchername',array('size'=>50,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'vouchername'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vouchervalue'); ?>
		<?php echo $form->textField($model,'vouchervalue'); ?>
		<?php echo $form->error($model,'vouchervalue'); ?>
	</div>

        
	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->