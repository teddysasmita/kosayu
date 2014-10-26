<?php 

class DetailstockentriesController extends Controller
{
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
                  //'postOnly + delete', // we only allow deletion via POST request
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
				'actions'=>array('create','update'),
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
	public function actionView($iddetail)
	{
         $model=$this->loadModel($iddetail);
         if(($model==NULL)&&isset(Yii::app()->session['Detailstockentries'])) {
            $model=new Detailstockentries;
            $model->attributes=$this->loadSession($iddetail);
         }   
         $this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id)
	{
		$model=new Detailstockentries;
            $master=Yii::app()->session['master'];
            
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
            $model->id=$id;  
            $idmaker=new idmaker();
            $model->iddetail=$idmaker->getCurrentID2();
            
            $this->onInsert($model);
            
            if(isset($_POST['Detailstockentries']))
		{
                  $temp=Yii::app()->session['Detailstockentries'];
                  $model->attributes=$_POST['Detailstockentries'];
                  $temp[]=$_POST['Detailstockentries'];
                  Yii::app()->session['Detailstockentries']=$temp;
			/*$model->attributes=$_POST['Detailstockentries'];
			if($model->save())*/
                  if ($master=='create')
                     $this->redirect(array('stockentries/createdetail'));
                  else if($master=='update')
                     $this->redirect(array('stockentries/updatedetail'));
		}
            $this->render('create',array(
			'model'=>$model,'master'=>$master
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($iddetail)
	{
		$model=$this->loadModel($iddetail);
            $master=Yii::app()->session['master'];
            
            if(($model==NULL)&&isset(Yii::app()->session['Detailstockentries'])) {
               $model=new Detailstockentries;
               $model->attributes=$this->loadSession($iddetail);
            }

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Detailstockentries']))
		{
			$temp=Yii::app()->session['Detailstockentries'];
                  $model->attributes=$_POST['Detailstockentries'];
                  foreach ($temp as $tk=>$tv) {
                     if($tv['iddetail']==$_POST['Detailstockentries']['iddetail']) {
                        $temp[$tk]=$_POST['Detailstockentries'];
                        break;
                     }
                  }
                  Yii::app()->session['Detailstockentries']=$temp;
			
                  //if($model->save())
                  if ($master=='create')
                     $this->redirect(array('stockentries/createdetail'));
                  else if($master=='update')
                     $this->redirect(array('stockentries/updatedetail'));
		}

		$this->render('update',array(
			'model'=>$model,'master'=>$master
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($iddetail)
	{
		//$this->loadModel($iddetail)->delete();
            $details=Yii::app()->session['Detailstockentries'];
            foreach ($details as $ik => $iv) {
               if($iv['iddetail']==$iddetail) {
                  if(isset(Yii::app()->session['Deletedetailstockentries']))
                     $deletelist=Yii::app()->session['Deletedetailstockentries'];
                  $deletelist[]=$iv;
                  Yii::app()->session['Deletedetailstockentries']=$deletelist;
                  unset($details[$ik]);
                  break;
               }
            }
            Yii::app()->session['Detailstockentries']=$details;
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])) 
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('stockentries/update', 'id'=>$iv['id']));
       }
             

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Detailstockentries');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Detailstockentries('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Detailstockentries']))
			$model->attributes=$_GET['Detailstockentries'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Detailstockentries the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Detailstockentries::model()->findByPk($id);
		/*
             if($model===null)
               throw new CHttpException(404,'The requested page does not exist.');
		*/
            return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Detailstockentries $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='detailstockentries-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
      
      protected function onInsert(& $model)
      {
         
      }
      
      protected function loadSession($iddetail)
      {
         $details=Yii::app()->session['Detailstockentries'];
         foreach ($details as $row) {
            if($row['iddetail']==$iddetail)
               return $row;
         }
         throw new CHttpException(404,'The requested page does not exist.');
         return NULL;
      }
}
