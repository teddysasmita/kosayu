<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
		'Proses'=>array('/site/proses'),
		'Daftar'=>array('default/index'),
		'Berdasarkan Kuantitas dan Lokasi'
);
?>

<h1><?php echo "Masukkan Kriteria" ?></h1>

<div class="form">
<?php 
	echo CHtml::beginForm("index.php?r=stockadmin/default/quantity", 'get');	
?>
	
<div class="row">
<?php
echo CHtml::label('Nama Barang','itemname');
$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
		'name'=>'itemname',
		'sourceUrl'=>Yii::app()->createUrl('LookUp/getItemName'),
		'htmlOptions'=>array('size'=>50),
		'value'=>$itemname,
));
?>
</div>

<div class="row">
<?php
echo CHtml::label('Gudang', 'whcode');
$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
		'name'=>'whcode',
		'sourceUrl'=>Yii::app()->createUrl('LookUp/getWarehouse'),
		'htmlOptions'=>array('size'=>50),
		'value'=>$whcode
));
?>
</div>

<div class="row">
<?php
echo CHtml::label('Kondisi', 'status');
echo CHtml::dropDownList('status', $status, array('Semua'=>'Semua', '1'=>'Bagus', '0'=>'Rusak', 
	'2'=>'Servis'),
	array('empty'=>'Harap Pilih'));
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
	$mydp = new CArrayDataProvider($alldata, array(
			'keyField'=>'iddetail',
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
							'header'=>'Nama Barang',
							'name'=>'name',
					),
					array(
							'header'=>'Jumlah',
							'name'=>'total',
					),
					array(
							'header'=>'Gudang',
							'name'=>'code',
					),
					array(
							'header'=>'Kondisi',
							'name'=>'status',
							'value'=>"lookup::StockStatusName(\$data['status'])"
					),
					array(
						'header'=>'Sedia',
						'name'=>'avail',
						'value'=>"lookup::StockAvailName(\$data['avail'])"
					),
			),
	));
//}


?>
