<?php
/* @var $this ItembatchController */
/* @var $model Itembatch */
/* @var $form CActiveForm */
?>

<div class="form">
    

<?php 

$itemScript=<<<EOS
	$('#Itembatch_batchcode').change(function(){
		$('#command').val('setCode');
		$('#itembatch-form').submit();
	});
	$('#Itembatch_iditem').change(
		function() {
			$.getJSON('index.php?r=LookUp/getItemName2',{ id: $('#Itembatch_iditem').val() },
               	function(data) {
				 	$('#itemname').html(data);
               })
	});
EOS;
Yii::app()->clientScript->registerScript('itemscript', $itemScript, CClientScript::POS_READY);

	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'itembatch-form',
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
		echo CHtml::hiddenField('command');
    ?>

    <div class="row">
		<?php echo $form->labelEx($model,'batchcode'); ?>
		<?php echo $form->textField($model,'batchcode',array('size'=>30,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'batchcode'); ?>
	</div>
	
    <div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
            <?php
               //$brands=Yii::app()->db->createCommand()->selectDistinct('brand')->from('itembatch')->queryColumn();

               $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                  'name'=>'Itembatch[iditem]',
                  'sourceUrl'=> Yii::app()->createUrl('LookUp/getItem2'),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->iditem,
               ));
            ?>
		<?php //echo $form->textField($model,'brand',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'iditem'); ?>
      </div>
      
    <div class="row">
		<?php echo CHtml::label('',''); ?>
		<?php echo CHtml::tag('span',array('id'=>'itemname', 'class'=>'money'), 
			lookup::ItemNameFromItemID($model->iditem)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'buyprice'); ?>
		<?php echo $form->textField($model,'buyprice'); ?>
		<?php echo $form->error($model,'buyprice'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'sellprice'); ?>
		<?php echo $form->textField($model,'sellprice'); ?>
		<?php echo $form->error($model,'sellprice'); ?>
	</div>
	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
