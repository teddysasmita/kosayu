<?php
/* @var $this CashboxesController */
/* @var $model Cashboxes */

$this->breadcrumbs=array(
   'Master Data'=>array('/site/master Data'),
   'Daftar'=>array('index'),
   'Data yang telah Terhapus',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Akun Kas</h1>

<?php 
    $data=Yii::app()->tracker->createCommand()
       ->select('a.*')->from('cashboxes a')->join('userjournal b', 'b.id=a.idtrack')
       ->where('b.action=:action', array(':action'=>'d'))->queryAll();
    $ap=new CArrayDataProvider($data);
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cashboxes-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		//'id',
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
                   'updateButtonUrl'=>"Action::decodeRestoreDeletedCashboxUrl(\$data)",
		),
	),
    )); 
?>
