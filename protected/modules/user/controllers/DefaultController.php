<?php

class DefaultController extends Controller
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
                            'actions'=>array('create','update','admin','delete', 'addAuthorization', 
                            'assignoperation', 'assigntask', 'assignrole',
                            'deleteassignedoperation', 'deleteassignedtask', 'deleteassignedrole',
                            'allowedhosts'),
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
		$model=new Users;
            
            $idmaker=new idmaker();
            $model->id=$idmaker->getcurrentID2();
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
            $model->active='-';
            
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save()) {
                            $sql="update users set passkey=PASSWORD('$model->passkey') where id='$model->id'";
                            Yii::app()->authdb->createCommand($sql)->execute();
                            $this->redirect(array('view','id'=>$model->id));
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save()) {
				$sql="update users set passkey=PASSWORD('$model->passkey') where id='$model->id'";
				Yii::app()->authdb->createCommand($sql)->execute();
				$this->redirect(array('view','id'=>$model->id));
			
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
		$dataProvider=new CActiveDataProvider('Users');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Users the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Users $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
      
      public function actionAddAuthorization($id) 
      {
         $authmodel=new Auths();
         $model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Auths']))
		{
			$authmodel->attributes=$_POST['Auths'];
			if($authmodel->save())
				$this->redirect(array('view','id'=>$model->id));
		}

            $this->redirect(array('auths/createforuser', 'iduser'=>$id));         
      }
      
      public function actionAssignoperation($id)
      {
         $model=new AuthAssignment;

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		
         if(isset($_POST['AuthAssignment'])) {
         	$model->attributes=$_POST['AuthAssignment'];
            if($model->save()) {
               $this->redirect(array('view', 'id'=>$id));
            }
         };
         
         $this->render('assignoperation',array(
			'model'=>$model,'userid'=>$id
         ));
      }
      
      public function actionAssigntask($id)
      {
         $model=new AuthAssignment;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

         if(isset($_POST['AuthAssignment']))
         {
            $model->attributes=$_POST['AuthAssignment'];
            if($model->save()) {
               $this->redirect(array('view', 'id'=>$id));
            }
         }
         
         $this->render('assigntask',array(
			'model'=>$model,'userid'=>$id
         ));
      }
      
        public function actionAssignrole($id)
        {
             $model=new AuthAssignment;

                // Uncomment the following line if AJAX validation is needed
                // $this->performAjaxValidation($model);

             if(isset($_POST['AuthAssignment']))
             {
                $model->attributes=$_POST['AuthAssignment'];
                if($model->save()) {
                   $this->redirect(array('view', 'id'=>$id));
                }
             }

             $this->render('assignrole',array(
                'model'=>$model,'userid'=>$id
             ));
        }
      
        public function loadModelAssignment($id)
	{
            $model=AuthAssignment::model()->findByPk($id);
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
	}
      
        public function actionDeleteassignedrole($id)
	{
             $this->loadModelAssignment($id)->delete();

                    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
             if(!isset($_GET['ajax']))
                            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	public function actionDeleteassignedtask($id)
	{
             $this->loadModelAssignment($id)->delete();

                    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
             if(!isset($_GET['ajax']))
                            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}
      
        public function actionDeleteassignedoperation($id)
	{
             $this->loadModelAssignment($id)->delete();

                    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
             if(!isset($_GET['ajax']))
                            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}
        
        public function actionAllowedhosts($id)
        {
            $this->render('allowedhosts',array(
                'id'=>$id
            ));
        }
}
