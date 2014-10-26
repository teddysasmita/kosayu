<?php
/* @var $this DetailpurchasesretursController */
/* @var $model Detailpurchasesreturs */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('default/index'),
      'Lihat Data'=>array('default/view', 'id'=>$id),
	'Data Detil yang telah terhapus',
);

$this->menu=array(
	/*array('label'=>'List Detailpurchasesreturs', 'url'=>array('index')),
	array('label'=>'Create Detailpurchasesreturs', 'url'=>array('create')),
      */
 );

?>

<h1>Detil Retur Pembelian</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php
    
    $data=Yii::app()->tracker->createCommand()->
       select('a.*')->from('detailpurchasesreturs a')->join('userjournal b', 'b.id=a.idtrack')
       ->where('b.action=:action and a.id=:id', array(':action'=>'d', 'id'=>$id))->queryAll();
    $ap=new CArrayDataProvider($data);
?>
 

<?php
 $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailpurchasesreturs-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'iddetail',
		'id',
		'iditem',
		'sernum',
		/*
		'price',
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
                   'updateButtonUrl'=>"Action::decodeRestoreHistoryCustomerUrl(\$data)",
		),
	),
)); ?>
