<?php
/* @var $this DetailitemtipgroupsController */
/* @var $model Detailitemtipgroups */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailitemtipgroups', 'url'=>array('index')),
	array('label'=>'Create Detailitemtipgroups', 'url'=>array('create')),
	array('label'=>'Update Detailitemtipgroups', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailitemtipgroups', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailitemtipgroups', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('detailitemtipgroups/update',
      'iddetail'=>$model->iddetail)),
   */
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Kelompok Komisi Barang</h1>

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
         'label'=>'Userlog',
         'value'=>lookup::UserNameFromUserID($model->userlog),
      ),
		'datetimelog',
	),
)); ?>
