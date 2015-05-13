<?php
/* @var $this PaysalariesController */
/* @var $model Paysalaries */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Sejarah',
);

$this->menu=array(
	array('label'=>'Daftar', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Pembayaran Gaji Karyawan</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('paysalaries')->where('id=:id',array(':id'=>$model->id))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'paysalaries-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'regnum',
		'idatetime',
		'idemployee',
		/*
		'discount',
		'status',
		'remark',
		'userlog',
		'datetimelog',
		*/
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryPaySalaryUrl(\$data)",
		),
	),
)); ?>
