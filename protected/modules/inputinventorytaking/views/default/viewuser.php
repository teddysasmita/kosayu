<?php
/* @var $this InputinventorytakingsController */
/* @var $model Inputinventorytakings */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Aktifitas User',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	/*array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
	array('label'=>'Sejarah', 'url'=>array('history','id'=>$model->id)),
	array('label'=>'Data Detil yang telah terhapus', 'url'=>array('detailinputinventorytakings/deleted','id'=>$model->id)),
	*/
);
?>

<h1>Input Stok Opname</h1>

<?php 
$dataProvider=new CArrayDataProvider($detaildata, array(
	'keyField'=>'iddetail'
));
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
           array(
               'header'=>'Tanggal',
               'name'=>'idatetime',
           ),
          array(
              'header'=>'Nama Barang',
              'name'=>'name',
          ),
          array(
              'header'=>'Gudang',
              'name'=>'idwarehouse',
				'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
          ),
			array(
				'header'=>'Qty',
				'name'=>'qty',
				'type'=>'number'
			),
          array(
              'class'=>'CButtonColumn',
              'buttons'=> array(
                  'view'=>array(
                     'visible'=>'false'
                  )
              ),
				'deleteButtonUrl'=>"Action::decodeDeleteDetailInputInventoryTakingUrl(\$data)",
              'updateButtonUrl'=>"Action::decodeUpdate2DetailInputInventoryTakingUrl(\$data)",
          )
      ),
));
 ?>

