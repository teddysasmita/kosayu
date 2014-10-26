<?php
/* @var $this PurchasespaymentsController */
/* @var $model Purchasespayments */

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
         'url'=>array('/purchasesorder/detailpurchasespayments/deleted', 'id'=>$model->id)),
);
?>

<h1>Pembayaran pada Pemasok</h1>

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
		'label'=>'Total',
		'value'=>number_format($model->total)
		),
		array(
				'label'=>'Diskon',
				'value'=>number_format($model->discount)
		),
		array(
			'label'=>'Status',
			'value'=>lookup::paymentStatus($model->status)
		),
		array(
            'label'=>'Userlog',
            'value'=>lookup::UserNameFromUserID($model->userlog),
        ),
		'datetimelog',
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailpurchasespayments where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailpurchasespayments where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
      'totalItemCount'=>$count,
   ));
   $this->widget('zii.widgets.grid.CGridView', array(
   'dataProvider'=>$dataProvider,
   'columns'=>array(
      array(
             'header'=>'Nomor PO',
             'name'=>'idpurchaseorder',
             'value'=>"lookup::PurchasesOrderNumFromID(\$data['idpurchaseorder'])"
         ),
      array(
         'header'=>'Total',
         'type'=>'number',
         'name'=>'total',
      ),
      array(
         'header'=>'Diskon',
         'type'=>'number',
         'name'=>'discount',
      ), 
      array(
         'header'=>'Terbayar',
         'type'=>'number',
         'name'=>'paid',
      ), 
      array(
         'header'=>'Dibayar',
         'type'=>'number',
         'name'=>'amount',
      ), 
      /*array(
         'class'=>'CButtonColumn',
         'buttons'=> array(
            'delete'=>array(
               'visible'=>'false'
             ),
            'update'=>array(
               'visible'=>'false'
            )
         ),
         'viewButtonUrl'=>"Action::decodeViewDetailPurchaseMemoUrl(\$data)",
      )*/      
   )));
 ?>
