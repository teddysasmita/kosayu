<?php
/* @var $this DeliveryreplacesController */
/* @var $model Deliveryreplaces */
/* @var $form CActiveForm */
?>

<div class="form">

<?php

	$deliveryScript=<<<EOS
	$('#Deliveryreplaces_deliverynum').change(function(event) {
		$('#command').val('loadDelivery');
		$('#deliveryreplaces-form').submit();
	});
EOS;
   Yii::app()->clientScript->registerScript("deliveryScript", $deliveryScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
		'id'=>'deliveryreplaces-form',
		'enableAjaxValidation'=>true,
      	'action'=>Yii::app()->createUrl("/deliveryreplaces/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
		'id'=>'deliveryreplaces-form',
		'enableAjaxValidation'=>true,
      	'action'=>Yii::app()->createUrl("/deliveryreplaces/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
        echo $form->hiddenField($model, 'status');
        echo $form->hiddenField($model, 'regnum');
        echo $form->hiddenField($model, 'receivername');
        echo $form->hiddenField($model, 'receiveraddress');
        echo $form->hiddenField($model, 'receiverphone');
      ?>
      
	<div class='error'>
		<?php echo CHtml::label($form_error, FALSE)?>
	</div>
      
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Deliveryreplaces[idatetime]',
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
		<?php echo $form->labelEx($model,'deliverynum'); ?>
         <?php 
			echo $form->textField($model, 'deliverynum', array('size'=>12));
         ?>
         <?php echo $form->error($model,'deliverynum'); ?>
	</div>

	<div class="row">
         <?php echo $form->labelEx($model,'receivername'); ?>
         <?php 
			echo CHtml::label($model->receivername, FALSE);
         ?>
         <?php echo $form->error($model,'receivername'); ?>
	</div>
	
	<div class="row">
         <?php echo $form->labelEx($model,'receiveraddress'); ?>
         <?php
         	echo CHtml::label($model->receiveraddress, FALSE); 
         ?>
         <?php echo $form->error($model,'receiveraddress'); ?>
	</div>
	
	<div class="row">
         <?php echo $form->labelEx($model,'receiverphone'); ?>
         <?php 
			//echo $form->textField($model, 'receiverphone');
         echo CHtml::label($model->receiverphone, FALSE);
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
	$rawdata = array();
	$count = 0;
    if (isset(Yii::app()->session['Detaildeliveryreplaces'])) {
       $rawdata=Yii::app()->session['Detaildeliveryreplaces'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detaildeliveryreplaces where id='$model->id'")
       		->queryScalar();
       $sql="select * from detaildeliveryreplaces where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
		  'keyField'=>'iddetail',
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
			array(
				'header'=>'Nomor Barang Keluar',
				'name'=>'regnum',
			),
			array(
				'header'=>'Nama Barang',
				'name'=>'iditem',
				'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
			),
			array(
				'header'=>'Serialnum',
				'name'=>'serialnum',
			),
			array(
				'header'=>'Gudang',
				'name'=>'idwarehouse',
				'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
			),
			array(
				'class'=>'CCheckBoxColumn',
				'header'=>'Tukar',
				'selectableRows'=>2,
				'headerTemplate'=>'<span> Pilih {item}</span>',
				'value'=>"\$data['iddetail']",
				'checked'=>"lookup::RepairCheck(\$data)",
			),
		),
    ));
?>

	<div class='error'>
		<?php echo CHtml::label($form_error, FALSE)?>
	</div>
	
   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->