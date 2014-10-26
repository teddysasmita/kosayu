<?php
/* @var $this SalesordersController */
/* @var $model Salesorders */

$this->breadcrumbs=array(
    'Proses'=>array('/site/proses'),
    'Daftar'=>array('index'),
    'Data yang telah terhapus',
);

$this->menu=array(
	array('label'=>'List Salesorders', 'url'=>array('index')),
	array('label'=>'Create Salesorders', 'url'=>array('create')),
);

?>

<h1>Pemesanan oleh Pelanggan</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()
       ->select('a.*')->from('salesorders a')->join('userjournal b', 'b.id=a.idtrack')
       ->where('b.action=:action', array(':action'=>'d'))->queryAll();
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'salesorders-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'idatetime',
		array(
                   'name'=>'Nama Pelanggan',
                   'value'=>"lookup::CustomerNameFromCustomerID(\$data['idcustomer'])",
                ),
		'total',
		'discount',
		'status',
		array(
                    'class'=>'CButtonColumn',
                   'buttons'=> array(
                        'view'=>array(
                            'visible'=>'false',
                        ),
                        'delete'=>array(
                          'visible'=>'false',
                        ),
                    ),
                   'updateButtonUrl'=>"Action::decodeRestoreDeletedSalesorderUrl(\$data)",
		),
	),
    )); 
?>
