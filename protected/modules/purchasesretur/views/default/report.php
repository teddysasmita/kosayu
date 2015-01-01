<?php
/* @var $this SalesposreportController */
/* @var $model Salesposreport */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Laporan Retur Pembelian',
);

$this->menu=array(
	//array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Laporan Retur Pembelian</h1>

<div class="form">

<?php  
	echo CHtml::beginForm(Yii::app()->createUrl('purchasesretur/default/reportprint'), 'get');
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
	<?php echo CHtml::label('Urutkan Berdasar', FALSE); ?>
	<?php
		echo CHtml::dropDownList('order', '', 
			array('B'=>'Kode Barang', 'S'=>'Pemasok', 'T'=>'Waktu'),
			array('empty'=>'Harap Pilih'));
	?>
	</div>
	
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Proses'); ?>
	</div>

<?php CHtml::endForm(); ?>

</div><!-- form -->
