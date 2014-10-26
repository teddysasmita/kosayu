   <?php
/* @var $this ReceiverepairsController */
/* @var $model Receiverepairs */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
	$supplierScript=<<<EOS
	$('#Receiverepairs_sendnum').change(function() {
		$('#command').val('setSendNum');
		$('#receiverepairs-form').submit();
	});
	
EOS;
	Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);
	
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'receiverepairs-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/receiverepair/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'receiverepairs-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/receiverepair/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">
		Fields with <span class="required">*</span> are required.
	</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'regnum');
        echo $form->hiddenField($model, 'datetimelog');
      ?>
        
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Receiverepairs[idatetime]',
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
		<?php echo $form->labelEx($model,'sendnum'); ?>
		<?php 
        	echo $form->textField($model, 'sendnum', array('size'=>40));
      	?>
		<?php echo $form->error($model,'sendnum'); ?>
	</div>
	
<?php 
    $dataProvider=new CArrayDataProvider($allitems, array(
          'totalItemCount'=>count($allitems),
    ));
    $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$dataProvider,
            'columns'=>array(
               array(
                   'header'=>'Nama Barang',
                   'name'=>'iditem',
                   'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
               ),
				array(
                   'header'=>'Nomor Seri',
                   'name'=>'serialnum',
				),
				array(
					'header'=>'Gudang',
					'name'=>'idwarehouse',
					'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
				),
              array(
                  	'class'=>'CCheckBoxColumn',
					'header'=>'Datang',
					'selectableRows'=>2,
					'headerTemplate'=>'<span> Pilih {item}</span>',
					'value'=>"\$data['iddetail']",
					'checked'=>"lookup::RepairCheck(\$data)",
				),
				array(
					'header'=>'Masuk',
					'name'=>'entry',
					'value'=>"lookup::ReceiveRepairExit(\$data)"
				),
          ),
    ));
    
?>

   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div>
<!-- form -->