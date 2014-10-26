<?php
/* @var $this DetailstockexitsController */
/* @var $model Detailstockexits */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailstockexits-form',
	'enableAjaxValidation'=>true,
   ));

$supplierScript=<<<EOS
	$('input,select').keypress(function(event) { return event.keyCode != 13; });

      $('#isAccepted').click(function() {
   		if ($('#isAccepted').prop('checked')) {
   			$('#Detailstockexits_serialnum').val('Belum Diterima');
   		}
      });
   		
	$('#Detailstockexits_serialnum').change(function() {
   		var myserialnum = $('#Detailstockexits_serialnum').val();
   		if (myserialnum !== 'Belum Diterima') {
   			$('#isAccepted').prop('checked', false);
   			$.getJSON('index.php?r=LookUp/checkSerial', 
   				{ serialnum: escape($('#Detailstockexits_serialnum').val()), 
   				idwh:$('#idwh').val() },
   				function(data) {
   				if ((data === false) || (data.avail == '0')) {
   					$('#status').removeClass('money');
   					$('#status').addClass('error');
   					$('#status').html('Tidak ditemukan');
   					$('#Detailstockexits_status').val('');
   				} else {
   					$('#Detailstockexits_status').val(data.status);
   					if (data.status == '1') {
   						$('#status').removeClass('error');
   						$('#status').addClass('money');
   						$('#status').html('Bagus');
   						$('#Detailstockexits_status').val('1');
   					} else if (data.status == '0') {
   						$('#status').removeClass('error');
   						$('#status').addClass('money');
   						$('#status').html('Rusak');
   						$('#Detailstockexits_status').val('0');
   					}
   				}
   			});
		};
	});
	
   	$('#myButton').click(
   		function(evt) {
   			var myserialnum = $('#Detailstockexits_serialnum').val();
   			if (myserialnum !== 'Belum Diterima') {
	   			$.getJSON('index.php?r=LookUp/checkItemSerial', { iditem: $('#Detailstockexits_iditem').val(), 
	   			serialnum: $('#Detailstockexits_serialnum').val(), idwh:$('#idwh').val() }, 
	   			function(data) {
	   				if (data=='0') {
	            		$('#Detailstockexits_serialnum_em_').html('Data tidak ditemukan');
						$('#Detailstockexits_serialnum_em_').prop('style', 'display:block');
					} else {
						$('#Detailstockexits_serialnum_em_').html('');
						$('#Detailstockexits_serialnum_em_').prop('style', 'display:none');
	   					$('#detailstockexits-form').submit();
	   				};
	   			});
   			} else {
   				$('#Detailstockexits_serialnum_em_').html('');
				$('#Detailstockexits_serialnum_em_').prop('style', 'display:none');
   				$('#detailstockexits-form').submit();
   			}
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
         echo $form->hiddenField($model,'iditem');
         echo $form->hiddenField($model,'status');
         echo CHtml::hiddenField('idwh',$idwh);
        ?>

	<div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php 
			echo CHtml::tag('span', array('id'=>'itemname'), lookup::ItemNameFromItemID($model->iditem), true);
        ?>
		<?php echo $form->error($model,'iditem'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'serialnum'); ?>
		<?php echo $form->textField($model,'serialnum'); ?>
		<?php echo $form->error($model,'serialnum'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Belum Diterima', false); ?>
		<?php 
			echo CHtml::checkBox('isAccepted', $model->serialnum == 'Belum Diterima'); 
		?>
	</div>
        
    <div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
			echo CHtml::tag('span', array('id'=>'status', 'class'=>'error'), $error);
		?>		
		<?php echo $form->error($model,'status'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::Button($mode, array('id'=>'myButton')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->