<?php
/* @var $this SalesordersController */
/* @var $model Salesorders */

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
        array('label'=>'Sejarah', 'url'=>array('history','id'=>$model->id)),
        array('label'=>'Data Detil yang telah terhapus', 'url'=>array('detailsalesorders/deleted','id'=>$model->id)),
);
?>

<h1>Pemesanan oleh Pelanggan</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idatetime',
            array(
                'label'=>'Pelanggan',
                'value'=>lookup::CustomerNameFromCustomerID($model->idcustomer)
            ),
		array(
                'label'=>'Total',
                'type'=>'number',
                'name'=>'total'
            ),
            array(
                'label'=>'Diskon',
                'type'=>'number',
                'name'=>'discount'
            ),
            array(
                'label'=>'Status',
                'value'=>lookup::orderStatus($model->status)
            )  
	),
)); ?>

<?php 
$count=Yii::app()->db->createCommand("select count(*) from detailsalesorders where id='$model->id'")->queryScalar();
$sql="select * from detailsalesorders where id='$model->id'";

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
              'header'=>'Price',
              'name'=>'price',
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
              'viewButtonUrl'=>"Action::decodeViewDetailSalesOrderUrl(\$data)",
          )
      ),
));
 ?>

