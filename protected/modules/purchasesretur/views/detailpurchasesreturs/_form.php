<?php
/* @var $this DetailpurchasesretursController */
/* @var $model Detailpurchasesreturs */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailpurchasesreturs-form',
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
         echo $form->hiddenField($model,'iditem');
         echo $form->hiddenField($model, 'prevprice');
         echo $form->hiddenField($model, 'prevcost1');
         echo $form->hiddenField($model, 'prevcost2');
        ?>

	<div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php echo CHtml::label(lookup::ItemNameFromItemID($model->iditem), false); ?>
		<?php echo $form->error($model,'iditem'); ?>
	</div>
      
   <div class="row">
		<?php echo $form->labelEx($model,'qty'); ?>
		<?php echo $form->textField($model, 'qty'); ?>
		<?php echo $form->error($model,'qty'); ?>
	</div>
      
   <div class="row">
		<?php echo $form->labelEx($model,'prevprice'); ?>
		<?php echo CHtml::label(number_format($model->prevprice), false); ?>
		<?php echo $form->error($model,'prevprice'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price'); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'discount'); ?>
		<?php echo $form->textField($model,'discount'); ?>
		<?php echo $form->error($model,'discount'); ?>
	</div>
   
   <div class="row">
		<?php echo $form->labelEx($model,'prevcost1'); ?>
		<?php echo CHtml::label(number_format($model->prevcost1), false); ?>
		<?php echo $form->error($model,'prevcost1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cost1'); ?>
		<?php echo $form->textField($model,'cost1'); ?>
		<?php echo $form->error($model,'cost1'); ?>
	</div>
   
   <div class="row">
		<?php echo $form->labelEx($model,'prevcost2'); ?>
		<?php echo CHtml::label(number_format($model->prevcost2), false); ?>
		<?php echo $form->error($model,'prevcost2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cost2'); ?>
		<?php echo $form->textField($model,'cost2'); ?>
		<?php echo $form->error($model,'cost2'); ?>
	</div>
        
	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->