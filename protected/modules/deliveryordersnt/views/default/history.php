<?php
/* @var $this DeliveryordersntController */
/* @var $model Deliveryordersnt */

$this->breadcrumbs=array(
    'Proses'=>array('/site/proses'),
    'Daftar'=>array('index'),
    'Lihat Data'=>array('view','id'=>$model->id),
    'Sejarah',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Pengiriman Barang Tanpa Transaksi</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()->
       select()->from('deliveryordersnt')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'deliveryordersnt-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'idatetime',
		'regnum',
		'receivername',
		'drivername',
		'vehicleinfo',
		/*array(
                    'class'=>'CButtonColumn',
                   'buttons'=> array(
                        'view'=>array(
                            'visible'=>'false',
                        ),
                        'delete'=>array(
                          'visible'=>'false',
                        ),
                    ),
                   'updateButtonUrl'=>"Action::decodeRestoreHistorySalesorderUrl(\$data)",
		),*/
	),
    )); 
?>
