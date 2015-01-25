<?php
/* @var $this DetailpurchasespaymentsController */
/* @var $model Detailpurchasespayments */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailpurchasespayments-form',
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
         echo $form->hiddenField($model,'idpurchase');
         echo $form->hiddenField($model,'total');
         echo $form->hiddenField($model,'discount');
         echo $form->hiddenField($model,'paid');
         echo $form->hiddenField($model,'labelcost');
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
		<?php echo $form->labelEx($model,'labelcost'); ?>
		<?php echo CHtml::label(number_format($model->labelcost), false); ?>
		<?php echo $form->error($model,'labelcost'); ?>
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