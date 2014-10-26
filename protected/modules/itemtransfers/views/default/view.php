<?php
/* @var $this ItemtransfersController */
/* @var $model Itemtransfers */

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
         'url'=>array('/itemtransfers/detailitemtransfers/deleted', 'id'=>$model->id)),
	array('label'=>'Cetak', 'url'=>array('printtb', 'id'=>$model->id))
);
?>

<h1>Pemindahan Barang</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		array(
			'name'=>'idwhsource',
			'value'=>lookup::WarehouseNameFromWarehouseID($model->idwhsource),
		),
		array(
			'name'=>'idwhdest',
			'value'=>lookup::WarehouseNameFromWarehouseID($model->idwhdest),
		),
		array(
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
      
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailitemtransfers where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailitemtransfers where id='$model->id'";

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
				array(
						'header'=>'Jumlah',
						'name'=>'qty',
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
						'viewButtonUrl'=>"Action::decodeViewDetailDeliveryOrderUrl(\$data)",
				)
		),
	));
 ?>
