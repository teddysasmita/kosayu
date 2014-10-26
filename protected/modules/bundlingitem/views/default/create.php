   <?php
   /* @var $this ItemsController */
   /* @var $model Items */

   $this->breadcrumbs=array(
         'Items'=>array('index'),
         'Create',
   );

   if ($model->type==2) {
      $this->menu=array(
        /*
            array('label'=>'List Items', 'url'=>array('index')),
            array('label'=>'Manage Items', 'url'=>array('admin')),
        */
        array('label'=>'Add Detail', 'url'=>array('detailitems/create', 'id'=>$model->id),
             'linkOptions'=>array('id'=>'adddetail')),
      );
   };

   $namescript=<<<OK
      $(function() {
         $('#Items_type').change(function(event){
           var mainform;
           mainform=$('#items-form');
           $('#command').val('setitemtype');
           mainform.submit();
           event.preventDefault();
         });
         $('#adddetail').click(function(event){
           var mainform;
           var hiddenvar;
           mainform=$('#items-form');
           $('#command').val('adddetail');
           mainform.submit();
           event.preventDefault();
         });
     });
OK;
   Yii::app()->clientScript->registerScript('cscript', $namescript, CClientScript::POS_READY);
   ?>

   <h1>Create Items</h1>

   <?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'create')); ?>