<?php 
/* @var $this ItemsController */
/* @var $model Items */
/* @var $form CActiveForm */
?>

<div class="form">
<?php
$namescript=<<<OK
   function combine(separator, brand, object, model, attribute) {
      return brand + separator + object + separator + model + separator + attribute;
   };
        
   $(function() {
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
  });
OK;
Yii::app()->clientScript->registerScript('myscript', $namescript, CClientScript::POS_READY);
?>
   
<?php 
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'items-form',
	'enableAjaxValidation'=>false,
      //'action'=>Yii::app()->createUrl("default/create")
      'action'=>$this->createUrl("default/create")
   ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'items-form',
	'enableAjaxValidation'=>false,
      'action'=>$this->createUrl("default/update", array('id'=>$model->id))
      ));
 ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php
             echo $form->hiddenField($model, 'id');
             echo $form->hiddenField($model, 'userlog');
             echo $form->hiddenField($model, 'datetimelog');
             echo $form->hiddenField($model, 'rowdeleted');

             echo CHtml::hiddenField('command');
          ?>
	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
            <?php
               echo $form->dropDownList($model, 'type', array('-'=>'Harap Pilih', 
                  1=>'Tunggal', 2=>'Paket',
                  3=>'Jasa'));
            
            ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>255, 'readonly'=>true)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'brand'); ?>
		<?php echo $form->textField($model,'brand',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'brand'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'objects'); ?>
		<?php echo $form->textField($model,'objects',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'objects'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'model'); ?>
		<?php echo $form->textField($model,'model',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'model'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'attribute'); ?>
		<?php echo $form->textField($model,'attribute',array('size'=>50,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'attribute'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'picture'); ?>
		<?php echo $form->textField($model,'picture'); ?>
		<?php echo $form->error($model,'picture'); ?>
	</div>
      
      <?php
   if ($model->type==2) {
      if(isset(Yii::app()->session['Detailitems'])) {
         $rawdata=Yii::app()->session['Detailitems'];
         $count=count($rawdata);
      } else {
         $sql="select count(*) as total from detailitems a join items b on b.id=a.iditem where a.id='$model->id'";
         $count=Yii::app()->db->createCommand($sql)->queryScalar();
         $sql="select b.name, a.id, a.iditem, a.iddetail, a.qty from detailitems a join items b on b.id=a.iditem where a.id='$model->id'";
         $rawdata=Yii::app()->db->createCommand($sql)->queryAll();
      };
      $dataProvider=new CArrayDataProvider($rawdata,array(
               'totalItemCount'=>$count,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     ));
      
      $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(
           array(
               'header'=>'Nama Barang',
               'name'=>'name',
               //'value'=>"\$data['id']"
               'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
           ),
          array(
              'header'=>'Jumlah',
              'name'=>'qty',
          ),
          array(
              'class'=>'CButtonColumn',
              'buttons'=>array(
                  'view'=>array(
                      'visible'=>'false',
                  ),
              ),
               'updateButtonUrl'=>"Action::decodeUpdateDetailItemUrl(\$data)",
              'deleteButtonUrl'=>"Action::decodeDeleteDetailItemUrl(\$data)"
          )
      )
   )); 
         
      }
      
      ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton(ucfirst($command)); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->