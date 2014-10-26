<?php
/* @var $this Detailpurchasesorders2Controller */
/* @var $model Detailpurchasesorders2 */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Daftar'=>array('default/index'),
      'Lihat Data'=>array('default/view', 'id'=>$id),
	'Data Detil yang telah terhapus',
);

$this->menu=array(
   /*
   array('label'=>'List Detailpurchasesorders2', 'url'=>array('index')),
   array('label'=>'Create Detailpurchasesorders2', 'url'=>array('create')),
   */
);

?>

<h1>Detil Pemesanan ke Pemasok</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php
    
    $data=Yii::app()->tracker->createCommand()->
       select('a.*')->from('detailpurchasesorders2 a')->join('userjournal b', 'b.id=a.idtrack')
       ->where('b.action=:action', array(':action'=>'d'))->queryAll();
    $ap=new CArrayDataProvider($data);
?>
 

<?php
 $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detailpurchasesorders2-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'iddetail',
		'id',
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
