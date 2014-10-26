<?php
/* @var $this RequestdisplaysController */
/* @var $model Requestdisplays */
/* @var $form CActiveForm */
?>

<div class="form">

   <?php 
   $transScript=<<<EOS
		$('.updateButton').click(
		function(evt) {
			$('#command').val('updateDetail');
			$('#detailcommand').val(this.href);
			$('#requestdisplays-form').submit();
		});
   		$('#Requestdisplays_salesname').change(
   		function(evt) {
			$.getJSON('index.php?r=LookUp/getSalesID',{ name: $('#Requestdisplays_salesname').val() },
               function(data) {
                  $('#Requestdisplays_idsales').val(data);
               })		
   		});
EOS;
   Yii::app()->clientScript->registerScript("transScript", $transScript, CClientscript::POS_READY);
   
   if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'requestdisplays-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/requestdisplays/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'requestdisplays-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/requestdisplays/default/update", array('id'=>$model->id))
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
         echo $form->hiddenField($model, 'idsales');
         
         echo CHtml::hiddenField('command');
      ?>
      
	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
		<?php 
            //echo $form->dateField($model,'idatetime',array('size'=>19,'maxlength'=>19)); 
            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
               'name'=>'Requestdisplays[idatetime]',
                  // additional javascript options for the date picker plugin
               'options'=>array(
                  'showAnim'=>'fold',
                  'dateFormat'=>'yy/mm/dd',
                  'defaultdate'=>$model->idatetime
               ),
               'htmlOptions'=>array(
                  'style'=>'height:20px;',
					'id'=>'Requestdisplays_idatetime'
               ),
               'value'=>$model->idatetime,
            ));
            ?> 
		<?php echo $form->error($model,'idatetime'); ?>
	</div>

	<div class="row">
         <?php echo $form->labelEx($model,'idsales'); ?>
         <?php 
            $this->widget("zii.widgets.jui.CJuiAutoComplete", array(
                'name'=>'Requestdisplays_salesname',
                'sourceUrl'=>Yii::app()->createUrl('LookUp/getSalesName'),
              'value'=>lookup::SalesNameFromID($model->idsales)
            ));
			//echo $form->textField($model, 'receivername', array('size'=>50));
         ?>
         <?php echo $form->error($model,'idsales'); ?>
	</div>
	
<?php 

if (isset(Yii::app()->session['Detailrequestdisplays'])) {
   $rawdata=Yii::app()->session['Detailrequestdisplays'];
   $count=count($rawdata);
} else {
   $count=Yii::app()->db->createCommand("select count(*) from detailrequestdisplays where id='$model->id'")->queryScalar();
   $sql="select * from detailrequestdisplays where id='$model->id'";
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
			'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])",
		),
		'qty',
		array(
			'header'=>'Gudang',
			'name'=>'idwarehouse',
			'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])",
		),
		array(
			'class'=>'CButtonColumn',
			'buttons'=> array(
				'view'=>array(
					'visible'=>'false'
				)
			),
			'updateButtonOptions'=>array("class"=>'updateButton'),
			'updateButtonUrl'=>"Action::decodeUpdateDetailRequestDisplayUrl(\$data)",
			'deleteButtonUrl'=>"Action::decodeDeleteDetailRequestDisplayUrl(\$data)",
		)
	),
));
 ?>
 
	<div class="row buttons">
		<?php echo CHtml::submitButton(ucfirst($command)); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->