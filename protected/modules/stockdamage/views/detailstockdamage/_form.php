<?php
/* @var $this DetailstockdamageController */
/* @var $model Detailstockdamage */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailstockdamage-form',
	'enableAjaxValidation'=>true,
   ));

$supplierScript=<<<EOS
	$('input,select').keypress(function(event) { return event.keyCode != 13; });

      $('#isAccepted').click(function() {
   		if ($('#isAccepted').prop('checked')) {
   			$('#Detailstockdamage_serialnum').val('Belum Diterima');
   		}
      });
   		
	$('#Detailstockdamage_serialnum').change(function(evt) {
   		var myserialnum = $('#Detailstockdamage_serialnum').val();
   		if (myserialnum !== 'Belum Diterima') {
   			$('#isAccepted').prop('checked', false);
   			$.getJSON('index.php?r=LookUp/CheckSerial', { serialnum: $('#Detailstockdamage_serialnum').val(),
   				idwh: $('#idwh').val() },
   				function(data) {
   					if (data == false) {
   						$('#Detailstockdamage_serialnum_em_').html('Data tidak ditemukan');
						$('#Detailstockdamage_serialnum_em_').prop('style', 'display:block');
   					} else {
   						$('#Detailstockdamage_serialnum_em_').html('');
						$('#Detailstockdamage_serialnum_em_').prop('style', 'display:none');
	   				};
   				});
		}
	});
	
   	$('#myButton').click(
   		function(evt) {
   			var myserialnum = $('#Detailstockdamage_serialnum').val();
   			if (myserialnum !== 'Belum Diterima') {
	   			$.getJSON('index.php?r=LookUp/checkItemSerial', { iditem: $('#Detailstockdamage_iditem').val(), 
	   			serialnum: $('#Detailstockdamage_serialnum').val(), idwh:$('#idwh').val() }, 
	   			function(data) {
	   				if (data=='0') {
	            		$('#Detailstockdamage_serialnum_em_').html('Data tidak ditemukan');
						$('#Detailstockdamage_serialnum_em_').prop('style', 'display:block');
					} else {
						$('#Detailstockdamage_serialnum_em_').html('');
						$('#Detailstockdamage_serialnum_em_').prop('style', 'display:none');
	   					$('#detailstockdamage-form').submit();
	   				};
	   			});
   			} else {
   				$('#Detailstockdamage_serialnum_em_').html('');
				$('#Detailstockdamage_serialnum_em_').prop('style', 'display:none');
   				$('#detailstockdamage-form').submit();
   			}
   	});
   		
   	 $('#Detailstockdamage_itemname').focus(function(){
         $('#ItemDialog').dialog('open');
      });
		
      $('#dialog-item-name').change(
         function(){
            $.getJSON('index.php?r=LookUp/getItem',{ name: $('#dialog-item-name').val() },
               function(data) {
                  $('#dialog-item-select').html('');
                  var ct=0;
                  while(ct < data.length) {
                     $('#dialog-item-select').append(
                        '<option value='+data[ct]+'>'+unescape(data[ct])+'</option>'
                     );
                     ct++;
                  }
               })
         }
      );
		
      $('#dialog-item-select').click(
         function(){
           $('#dialog-item-name').val(unescape($('#dialog-item-select').val()));
         }
      );
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
         echo CHtml::hiddenField('idwh',$idwh);
        ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php 
               echo CHtml::textField('Detailstockdamage_itemname', lookup::ItemNameFromItemID($model->iditem) , array('size'=>50));   
               $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                  'id'=>'ItemDialog',
                  'options'=>array(
                      'title'=>'Pilih Barang',
                      'autoOpen'=>false,
                      'height'=>300,
                      'width'=>600,
                      'modal'=>true,
                      'buttons'=>array(
                          array('text'=>'Ok', 'click'=>'js:function(){
                             $(\'#Detailstockdamage_itemname\').val($(\'#dialog-item-name\').val());
                             $.get(\'index.php?r=LookUp/getItemID\',{ name: encodeURI($(\'#dialog-item-name\').val()) },
                                 function(data) {
                                    $(\'#Detailstockdamage_iditem\').val(data);
                                 })
                             $(this).dialog("close");
                           }'),
                          array('text'=>'Close', 'click'=>'js:function(){
                              $(this).dialog("close");
                          }'),
                      ),
                  ),
               ));
               $myd=<<<EOS
         
            <div><input type="text" name="itemname" id="dialog-item-name" size='50'/></div>
            <div><select size='8' width='100' id='dialog-item-select'>   
                <option>Harap Pilih</option>
            </select>           
            </div>
            </select>           
EOS;
               echo $myd;
               $this->endWidget('zii.widgets.jui.CJuiDialog');
            ?>
		<?php echo $form->error($model,'iditem'); ?>
	</div>
	

	<div class="row">
		<?php echo $form->labelEx($model,'serialnum'); ?>
		<?php echo $form->textField($model,'serialnum'); ?>
		<?php echo $form->error($model,'serialnum'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
		<?php echo $form->textArea($model,'remark', array('COLS'=>40, 'ROWS'=>10)); ?>
		<?php echo $form->error($model,'remark'); ?>
	</div>
	
        
	<div class="row buttons">
		<?php echo CHtml::Button($mode, array('id'=>'myButton', 'name'=>'yt0')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->