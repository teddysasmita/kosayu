<?php
/* @var $this InputinventorytakingsController */
/* @var $model Inputinventorytakings */
/* @var $form CActiveForm */
?>

<div class="form">

   <?php 
   
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'inputinventorytakings-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/inputinventorytaking/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'inputinventorytakings-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/inputinventorytaking/default/update", array('id'=>$model->id))
      ));
   ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

      <?php
         echo $form->hiddenfield($model,'id');   
         echo $form->hiddenField($model,'userlog');
         echo $form->hiddenField($model,'datetimelog');
         echo $form->hiddenField($model, 'regnum');
         
         echo CHtml::hiddenField('command');
      ?>
      
         
	<div class="row">
		<?php echo $form->labelEx($model,'Tanggal'); ?>
		<?php 
            //echo $form->dateField($model,'idatetime',array('size'=>19,'maxlength'=>19)); 
            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
               'name'=>'Inputinventorytakings[idatetime]',
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

      <?php 

if (isset(Yii::app()->session['Detailinputinventorytakings'])) {
   $rawdata=Yii::app()->session['Detailinputinventorytakings'];
   $count=count($rawdata);
} else {
   $count=Yii::app()->db->createCommand("select count(*) from detailinputinventorytakings where id='$model->id'")->queryScalar();
   $sql="select * from detailinputinventorytakings where id='$model->id'";
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
			'name'=>'qty',
			'header'=>'Qty',
			'value'=>"number_format(\$data['qty'])"
          ),
          array(
			'name'=>'idwarehouse',
			'header'=>'Gudang',
			'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
          ),
          array(
              'class'=>'CButtonColumn',
              'buttons'=> array(
                  'delete'=>array(
                      'visible'=>'false'
                  ),
                  'view'=>array(
                     'visible'=>'false'
                  )
              ),
              'updateButtonUrl'=>"Action::decodeUpdateDetailInputInventoryTakingUrl(\$data)",
          )
      ),
));
 ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton(ucfirst($command)); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->