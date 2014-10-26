<?php
/* @var $this SalesreplaceController */
/* @var $model Salesreplace */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
   'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
	array('label'=>'Cetak', 'url'=>array('printreplace', 'id'=>$model->id))
);
?>

<h1>Ganti Barang Penjualan</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'idatetime',
		'regnum',
		'invnum',
		'reason',
		array(
			'name'=>'totalcash',
			'type'=>'number'
		),
		array(
			'name'=>'totalnoncash',
			'type'=>'number'
		),
		array(
			'name'=>'totaldiff',
			'type'=>'number'
		),
		//'userlog',
		//'datetimelog',
	),
)); ?>

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
		'totalItemCount'=>count($rawdata),
		 'keyField'=>'iddetail'
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
				'header'=>'Barang Baru',
				'name'=>'iditemnew',
				'value'=>"lookup::ItemNameFromItemID(\$data['iditemnew'])"
			),
			array(
				'header'=>'Qty Baru',
				'name'=>'qtynew',
				'type'=>'number'
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
		),
	));
?>
