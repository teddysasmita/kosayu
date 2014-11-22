<?php
/* @var $this PartnersController */
/* @var $model Partners */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
   'Daftar'=>array('index'),
   'Lihat Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Ubah Data', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Hapus Data', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
	array('label'=>'Sejarah', 'url'=>array('history', 'id'=>$model->id)),
	array('label'=>'Data Detil yang dihapus', 
         'url'=>array('/partner/detailpartners/deleted', 'id'=>$model->id)),
);
?>

<h1>Rekanan / Agen</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'name',
		'defaulttip',
		'address',
		'phone',	
		array(
			'label'=>'Userlog',
			'value'=>lookup::UserNameFromUserID($model->userlog),
		),
		'datetimelog',
	),
)); ?>

<?php 
   $count=Yii::app()->db->createCommand("select count(*) from detailpartners where id='$model->id'")
      ->queryScalar();
   $sql="select * from detailpartners where id='$model->id'";

   $dataProvider=new CSqlDataProvider($sql,array(
          'totalItemCount'=>$count,
          ));
   $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(
         	array(
         		'header'=>'Komposisi',
         		'name'=>'comname',
         	),
         	array(
         		'header'=>'Komisi(%)',
         		'name'=>'tip',
         		'type'=>'number'
         	),
            array(
                  'class'=>'CButtonColumn',
                  'buttons'=> array(
                      'delete'=>array(
                       'visible'=>'false'
                      ),
                     'update'=>array(
                        'visible'=>'false'
                     ),
                  	'view'=>array(
                  		'visible'=>'false'
                  	)
                  ),
                  //'viewButtonUrl'=>"Action::decodeViewDetailPartnerUrl(\$data)",
              )
         ),
   ));
 ?>
