<?php
/* @var $this PurchasesretursController */
/* @var $model Purchasesreturs */

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
         'url'=>array('/purchasesorder/detailpurchasesreturs/deleted', 'id'=>$model->id)),
);
?>

<h1>Retur Pembelian</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		array(
        'label'=>'Nama Pemasok',
        'value'=>lookup::SupplierNameFromSupplierID($model->idsupplier)
      ),
      array(
         'label'=>'Nomor PO',
         'value'=>lookup::PurchasesOrderNumFromID($model->idpurchaseorder),
      ),
		array(
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailpurchasesreturs where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailpurchasesreturs where id='$model->id'";

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
         'header'=>'Diterima',
         'type'=>'number',
         'name'=>'receivedqty',
      ),
      array(
         'header'=>'Harga Awal',
         'type'=>'number',
         'name'=>'prevprice',
      ), 
      array(
         'header'=>'Harga Baru',
         'type'=>'number',
         'name'=>'price',
      ),  
      array(
         'header'=>'Biaya 1',
         'type'=>'number',
         'name'=>'cost1',
      ),  
      array(
         'header'=>'Biaya 2',
         'type'=>'number',
         'name'=>'cost2',
      ),
		array(
			'header'=>'Dikembalikan',
			'type'=>'number',
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
         'viewButtonUrl'=>"Action::decodeViewDetailPurchaseReturUrl(\$data)",
      )      
   )));
 ?>
