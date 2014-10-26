<?php
/* @var $this RequestdisplaysController */
/* @var $model Requestdisplays */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
        array('label'=>'Sejarah', 'url'=>array('history','id'=>$model->id)),
        array('label'=>'Data Detil yang telah terhapus', 'url'=>array('detailrequestdisplays/deleted','id'=>$model->id)),
	array('label'=>'Cetak', 'url'=>array('printsjm', 'id'=>$model->id))
);
?>

<h1>Permintaan Barang Display</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'regnum',
		'idatetime',
		array(
			'name'=>'idsales',
			'label'=>'Nama Sales',
			'value'=>lookup::SalesNameFromID($model->idsales)
		)         
	),
)); ?>

<?php 
$count=Yii::app()->db->createCommand("select count(*) from detailrequestdisplays where id='$model->id'")->queryScalar();
$sql="select * from detailrequestdisplays where id='$model->id'";

$dataProvider=new CSqlDataProvider($sql,array(
       'totalItemCount'=>$count,
       ));
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
        array(
			'header'=>'Nama Barang',
			'name'=>'iditem',
			'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])",
		), 
		array(
			'header'=>'Jumlah',
			'name'=>'qty'
		),  	
		array(
			'header'=>'Gudang',
			'name'=>'idwarehouse',
			'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])",
		),
          array(
              'class'=>'CButtonColumn',
              'buttons'=> array(
                  'delete'=>array(
                      'visible'=>'false'
                  ),
                  'update'=>array(
                     'visible'=>'false'
                  )
              ),
              'viewButtonUrl'=>"Action::decodeViewDetailRequestDisplayUrl(\$data)",
          )
      ),
));
 ?>

