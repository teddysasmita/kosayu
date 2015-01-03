<?php
/* @var $this PurchasesController */
/* @var $model Purchases */

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
         'url'=>array('/purchasesorder/detailpurchases/deleted', 'id'=>$model->id)),
	array('label'=>'Cetak', 'url'=>array('print', 'id'=>$model->id)),
		
);
?>

<h1>Pembelian dari Pemasok</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'regnum',
		'idatetime',
		'idorder',
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
		'pdatetime',
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
   $count=Yii::app()->db->createCommand("select count(*) from detailpurchases where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailpurchases where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
          'totalItemCount'=>$count,
          ));
   $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(  
         	array(
                  'header'=>'Kode Batch',
                  'name'=>'batchcode',
              ),
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
                 'header'=>'Disc',
                 'name'=>'discount',
                 'type'=>'number'
             ),
			array(
         		'header'=>'Hrg Jual @',
         		'name'=>'sellprice',
         		'type'=>'number',
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
 ?>
