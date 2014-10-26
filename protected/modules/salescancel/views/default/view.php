<?php
/* @var $this SalescancelController */
/* @var $model Salescancel */

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
	array('label'=>'Cetak', 'url'=>array('printcancel', 'id'=>$model->id)),
);
?>

<h1>Pembatalan Penjualan</h1>

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
		//'userlog',
		//'datetimelog',
	),
)); ?>

<?php 
	if (isset($rawdata)) {
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
          ),
    	));
	};
?>
