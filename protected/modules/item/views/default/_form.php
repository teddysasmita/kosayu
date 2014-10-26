<?php
/* @var $this ItemsController */
/* @var $model Items */
/* @var $form CActiveForm */
?>

<div class="form">
    
<?php


$namescript=<<<OK
   function combine(separator, brand, object, model, attribute) {
      return object + separator + brand + separator + model + separator + attribute;
   };
        
     $('#Items_brand').change(function(event) {
         $('#Items_name').val(combine( ' ', $('#Items_brand').val(), $('#Items_objects').val(), $('#Items_model').val(), $('#Items_attribute').val() ));
     });
     $('#Items_objects').change(function(event) {
        $('#Items_name').val(combine( ' ', $('#Items_brand').val(), $('#Items_objects').val(), $('#Items_model').val(), $('#Items_attribute').val() ));
     });
     $('#Items_model').change(function(event) {
        $('#Items_name').val(combine( ' ', $('#Items_brand').val(), $('#Items_objects').val(), $('#Items_model').val(), $('#Items_attribute').val() ));       
     });
     $('#Items_attribute').change(function(event) {
        $('#Items_name').val(combine( ' ', $('#Items_brand').val(), $('#Items_objects').val(), $('#Items_model').val(), $('#Items_attribute').val() ));       
     }); 
OK;
Yii::app()->clientScript->registerScript('myscript', $namescript, CClientScript::POS_READY);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'items-form',
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
          ?>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>30,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php 
         echo $form->dropDownList($model, 'type', array(1=>'Tunggal', 
            2=>'Tambahan', 3=>'Jasa'), array('empty'=>'Harap Pilih'));
      ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'brand'); ?>
            <?php
               //$brands=Yii::app()->db->createCommand()->selectDistinct('brand')->from('items')->queryColumn();

               $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                  'name'=>'Items[brand]',
                  'sourceUrl'=> Yii::app()->createUrl('LookUp/getBrand'),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->brand,
               ));
            ?>
		<?php //echo $form->textField($model,'brand',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'brand'); ?>
      </div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'objects'); ?>
            <?php
               //$objects=Yii::app()->db->createCommand()->selectDistinct('objects')->from('items')->queryColumn();

               $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                  'name'=>'Items[objects]',
                  'sourceUrl'=> Yii::app()->createUrl('LookUp/getObjects'),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->objects,
               ));
            ?>
		<?php //echo $form->textField($model,'objects',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'objects'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'model'); ?>
            <?php
               //$models=Yii::app()->db->createCommand()->selectDistinct('model')->from('items')->queryColumn();

               $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                  'name'=>'Items[model]',
                  'sourceUrl'=> Yii::app()->createUrl('LookUp/getModel'),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->model,
               ));
            ?>
		<?php //echo $form->textField($model,'model',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'model'); ?>
	</div>

      <div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50 ,'maxlength'=>255, 'readonly'=>'true')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

   	<div class="row">
		<?php echo $form->labelEx($model,'attribute'); ?>
		<?php echo $form->textField($model,'attribute',array('size'=>50,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'attribute'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'picture'); ?>
		<?php 
                    //echo CHtml::hiddenField('MAX_FILE_SIZE', 100000);
                    echo $form->fileField($model,'picture', array()); 
                ?>
		<?php echo $form->error($model,'picture'); ?>
	</div>

	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
