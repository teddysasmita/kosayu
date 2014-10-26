<?php
/* @var $this PurchasesordersController */
/* @var $model Purchasesorders */

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
         'url'=>array('/purchasesorder/detailpurchasesorders/deleted', 'id'=>$model->id)),
      array('label'=>'Data Detil Voucher yang dihapus', 
         'url'=>array('/purchasesorder/detailpurchasesorders2/deleted', 'id'=>$model->id)),
);
?>

<h1>Pemesanan ke Pemasok</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		'rdatetime',
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
                'value'=>lookup::orderStatus($model->status)
            ),
		'remark',
		array(
               'label'=>'Userlog',
               'value'=>lookup::UserNameFromUserID($model->userlog),
            ),
		'datetimelog',
      
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailpurchasesorders where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailpurchasesorders where id='$model->id'";

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
                 'header'=>'Qty',
                 'name'=>'qty',
             ),
             array(
                 'header'=>'Harga @',
                 'name'=>'price',
                 'type'=>'number',
             ),
            array(
                 'header'=>'Biaya 1 @',
                 'name'=>'cost1',
                 'type'=>'number',
             ),
            array(
                 'header'=>'Biaya 2 @',
                 'name'=>'cost2',
                 'type'=>'number',
             ),
             array(
                 'header'=>'Disc',
                 'name'=>'discount',
                 'type'=>'number'
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
               'viewButtonUrl'=>"Action::decodeViewDetailPurchasesOrderUrl(\$data, $model->regnum)",
            )
         ),
   ));
   
   $count=Yii::app()->db->createCommand("select count(*) from detailpurchasesorders2 where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailpurchasesorders2 where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
          'totalItemCount'=>$count,
          ));
   $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(
              array(
                  'header'=>'Nama Voucher',
                  'name'=>'vouchername',
              ),
             array(
                 'header'=>'Nilai',
                 'name'=>'vouchervalue',
                'type'=>'number',
             ),
         ),
   ));
 ?>
