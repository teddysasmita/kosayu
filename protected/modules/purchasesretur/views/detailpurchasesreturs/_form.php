<?php
/* @var $this DetailpurchasesretursController */
/* @var $model Detailpurchasesreturs */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
$itemScript=<<<EOS
	$('#Detailpurchasesreturs_batchcode').change(function(){
		$('#command').val('setCode');
		$('#detailpurchasesreturs-form').submit();
	});
EOS;
Yii::app()->clientScript->registerScript('itemscript', $itemScript, CClientScript::POS_READY);

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
         echo $form->hiddenField($model,'price');
         echo $form->hiddenField($model,'discount');
         echo CHtml::hiddenField('command');
        ?>
    <div class="row">
		<?php echo $form->labelEx($model,'batchcode'); ?>
		<?php echo $form->textField($model,'batchcode'); ?>
		<?php echo $form->error($model,'batchcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php echo CHtml::tag('span', array('id'=>"itemname"),
				lookup::ItemNameFromItemID($model->iditem)); ?>
		<?php echo $form->error($model,'iditem'); ?>
	</div>
      
   <div class="row">
		<?php echo $form->labelEx($model,'qty'); ?>
		<?php echo $form->textField($model, 'qty'); ?>
		<?php echo $form->error($model,'qty'); ?>
	</div>
      
	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo CHtml::tag('span', array('id'=>"price"),
				$model->price); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'discount'); ?>
		<?php echo CHtml::tag('span', array('id'=>"discount"),
				$model->discount); ?>
		<?php echo $form->error($model,'discount'); ?>
	</div>
   
	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->