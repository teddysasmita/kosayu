<?php
/* @var $this DetailreturstocksController */
/* @var $model Detailreturstocks */


 $this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view','id'=>$model->id),
   'Lihat Detil'
 );

$this->menu=array(
	/*array('label'=>'List Detailreturstocks', 'url'=>array('index')),
	array('label'=>'Create Detailreturstocks', 'url'=>array('create')),
	array('label'=>'Update Detailreturstocks', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailreturstocks', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailreturstocks', 'url'=>array('admin')),
	array('label'=>'Ubah Detil', 'url'=>array('/returstocks/detailreturstocks/update',
      'iddetail'=>$model->iddetail)),
    */
   array('label'=>'Sejarah', 'url'=>array('history', 'iddetail'=>$model->iddetail)),
);
?>

<h1>Detil Pengembalian Barang ke Pemasok</h1>

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
		'remark',
		array(
				'label'=>'Gudang',
				'value'=>lookup::WarehouseNameFromWarehouseID($model->idwarehouse)
		),
		array(
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
	),
)); 

?>
