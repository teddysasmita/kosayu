<?php
/* @var $this DetailidguideprintsController */
/* @var $model Detailidguideprints */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailidguideprints', 'url'=>array('index')),
	array('label'=>'Create Detailidguideprints', 'url'=>array('create')),
	array('label'=>'Update Detailidguideprints', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailidguideprints', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailidguideprints', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('detailidguideprints/update',
      'iddetail'=>$model->iddetail)),
   */
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Detil Cetak Kartu Guide</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'iddetail',
		//'id',
		array(
         'label'=>'Nama Guide',
         'value'=>lookup::GuideNameFromID($model->idguide``)
      ),
		//'idunit',
		array(
         'label'=>'Userlog',
         'value'=>lookup::UserNameFromUserID($model->userlog),
      ),
		'datetimelog',
	),
)); ?>
