<?php

class Detailpurchasespayments3Controller extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC32b';
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
            $model=$this->loadModel($iddetail);
            if(($model==NULL)&&isset(Yii::app()->session['Detailpurchasespayments3'])) {
                $model=new Payments;
                $model->attributes=$this->loadSession($iddetail);
            }  
            $this->render('view',array(
				'model'=>$model,
			));
		} else {
        	throw new CHttpException(404,'You have no authorization for this operation.');
        };
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($idtransaction)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                    Yii::app()->user->id))  {   
			$this->state='c';
			$this->trackActivity('c');    
                    
			$model=new Payments;
			$this->afterInsert($idtransaction, $model);
                
			$master=Yii::app()->session['master'];
                                
				// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);
                
			if(isset($_POST['Payments'])) {
				$temp=Yii::app()->session['Detailpurchasespayments3'];
				$model->attributes=$_POST['Payments'];
                    //posting into session
                    
				if (isset($_POST['yt0'])) {
					if ($model->validate()) {
						$temp[]=$_POST['Payments'];
						Yii::app()->session['Detailpurchasespayments3']=$temp;
						if ($master=='create')
							$this->redirect(array('default/createdetail3'));
						else if($master=='update')
							$this->redirect(array('default/updatedetail3'));
					}    
				}
			}                

			$this->render('create',array(
				'model'=>$model, 'master'=>$master
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
                
                $master=Yii::app()->session['master'];
                
                $model=$this->loadModel($id);
                if(isset(Yii::app()->session['Detailpurchasespayments3'])) {
                    $model=new Payments;
                    $model->attributes=$this->loadSession($id);
                }
                $this->afterEdit($model);
                    
                // Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);
                
               if(isset($_POST['Payments'])) {
                  $temp=Yii::app()->session['Detailpurchasespayments3'];
                  $model->attributes=$_POST['Payments'];
                  foreach ($temp as $tk=>$tv) {
                     if($tv['id']==$_POST['Payments']['id']) {
                         $temp[$tk]=$_POST['Payments'];
                         break;
                     }
                  }
                    //posting into session
                  if (isset($_POST['yt0'])) {
	                  if($model->validate()) {
	                     Yii::app()->session['Detailpurchasespayments3']=$temp;
	
	                     if ($master=='create')
	                           $this->redirect(array('default/createdetail3'));
	                     else if($master=='update')
	                           $this->redirect(array('default/updatedetail3'));
	                  }	
                  }
				}
               
                $this->render('update',array(
                        'model'=>$model,'master'=>$master
                ));
            }  else {
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
                
                $details=Yii::app()->session['Detailpurchasespayments3'];
                foreach ($details as $ik => $iv) {
                   if($iv['id']==$id) {
                      if(isset(Yii::app()->session['Deletedetailpurchasespayments']))
                         $deletelist=Yii::app()->session['Deletedetailpurchasespayments'];
                      $deletelist[]=$iv;
                      Yii::app()->session['Deletedetailpurchasespayments']=$deletelist;
                      unset($details[$ik]);
                      break;
                   }
                }
                
                            
                Yii::app()->session['Detailpurchasespayments3']=$details;

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }

	public function actionHistory($iddetail)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $model=$this->loadModel($iddetail);
                $this->render('history', array(
                   'model'=>$model,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }   
        }
        
        public function actionDeleted($id)
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
               Yii::app()->user->id)) {
                $this->render('deleted', array(
                    'id'=>$id,
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
                $this->tracker->restore('detailpurchasespayments', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Payments');
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
                $this->tracker->restoreDeleted('detailpurchasespayments', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Payments');
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
	 * @return Payments the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Payments::model()->findByPk($id);
		/*if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		*/
                return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Payments $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='detailpurchasespayments-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        protected function loadSession($iddetail)
        {
            $details=Yii::app()->session['Detailpurchasespayments3'];
            foreach ($details as $row) {
                if($row['id']==$iddetail)
                    return $row;
            }
            throw new CHttpException(404,'The requested page does not exist.');
        }
        
        
        protected function afterInsert($id, & $model)
        {
            $idmaker=new idmaker();
            $model->idtransaction=$id;  
            $model->id=$idmaker->getCurrentID2();
            $model->userlog=Yii::app()->user->id;
            $model->idatetime=$idmaker->getDateTime();
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
        
        protected function afterDelete(& $model)
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
}
