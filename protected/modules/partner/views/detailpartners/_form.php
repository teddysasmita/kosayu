<?php
/* @var $this DetailpartnersController */
/* @var $model Detailpartners */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailpartners-form',
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
		<?php echo $form->labelEx($model,'comname'); ?>
		<?php 
           echo $form->textField($model, 'comname'); 
        ?>
		<?php echo $form->error($model,'comname'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'tip'); ?>
		<?php 
           echo $form->textField($model, 'tip'); 
        ?>
		<?php echo $form->error($model,'tip'); ?>
	</div>
	

	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->