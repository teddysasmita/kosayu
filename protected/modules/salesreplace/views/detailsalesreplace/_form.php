<?php
/* @var $this DetailsalesreplaceController */
/* @var $model Detailsalesreplace */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
 
    $itemScript=<<<EOS
      $('#Detailsalesreplace_itemname').focus(function(){
		if ($('#Detailsalesreplace_itemname').prop('readOnly') != true) 
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
		
	$('#Detailsalesreplace_itemnewname').focus(function(){
		if ($('#Detailsalesreplace_itemnewname').prop('readOnly') != true)
			$('#ItemnewDialog').dialog('open');
      });
      $('#dialog-itemnew-name').change(
         function(){
            $.getJSON('index.php?r=LookUp/getItem',{ name: $('#dialog-itemnew-name').val() },
               function(data) {
                  $('#dialog-itemnew-select').html('');
                  var ct=0;
                  while(ct < data.length) {
                     $('#dialog-itemnew-select').append(
                        '<option value='+data[ct]+'>'+unescape(data[ct])+'</option>'
                     );
                     ct++;
                  }
               })
         }
      );
      $('#dialog-itemnew-select').click(
         function(){
           $('#dialog-itemnew-name').val(unescape($('#dialog-itemnew-select').val()));
         }
      );
		
	$('#Detailsalesreplace_iditemnew').change(
		function(){
			$.getJSON('index.php?r=Lookup/getItemPrice', {iditem: $('#Detailsalesreplace_iditemnew').val()},
				function(data) {
					$('#Detailsalesreplace_pricenew').val(data);
				}
		)}
	);
    
	function setNewAttr(state) {
		if(state=='0' || state=='2') {
			$('#Detailsalesreplace_itemnewname').prop('readOnly', true);
			$('#Detailsalesreplace_qtynew').prop('readOnly', true);
			$('#Detailsalesreplace_pricenew').prop('readOnly', true);
		} else if (state=='1') {
			$('#Detailsalesreplace_itemnewname').prop('readOnly', false);
			$('#Detailsalesreplace_qtynew').prop('readOnly', false);
			$('#Detailsalesreplace_pricenew').prop('readOnly', false);
		};
	};
		
     $('#Detailsalesreplace_deleted').change(
    	function() {
			var state=$('#Detailsalesreplace_deleted').val();
			setNewAttr(state);
		}	
     );
EOS;
   Yii::app()->clientScript->registerScript('itemscript', $itemScript, CClientScript::POS_READY);
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailsalesreplace-form',
	'enableAjaxValidation'=>true,
   ));
 ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
        <?php 
         echo $form->hiddenField($model,'iddetail');
         echo $form->hiddenField($model,'id');
         echo $form->hiddenField($model,'userlog');
         echo $form->hiddenField($model,'datetimelog');
         echo $form->hiddenField($model,'iditem');
         echo $form->hiddenField($model,'qty');
         echo $form->hiddenField($model,'price');
         echo $form->hiddenField($model,'iditemnew');
        ?>

	<div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php 
               /*echo CHtml::textField('Detailsalesreplace_itemname', lookup::ItemNameFromItemID($model->iditem) , array('size'=>50));   
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
                             $(\'#Detailsalesreplace_itemname\').val($(\'#dialog-item-name\').val());
                             $.get(\'index.php?r=LookUp/getItemID\',{ name: encodeURI($(\'#dialog-item-name\').val()) },
                                 function(data) {
                                    $(\'#Detailsalesreplace_iditem\').val(data);
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
               $this->endWidget('zii.widgets.jui.CJuiDialog');*/
				echo CHtml::label(lookup::ItemNameFromItemID($model->iditem), FALSE);
            ?>
		<?php echo $form->error($model,'iditem'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'qty'); ?>
		<?php //echo $form->textField($model,'qty'); 
            echo CHtml::label(number_format($model->qty), FALSE);
		?>
		<?php echo $form->error($model,'qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php //echo $form->textField($model,'price'); 
			echo CHtml::label(number_format($model->price), FALSE);
		?>
		<?php echo $form->error($model,'price'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'discount'); ?>
		<?php //echo $form->textField($model,'price'); 
			echo CHtml::label(number_format($model->discount), FALSE);
		?>
		<?php echo $form->error($model,'discount'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'deleted'); ?>
		<?php 
			echo $form->dropDownList($model, 'deleted', 
				array('0'=>'Tetap', '1'=>'Dirubah', '2'=>'Dihapus'),
				array('empty'=>'Harap Pilih'));		
		?>
		<?php echo $form->error($model,'price'); ?>
	</div>
	
	
	<div class="row">
		<?php echo $form->labelEx($model,'iditemnew'); ?>
		<?php 
			echo CHtml::textField('Detailsalesreplace_itemnewname', 
				lookup::ItemNameFromItemID($model->iditemnew) , array('size'=>50));   
			$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                  'id'=>'ItemnewDialog',
                  'options'=>array(
                      'title'=>'Pilih Barang Baru',
                      'autoOpen'=>false,
                      'height'=>300,
                      'width'=>600,
                      'modal'=>true,
                      'buttons'=>array(
                          array('text'=>'Ok', 'click'=>'js:function(){
                             $(\'#Detailsalesreplace_itemnewname\').val($(\'#dialog-itemnew-name\').val());
                             $.get(\'index.php?r=LookUp/getItemID\',{ name: encodeURI($(\'#dialog-itemnew-name\').val()) },
                                 function(data) {
                                    $(\'#Detailsalesreplace_iditemnew\').val(data);
									$.get(\'index.php?r=LookUp/getItemPrice\',{ iditem: encodeURI(data)},
										function(price) {
											$(\'#Detailsalesreplace_pricenew\').val(price);
										})
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
         
            <div><input type="text" name="itemnewname" id="dialog-itemnew-name" size='50'/></div>
            <div><select size='8' width='100' id='dialog-itemnew-select'>   
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
		<?php echo $form->labelEx($model,'qtynew'); ?>
		<?php echo $form->textField($model,'qtynew'); ?>
		<?php echo $form->error($model,'qtynew'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'discountnew'); ?>
		<?php echo $form->textField($model,'discountnew'); ?>
		<?php echo $form->error($model,'discountnew'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'pricenew'); ?>
		<?php echo $form->textField($model,'pricenew'); ?>
		<?php echo $form->error($model,'pricenew'); ?>
	</div>
	   
       
	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->