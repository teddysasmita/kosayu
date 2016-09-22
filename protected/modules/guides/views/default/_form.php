<?php
/* @var $this GuidesController */
/* @var $model Guides */
/* @var $form CActiveForm */
?>

<div class="form">

<?php 
	$transScript = <<<EOS
	$("#Guides_idpartner").focusout(
			function(event) {
				$.getJSON("index.php?r=LookUp/getPartnerName2",
					{ id: $("#Guides_idpartner").val(), formname: "Guides" },
						function(data) {
							if (data == 0) {
								$("#partnername").removeClass('money');
								$("#partnername").addClass('errorMessage');
								$("#partnername").html('Data Partner tidak ditemukan');
								$("#Guides_idpartner").val('');
							} else {
								$("#partnername").addClass('money');
								$("#partnername").removeClass('errorMessage');
								$("#partnername").html(data);
								$.getJSON("index.php?r=LookUp/getPartnerComp",
								{ idpartner: $("#Guides_idpartner").val()},
								function (data) {
									$("#idcomp").html(data);
								});
							}
				});
				
		});
EOS;
	Yii::app()->clientScript->registerScript("transScript", $transScript, CClientscript::POS_READY);
	
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customers-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php 
       echo $form->hiddenField($model,'id');
       echo $form->hiddenField($model,'userlog');
       echo $form->hiddenField($model,'datetimelog');
             
   ?>
	<div class="row">
		<?php echo $form->labelEx($model,'firstname'); ?>
		<?php echo $form->textField($model,'firstname',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'firstname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lastname'); ?>
		<?php echo $form->textField($model,'lastname',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'lastname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idnum'); ?>
		<?php echo $form->textField($model,'idnum',array('size'=>60,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'idnum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idpartner'); ?>
         <?php 
         	$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
				'name'=>'Guides[idpartner]',
				'sourceUrl'=>Yii::app()->createUrl('LookUp/getPartner'),
				'htmlOptions'=>array('size'=>35, 
						'id'=>'Guides_idpartner'					
	         	),
				'value'=>$model->idpartner
			));
         ?>
		<?php echo $form->error($model,'idpartner'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('', false);
			echo CHtml::tag('span', array('id'=>'partnername', 'class'=>'money'), ''); 
		?>
	</div>
	
	<div class="row" id="idcomp">
	</div>
	
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->