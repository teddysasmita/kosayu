<?php
/* @var $this DeliveryordersntController */
/* @var $model Deliveryordersnt */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
        array('label'=>'Sejarah', 'url'=>array('history','id'=>$model->id)),
        array('label'=>'Data Detil yang telah terhapus', 'url'=>array('detaildeliveryordersnt/deleted','id'=>$model->id)),
	array('label'=>'Cetak', 'url'=>array('printsjm', 'id'=>$model->id))
);
?>

<h1>Pengiriman Barang Tanpa Transaksi</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'regnum',
		'idatetime',
		'receivername',
		'receiveraddress',
		'receiverphone',
		'drivername',
		'vehicleinfo',         
	),
)); ?>

<?php 
$count=Yii::app()->db->createCommand("select count(*) from detaildeliveryordersnt where id='$model->id'")->queryScalar();
$sql="select * from detaildeliveryordersnt where id='$model->id'";

$dataProvider=new CSqlDataProvider($sql,array(
       'totalItemCount'=>$count,
       ));
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
        array(
			'header'=>'Nama Barang',
			'name'=>'itemname'
		), 
		array(
			'header'=>'Jumlah',
			'name'=>'qty'
		),  	
          array(
              'class'=>'CButtonColumn',
              'buttons'=> array(
                  'delete'=>array(
                      'visible'=>'false'
                  ),
                  'update'=>array(
                     'visible'=>'false'
                  )
              ),
              'viewButtonUrl'=>"Action::decodeViewDetailDeliveryOrderNtUrl(\$data)",
          )
      ),
));
 ?>

