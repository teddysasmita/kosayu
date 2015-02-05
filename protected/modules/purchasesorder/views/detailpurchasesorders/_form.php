<?php
/* @var $this DetailpurchasesordersController */
/* @var $model Detailpurchasesorders */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
 
    $itemScript=<<<EOS
    
	$('#Detailpurchasesorders_batchcode').change(function(){
		$('#command').val('setCode');
		$('#detailpurchasesorders-form').submit();
	});
      $('#Detailpurchasesorders_itemname').click(function(){
         $('#ItemDialog').dialog('open');
      });
      $('#dialog-item-name').change(
         function(){
            $.getJSON('index.php?r=LookUp/getItemAll',{ name: $('#dialog-item-name').val() },
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
   Yii::app()->clientScript->registerScript('itemscript', $itemScript, CClientScript::POS_READY);
   
   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailpurchasesorders-form',
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
         echo $form->hiddenField($model, 'idunit');
         echo CHtml::hiddenField('command');
        ?>
        
    <div class="row">
		<?php echo $form->labelEx($model,'batchcode'); ?>
		<?php echo $form->textField($model,'batchcode'); ?>
		<?php echo $form->error($model,'batchcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php 
               echo CHtml::textField('Detailpurchasesorders_itemname', lookup::ItemNameFromItemID2($model->iditem) , array('size'=>50));   
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
                             $(\'#Detailpurchasesorders_itemname\').val($(\'#dialog-item-name\').val());
                             $.get(\'index.php?r=LookUp/getItemID2\',{ name: encodeURI($(\'#dialog-item-name\').val()) },
                                 function(data) {
                                    $(\'#Detailpurchasesorders_iditem\').val(data);
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
		<?php echo $form->labelEx($model,'qty'); ?>
		<?php echo $form->textField($model,'qty'); ?>
		<?php echo $form->error($model,'qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'discount'); ?>
		<?php echo $form->textField($model,'discount'); ?>
		<?php echo $form->error($model,'discount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price'); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>
        
	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->