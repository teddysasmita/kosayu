<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $formid='AB18';
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
			$this->state='c';
			$this->trackActivity('c');    
                    
			$model=new Itembatch;
			$this->afterInsert($model);
                
		// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if(isset($_POST['Itembatch'])) {
				$model->attributes=$_POST['Itembatch'];
				if (isset($_POST['yt0'])) {
	            	$this->beforePost($model);
					if($model->save()) {
						$this->afterPost($model);
						$this->redirect(array('view','id'=>$model->id));                 
					}    
				} else if ($_POST['command'] == 'setCode') {
					//die('<DIV>Here</DIV>');
					$this->getBatchCodeInfo($model);	
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

			if(isset($_POST['itembatch']))
			{
				$model->attributes=$_POST['itembatch'];
                         
				$this->beforePost($model);   
				$this->tracker->modify('itembatch', $id);
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
                $this->tracker->delete('itembatch', $id);
                
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
                
	            $dataProvider=new CActiveDataProvider('Itembatch',
	            	array(
                     'criteria'=>array(
                        'order'=>'datetimelog desc, id desc'
                     )
                  ));
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
               
                $model=new Itembatch('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Itembatch']))
			$model->attributes=$_GET['Itembatch'];

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
                $this->tracker->restore('itembatch', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Itembatch');
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
                $this->tracker->restoreDeleted('itembatch', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Itembatch');
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
	 * @return Itembatch the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Itembatch::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Itembatch $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='itembatch-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        protected function afterInsert(& $model)
        {
            $idmaker=new idmaker();
            $model->id=$idmaker->getcurrentID2();  
        }
        
        protected function afterPost(& $model)
        {
        	if ($model->sellprice > 0) {
        		Yii::import('application.modules.sellingprice.models.*');
        		
        		$sellprice = Sellingprices::model()->findByPk($model->id);
        		if (is_null($sellprice)) {
        			$sellprice = new Sellingprices();
        			$sellprice->id = $model->id;
        			$sellprice->regnum = idmaker::getRegNum('AC11');
        		}
        		$sellprice->idatetime = $model->idatetime;
        		//$sellprice->iditem = lookup::ItemCodeFromItemID($d['iditem']);
        		$sellprice->iditem = $model->batchcode;
        		$sellprice->normalprice = $model->sellprice;
        		$sellprice->minprice = $model->sellprice;
        		$sellprice->approvalby = 'Pak Made';
        		$sellprice->datetimelog = $model->datetimelog;
        		$sellprice->userlog = $model->userlog;
        	
        		$resp = $sellprice->save();
        		if (!$resp) {
        			throw new CHttpException(100,'There is an error in after post');
        		}
        		idmaker::saveRegNum('AC11', $sellprice->regnum);
        	}
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
	
	protected function getBatchCodeInfo(& $model)
	{
		$databuy = Yii::app()->db->createCommand()
        	->select()->from("itembatch")
        	->where("batchcode = :p_batchcode", array(':p_batchcode'=>$model->batchcode))
        	->order("id desc")
        	->queryRow();
		
		if ($databuy) {
			$model->iditem = $databuy['iditem'];
        	$model->buyprice = $databuy['buyprice'];
        }
        
        $datasell = Yii::app()->db->createCommand()
        	->select('normalprice')->from("sellingprices")
        	->where("iditem = :p_batchcode", array(':p_batchcode'=>$model->batchcode))
        	->order("id desc")
        	->queryScalar();
        
        if ($datasell) {
        	$model->sellprice = $datasell;
        }
	}
}
