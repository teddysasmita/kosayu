<?php
/* @var $this DeliveryreplacesController */
/* @var $model Deliveryreplaces */

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
      array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
      array('label'=>'Data Detil yang dihapus', 
         'url'=>array('/purchasesorder/detaildeliveryreplaces/deleted', 'id'=>$model->id)),
	array('label'=>'Cetak', 'url'=>array('printsj', 'id'=>$model->id))
);
?>

<h1>Penukaran Pengiriman Barang</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		'deliverynum',
		'drivername',
		'vehicleinfo',
		'receivername',
		'receiveraddress',
		'receiverphone',
		array(
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
      
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detaildeliveryreplaces where id='$model->id'")
      ->queryScalar();
   $sql="select * from detaildeliveryreplaces where id='$model->id'";
   
   $dataProvider=new CSqlDataProvider($sql,array(
          'totalItemCount'=>$count,
          ));
   $this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
				array(
						'header'=>'Item Name',
						'name'=>'iditem',
						'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
				),
				'serialnum',
				array(
						'header'=>'Gudang',
						'name'=>'idwhsource',
						'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwhsource'])"
				),
		),
	));
 ?>
