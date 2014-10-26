<?php
/* @var $this CustomersController */
/* @var $model Customers */

$this->breadcrumbs=array(
    'Master Data'=>array('/site/masterdata'),
    'Daftar'=>array('index'),
    'Lihat Data'=>array('view','id'=>$model->id),
    'Sejarah',
);

$this->menu=array(
	//array('label'=>'List Customers', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Pelanggan</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()->
       select()->from('customers')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'customers-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'firstname',
		'lastname',
		'address',
		'phone',
		'email',
		'userlog',
		'datetimelog',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryCustomerUrl(\$data)",
		),
	),
    )); 
?>
