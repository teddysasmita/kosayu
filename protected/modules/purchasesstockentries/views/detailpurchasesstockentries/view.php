<?php
/* @var $this DetailpurchasesstockentriesController */
/* @var $model Detailpurchasesstockentries */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view','id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailpurchasesstockentries', 'url'=>array('index')),
	array('label'=>'Create Detailpurchasesstockentries', 'url'=>array('create')),
	array('label'=>'Update Detailpurchasesstockentries', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailpurchasesstockentries', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailpurchasesstockentries', 'url'=>array('admin')),
	array('label'=>'Ubah Detil', 'url'=>array('/purchasesstockentries/detailpurchasesstockentries/update',
      'iddetail'=>$model->iddetail)),
    */
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Detil Penerimaan Barang</h1>

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
			'name'=>'qty',
			'type'=>'number'
		),
		array(
				'name'=>'buyprice',
				'type'=>'number'
		),
		array(
				'name'=>'sellprice',
				'type'=>'number'
		),
		'remark',
		array(
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
	),
)); ?>
