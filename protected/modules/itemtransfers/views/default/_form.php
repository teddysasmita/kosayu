<?php
/* @var $this ItemtransfersController */
/* @var $model Itemtransfers */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
   $supplierScript=<<<EOS
	$('#Itemtransfers_whsourcename').change(function() {
		$.getJSON('index.php?r=Lookup/getWareHouseID', { name: $('#Itemtransfers_whsourcename').val() },
			function(data) {
				if (data !== 'NA') {
					$('#Itemtransfers_idwhsource').val(data);
				};
			});
	});
	
	$('#Itemtransfers_whdestname').change(function() {
		$.getJSON('index.php?r=Lookup/getWareHouseID', { name: $('#Itemtransfers_whdestname').val() },
			function(data) {
				if (data !== 'NA') {
					$('#Itemtransfers_idwhdest').val(data);
				};
			});
	});
		
		$('.updateButton').click(
		function(evt) {
			$('#command').val('updateDetail');
			$('#detailcommand').val(this.href);
			$('#itemtransfers-form').submit();
   			//evt.preventDefault();
		});  
EOS;
   Yii::app()->clientScript->registerScript("supplierScript", $supplierScript, CClientscript::POS_READY);

   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'itemtransfers-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/itemtransfers/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'itemtransfers-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/itemtransfers/default/update", array('id'=>$model->id))
      ));
  ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
      <?php 
        echo CHtml::hiddenField('command', '', array('id'=>'command'));
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
        echo $form->hiddenField($model, 'regnum');
        echo $form->hiddenField($model, 'idwhsource');
        echo $form->hiddenField($model, 'idwhdest');
      ?>
      
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Itemtransfers[idatetime]',
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
         <?php echo $form->labelEx($model,'idwhsource'); ?>
         <?php 
            /*$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
                'name'=>'Itemtransfers_whsourcename',
                'sourceUrl'=>Yii::app()->createUrl('LookUp/getWareHouse'),
              'value'=>lookup::WarehouseNameFromWarehouseID($model->idwhsource),
            ));*/
			$data=Yii::app()->db->createCommand()->select('id,code')->from('warehouses')->queryAll();
			$data=CHtml::listData($data, 'id', 'code');
			echo $form->dropDownList($model, 'idwhsource', $data, array('empty'=>'Harap Pilih'));
         ?>
         <?php echo $form->error($model,'idwhsource'); ?>
	</div>
	
	<div class="row">
         <?php echo $form->labelEx($model,'idwhdest'); ?>
         <?php 
            /*$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
                'name'=>'Itemtransfers_whdestename',
                'sourceUrl'=>Yii::app()->createUrl('LookUp/getWareHouse'),
              'value'=>lookup::WarehouseNameFromWarehouseID($model->idwhdest),
            ));*/
			$data=Yii::app()->db->createCommand()->select('id,code')->from('warehouses')->queryAll();
			$data=CHtml::listData($data, 'id', 'code');
			echo $form->dropDownList($model, 'idwhdest', $data, array('empty'=>'Harap Pilih'));
         ?>
         <?php echo $form->error($model,'idwhdest'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'remark'); ?>
		<?php 
			echo $form->textArea($model, 'remark', array('COLS'=>50, 'ROWS'=>5));
		?>
		<?php echo $form->error($model, 'remark'); ?>
	</div>

<?php 
   
    if (isset(Yii::app()->session['Detailitemtransfers'])) {
       $rawdata=Yii::app()->session['Detailitemtransfers'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailitemtransfers where id='$model->id'")->queryScalar();
       $sql="select * from detailitemtransfers where id='$model->id'";
       $rawdata=Yii::app()->db->createCommand($sql)->queryAll ();
    }
    $dataProvider=new CArrayDataProvider($rawdata, array(
          'totalItemCount'=>$count,
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
					'header'=>'Jmlh',
					'name'=>'qty',
				),
				array(
					'class'=>'CButtonColumn',
					'buttons'=> array(
						'view'=>array(
							'visible'=>'false'
						),
					),
					'updateButtonOptions'=>array("class"=>'updateButton'),
					'updateButtonUrl'=>"Action::decodeUpdateDetailDeliveryOrderUrl(\$data)",
					'deleteButtonUrl'=>"Action::decodeDeleteDetailDeliveryOrderUrl(\$data)",
				)
			),
    	));
?>

   <div class="row buttons">
      <?php echo CHtml::submitButton(ucfirst($command)); ?>
   </div>

<?php $this->endWidget(); ?>


      
</div><!-- form -->