<?php
/* @var $this DetailsalesreplaceController */
/* @var $model Detailsalesreplace */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view','id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailsalesreplace', 'url'=>array('index')),
	array('label'=>'Create Detailsalesreplace', 'url'=>array('create')),
	array('label'=>'Update Detailsalesreplace', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailsalesreplace', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailsalesreplace', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('/purchasesorder/detailsalesreplace/update',
      'iddetail'=>$model->iddetail)),*/
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Detil Perubahan Penjualan</h1>

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
