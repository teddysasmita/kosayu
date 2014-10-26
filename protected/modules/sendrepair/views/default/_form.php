   <?php
/* @var $this SendrepairsController */
/* @var $model Sendrepairs */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
	$supplierScript=<<<EOS
	$('#Sendrepairs_idservicecenter').change(function() {
		$.getJSON('index.php?r=LookUp/getSCAddress', 
		{id:$('#Sendrepairs_idservicecenter').val()},
		function(data) {
			$('#address').html(data);
		});
	});
	
EOS;
	Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);
	
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sendrepairs-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/sendrepair/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sendrepairs-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/sendrepair/default/update", array('id'=>$model->id))
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
                  'name'=>'Sendrepairs[idatetime]',
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
		<?php echo $form->labelEx($model,'idservicecenter'); ?>
		<?php 
			$data = Yii::app()->db->createCommand()
				->select("id, concat (brandname, ' - ', companyname) as name")
				->from('servicecenters')->queryAll();
			$data = CHtml::listData($data, 'id', 'name');
        	echo $form->dropDownList($model, 'idservicecenter', $data, array('empty'=>'Harap Pilih'));
      	?>
		<?php echo $form->error($model,'idservicecenter'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Alamat', false); ?>
		<?php 
			echo CHtml::tag('span', array('id'=>'address', 'class'=>'money'));
      	?>
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

	<div class="row">
		<?php echo $form->labelEx($model,'duedate'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Sendrepairs[duedate]',
                     // additional javascript options for the date picker plugin
                  'options'=>array(
                     'showAnim'=>'fold',
                     'dateFormat'=>'yy/mm/dd',
                     'defaultdate'=>$model->duedate
                  ),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
                  'value'=>$model->duedate,
               ));
            ?>
		<?php echo $form->error($model,'duedate'); ?>
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
					'header'=>'Kirim',
					'selectableRows'=>2,
					'headerTemplate'=>'<span> Pilih {item}</span>',
					'value'=>"\$data['iddetail']",
					'checked'=>"lookup::RepairCheck(\$data)",
				),
				array(
					'header'=>'Keluar',
					'name'=>'exit',
					'value'=>"lookup::SendRepairExit(\$data)"
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