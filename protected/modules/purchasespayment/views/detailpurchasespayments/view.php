<?php
/* @var $this DetailpurchasespaymentsController */
/* @var $model Detailpurchasespayments */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailpurchasespayments', 'url'=>array('index')),
	array('label'=>'Create Detailpurchasespayments', 'url'=>array('create')),
	array('label'=>'Update Detailpurchasespayments', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailpurchasespayments', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailpurchasespayments', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('detailpurchasespayments/update',
      'iddetail'=>$model->iddetail)),
   */
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Detil Pembayaran Pada Pemasok</h1>

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
      array(
         'label'=>'Harga Baru', 
         'value'=>number_format($model->price)
		),
      array(
         'label'=>'Userlog',
         'value'=>lookup::UserNameFromUserID($model->userlog),
      ),
		'datetimelog',
	),
)); ?>
