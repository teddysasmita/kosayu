<?php
/* @var $this DetailidguideprintsController */
/* @var $model Detailidguideprints */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailidguideprints-form',
	'enableAjaxValidation'=>true,
   ));

$supplierScript=<<<EOS
      $('#isAccepted').click(function() {
   		if ($('#isAccepted').prop('checked')) {
   			$('#Detailidguideprints_serialnum').val('Belum Diterima');
   		}
      });
	$("#idguideprints_idguide").focusout(
			function(event) {
				$.getJSON("index.php?r=LookUp/getGuideName",
					{ id: $("#Idguideprints_idguide").val() },
						function(data) {
							if (data == 0) {
								$("#guidename").removeClass('money');
								$("#guidename").addClass('errorMessage');
								$("#guidename").html('Data Guide tidak ditemukan');
								$("#Guidepayments_idguide").val('');
							} else {
								$("#guidename").addClass('money');
								$("#guidename").removeClass('errorMessage');
								$("#guidename").html(data);
							}
				});
				
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
		<?php echo $form->labelEx($model,'idguide'); ?>
		<?php 
			//echo $form->textField($model, 'idguide');
			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'name'=>'Detailidguideprints[idguide]',
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
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->