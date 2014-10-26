<?php
/* @var $this StockexitsController */
/* @var $model Stockexits */

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
         'url'=>array('/purchasesorder/detailstockexits/deleted', 'id'=>$model->id)),
	array('label'=>'Ringkasan', 'url'=>array('summary', 'id'=>$model->id)),
	array('label'=>'Cetak', 'url'=>array('printsummary', 'id'=>$model->id)),
);
?>

<h1>Pengeluaran Barang</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		'transid',
		array(
			'label'=>'Jenis Transaksi',
			'value'=>Action::getTransName($model->transname),
		),
      	'transinfo',
		array(
			'label'=>'Nama Gudang',
			'value'=>lookup::WarehouseNameFromWarehouseID($model->idwarehouse)
		),
		array(
			'label'=>'Userlog',
			'value'=>lookup::UserNameFromUserID($model->userlog),
		),
		'datetimelog',
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailstockexits where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailstockexits where id='$model->id'";

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
              'header'=>'Serial Number',
              'name'=>'serialnum ',
              'value'=>"\$data['serialnum']"
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
                  'viewButtonUrl'=>"Action::decodeViewDetailStockExitUrl(\$data)",
              )
         ),
   ));
 ?>
