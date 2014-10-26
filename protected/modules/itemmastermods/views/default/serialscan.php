<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
		'Proses'=>array('/site/proses'),
		'Daftar'=>array('default/index'),
		'Nomor seri Barang hasil scan dgn Laptop'
);
?>

<h1><?php echo "Masukkan Kriteria" ?></h1>

<div class="form">
<?php 
	echo CHtml::beginForm("index.php?r=stockentries/default/serialscan", 'get');	
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

<div class="row">
<?php 
	echo CHtml::submitButton('Kerjakan',array('name'=>'go'));
?>
</div>

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
							'header'=>'Nomor Urut',
							'name'=>'regnum',
					),
					array(
							'header'=>'Tanggal',
							'name'=>'idatetime',
					),
					array(
							'header'=>'Nama Barang',
							'name'=>'name',
					),
					array(
							'header'=>'Nomor Seri',
							'name'=>'serialnum',
					),
					array(
							'header'=>'Gudang',
							'name'=>'code',
					),
			),
	));
//}


?>
