<?php
/* @var $this DetailacquisitionsController */
/* @var $model Detailacquisitions */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailacquisitions-form',
	'enableAjaxValidation'=>true,
   ));

$supplierScript=<<<EOS
	$('input,select').keypress(function(event) { return event.keyCode != 13; });

      $('#isAccepted').click(function() {
   		if ($('#isAccepted').prop('checked')) {
   			$('#Detailacquisitions_serialnum').val('Belum Diterima');
   		}
      });
   		
	$('#Detailacquisitions_serialnum').change(function() {
   		var myserialnum = $('#Detailacquisitions_serialnum').val();
   		if (myserialnum !== 'Belum Diterima') {
   			$('#isAccepted').prop('checked', false);
   			$.getJSON('index.php?r=LookUp/checkSerial', { serialnum: $('#Detailacquisitions_serialnum').val(), 
   				idwh:$('#idwh').val() },
   				function(data) {
   				if (data == false) {
   					$('#avail').removeClass('money');
   					$('#avail').addClass('error');
   					$('#avail').html('Tidak ditemukan');
   				} else {
   					$('#Detailacquisitions_avail').val(data.avail);
   					if (data.avail = '1') {
   						$('#avail').removeClass('error');
   						$('#avail').addClass('money');
   						$('#avail').html('Tersedia');
   					} else if (data.avail = '2') {
   						$('#avail').removeClass('error');
   						$('#avail').addClass('money');
   						$('#avail').html('Rusak');
   					}
   				}
   			});
		};
	});
	
   	$('#myButton').click(
   		function(evt) {
   			var myserialnum = $('#Detailacquisitions_serialnum').val();
   			if (myserialnum !== 'Belum Diterima') {
	   			$.getJSON('index.php?r=LookUp/checkItemSerial', { iditem: $('#Detailacquisitions_iditem').val(), 
	   			serialnum: $('#Detailacquisitions_serialnum').val(), idwh:$('#idwh').val() }, 
	   			function(data) {
	   				if (data=='0') {
	            		$('#Detailacquisitions_serialnum_em_').html('Data tidak ditemukan');
						$('#Detailacquisitions_serialnum_em_').prop('style', 'display:block');
					} else {
						$('#Detailacquisitions_serialnum_em_').html('');
						$('#Detailacquisitions_serialnum_em_').prop('style', 'display:none');
	   					$('#detailacquisitions-form').submit();
	   				};
	   			});
   			} else {
   				$('#Detailacquisitions_serialnum_em_').html('');
				$('#Detailacquisitions_serialnum_em_').prop('style', 'display:none');
   				$('#detailacquisitions-form').submit();
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
        ?>

	<div class="row">
		<?php echo $form->labelEx($model,'serialnum'); ?>
		<?php echo $form->textField($model,'serialnum'); ?>
		<?php echo $form->error($model,'serialnum'); ?>
	</div>
	
	<div class="row">
		<?php
		echo $form->labelEx($model, 'avail');
		echo $form->dropDownList($model, 'avail', array('Semua'=>'Semua', '1'=>'Tersedia', '2'=>'Rusak'),
			array('empty'=>'Harap Pilih'));
		?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($mode, array('id'=>'myButton')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->