<?php
/* @var $this StickertoguidesController */
/* @var $model Stickertoguides */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php 
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'stickertoguides-form',
			'enableAjaxValidation'=>true,
		)); 

		$stickertoguideScript = <<<EOS
		$("#checksticker").click(
			function(event) {
				$.getJSON('index.php?r=LookUp/checkStickerInfo', 
					{ stickernum: $("#Stickertoguides_stickernum").val(),
					stickerdate: $("#Stickertoguides_stickerdate").val() },
						function(data) {
							switch (data) {
								case 0:	
									$("#stickeravail").removeClass('money');
									$("#stickeravail").addClass('errorMessage');
									$("#stickeravail").html('Data tidak ditemukan');
									$("#Stickertoguides_stickernum").val('');
									$("#Stickertoguides_stickerdate").val('');
									break;
								case 1:
									$("#stickeravail").removeClass('money');
									$("#stickeravail").addClass('errorMessage');
									$("#stickeravail").html('Data sudah didaftarkan');
									$("#Stickertoguides_stickernum").val('');
									$("#Stickertoguides_stickerdate").val('');
									break;
								case 2:
									$("#stickeravail").removeClass('errorMessage');
									$("#stickeravail").addClass('money');
									$("#stickeravail").html('Data valid');
									break;
							}
				});
		});
		
		$("#Stickertoguides_idguide").focusout(
			function(event) {
				$.getJSON("index.php?r=LookUp/getGuideName",
					{ id: $("#Stickertoguides_idguide").val() },
						function(data) {
							if (data == 0) {
								$("#guidename").removeClass('money');
								$("#guidename").addClass('errorMessage');
								$("#guidename").html('Data Guide tidak ditemukan');
								$("#Stickertoguides_idguide").val('');
							} else {
								$("#guidename").addClass('money');
								$("#guidename").removeClass('errorMessage');
								$("#guidename").html(data);
							}
				});
				
		});
		
EOS;
		Yii::app()->clientScript->registerScript("stickertoguideScript", $stickertoguideScript, 
			CClientscript::POS_READY);
	?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php 
       echo $form->hiddenField($model,'id');
       echo $form->hiddenField($model,'userlog');
       echo $form->hiddenField($model,'datetimelog');
	   echo $form->hiddenField($model,'regnum');
   	?>
   	
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
		<?php 
			//echo $form->textField($model,'idatetime',array('size'=>60,'maxlength'=>100)); 
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name'=>'Stickertoguides[idatetime]',
				// additional javascript options for the date picker plugin
				'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>'yy/mm/dd',
						'defaultdate'=>$model->idatetime
				),
				'htmlOptions'=>array(
						'style'=>'height:20px;',
				),
				'value'=>$model->idatetime,
			));
		?>
		<?php echo $form->error($model,'idatetime'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'stickernum'); ?>
		<?php echo $form->textField($model,'stickernum',array('size'=>20,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'stickernum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'stickerdate'); ?>
		<?php 
			//echo $form->textField($model,'stickerdate',array('size'=>60,'maxlength'=>100)); 
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name'=>'Stickertoguides[stickerdate]',
				// additional javascript options for the date picker plugin
				'options'=>array(
						'showAnim'=>'fold',
						'dateFormat'=>'yy/mm/dd',
						'defaultdate'=>$model->stickerdate
				),
				'htmlOptions'=>array(
						'style'=>'height:20px;',
				),
				'value'=>$model->stickerdate,
			));
		?>
		<?php echo $form->error($model,'stickerdate'); ?>
	</div>
	
	<div class="row">
		<?php 
			echo CHtml::label('', FALSE, ['id'=>'stickeravail']);
			echo CHtml::button('Periksa',['id'=>'checksticker']); 
		?>
	</div>

	<div class="row">
		<?php echo CHtml::label('', false);
			//echo CHtml::tag('span', array('id'=>'stickeravail', 'class'=>'money'), ''); 
		?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'idguide'); ?>
		<?php 
			//echo $form->textField($model, 'idguide');
			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'name'=>'Stickertoguides[idguide]',
				// additional javascript options for the date picker plugin
				'sourceUrl'=> Yii::app()->createUrl('LookUp/CompleteGuide'),
				'htmlOptions'=>array(
						'style'=>'height:20px;',
				),
				'value'=>$model->idguide,
			));
		?>
		<?php echo $form->error($model,'idguide'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('', false);
			echo CHtml::tag('span', array('id'=>'guidename', 'class'=>'money'), ''); 
		?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',
			['id'=>'submitbtn']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->