<?php
/* @var $this DeliveryordersntController */
/* @var $model Deliveryordersnt */
/* @var $form CActiveForm */
?>

<div class="form">

   <?php 
   
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'deliveryordersnt-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/deliveryordersnt/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'deliveryordersnt-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/deliveryordersnt/default/update", array('id'=>$model->id))
      ));
   ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

      <?php
         echo $form->hiddenfield($model,'id');   
         echo $form->hiddenField($model,'userlog');
         echo $form->hiddenField($model,'datetimelog');
         echo $form->hiddenField($model,'status');
         echo $form->hiddenField($model,'regnum');
         
         echo CHtml::hiddenField('command');
      ?>
      
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
		<?php 
            //echo $form->dateField($model,'idatetime',array('size'=>19,'maxlength'=>19)); 
            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
               'name'=>'Deliveryordersnt[idatetime]',
                  // additional javascript options for the date picker plugin
               'options'=>array(
                  'showAnim'=>'fold',
                  'dateFormat'=>'yy/mm/dd',
                  'defaultdate'=>$model->idatetime
               ),
               'htmlOptions'=>array(
                  'style'=>'height:20px;',
					'id'=>'Deliveryordersnt_idatetime'
               ),
               'value'=>$model->idatetime,
            ));
            ?> 
		<?php echo $form->error($model,'idatetime'); ?>
	</div>

	<div class="row">
         <?php echo $form->labelEx($model,'receivername'); ?>
         <?php 
            /*$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
                'name'=>'Deliveryordersnt_receivername',
                'sourceUrl'=>Yii::app()->createUrl('LookUp/getReceiverinfobyname'),
              'value'=>$model->receivername
            ));*/
			echo $form->textField($model, 'receivername', array('size'=>50));
         ?>
         <?php echo $form->error($model,'receivername'); ?>
	</div>
	
	<div class="row">
         <?php echo $form->labelEx($model,'receiveraddress'); ?>
         <?php 
            /*$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
                'name'=>'Deliveryordersnt_receivername',
                'sourceUrl'=>Yii::app()->createUrl('LookUp/getReceiverinfobyaddress'),
              'value'=>$model->receiveraddress
            ));*/
			echo $form->textField($model, 'receiveraddress', array('size'=>50));
         ?>
         <?php echo $form->error($model,'receiveraddress'); ?>
	</div>
	
	<div class="row">
         <?php echo $form->labelEx($model,'receiverphone'); ?>
         <?php 
            /*$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
                'name'=>'Deliveryordersnt_receiverphone',
                'sourceUrl'=>Yii::app()->createUrl('LookUp/getReceiverinfobyname'),
              'value'=>$model->receiverphone
            ));*/
			echo $form->textField($model, 'receiverphone');
         ?>
         <?php echo $form->error($model,'receiverphone'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'drivername'); ?>
		<?php 
			echo $form->textField($model, 'drivername', array('size'=>50));
		?>
		<?php echo $form->error($model, 'drivername'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'vehicleinfo'); ?>
		<?php 
			echo $form->textField($model, 'vehicleinfo', array('size'=>50));
		?>
		<?php echo $form->error($model, 'vehicleinfo'); ?>
	</div>

      <?php 

if (isset(Yii::app()->session['Detaildeliveryordersnt'])) {
   $rawdata=Yii::app()->session['Detaildeliveryordersnt'];
   $count=count($rawdata);
} else {
   $count=Yii::app()->db->createCommand("select count(*) from detaildeliveryordersnt where id='$model->id'")->queryScalar();
   $sql="select * from detaildeliveryordersnt where id='$model->id'";
   $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
}
$dataProvider=new CArrayDataProvider($rawdata, array(
      'totalItemCount'=>$count,
));
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
			'itemname',
			'qty',
          array(
              'class'=>'CButtonColumn',
              'buttons'=> array(
                  'view'=>array(
                     'visible'=>'false'
                  )
              ),
              'updateButtonUrl'=>"Action::decodeUpdateDetailDeliveryOrderNtUrl(\$data)",
			  'deleteButtonUrl'=>"Action::decodeDeleteDetailDeliveryOrderNtUrl(\$data)",
          )
      ),
));
 ?>
 
	<div class="row buttons">
		<?php echo CHtml::submitButton(ucfirst($command)); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->