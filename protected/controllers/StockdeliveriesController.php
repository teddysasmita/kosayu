<?php

class StockdeliveriesController extends Controller
{
      public $formid='AC10';
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
            $model=new Stockdeliveries;
            Yii::app()->session['master']='create';
            //as the operator enter for the first time, we initiate the default values
            if (!isset(Yii::app()->session['Stockdeliveries'])) {
               $this->onInsertMaster($model);
               Yii::app()->session['Stockdeliveries']=$model->attributes;
            }  
            
            // Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
            
            if (isset($_POST)){
               if(isset($_POST['yt0']))
               {
                  $model->attributes=$_POST['Stockdeliveries'];
                  $details=Yii::app()->session['Detailstockdeliveries'];
                  
                  if($model->save()&&$this->saveNewDetails($details)) {
                     Yii::app()->session->remove('Stockdeliveries');
                     Yii::app()->session->remove('Detailstockdeliveries');
                     $this->redirect(array('view','id'=>$model->id));
                  }
                    
               } else if (isset($_POST['command'])){
                  // save the current master data before going to the detail page
                  if($_POST['command']=='adddetail') {
                     $model->attributes=$_POST['Stockdeliveries'];
                     Yii::app()->session['Stockdeliveries']=$_POST['Stockdeliveries'];
                     $this->redirect(array('detailstockdeliveries/create','id'=>$model->id));
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
            if (!isset(Yii::app()->session['Stockdeliveries'])&&
                !isset(Yii::app()->session['Detailstockdeliveries'])) {
               Yii::app()->session['Stockdeliveries']=$model->attributes;
               Yii::app()->session['Detailstockdeliveries']=$this->loadDetails($id);
            }
            
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
            

		if(isset($_POST)) {
               if(isset($_POST['yt0'])) {
			$model->attributes=$_POST['Stockdeliveries'];
                  $details=Yii::app()->session['Detailstockdeliveries'];
                  $respond=true;
                  if (isset(Yii::app()->session['Deletedetailstockdeliveries'])) {
                     $deletedetails=Yii::app()->session['Deletedetailstockdeliveries'];
                     $respond=$this->deleteDetails($deletedetails);
                  }
                  if($model->save()&&$this->saveDetails($details)
                     &&$respond) {
                     Yii::app()->session->remove('Stockdeliveries');
                     Yii::app()->session->remove('Detailstockdeliveries');
                     Yii::app()->session->remove('Deletedetailstockdeliveries');
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
         if(isset(Yii::app()->session['Stockdeliveries']))
            Yii::app()->session->remove('Stockdeliveries');
         if(isset(Yii::app()->session['Detailstockdeliveries']))
            Yii::app()->session->remove('Detailstockdeliveries');
         $dataProvider=new CActiveDataProvider('Stockdeliveries');
         $this->render('index',array(
		'dataProvider'=>$dataProvider,
         ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        	$model=new Stockdeliveries('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Stockdeliveries']))
			$model->attributes=$_GET['Stockdeliveries'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Stockdeliveries the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Stockdeliveries::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Stockdeliveries $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='stockdeliveries-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
      
      
      public function actionCreateDetail()
      {
         //this action continues the process from the detail page
         $model=new Stockdeliveries;
         $model->attributes=Yii::app()->session['Stockdeliveries'];
         
         $details=Yii::app()->session['Detailstockdeliveries'];
         $this->onInsertDetail($model, $details);
         		
         $this->render('create',array(
		'model'=>$model,
         ));
      }
      
      public function actionUpdateDetail()
      {
         $model=new Stockdeliveries;
         $model->attributes=Yii::app()->session['Stockdeliveries'];
         
         $details=Yii::app()->session['Detailstockdeliveries'];
         $this->onUpdateDetail($model, $details);
            
         $this->render('update',array(
		'model'=>$model,
         ));
      }
      
      public function actionDeleteDetail()
      {
         $model=new Stockdeliveries;
         $model->attributes=Yii::app()->session['Stockdeliveries'];
         
         $details=Yii::app()->session['Detailstockdeliveries'];
         $this->onUpdateDetail($model, $details);
            
         $this->render('update',array(
		'model'=>$model,
         ));
      }
     
      protected function saveNewDetails(array $details)
      {
         $respond=true;
                  
         foreach ($details as $row) {
            $detailmodel=new Detailstockdeliveries;
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
            $detailmodel=Detailstockdeliveries::model()->findByPk($row['iddetail']);
            if($detailmodel==NULL)
               $detailmodel=new Detailstockdeliveries;
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
            $detailmodel=Detailstockdeliveries::model()->findByPk($row['iddetail']);
            $respond=$respond&&$detailmodel->delete();
            if (!$respond) {
              break;
            }
         }
         return $respond;
      }
      
      
      protected function loadDetails($id)
      {
         $sql="select * from detailstockdeliveries where id='$id'";
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
      }
      
      protected function onInsertDetail(& $model, $details)
      {
         
      }
      
      protected function onUpdateDetail(& $model, $details)
      {
         
      }
}
