<?php
/* @var $this DetailrequestdisplaysController */
/* @var $model Detailrequestdisplays */
/* @var $form CActiveForm */
?>

<div class="form">

<?php

$invdetails=json_encode(Yii::app()->session['Detailrequestdisplays2']);
$itemScript=<<<EOS
	var invdetails=$invdetails;
		
	function checkItems(iditem) {
		var found=false;
		for(var i=0; i<invdetails.length(); i++ ) {
			if ((invdetails[i].iditem == iditem)) {
				found=true;
				break;
			}
		}
		return found;
	};
	
      $('#Detailrequestdisplays_itemname').focus(function(){
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
	
	$('#yt0').click(
		function(evt) {
			if (!checkItems($('#Detailrequestdisplays_iditem'))) {
				alert('Barang tidak terdaftar');
				evt.preventDefault();
			}
		}
	);
EOS;
Yii::app()->clientScript->registerScript('itemscript', $itemScript, CClientScript::POS_READY);


   $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailrequestdisplays-form',
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
        ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php 
               echo CHtml::textField('Detailrequestdisplays_itemname', lookup::ItemNameFromItemID($model->iditem) , array('size'=>50));   
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
                             $(\'#Detailrequestdisplays_itemname\').val($(\'#dialog-item-name\').val());
                             $.get(\'index.php?r=LookUp/getItemID\',{ name: encodeURI($(\'#dialog-item-name\').val()) },
                                 function(data) {
                                    $(\'#Detailrequestdisplays_iditem\').val(data);
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
		<?php echo $form->labelEx($model,'idwarehouse'); ?>
		<?php
			$data=Yii::app()->db->createCommand()->select('id,code')->from('warehouses')->queryAll();
			$data=CHtml::listData($data, 'id', 'code'); 
			echo $form->dropDownList($model, 'idwarehouse', $data, array('empty'=>'Harap Pilih')); 
		?>
		<?php echo $form->error($model,'idwarehouse'); ?>
	</div>
        
    <div class='row'>
    	<?php 
    		if (strlen($error))
    			echo CHtml::tag('span', array('class'=>'errorMessage'), $error);
    	?>
    </div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($mode, array('id'=>'yt0')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->