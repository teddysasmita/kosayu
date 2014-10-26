<?php
/* @var $this DetailsalesretursController */
/* @var $model Detailsalesreturs */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view','id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailsalesreturs', 'url'=>array('index')),
	array('label'=>'Create Detailsalesreturs', 'url'=>array('create')),
	array('label'=>'Update Detailsalesreturs', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailsalesreturs', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailsalesreturs', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('/purchasesorder/detailsalesreturs/update',
      'iddetail'=>$model->iddetail)),
   */
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Detil Retur Penjualan</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'iddetail',
		//'id',
		array(
			'name'=>'iditem',
			'value'=>lookup::ItemNameFromItemID($model->iditem)
		),
		'qty',
		array(
			'name'=>'idwarehouse',
			'value'=>lookup::WarehouseNameFromWarehouseID($model->idwarehouse)
		),
		array(
			'label'=>'userlog',
			'value'=>lookup::UserNameFromUserID($model->userlog)
		), 
		'datetimelog',
	),
)); ?>
