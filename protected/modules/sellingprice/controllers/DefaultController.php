<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $formid='AC11';
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
                $this->state='create';
                $this->trackActivity('c');    
                    
                $model=new Sellingprices;
                $this->afterInsert($model);
                
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['yt0']))
		{
			$model->attributes=$_POST['Sellingprices'];
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
                
			$this->state='update';
			$this->trackActivity('u');
                
			$model=$this->loadModel($id);
			$this->afterEdit($model);
                
		// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if(isset($_POST['Sellingprices']))
			{
				$model->attributes=$_POST['Sellingprices'];
                         
				$this->beforePost($model);   
				$this->tracker->modify('sellingprices', $id);
				if($model->save()) {
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
                $this->tracker->delete('sellingprices', $id);
                
                $model->delete();
                $this->afterDelete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
                
                $dataProvider=new CActiveDataProvider('Sellingprices', array(
					'criteria'=>array(
                		'order'=>'regnum desc'
                )));
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
			$model=new Sellingprices('search');
			$model->unsetAttributes();  // clear any default values
			
			if(isset($_GET['Sellingprices']))
				$model->attributes=$_GET['Sellingprices'];

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
                $this->tracker->restore('sellingprices', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Sellingprices');
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
                $this->tracker->restoreDeleted('sellingprices', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Sellingprices');
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
	 * @return Sellingprices the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Sellingprices::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Sellingprices $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sellingprices-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        protected function afterInsert(& $model)
        {
            $idmaker=new idmaker();
            $model->id=$idmaker->getcurrentID2();  
            $model->idatetime=$idmaker->getDateTime();
        }
        
        protected function afterPost(& $model)
        {
        	$idmaker=new idmaker();
        	if ($this->state == 'create')
        		$idmaker->saveRegNum($this->formid, $model->regnum);
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
            if ($this->state == 'create')
            	$model->regnum=$idmaker->getRegNum($this->formid);
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
        
        public function actionDisplaysellingprice($itemname)
        {
        	if(Yii::app()->authManager->checkAccess($this->formid.'-List',
        			Yii::app()->user->id))  {
        		$this->trackActivity('v');
        			$data = Yii::app()->db->createCommand()
        				->select('a.*, b.name')->from('sellingprices a')
						->join('items b', 'b.id = a.iditem')
						->where('b.name like :p_name', array(':p_name'=>'%'.$itemname.'%'))
						->order('b.name, a.idatetime desc')
						->queryAll();
        		$this->render('display1a',array(
        				'data'=>$data));
        	} else {
        		throw new CHttpException(404,'You have no authorization for this operation.');
        	};
        }
        
        public function actionGetsellingprice()
        {
        	if(Yii::app()->authManager->checkAccess($this->formid.'-List',
        			Yii::app()->user->id))  {
        		$this->trackActivity('v');
        		$this->render('display1');
        	} else {
        		throw new CHttpException(404,'You have no authorization for this operation.');
        	};
        }
}
