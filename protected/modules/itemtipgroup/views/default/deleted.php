<?php
/* @var $this ItemtipgroupsController */
/* @var $model Itemtipgroups */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Data yang telah dihapus',
);

$this->menu=array(
	array('label'=>'Daftar', 'url'=>array('index')),
	array('label'=>'Tambah Data', 'url'=>array('create')),
);

?>

<h1>Kelompok Komisi Barang</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php
    
    $data=Yii::app()->tracker->createCommand()->
       select('a.*')->from('itemtipgroups a')->join('userjournal b', 'b.id=a.idtrack')
       ->where('b.action=:action', array(':action'=>'d'))->queryAll();
    $ap=new CArrayDataProvider($data);
?>
 

<?php
 $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'itemtipgroups-grid',
	'dataProvider'=>$ap,
	'columns'=>array(
		'id',
		'name',
		'pct',
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
         'updateButtonUrl'=>"Action::decodeRestoreHistoryItemtipGroupUrl(\$data)",
		),
	),
)); ?>
