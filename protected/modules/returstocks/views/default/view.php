<?php
/* @var $this ReturstocksController */
/* @var $model Returstocks */

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
         'url'=>array('/purchasesorder/detailreturstocks/deleted', 'id'=>$model->id)),
	/*array('label'=>'Ringkasan', 'url'=>array('summary', 'id'=>$model->id)),*/
	array('label'=>'Cetak', 'url'=>array('printlpb', 'id'=>$model->id))
);
?>

<h1>Pengembalian Barang ke Pemasok</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		array(
			'label'=>'Catatan',
			'type'=>'ntext',
			'name'=>'remark',
		),
      array(
         'label'=>'Nama Pemasok',
         'value'=>lookup::SupplierNameFromSupplierID($model->idsupplier)
      ),
		array(
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailreturstocks where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailreturstocks where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
          'totalItemCount'=>$count,
          ));
   $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(
		array(
			'header'=>'Nama Barang',
			'name'=>'iditem',
			'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
		),
		array(
			'header'=>'Qty',
			'name'=>'qty',
			'type'=>'number'
		),
		array(
			'header'=>'Harga Beli',
			'name'=>'buyprice',
			'type'=>'number'
		),
		array(
			'header'=>'Gudang',
			'name'=>'idwarehouse',
			'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
		),
		/*array(
			'header'=>'Catatan',
			'name'=>'remark',
		),*/
			array(
                  'class'=>'CButtonColumn',
                  'buttons'=> array(
                      'delete'=>array(
                       'visible'=>'false'
                      ),
                     'update'=>array(
                        'visible'=>'false'
                     ),
					/*'view'=>array(
                        'visible'=>'false'
                     ),*/
                  ),
                  'viewButtonUrl'=>"Action::decodeViewDetailReturStockUrl(\$data)",
              )
         ),
   ));	
 ?>
 
 <?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailreturstocks2 where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailreturstocks2 where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
          'totalItemCount'=>$count,
          ));
   $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(
		array(
			'header'=>'Nama Barang',
			'name'=>'iditem',
			'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
		),
		array(
			'header'=>'Nomor Serial',
			'name'=>'serialnum',
		),
		array(
			'header'=>'Catatan',
			'name'=>'remark',
			'type'=>'ntext'
		),
		/*array(
			'header'=>'Catatan',
			'name'=>'remark',
		),*/
			array(
                  'class'=>'CButtonColumn',
                  'buttons'=> array(
                      'delete'=>array(
                       'visible'=>'false'
                      ),
                     'update'=>array(
                        'visible'=>'false'
                     ),
					/*'view'=>array(
                        'visible'=>'false'
                     ),*/
                  ),
                  'viewButtonUrl'=>"Action::decodeViewDetailReturStock2Url(\$data)",
              )
         ),
   ));	
 ?>
