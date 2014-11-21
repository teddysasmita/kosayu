<?php
/* @var $this DetailpartnersController */
/* @var $model Detailpartners */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailpartners', 'url'=>array('index')),
	array('label'=>'Create Detailpartners', 'url'=>array('create')),
	array('label'=>'Update Detailpartners', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailpartners', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailpartners', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('detailpartners/update',
      'iddetail'=>$model->iddetail)),
   */
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Rekanan / Agen</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'iddetail',
		//'id',
		array(
         'label'=>'Komposisi',
		 'name'=>'comname',
        ),
		array(
			'label'=>'Komisi',
			'name'=>'tip',
		),
		//'idunit',
		array(
         'label'=>'Userlog',
         'value'=>lookup::UserNameFromUserID($model->userlog),
      ),
		'datetimelog',
	),
)); ?>
