<?php
/* @var $this Detailpurchasesorders2Controller */
/* @var $model Detailpurchasesorders2 */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
   'Daftar'=>array('default/index'),
   'Lihat Data'=>array('default/view', 'id'=>$model->id),
   'Ubah Data'=>array('default/update', 'id'=>$model->id),
   'Lihat Detil'=>array('/purchasesorder/detailpurchasesorders2/view',
         'iddetail'=>$model->iddetail),
   'Sejarah'
);

$this->menu=array(
	//array('label'=>'List Detailpurchasesorders2', 'url'=>array('index')),
	//array('label'=>'Create Detailpurchasesorders2', 'url'=>array('create')),
);

?>

<h1>Sejarah</h1>

<?php    $data=Yii::app()->tracker->createCommand()->
       select()->from('detailpurchasesorders2')->where('id=:id',array(':id'=>$model->iddetail))->queryAll();
    $ap=new CArrayDataProvider($data);
 ?> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailpurchasesorders2-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		//'iddetail',
		//'id',
		'vouchername',
		'vouchervalue',
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
)); ?>
