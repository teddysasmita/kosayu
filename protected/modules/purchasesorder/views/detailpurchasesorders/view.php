<?php
/* @var $this DetailpurchasesordersController */
/* @var $model Detailpurchasesorders */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view','id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailpurchasesorders', 'url'=>array('index')),
	array('label'=>'Create Detailpurchasesorders', 'url'=>array('create')),
	array('label'=>'Update Detailpurchasesorders', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailpurchasesorders', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailpurchasesorders', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('/purchasesorder/detailpurchasesorders/update',
      'iddetail'=>$model->iddetail)),*/
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Detil Pemesanan ke Pemasok</h1>

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
               'label'=>'Biaya 1',
               'type'=>'number',
               'value'=>$model->cost1
            ),
      array(
               'label'=>'Biaya 2',
               'type'=>'number',
               'value'=>$model->cost2
            ),
		array(
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
	),
)); ?>
