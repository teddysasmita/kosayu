<?php
/* @var $this ItemsController */
/* @var $model Items */

$this->breadcrumbs=array(
	'Items'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Items', 'url'=>array('index')),
	array('label'=>'Create Items', 'url'=>array('create')),
	array('label'=>'Update Items', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Items', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Items', 'url'=>array('admin')),
);
?>

<h1>View Items #<?php echo $model->id; ?></h1>
   
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'code',
            array(
              'label'=>'Golongan',
              'value'=>lookup::TypeToName($model->type)
            ),
		'name',
		'brand',
		'objects',
		'model',
		'attribute',
		'picture',
		'userlog',
		'datetimelog',
	),
)); ?>

<?php
   if ($model->type==2) {
      
      $sql="select count(*) as total from detailitems a join items b on b.id=a.iditem where a.id='$model->id'";
      $count=Yii::app()->db->createCommand($sql)->queryScalar();
      $sql="select b.name, a.id, a.iditem, a.iddetail, a.qty from detailitems a join items b on b.id=a.iditem where a.id='$model->id'";
      $rawdata=Yii::app()->db->createCommand($sql)->queryAll();
     
      $dataProvider=new CArrayDataProvider($rawdata,array(
               'totalItemCount'=>$count,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     ));
      
      $this->widget('zii.widgets.grid.CGridView', array(
         'dataProvider'=>$dataProvider,
         'columns'=>array(
           array(
               'header'=>'Nama Barang',
               'name'=>'name',
               //'value'=>"\$data['id']"
               'value'=>"lookup::ItemNameFromItemID(\$data['iditem'])"
           ),
          array(
              'header'=>'Jumlah',
              'name'=>'qty',
          ),
      )
   )); 
         
      }
      
      ?>
