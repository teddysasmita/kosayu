<?php
/* @var $this Detailpurchasespayments3Controller */
/* @var $model Detailpurchasespayments3 */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailpurchasespayments3-form',
	'enableAjaxValidation'=>true,
   ));
 ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
        <?php 
         echo $form->hiddenField($model,'id');
         echo $form->hiddenField($model,'idtransaction');
         echo $form->hiddenField($model,'userlog');
         echo $form->hiddenField($model,'datetimelog');
        ?>

	<div class="row">
		<?php echo $form->labelEx($model,'idpurchase'); ?>
		<?php echo CHtml::label(lookup::PurchasesNumFromID($model->idpurchase), false); ?>
		<?php echo $form->error($model,'idpurchas'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'total'); ?>
		<?php echo CHtml::label(number_format($model->total), false); ?>
		<?php echo $form->error($model,'total'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'discount'); ?>
		<?php echo CHtml::label(number_format($model->discount), false); ?>
		<?php echo $form->error($model,'discount'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'paid'); ?>
		<?php echo CHtml::label($model->paid, false); ?>
		<?php echo $form->error($model,'paid'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model, 'amount'); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->