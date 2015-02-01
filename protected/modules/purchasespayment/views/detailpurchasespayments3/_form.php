<?php
/* @var $this Detailpurchasespayments3Controller */
/* @var $model Detailpurchasespayments3 */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php

$mymethod = json_encode($model->method);
   
$paymentScript=<<<EOS
	function setDisplay(method)  {
		if (method == 'BG') {
			$("#bg").show();
			$("#transfer").hide();
		} else if (method == 'T') {
			$("#bg").hide();
			$("#transfer").show();
		} else if (method == 'C') {
			$("#bg").hide();
			$("#transfer").hide();
		}
	}

	$("#Payments_method").change(
		function(event) {
		var method = $("#Payments_method").val();		
		setDisplay(method)
	});
	
	setDisplay($mymethod);
EOS;
   Yii::app()->clientScript->registerScript("paymentScript", $paymentScript, CClientscript::POS_READY);

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
         echo $form->hiddenField($model,'idatetime');
         echo $form->hiddenField($model,'userlog');
         echo $form->hiddenField($model,'datetimelog');
        ?>

	
	<div class="row">
		<?php echo $form->labelEx($model,'method'); ?>
		<?php echo $form->dropDownList($model, 'method', array('C'=>'Tunai', 'BG'=>'BG/Cheque', 'T'=>'Transfer'),
			array('empty'=>'Harap Pilih')); ?>
		<?php echo $form->error($model,'method'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model, 'amount'); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>
	
	<div id="bg">
	<div class="row">
		<?php echo $form->labelEx($model,'bg_idbank'); ?>
		<?php 
			$data = Yii::app()->db->createCommand()
				->select('id, name')->from('salesposbanks')
				->queryAll();
			$data = CHtml::listData($data, 'id', 'name');
			echo $form->dropDownList($model, 'bg_idbank', $data, array('empty'=>'Harap Pilih')); 
		?>
		<?php echo $form->error($model,'bg_idbank'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'bg_num'); ?>
		<?php echo $form->textField($model, 'bg_num'); ?>
		<?php echo $form->error($model,'bg_num'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'bg_pubdate'); ?>
		<?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Payments[bg_pubdate]',
                     // additional javascript options for the date picker plugin
                  'options'=>array(
                     'showAnim'=>'fold',
                     'dateFormat'=>'yy/mm/dd',
                     'defaultdate'=>$model->bg_pubdate
                  ),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->bg_pubdate,
               ));
            ?>
		
		<?php echo $form->error($model,'bg_pubdate'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'bg_duedate'); ?>
		<?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Payments[bg_duedate]',
                     // additional javascript options for the date picker plugin
                  'options'=>array(
                     'showAnim'=>'fold',
                     'dateFormat'=>'yy/mm/dd',
                     'defaultdate'=>$model->bg_duedate
                  ),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->bg_duedate,
               ));
            ?>
		
		<?php echo $form->error($model,'bg_duedate'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'bg_receiver'); ?>
		<?php echo $form->textField($model, 'bg_receiver'); ?>
		<?php echo $form->error($model,'bg_receiver'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'bg_type'); ?>
		<?php echo $form->dropDownList($model, 'bg_type', array('0'=>'BG', '1'=>'Cheque'),
			array('empty'=>'Harap Pilih')); ?>
		<?php echo $form->error($model,'bg_type'); ?>
	</div>
	
	</div>
	
	<div id="transfer">
	
	<div class="row">
		<?php echo $form->labelEx($model,'tr_idbank'); ?>
		<?php 
			$data = Yii::app()->db->createCommand()
				->select('id,name')->from('salesposbanks')
				->queryAll();
			$data = CHtml::listData($data, 'id', 'name');
			echo $form->dropDownList($model, 'tr_idbank', $data, array('empty'=>'Harap Pilih')); 
		?>
		<?php echo $form->error($model,'tr_idbank'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'tr_receiver'); ?>
		<?php echo $form->textField($model, 'tr_receiver'); ?>
		<?php echo $form->error($model,'tr_receiver'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'tr_bank'); ?>
		<?php echo $form->textField($model, 'tr_bank'); ?>
		<?php echo $form->error($model,'tr_bank'); ?>
	</div>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->