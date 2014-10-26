<?php
/* @var $this DetailrequestdisplaysController */
/* @var $model Detailrequestdisplays */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Daftar'=>array('default/index'),
      'Lihat Data'=>array('default/view', 'id'=>$id),
	'Data Detil yang telah terhapus',
);

$this->menu=array(
   /*
   array('label'=>'List Detailrequestdisplays', 'url'=>array('index')),
   array('label'=>'Create Detailrequestdisplays', 'url'=>array('create')),
   */
);

?>

<h1>Detil Permintaan Barang Display</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php
    
    $data=Yii::app()->tracker->createCommand()->
       select('a.*')->from('detailrequestdisplays a')->join('userjournal b', 'b.id=a.idtrack')
       ->where('b.action=:action', array(':action'=>'d'))->queryAll();
    $ap=new CArrayDataProvider($data);
?>
 

<?php
 $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailrequestdisplays-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'iddetail',
		'id',
		'iditem',
		'qty',
		'idwarehouse',
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
