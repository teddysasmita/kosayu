<?php
/* @var $this DetailpaysalariesController */
/* @var $model Detailpaysalaries */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view','id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailpaysalaries', 'url'=>array('index')),
	array('label'=>'Create Detailpaysalaries', 'url'=>array('create')),
	array('label'=>'Update Detailpaysalaries', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailpaysalaries', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailpaysalaries', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('/paysalariesorder/detailpaysalaries/update',
      'iddetail'=>$model->iddetail)),*/
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Detil Pembayaran Gaji Karyawan</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'amount',
		array(
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
	),
)); ?>
