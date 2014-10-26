<?php
/* @var $this PurchasesstockentriesController */
/* @var $model Purchasesstockentries */

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
         'url'=>array('/purchasesorder/detailpurchasesstockentries/deleted', 'id'=>$model->id)),
	/*array('label'=>'Ringkasan', 'url'=>array('summary', 'id'=>$model->id)),*/
	array('label'=>'Cetak', 'url'=>array('printlpb', 'id'=>$model->id))
);
?>

<h1>Penerimaan Barang</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		'sjnum',
		'ponum',
		'remark',		
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
   $count=Yii::app()->db->createCommand("select count(*) from detailpurchasesstockentries where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailpurchasesstockentries where id='$model->id'";

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
			'header'=>'Harga Jual',
			'name'=>'sellprice',
			'type'=>'number'
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
                  'viewButtonUrl'=>"Action::decodeViewDetailPurchasesStockEntryUrl(\$data)",
              )
         ),
   ));	
 ?>
