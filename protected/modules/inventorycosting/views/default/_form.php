<?php
/* @var $this InventorycostingsController */
/* @var $model Inventorycostings */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php 
	$itemScript=<<<EOS
      $('#Inventorycostings_itemname').focus(function(){
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
	Yii::app()->clientScript->registerScript('itemscript', $itemScript, CClientScript::POS_READY);
	 


	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'inventorycostings-form',
		'enableAjaxValidation'=>true,
	)); 
	?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php 
       echo $form->hiddenField($model,'id');
       echo $form->hiddenField($model,'userlog');
       echo $form->hiddenField($model,'datetimelog');
       echo $form->hiddenField($model, 'regnum');
       echo $form->hiddenField($model,'iditem');
   ?>
   
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name'=>'Inventorytakings[idatetime]',
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
         <?php echo $form->labelEx($model,'idinventorytaking'); ?>
         <?php 
        	$data=Yii::app()->db->createCommand()->select('id, operationlabel')
        		->from('inventorytakings')->where('status = :status', 
					array(':status'=>'1'))->queryAll();
			$data=CHtml::listdata($data,'id', 'operationlabel');
            echo $form->dropDownList($model, 'idinventorytaking', 
				$data, array('empty'=>'Harap Pilih'));
         ?>
         <?php echo $form->error($model,'idinventorytaking'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php 
               echo CHtml::textField('Inventorycostings_itemname', lookup::ItemNameFromItemID($model->iditem) , array('size'=>50));   
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
                             $(\'#Inventorycostings_itemname\').val($(\'#dialog-item-name\').val());
                             $.get(\'index.php?r=LookUp/getItemID\',{ name: encodeURI($(\'#dialog-item-name\').val()) },
                                 function(data) {
                                    $(\'#Inventorycostings_iditem\').val(data);
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
		<?php echo $form->labelEx($model,'cost'); ?>
		<?php echo $form->textField($model,'cost',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'cost'); ?>
	</div>

	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->