<?php
/* @var $this DetailacquisitionsController */
/* @var $model Detailacquisitions */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailacquisitions', 'url'=>array('index')),
	array('label'=>'Create Detailacquisitions', 'url'=>array('create')),
	array('label'=>'Update Detailacquisitions', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailacquisitions', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailacquisitions', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('detailacquisitions/update',
      'iddetail'=>$model->iddetail)),
   */
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Detil Akuisisi Barang dan Nomor Seri</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'iddetail',
		//'id',
		//'idunit',
		array(
			'label'=>'Nomor Serial',
			'value'=>$model->serialnum
      	),
		array(
			'label'=>'Kondisi',
			'value'=>$model->avail
		),
		array(
         'label'=>'Userlog',
         'value'=>lookup::UserNameFromUserID($model->userlog),
      ),
		'datetimelog',
	),
)); ?>
