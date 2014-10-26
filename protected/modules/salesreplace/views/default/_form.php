<?php
/* @var $this SalesreplaceController */
/* @var $model Salesreplace */
/* @var $form CActiveForm */
?>


<?php

$myscript=<<<EOS
	$('#Salesreplace_invnum').change(function() {
		$('#command').val('setInvnum');	
		$('#salesreplace-form').submit();
	});

	$('.updateButton').click(
		function(evt) {
			$('#command').val('updateDetail');
			$('#detailcommand').val(this.href);
			$('#salesreplace-form').submit();
		});   
EOS;
Yii::app()->clientScript->registerScript('myscript', $myscript, CClientScript::POS_READY);
?>

<div class="form">

<?php if($command=='create') 
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salesreplace-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/salesreplace/default/create")
      ));
   else if($command=='update')
      $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salesreplace-form',
	'enableAjaxValidation'=>true,
      'action'=>Yii::app()->createUrl("/salesreplace/default/update", array('id'=>$model->id))
      )); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php 
       echo $form->hiddenField($model,'id');
       echo $form->hiddenField($model,'userlog');
       echo $form->hiddenField($model,'datetimelog');  
       echo $form->hiddenField($model,'regnum');
       echo $form->hiddenfield($model, 'totalcash');
       echo $form->hiddenfield($model, 'totalnoncash');
       echo $form->hiddenfield($model, 'totaldiff');
       echo CHtml::hiddenField('command', '', array('id'=>'command'));
       echo CHtml::hiddenField('detailcommand', '', array('id'=>'detailcommand'));
   ?>
   <div class="row">
		<?php echo $form->labelEx($model,'Tanggal'); ?>
		<?php 
            //echo $form->dateField($model,'idatetime',array('size'=>19,'maxlength'=>19)); 
            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
               'name'=>'Salesreplace[idatetime]',
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
		<?php echo $form->labelEx($model,'invnum'); ?>
		<?php 
			echo $form->textField($model,'invnum',array('size'=>20,'maxlength'=>12)); 
			//echo CHtml::Button('Cari', array('id'=>'setInvnum'));
		?>
		<?php echo $form->error($model,'invnum'); ?>
		
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reason'); ?>
		<?php echo $form->textArea($model,'reason',array('cols'=>40,'rows'=>5)); ?>
		<?php echo $form->error($model,'reason'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'totalcash'); ?>
		<?php echo CHtml::label(number_format($model->totalcash),'false',
                    array('class'=>'money')); 
        ?>
		<?php echo $form->error($model,'totalcash'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'totalnoncash'); ?>
		<?php echo CHtml::label(number_format($model->totalnoncash),'false',
                    array('class'=>'money')); 
        ?>
		<?php echo $form->error($model,'totalnoncash'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'totaldiff'); ?>
		<?php echo CHtml::label(number_format($model->totaldiff),'false',
                    array('class'=>'money')); 
        ?>
		<?php echo $form->error($model,'totaldiff'); ?>
	</div>
	
	<?php 
	if (isset(Yii::app()->session['Detailsalesreplace'])) {
       $rawdata=Yii::app()->session['Detailsalesreplace'];
       $count=count($rawdata);
    } else {
       $count=Yii::app()->db->createCommand("select count(*) from detailsalesreplace where id='$model->id'")->queryScalar();
       $sql="select * from detailsalesreplace where id='$model->id'";
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
               'header'=>'Qty',
               'name'=>'qty',
            ),
            array(
               'header'=>'Harga',
               'name'=>'price',
               'type'=>'number'
            ),
			array(
				'header'=>'Diskon',
				'name'=>'discount',
				'type'=>'number'
			),
          	array(
				'header'=>'Nama Barang Baru',
				'name'=>'iditemnew',
				'value'=>"lookup::ItemNameFromItemID(\$data['iditemnew'])"
			),
			array(
				'header'=>'Qty Baru',
				'name'=>'qtynew',
			),
			array(
				'header'=>'Harga Baru',
				'name'=>'pricenew',
				'type'=>'number'
			),
			array(
				'header'=>'Diskon Baru',
				'name'=>'discountnew',
				'type'=>'number'
			),
			array(
				'header'=>'Proses',
				'name'=>'deleted',
				'value'=>"lookup::SalesreplaceNameFromCode(\$data['deleted'])",
			),
            array(
				'class'=>'CButtonColumn',
				'buttons'=> array(
					'delete'=>array(
						'visible'=>'false'
					),
					'view'=>array(
						'visible'=>'false'
					),
				),
				'updateButtonOptions'=>array("class"=>'updateButton'),
               'updateButtonUrl'=>"Action::decodeUpdateDetailSalesReplaceUrl(\$data)",
            )
          ),
    ));
	?>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->