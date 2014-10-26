<?php
/* @var $this DetaildeliveryordersController */
/* @var $model Detaildeliveryorders */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
 
    $itemScript=<<<EOS
      $('#Detaildeliveryorders_itemname').focus(function(){
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
	'id'=>'detaildeliveryorders-form',
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
         echo $form->hiddenField($model,'leftqty');
         echo $form->hiddenField($model,'invqty');
         //echo $form->hiddenField($model, 'idunit');
        ?>

	<div class="row">
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php echo CHtml::label(lookup::ItemNameFromItemID($model->iditem), FALSE);?>
		<?php echo $form->error($model,'iditem'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'invqty'); ?>
		<?php echo CHtml::label($model->invqty, FALSE); ?>
		<?php echo $form->error($model,'invqty'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'leftqty'); ?>
		<?php echo CHtml::label($model->leftqty, FALSE); ?>
		<?php echo $form->error($model,'leftqty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'qty'); ?>
		<?php echo $form->textField($model,'qty'); ?>
		<?php echo $form->error($model,'qty'); ?>
	</div>
        
	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->