<?php
/* @var $this PurchasesreceiptsController */
/* @var $model Purchasesreceipts */

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
         'url'=>array('/purchasesorder/detailpurchasesreceipts/deleted', 'id'=>$model->id)),
	array('label'=>'Ringkasan', 'url'=>array('summary', 'id'=>$model->id)),
	array('label'=>'Cetak', 'url'=>array('printsummary', 'id'=>$model->id)),
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
         'label'=>'Nomor SJ',
         'value'=>$model->donum,
      ),
      array(
         'label'=>'Nama Pemasok',
         'value'=>lookup::SupplierNameFromSupplierID($model->idsupplier)
      ),
      array(
         'label'=>'Penanggungjawab',
         'value'=>$model->pic,
      ),
		array(
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailpurchasesreceipts where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailpurchasesreceipts where id='$model->id'";

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
               'header'=>'Sisa',
               'name'=>'leftqty',
            ),
			array(
               'header'=>'Qty',
               'name'=>'qty',
            ),
            array(
				'header'=>'Gudang',
				'name'=>'idwarehouse',
				'value'=>"lookup::WarehouseNameFromWarehouseID(\$data['idwarehouse'])"
			),
			array(
				'header'=>'Nomor PO',
				'name'=>'idpurchaseorder',
				//'value'=>"lookup::PurchasesOrderNumFromID(\$data['idpurchaseorder'])"
			),
			/*array(
                  'class'=>'CButtonColumn',
                  'buttons'=> array(
                      'delete'=>array(
                       'visible'=>'false'
                      ),
                     'update'=>array(
                        'visible'=>'false'
                     ),
					'view'=>array(
                        'visible'=>'false'
                     ),
                  ),
                  //'viewButtonUrl'=>"Action::decodeViewDetailStockEntryUrl(\$data)",
              )*/
         ),
   ));
 ?>
