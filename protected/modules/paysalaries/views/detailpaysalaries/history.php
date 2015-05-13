<?php
/* @var $this DetailpaysalariesController */
/* @var $model Detailpaysalariesorders */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/paysalariesorder/detailpaysalariesorders/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detailpaysalariesorders', 'url'=>array('index')),
	//array('label'=>'Create Detailpaysalariesorders', 'url'=>array('create')),
);

?>

<h1>Detil Pembayaran Gaji Karyawan</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detailpaysalaries')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailpaysalariesorders-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'iddetail',
		'id',
		'name',
		'amount',
		/*
		
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryDetailPaySalaryUrl(\$data)",
		),
	),
)); ?>
