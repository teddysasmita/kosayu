<?php
/* @var $this StockexitreportController */
/* @var $model Stockexitreport */
/* @var $form CActiveForm */
?>

<div class="form">

<?php  
	echo CHtml::beginForm(Yii::app()->createUrl('/stockexits/default/getexcel'), 'get');
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>


	<div class="row">
		<?php echo CHtml::label('Tanggal Awal', FALSE); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'startdate',
                     // additional javascript options for the date picker plugin
                  'options'=>array(
                     'showAnim'=>'fold',
                     'dateFormat'=>'yy/mm/dd',
                     'defaultdate'=>idmaker::getDateTime()
                  ),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
               )); 
		?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Tanggal Akhir', FALSE); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'enddate',
                     // additional javascript options for the date picker plugin
                  'options'=>array(
                     'showAnim'=>'fold',
                     'dateFormat'=>'yy/mm/dd',
                     'defaultdate'=>idmaker::getDateTime()
                  ),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
               )); 
		?>
	</div>
	
	<div class="row">
	<?php echo CHtml::label('Jenis Barang', FALSE); ?>
	<?php
               //$brands=Yii::app()->db->createCommand()->selectDistinct('brand')->from('items')->queryColumn();

		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'name'=>'objects',
			'sourceUrl'=> Yii::app()->createUrl('LookUp/getObjects'),
			'htmlOptions'=>array(
				'style'=>'height:20px;',
			),
		));
	?>
	</div>
	
	<?php echo CHtml::label('Merk Barang', FALSE); ?>
	<?php
               //$brands=Yii::app()->db->createCommand()->selectDistinct('brand')->from('items')->queryColumn();

		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'name'=>'brand',
			'sourceUrl'=> Yii::app()->createUrl('LookUp/getBrand'),
			'htmlOptions'=>array(
				'style'=>'height:20px;',
			),
		));
	?>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Proses'); ?>
	</div>

<?php CHtml::endForm(); ?>

</div><!-- form -->