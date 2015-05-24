<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
		'Proses'=>array('/site/proses'),
		'Daftar'=>array('default/index'),
		'Berdasarkan Aliran Stok'
);

$this->menu=array(
		array('label'=>'Cetak', 'url'=>array('stockFlow')),
);
?>

<h1><?php echo "Masukkan Kriteria" ?></h1>

<div class="form">
<?php 
	echo CHtml::beginForm("index.php?r=stockadmin/default/flow", 'post');	
?>
	
<div class="row">
	<?php echo CHtml::label('Tanggal Awal', FALSE); ?>
	<?php 
		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'name'=>'cstart',
                     // additional javascript options for the date picker plugin
			'options'=>array(
			'showAnim'=>'fold',
			'dateFormat'=>'yy/mm/dd',
			'defaultdate'=>idmaker::getDateTime()
			),
			'htmlOptions'=>array(
				'style'=>'height:20px;',
			),
			'value'=>$cstart
		)); 
	?>
</div>

<div class="row">
	<?php echo CHtml::label('Tanggal Akhir', FALSE); ?>
	<?php 
		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'name'=>'cend',
                     // additional javascript options for the date picker plugin
			'options'=>array(
			'showAnim'=>'fold',
			'dateFormat'=>'yy/mm/dd',
			'defaultdate'=>idmaker::getDateTime()
			),
			'htmlOptions'=>array(
				'style'=>'height:20px;',
			),
			'value'=>$cend
		)); 
	?>
</div>

<div class="row">
	<?php echo CHtml::label('Awalan Kode', FALSE); ?>
	<?php 
		echo CHtml::textField('cprefix', $cprefix); 
	?>
</div>

<div class="row">
<?php 
	echo CHtml::submitButton('Kerjakan',array('name'=>'go'));
?>
</div>

<?php 
	echo CHtml::endForm();
?>
</div> <!-- form -->

<?php 

//if (isset($alldata)) {
	if (isset(Yii::app()->session['stockflowreport']))
		$alldata = Yii::app()->session['stockflowreport'];
	else
		$alldata = array();
	
	$mydp = new CArrayDataProvider($alldata, array(
			'keyField'=>'batchcode',
			'pagination'=>array(
				'pageSize'=>20
			),
	));
	$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'quantity-grid',
			'dataProvider'=>$mydp,
			'columns'=>array(
					//'id',
					array(
							'header'=>'Kode Barang',
							'name'=>'batchcode',
					),
					array(
							'header'=>'Nama Barang',
							'name'=>'name',
					),
					array(
							'header'=>'Jml Awal',
							'name'=>'startqty',
					),
					array(
							'header'=>'Jml Beli',
							'name'=>'receiveqty',
					),
					array(
							'header'=>'Jml Jual',
							'name'=>'soldqty',
					),
					array(
							'header'=>'Jml Retur Beli',
							'name'=>'returqty',
					),
					array(
							'header'=>'Jml Retur Jual',
							'name'=>'salereturqty',
					),
					array(
							'header'=>'Penyesuaian',
							'name'=>'stockadjustqty',
					),
					array(
							'header'=>'Jml Akhir',
							'name'=>'endqty',
					),
			),
	));
//}


?>
