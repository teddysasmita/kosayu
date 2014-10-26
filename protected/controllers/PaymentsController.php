<?php

class PaymentsController extends Controller
{
      public $formid='AC1';
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','createdetail', 'updatedetail'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{     
            $model=new Payments;
            Yii::app()->session['master']='create';
            //as the operator enter for the first time, we initiate the default values
            if (!isset(Yii::app()->session['Payments'])) {
               $this->onInsertMaster($model);
               Yii::app()->session['Payments']=$model->attributes;
            }  
            
            // Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
            
            if (isset($_POST)){
               if(isset($_POST['yt0']))
               {
                  $model->attributes=$_POST['Payments'];
                  $details=Yii::app()->session['Detailpayments'];
                  
                  if($model->save()&&$this->saveNewDetails($details)) {
                     $idmaker=new idmaker();
                     $idmaker->saveRegNum($this->formid, $model->regnum);
                     Yii::app()->session->remove('Payments');
                     Yii::app()->session->remove('Detailpayments');
                     $this->redirect(array('view','id'=>$model->id));
                  }
                    
               } else if (isset($_POST['command'])){
                  // save the current master data before going to the detail page
                  if($_POST['command']=='adddetail') {
                     $model->attributes=$_POST['Payments'];
                     Yii::app()->session['Payments']=$_POST['Payments'];
                     $this->redirect(array('detailpayments/create','id'=>$model->id));
                  }
               }
            }
            
		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
            Yii::app()->session['master']='update';
            if (!isset(Yii::app()->session['Payments'])&&
                !isset(Yii::app()->session['Detailpayments'])) {
               Yii::app()->session['Payments']=$model->attributes;
               Yii::app()->session['Detailpayments']=$this->loadDetails($id);
            }
            
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
            

		if(isset($_POST)) {
               if(isset($_POST['yt0'])) {
			$model->attributes=$_POST['Payments'];
                  $details=Yii::app()->session['Detailpayments'];
                  $respond=true;
                  if (isset(Yii::app()->session['Deletedetailpayments'])) {
                     $deletedetails=Yii::app()->session['Deletedetailpayments'];
                     $respond=$this->deleteDetails($deletedetails);
                  }
                  if($model->save()&&$this->saveDetails($details)
                     &&$respond) {
                     Yii::app()->session->remove('Payments');
                     Yii::app()->session->remove('Detailpayments');
                     Yii::app()->session->remove('Deletedetailpayments');
                     $this->redirect(array('view','id'=>$model->id));
                  }
               }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
         if(isset(Yii::app()->session['Payments']))
            Yii::app()->session->remove('Payments');
         if(isset(Yii::app()->session['Detailpayments']))
            Yii::app()->session->remove('Detailpayments');
         $dataProvider=new CActiveDataProvider('Payments');
         $this->render('index',array(
		'dataProvider'=>$dataProvider,
         ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        	$model=new Payments('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Payments']))
			$model->attributes=$_GET['Payments'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Payments the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Payments::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Payments $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='payments-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
      
      
      public function actionCreateDetail()
      {
         //this action continues the process from the detail page
         $model=new Payments;
         $model->attributes=Yii::app()->session['Payments'];
         
         $details=Yii::app()->session['Detailpayments'];
         $this->onInsertDetail($model, $details);
         		
         $this->render('create',array(
		'model'=>$model,
         ));
      }
      
      public function actionUpdateDetail()
      {
         $model=new Payments;
         $model->attributes=Yii::app()->session['Payments'];
         
         $details=Yii::app()->session['Detailpayments'];
         $this->onUpdateDetail($model, $details);
            
         $this->render('update',array(
		'model'=>$model,
         ));
      }
      
      public function actionDeleteDetail()
      {
         $model=new Payments;
         $model->attributes=Yii::app()->session['Payments'];
         
         $details=Yii::app()->session['Detailpayments'];
         $this->onUpdateDetail($model, $details);
            
         $this->render('update',array(
		'model'=>$model,
         ));
      }
     
      protected function saveNewDetails(array $details)
      {
         $respond=true;
                  
         foreach ($details as $row) {
            $detailmodel=new Detailpayments;
            $detailmodel->attributes=$row;
            $respond=$respond&&$detailmodel->insert();
            if (!$respond) {
               break;
            }
         }
         return $respond;
      }
      
      protected function saveDetails(array $details)
      {
         $respond=true;
            
         foreach ($details as $row) {
            $detailmodel=Detailpayments::model()->findByPk($row['iddetail']);
            if($detailmodel==NULL)
               $detailmodel=new Detailpayments;
            $detailmodel->attributes=$row;
            $respond=$respond&&$detailmodel->save();
            if (!$respond) {
              break;
            }
         }
         return $respond;
      }
      
      protected function deleteDetails(array $details)
      {
         $respond=true;
            
         foreach ($details as $row) {
            $detailmodel=Detailpayments::model()->findByPk($row['iddetail']);
            $respond=$respond&&$detailmodel->delete();
            if (!$respond) {
              break;
            }
         }
         return $respond;
      }
      
      
      protected function loadDetails($id)
      {
         $sql="select * from detailpayments where id='$id'";
         $details=Yii::app()->db->createCommand($sql)->queryAll();
         
         return $details;
      }
      
      protected function onInsertMaster(& $model)
      {
         $idmaker=new idmaker();
         $model->id=$idmaker->getCurrentID2();
         $model->idatetime=$idmaker->getDateTime();
         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         $model->regnum=$idmaker->getRegNum($this->formid);
         $model->status=20;
      }
      
      protected function onInsertDetail(& $model, $details)
      {
         $total=0;
         $totaldisc=0;
         foreach ($details as $row) {
            $total=$total+$row['price'];
            $totaldisc=$totaldisc+$row['discount'];
         }
         $model->attributes=Yii::app()->session['Payments'];
         $model->total=$total;
         $model->discount=$totaldisc;
      }
      
      protected function onUpdateDetail(& $model, $details)
      {
         $total=0;
         $totaldisc=0;
         foreach ($details as $row) {
            $total=$total+$row['price'];
            $totaldisc=$totaldisc+$row['discount'];
         }
         $model->attributes=Yii::app()->session['Payments'];
         $model->total=$total;
         $model->discount=$totaldisc;      
      }
}
