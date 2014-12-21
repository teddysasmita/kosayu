<?php
/* @var $this CashboxesController */
/* @var $model Cashboxes */

$this->breadcrumbs=array(
    'Master Data'=>array('/site/masterdata'),
    'Daftar'=>array('index'),
    'Lihat Data'=>array('view','id'=>$model->id),
    'Sejarah',
);

$this->menu=array(
	//array('label'=>'List Cashboxes', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Akun Kas</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()->
       select()->from('cashboxes')->where("id='$model->id'")->queryAll();
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cashboxes-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'name',
		'accountnum',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryCashboxUrl(\$data)",
		),
	),
    )); 
?>
