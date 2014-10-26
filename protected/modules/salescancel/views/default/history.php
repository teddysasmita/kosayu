<?php
/* @var $this SalescancelController */
/* @var $model Salescancel */

$this->breadcrumbs=array(
    'Proses'=>array('/site/proses'),
    'Daftar'=>array('index'),
    'Lihat Data'=>array('view','id'=>$model->id),
    'Sejarah',
);

$this->menu=array(
	//array('label'=>'List Salescancel', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Pembatalan Penjualan</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()->
       select()->from('salescancel')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'salescancel-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'idatetime',
		'regnum',
		'invnum',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistorySalesCancelUrl(\$data)",
		),
	),
    )); 
?>
