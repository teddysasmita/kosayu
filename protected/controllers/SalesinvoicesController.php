<?php

class SalesinvoicesController extends Controller
{
      public $formid='AC2';
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
            $model=new Salesinvoices;
            Yii::app()->session['master']='create';
            //as the operator enter for the first time, we initiate the default values
            if (!isset(Yii::app()->session['Salesinvoices'])) {
               $this->onInsertMaster($model);
               Yii::app()->session['Salesinvoices']=$model->attributes;
            }  
            
            // Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
            
            if (isset($_POST)){
               if(isset($_POST['yt0']))
               {
                  $model->attributes=$_POST['Salesinvoices'];
                  $details=Yii::app()->session['Detailsalesinvoices'];
                  
                  if($model->save()&&$this->saveNewDetails($details)) {
                     $idmaker=new idmaker();
                     $idmaker->saveRegNum($this->formid, $model->regnum);
                     Yii::app()->session->remove('Salesinvoices');
                     Yii::app()->session->remove('Detailsalesinvoices');
                     $this->redirect(array('view','id'=>$model->id));
                  }   
               } else if (isset($_POST['command'])){
                  // save the current master data before going to the detail page
                  if($_POST['command']=='adddetail') {
                     $model->attributes=$_POST['Salesinvoices'];
                     Yii::app()->session['Salesinvoices']=$_POST['Salesinvoices'];
                     $this->redirect(array('detailsalesinvoices/create','id'=>$model->id));
                  } else if ($_POST['command']=='setidcustomer') {
                     $model->attributes=$_POST['Salesinvoices'];
                     Yii::app()->session['Salesinvoices']=$_POST['Salesinvoices'];
                  } else if ($_POST['command']=='setidinvoice') {
                     $model->attributes=$_POST['Salesinvoices'];
                     Yii::app()->session['Salesinvoices']=$_POST['Salesinvoices'];
                     $this->loadSalesOrder($model);
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
            if (!isset(Yii::app()->session['Salesinvoices'])&&
                !isset(Yii::app()->session['Detailsalesinvoices'])) {
               Yii::app()->session['Salesinvoices']=$model->attributes;
               Yii::app()->session['Detailsalesinvoices']=$this->loadDetails($id);
            }
            
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
            

		if(isset($_POST)) {
               if(isset($_POST['yt0'])) {
			$model->attributes=$_POST['Salesinvoices'];
                  $details=Yii::app()->session['Detailsalesinvoices'];
                  $respond=true;
                  if (isset(Yii::app()->session['Deletedetailsalesinvoices'])) {
                     $deletedetails=Yii::app()->session['Deletedetailsalesinvoices'];
                     $respond=$this->deleteDetails($deletedetails);
                  }
                  if($model->save()&&$this->saveDetails($details)
                     &&$respond) {
                     Yii::app()->session->remove('Salesinvoices');
                     Yii::app()->session->remove('Detailsalesinvoices');
                     Yii::app()->session->remove('Deletedetailsalesinvoices');
                     $this->redirect(array('view','id'=>$model->id));
                  } else {
                     throw new CHttpException(404,'The requested page does not exist.');
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
		$model=$this->loadModel($id);
            $model->rowdeleted='1';
            $model->save();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
         if(isset(Yii::app()->session['Salesinvoices']))
            Yii::app()->session->remove('Salesinvoices');
         if(isset(Yii::app()->session['Detailsalesinvoices']))
            Yii::app()->session->remove('Detailsalesinvoices');
         $dataProvider=new CActiveDataProvider('Salesinvoices');
         $this->render('index',array(
		'dataProvider'=>$dataProvider,
         ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        	$model=new Salesinvoices('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Salesinvoices']))
			$model->attributes=$_GET['Salesinvoices'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Salesinvoices the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Salesinvoices::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Salesinvoices $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='salesinvoices-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
      
      
      public function actionCreateDetail()
      {
         //this action continues the process from the detail page
         $model=new Salesinvoices;
         $model->attributes=Yii::app()->session['Salesinvoices'];
         
         $details=Yii::app()->session['Detailsalesinvoices'];
         $this->onInsertDetail($model, $details);
         		
         $this->render('create',array(
		'model'=>$model,
         ));
      }
      
      public function actionUpdateDetail()
      {
         $model=new Salesinvoices;
         $model->attributes=Yii::app()->session['Salesinvoices'];
         
         $details=Yii::app()->session['Detailsalesinvoices'];
         $this->onUpdateDetail($model, $details);
            
         $this->render('update',array(
		'model'=>$model,
         ));
      }
      
      public function actionDeleteDetail()
      {
         $model=new Salesinvoices;
         $model->attributes=Yii::app()->session['Salesinvoices'];
         
         $details=Yii::app()->session['Detailsalesinvoices'];
         $this->onUpdateDetail($model, $details);
            
         $this->render('update',array(
		'model'=>$model,
         ));
      }
     
      protected function saveNewDetails(array $details)
      {
         $respond=true;
                  
         foreach ($details as $row) {
            $detailmodel=new Detailsalesinvoices;
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
            $detailmodel=Detailsalesinvoices::model()->findByPk($row['iddetail']);
            if($detailmodel==NULL)
               $detailmodel=new Detailsalesinvoices;
            $detailmodel->attributes=$row;
            $respond=$respond&&$detailmodel->save();
            if (!$respond) {
              throw new CHttpException(405,'The requested page does not exist.');
              break;
            }
         }
         return $respond;
      }
      
      protected function deleteDetails(array $details)
      {
         $respond=true;
            
         foreach ($details as $row) {
            $detailmodel=Detailsalesinvoices::model()->findByPk($row['iddetail']);
            $respond=$respond&&$detailmodel->delete();
            if (!$respond) {
              break;
            }
         }
         return $respond;
      }
      
      
      protected function loadDetails($id)
      {
         $sql="select * from detailsalesinvoices where id='$id'";
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
         $model->status=20;
         $model->regnum=$idmaker->getRegNum($this->formid);
         $model->idcustomer='-';
         $model->idinvoice='-';
      }
      
      protected function onInsertDetail(& $model, $details)
      {
         $total=0;
         $totaldisc=0;
         foreach ($details as $row) {
            $total=$total+$row['price']*$row['qty'];
            $totaldisc=$totaldisc+$row['discount']*$row['qty'];
         }
         $model->attributes=Yii::app()->session['Salesinvoices'];
         $model->total=$total;
         $model->discount=$totaldisc;
      }
      
      protected function onUpdateDetail(& $model, $details)
      {
         $total=0;
         $totaldisc=0;
         foreach ($details as $row) {
            $total=$total+$row['price']*$row['qty'];
            $totaldisc=$totaldisc+$row['discount']*$row['qty'];
         }
         $model->attributes=Yii::app()->session['Salesinvoices'];
         $model->total=$total;
         $model->discount=$totaldisc;      
      }
      
      protected function loadSalesOrder($model) {
         $sql="select iddetail as iddtorder, id as idorder, iditem, qty as lqty, price, discount from detailsalesorders where id='$model->idinvoice'";
         $rawdata=Yii::app()->db->createCommand($sql)->queryAll();
         $idmaker=new idmaker();
         foreach ($rawdata as &$row) {
            $row['lqty']=$row['lqty']-$this->getSent($row['iddtorder']);
            $row['qty']=0;
            $row['id']=$model->id;
            $row['iddetail']=$idmaker->getCurrentID2();
         };
         Yii::app()->session['Detailsalesinvoices']=$rawdata;                
      }      
      
      public function getSent($iddtorder) {
         $sql="select sum(qty) from detailsalesinvoices where iddetailorder='$iddtorder'";
         $amount=Yii::app()->db->createCommand($sql)->queryScalar();
         if ($amount>0) 
            return $amount;
         else
            return 0;
      }
           
}
