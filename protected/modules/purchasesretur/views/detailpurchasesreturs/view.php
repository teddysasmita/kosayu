<?php
/* @var $this DetailpurchasesretursController */
/* @var $model Detailpurchasesreturs */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailpurchasesreturs', 'url'=>array('index')),
	array('label'=>'Create Detailpurchasesreturs', 'url'=>array('create')),
	array('label'=>'Update Detailpurchasesreturs', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailpurchasesreturs', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailpurchasesreturs', 'url'=>array('admin')),
   array('label'=>'Ubah Detil', 'url'=>array('detailpurchasesreturs/update',
      'iddetail'=>$model->iddetail)),
   */
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Detil Retur Pembelian</h1>

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
         'label'=>'Harga Terakhir', 
         'value'=>number_format($model->prevprice)
		),
      array(
         'label'=>'Harga Baru', 
         'value'=>number_format($model->price)
		),
      array(
         'label'=>'Biaya 1 Terakhir', 
         'value'=>number_format($model->prevcost1)
		),
      array(
         'label'=>'Biaya 1 Baru', 
         'value'=>number_format($model->cost1)
		),
      array(
         'label'=>'Biaya 2 Terakhir', 
         'value'=>number_format($model->prevcost2)
		),
      array(
         'label'=>'Biaya 2 Baru', 
         'value'=>number_format($model->cost2)
		),
      array(
         'label'=>'Userlog',
         'value'=>lookup::UserNameFromUserID($model->userlog),
      ),
		'datetimelog',
	),
)); ?>
