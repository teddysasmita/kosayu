<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC66';
	public $tracker;
	public $state;
           
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
        
        /*
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
        */    
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
            $this->trackActivity('v');
            $this->render('view',array(
                'model'=>$this->loadModel($id),
            ));
		} else {
        	throw new CHttpException(404,'You have no authorization for this operation.');
        };
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                    Yii::app()->user->id))  {            

			$this->state='c';
			$this->trackActivity('c');
                
			$model=new Stickertoguides;
			$this->afterInsert($model);
                
		// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);
                        
			if(isset($_POST['Stickertoguides'])) {
            	if (isset($_POST['yt1'])) {        
					$model->attributes=$_POST['Stickertoguides'];
					$this->beforePost($model);
                    
					$respond = $model->save();
					if(!$respond) {
						throw new CHttpException(5002,'There is an error in master posting: '.serialize($model->errors));
					}
                    
					/*if(isset(Yii::app()->session['Detailstickertoguides']) ) {
                    	$details=Yii::app()->session['Detailstickertoguides'];
                    	$respond=$this->saveDetails($details);
                    	if (!$respond)
                    		throw new CHttpException(5002,'There is an error in detail posting');
					}*/
                    
					$this->afterPost($model);
					/*
					Yii::app()->session->remove('Stickertoguides');
					Yii::app()->session->remove('Detailstickertoguides');  
					*/
					$this->redirect(array('view','id'=>$model->id));
            	} 
			}      

			$this->render('create',array(
				'model'=>$model,
			));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                    Yii::app()->user->id))  {
                
			$this->state='u';
			$this->trackActivity('u');
                
			$model=$this->loadModel($id);
			$this->afterEdit($model);

		// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if(isset($_POST['Stickertoguides'])) {	
				if (isset($_POST['yt1'])) {
					$model->attributes=$_POST['Stickertoguides'];
					$this->beforePost($model);    
					$this->tracker->modify('stickertoguides', $id);
				
					$respond = $model->save();
					if(!$respond) {
						throw new CHttpException(5002,'There is an error in master posting: '.serialize($model->errors));
					}
				
				/*
				if(isset(Yii::app()->session['Detailstickertoguides']) ) {
					$details=Yii::app()->session['Detailstickertoguides'];
					$respond=$this->saveDetails($details);
					if (!$respond)
						throw new CHttpException(5002,'There is an error in detail posting');
				}
				*/
					
					$this->afterPost($model);
				/*
				Yii::app()->session->remove('Stickertoguides');
				Yii::app()->session->remove('Detailstickertoguides');
				*/	
					$this->redirect(array('view','id'=>$model->id));
				}
			}

			$this->render('update',array(
                    'model'=>$model,
			));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
            if(Yii::app()->authManager->checkAccess($this->formid.'-Delete', 
                    Yii::app()->user->id))  {
                $this->trackActivity('d');
                $model=$this->loadModel($id);
                $this->beforeDelete($model);
                $this->tracker->delete('customers', $id);
                $model->delete();
                $this->afterDelete();    
                    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser

                if(!isset($_GET['ajax']))
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
            if(Yii::app()->authManager->checkAccess($this->formid.'-List', 
                Yii::app()->user->id)) {
                $this->trackActivity('l');
                
                $dataProvider=new CActiveDataProvider('Stickertoguides');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
            if(Yii::app()->authManager->checkAccess($this->formid.'-List', 
                Yii::app()->user->id)) {
                $this->trackActivity('s');
                
                $model=new Stickertoguides('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Stickertoguides']))
			$model->attributes=$_GET['Stickertoguides'];

		$this->render('admin',array(
			'model'=>$model,
		));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
	}
        
        public function actionHistory($id)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $model=$this->loadModel($id);
                $this->render('history', array(
                   'model'=>$model,
                    
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }   
        }
        
        public function actionDeleted()
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->render('deleted', array(
         
                    
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }   
        }
        
        public function actionRestore($idtrack)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->trackActivity('r');
                $this->tracker->restore('customers', $idtrack);
                $dataProvider=new CActiveDataProvider('Stickertoguides');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }
        
        public function actionRestoreDeleted($idtrack)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->trackActivity('n');
                $this->tracker->restoreDeleted('customers', $idtrack);
                $dataProvider=new CActiveDataProvider('Stickertoguides');
                $this->render('index',array(
                    'dataProvider'=>$dataProvider,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }
        

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Stickertoguides the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Stickertoguides::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Stickertoguides $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='customers-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
    }
        
        protected function afterInsert(& $model)
        {
            $idmaker=new idmaker();

            $model->id=$idmaker->getcurrentID2();  
            $model->idatetime= $idmaker->getDateTime();
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();   
           	$model->regnum = $idmaker->getRegNum($this->formid); 
           	$model->paid = '0';
        }
        
        protected function afterPost(& $model)
        {
            
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
            if ($this->state == 'c') {
            	$model->regnum = $idmaker->getRegNum($this->formid);
            	$idmaker->saveRegNum($this->formid, $model->regnum);
            };
        }
        
        protected function beforeDelete(& $model)
        {
            
        }
        
        protected function afterDelete()
        {
               
        }
        
        protected function afterEdit(& $model)
        {
            
        }
        
        protected function trackActivity($action)
        {
            $this->tracker=new Tracker();
            $this->tracker->init();
            $this->tracker->logActivity($this->formid, $action);
        }
        
	protected function saveDetails(array $details)
	{
		$idmaker=new idmaker();
        
		$respond=true;
		foreach ($details as $row) {
        	$detailmodel=Detailstickertoguides::model()->findByPk($row['iddetail']);
        	if($detailmodel==NULL) {
        		$detailmodel=new Detailstickertoguides;
        	} else {
        		if(count(array_diff($detailmodel->attributes,$row))) {
        			$this->tracker->init();
        			$this->tracker->modify('detailstickertoguides', array('iddetail'=>$detailmodel->iddetail));
        		}
        	}
        	$detailmodel->attributes=$row;
        	$detailmodel->userlog=Yii::app()->user->id;
        	$detailmodel->datetimelog=$idmaker->getDateTime();
        	$respond=$detailmodel->save();
        	if (!$respond) {
        		break;
        	}
        }
        return $respond;
	}
	
}
