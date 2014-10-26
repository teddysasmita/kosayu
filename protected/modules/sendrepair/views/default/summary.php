<?php
/* @var $this PurchasesreceiptsController */
/* @var $model Purchasesreceipts */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
   'Lihat Data'=>array('view', 'id'=>$model->id),
	'Ringkasan'		
);

$this->menu=array(
	/*array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
	array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
	array('label'=>'Data Detil yang dihapus', 
         'url'=>array('/purchasesorder/detailpurchasesreceipts/deleted', 'id'=>$model->id)),
	array('label'=>'Ringkasan', 'url'=>array('summary', 'id'=>$model->id)),
	*/
);
?>

<h1>Penerimaan Barang dari Pemasok</h1>

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
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
	),
)); ?>

<?php 
   $count=count(Yii::app()->db
     ->createCommand("select iditem, count(iditem) from detailpurchasesreceipts ".
			"where id='$model->id' group by iditem")
		->queryAll());
   $sql="select iditem, count(iditem) as totalqty from detailpurchasesreceipts ".
   		"where id='$model->id' group by iditem";

   $dataProvider=new CSqlDataProvider($sql,array(
          'totalItemCount'=>$count,'keyField'=>'iditem',
          ));
   $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(
			/*array(
				'header'=>'Nomor PO',
				'value'=>"lookup::PurchasesOrderNumFromID(\$data['idpurchaseorder'])",
			),*/
            array(
               'header'=>'Item Name',
               'name'=>'iditem',
               'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
            ),
			/*array(
				'header'=>'Nama Gudang',
				'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
			),*/
            array(
              'header'=>'Total',
              'name'=>'totalqty',
              'value'=>"\$data['totalqty']"
            ),
   		)
	));
 ?>
