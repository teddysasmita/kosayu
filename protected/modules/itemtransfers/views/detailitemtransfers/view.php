<?php
/* @var $this DetailitemtransfersController */
/* @var $model Detailitemtransfers */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view','id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailitemtransfers', 'url'=>array('index')),
	array('label'=>'Create Detailitemtransfers', 'url'=>array('create')),
	array('label'=>'Update Detailitemtransfers', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailitemtransfers', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailitemtransfers', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('/purchasesorder/detailitemtransfers/update',
      'iddetail'=>$model->iddetail)),
   */
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Detil Pemindahan Barang</h1>

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
			'label'=>'userlog',
			'value'=>lookup::UserNameFromUserID($model->userlog)
		), 
		'datetimelog',
	),
)); ?>
