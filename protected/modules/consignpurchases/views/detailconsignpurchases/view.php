<?php
/* @var $this DetailconsignpurchasesController */
/* @var $model Detailconsignpurchases */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view','id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailconsignpurchases', 'url'=>array('index')),
	array('label'=>'Create Detailconsignpurchases', 'url'=>array('create')),
	array('label'=>'Update Detailconsignpurchases', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailconsignpurchases', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailconsignpurchases', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('/consignpurchasesorder/detailconsignpurchases/update',
      'iddetail'=>$model->iddetail)),*/
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Pembelian Konsinyasi dari Pemasok</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'iddetail',
		//'id',
		array(
               'label'=>'Nama Barang',
               'value'=>lookup::ItemNameFromItemID($model->iditem)
            ),
		//'idunit',
		'qty',
		'discount',
		array(
               'label'=>'Harga',
               'type'=>'number',
               'value'=>$model->price
            ),
		array(
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
	),
)); ?>
