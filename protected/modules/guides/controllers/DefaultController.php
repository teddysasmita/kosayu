<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AB21';
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
                
			$model=new Guides;
			$this->afterInsert($model);
                
		// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);
                        
			if(isset($_POST['Guides'])) {
				$model->attributes=$_POST['Guides'];
				$this->beforePost($model);
				if($model->save()) {
					$this->afterPost($model);
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

			if (isset($_POST['Guides'])) {
				$model->attributes=$_POST['Guides'];
                    
				$this->beforePost($model);    
				$this->tracker->modify('customers', $id);
				if ($model->save()) {
					$this->afterPost($model);                    
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
                
                Yii::app()->session->remove('guideactivity');
                $dataProvider=new CActiveDataProvider('Guides');
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
                
                $model=new Guides('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Guides']))
			$model->attributes=$_GET['Guides'];

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
                $dataProvider=new CActiveDataProvider('Guides');
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
                $dataProvider=new CActiveDataProvider('Guides');
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
	 * @return Guides the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Guides::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Guides $model the model to be validated
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
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();    
        }
        
        protected function afterPost(& $model)
        {
            
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
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
        
        public function actionViewActivity($id, $startdate, $enddate, $print = '0')
        {
        	if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
        			Yii::app()->user->id)) {
        		$this->trackActivity('r');
        		
        		
        		$model = $this->loadModel($id);
        		
        		$data = Yii::app()->db->createCommand()
        			->select()
        			->from('stickertoguides')
        			->where('idguide = :p_idguide and (idatetime >= :p_startdate and idatetime <= :p_enddate)',
        				[':p_idguide'=>$id, ':p_startdate'=>$startdate, ':p_enddate'=>$enddate])
        			->queryAll();
        	
        		$activity = Yii::app()->db->createCommand()
        			->select('sum((b.price-b.discount) * b.qty) as totalsales')
        			->from('detailguidepayments b')
        			->join('guidepayments a', 'a.id = b.id')
        			->where('a.idguide = :p_idguide and b.stickernum = :p_stickernum and b.stickerdate like :p_stickerdate');
        
        		if (($data == false) || (count($data) == 0))
        			$data = [];
        		else {
        			foreach($data as & $dt) {
         				$activity->bindValue(':p_idguide', $id, PDO::PARAM_STR);
        				$activity->bindValue(':p_stickernum', $dt['stickernum'], PDO::PARAM_STR);
        				$activity->bindValue(':p_stickerdate', $dt['stickerdate'].'%', PDO::PARAM_STR);
        				$totalsales = $activity->queryScalar();
        				if ($totalsales == FALSE)
        					$dt['totalsales'] = 0;
        				else
        					$dt['totalsales'] = $totalsales;
        			}
        			if (isset(Yii::app()->session['guideactivity']))
        				Yii::app()->session->remove('guideactivity');
        			//print_r($data);
        			Yii::app()->session['guideactivity'] = $data;
        		}
        		if ($print == '0')
        			$this->render('activity',
        				['model'=>$model, 'data'=>$data, 'startdate'=>$startdate, 'enddate'=>$enddate]       		
	        		);
        		else if ($print == '1')
        			$this->renderPartial('printreport1',
        				['model'=>$model, 'data'=>$data, 'startdate'=>$startdate, 'enddate'=>$enddate]       		
	        		);
        		
        	} else {
        		throw new CHttpException(404,'You have no authorization for this operation.');
        	}
        }
        
        public function actionViewPayment($id, $startdate, $enddate, $print = '0')
        {
        	if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
        		Yii::app()->user->id)) {
				$this->trackActivity('r');
     			
				$model = $this->loadModel($id);
        
				$data = Yii::app()->db->createCommand()
					->select()
        			->from('guidepayments')
        			->where('idguide = :p_idguide and (idatetime >= :p_startdate and idatetime <= :p_enddate)',
        				[':p_idguide'=>$id, ':p_startdate'=>$startdate, ':p_enddate'=>$enddate])
					->queryAll();
        
				if ($data == false)
        			$data = [];
        
				if ($print == '0')
					$this->render('payment',
        				['model'=>$model, 'data'=>$data, 'startdate'=>$startdate, 'enddate'=>$enddate]
        			);
				else if ($print == '1')
					$this->renderPartial('printreport2',
        				['model'=>$model, 'data'=>$data, 'startdate'=>$startdate, 'enddate'=>$enddate]
        			);
        	} else {
        		throw new CHttpException(404,'You have no authorization for this operation.');
        	}
        }
}
