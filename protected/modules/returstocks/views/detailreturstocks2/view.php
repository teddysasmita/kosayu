<?php
/* @var $this DetailstockexitsController */
/* @var $model Detailstockexits */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailstockexits', 'url'=>array('index')),
	array('label'=>'Create Detailstockexits', 'url'=>array('create')),
	array('label'=>'Update Detailstockexits', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailstockexits', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailstockexits', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('detailstockexits/update',
      'iddetail'=>$model->iddetail)),
   */
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Pengeluaran Barang</h1>

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
		array(
         'label'=>'Nomor Serial',
         'value'=>$model->serialnum
      ),
		array(
         'label'=>'Userlog',
         'value'=>lookup::UserNameFromUserID($model->userlog),
      ),
		'datetimelog',
	),
)); ?>
