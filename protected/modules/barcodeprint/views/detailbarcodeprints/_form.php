<?php
/* @var $this DetailbarcodeprintsController */
/* @var $model Detailbarcodeprints */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailbarcodeprints-form',
	'enableAjaxValidation'=>true,
   ));

$supplierScript=<<<EOS
      $('#isAccepted').click(function() {
   		if ($('#isAccepted').prop('checked')) {
   			$('#Detailbarcodeprints_serialnum').val('Belum Diterima');
   		}
      });
	$('#Detailbarcodeprints_serialnum').change(function() {
   		var myserialnum = $('#Detailbarcodeprints_serialnum').val();
   		if (myserialnum !== 'Belum Diterima')
   			$('#isAccepted').prop('checked', false);
	});
EOS;
   Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);
   
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
		<?php echo $form->labelEx($model,'num'); ?>
		<?php echo $form->textField($model, 'num')?>
		<?php echo $form->error($model,'num'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->