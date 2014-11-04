<?php
/* @var $this ItemsController */
/* @var $model Items */
/* @var $form CActiveForm */
?>

<div class="form">
    
<?php

$currScript=<<<EOS
      $('#Currencyrates_name').focus(function(){
         $('#CurrDialog').dialog('open');
      });
      $('#dialog-curr-name').change(
         function(){
            $.getJSON('index.php?r=LookUp/getCurr',{ name: $('#dialog-curr-name').val() },
               function(data) {
                  $('#dialog-curr-select').html('');
                  var ct=0;
                  while(ct < data.length) {
                     $('#dialog-curr-select').append(
                        '<option value='+data[ct]+'>'+unescape(data[ct])+'</option>'
                     );
                     ct++;
                  }
               })
         }
      );
      $('#dialog-curr-select').click(
         function(){
           $('#dialog-curr-name').val(unescape($('#dialog-curr-select').val()));
         }
      );
EOS;
Yii::app()->clientScript->registerScript('currcript', $currScript, CClientScript::POS_READY);

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'currencyrates-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php
             echo $form->hiddenField($model, 'id');
             echo $form->hiddenField($model, 'userlog');
             echo $form->hiddenField($model, 'datetimelog');
             echo $form->hiddenField($model, 'idcurr');
             echo $form->hiddenField($model, 'regnum');
          ?>

	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Currencyrates[idatetime]',
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
		<?php echo $form->labelEx($model,'idcurr'); ?>
		<?php 
               echo CHtml::textField('Currencyrates_name', lookup::CurrNameFromID($model->idcurr) , array('size'=>50));   
               $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                  'id'=>'CurrDialog',
                  'options'=>array(
                      'title'=>'Pilih Mata Uang Asing',
                      'autoOpen'=>false,
                      'height'=>300,
                      'width'=>600,
                      'modal'=>true,
                      'buttons'=>array(
                          array('text'=>'Ok', 'click'=>'js:function(){
                             $(\'#Currencyrates_name\').val($(\'#dialog-curr-name\').val());
                             $.get(\'index.php?r=LookUp/getCurrID\',{ name: encodeURI($(\'#dialog-curr-name\').val()) },
                                 function(data) {
                                    $(\'#Currencyrates_idcurr\').val(data);
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
         
            <div><input type="text" name="currname" id="dialog-curr-name" size='50'/></div>
            <div><select size='8' width='100' id='dialog-curr-select'>   
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
		<?php echo $form->labelEx($model,'rate'); ?>
		<?php 
         	echo $form->textField($model, 'rate' );
     	?>
		<?php echo $form->error($model,'rate'); ?>
	</div>
	
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
