<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
		'Proses'=>array('/site/proses'),
		'Daftar'=>array('default/index'),
		'Lacak Nomor Seri'
);
?>

<h1><?php echo "Masukkan Kriteria" ?></h1>

<div class="form">
<?php 
	echo CHtml::beginForm("index.php?r=stockadmin/default/trace", 'get');	
?>
	
<div class="row">
<?php
echo CHtml::label('Nomor Seri','serialnum');
echo CHtml::textField('serialnum', $serialnum);
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

<h2><?php echo $serialnum; ?></h2>
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
							'header'=>'Gudang',
							'name'=>'code',
					),
					array(
							'header'=>'Sedia',
							'name'=>'avail',
							'value'=>"lookup::StockAvailName(\$data['avail'])",
					),
					array(
						'header'=>'Status',
						'name'=>'status',
						'value'=>"lookup::StockStatusName(\$data['status'])",
					),
			),
	));
//}


?>
