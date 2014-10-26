<?php
/* @var $this DetailstockentriesController */
/* @var $model Detailstockentries */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailstockentries-form',
	'enableAjaxValidation'=>true,
   ));

$supplierScript=<<<EOS
   		
      $('#isAccepted').click(function() {
   		if ($('#isAccepted').prop('checked')) {
   			$('#Detailstockentries_serialnum').val('Belum Diterima');
   		}
      });
	$('#Detailstockentries_serialnum').change(function() {
   		var myserialnum = $('#Detailstockentries_serialnum').val();
   		if (myserialnum !== 'Belum Diterima') {
   			$('#isAccepted').prop('checked', false);
   			
   		
			if( $('#transname').val() == 'AC18') {
				$.getJSON('index.php?r=LookUp/checkSerial', 
					{'serialnum': escape(myserialnum), 
   					'idwh' : $('#idwhsource').val()},
   					function(data) {
   						if (data !== false) {
   							var message;
   							$('#Detailstockentries_status').val(data.status);	
   							if (data.status == '1')
								message = 'Bagus'
   							else if (data.status == '0')
								message = 'Rusak';
   							$('#statusinfo').addClass('money');
   							$('#statusinfo').removeClass('errorMessage');
   							$('#statusinfo').html(message);
   						} else {
   							$('#Detailstockentries_status').val('');
   							$('#statusinfo').addClass('errorMessage');
   							$('#statusinfo').removeClass('money');
   							$('#statusinfo').html('Barang tidak ditemukan');
						}
					});
			} else {
   				$.getJSON('index.php?r=LookUp/checkSerial', {'serialnum': escape(myserialnum), 
   						'idwh' : $('#idwh').val()},
   				function(data) {
   					if (data == false) {
   						$('#statusinfo').addClass('money');
   						$('#statusinfo').removeClass('errorMessage');
   						$('#statusinfo').html('Item bisa diterima');
   						$('#Detailstockentries_status').val('1');
   					} else if (data.avail == '0') {
   						$('#statusinfo').addClass('money');
   						$('#statusinfo').removeClass('errorMessage');
   						$('#statusinfo').html('Item bisa diterima');
						$('#Detailstockentries_status').val(data.status);
   					} else if (data.avail == '1') {
   						$('#statusinfo').addClass('errorMessage');
   						$('#statusinfo').removeClass('money');
   						$('#statusinfo').html('Nomor seri telah terdaftar');
   					} 
				});
			}
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
        echo CHtml::hiddenField('idwh', $idwh);
        echo CHtml::hiddenField('transname', $transname);
		if ($transname == 'AC18') {
			$idwhsource = Yii::app()->db->createCommand()->select('idwhsource')->from('itemtransfers')
				->where('regnum = :p_regnum', array(':p_regnum'=>$transid))->queryScalar();
			if ($idwhsource !== FALSE)
			echo CHtml::hiddenField('idwhsource', $idwhsource);
		}
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php 
               echo CHtml::label(lookup::ItemNameFromItemID($model->iditem), false);
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
			if ($transname	== 'AC12') {
				echo $form->dropDownList($model, 'status', 
					array('empty' => 'Harap Pilih','1'=>'Bagus', '0'=>'Rusak' )); 
			} else {
				echo $form->hiddenField($model, 'status');
				echo CHtml::tag('span', array('id'=>'statusinfo'));
			}
		?>
		<?php echo $form->error($model,'status'); ?>
	</div>
        
    <div class="row">
		
		<?php 
			echo CHtml::tag('span', array('id'=>'status', 'class'=>'error'), $error);
		?>		
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->