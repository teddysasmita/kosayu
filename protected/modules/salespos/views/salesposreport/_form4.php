<?php
/* @var $this SalesposreportController */
/* @var $model Salesposreport */
/* @var $form CActiveForm */
?>

<div class="form">

<?php  
	echo CHtml::beginForm(Yii::app()->createUrl('/salespos/salesposreport/getsales4'), 'get');
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
	<?php echo CHtml::label('Nama Pemasok', FALSE); ?>
	<?php
		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'name'=>'suppliercode',
			'sourceUrl'=> Yii::app()->createUrl('LookUp/getSupplier'),
			'htmlOptions'=>array(
				'style'=>'height:20px;',
			),
		));
	?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Proses'); ?>
	</div>

<?php CHtml::endForm(); ?>

</div><!-- form -->
	
	