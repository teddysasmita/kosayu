<?php

class ItemtransfersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
      public $formid='AB1';
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
            $this->trackActivity('v');
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
             if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                    Yii::app()->user->id))  {   
                $this->state='c';
                $this->trackActivity('c');    
                    
                $model=new Itemtransfers;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Itemtransfers'])) {
                   Yii::app()->session['Itemtransfers']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Itemtransfers'];
                }
                
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
                
                if (isset($_POST)){
                   if(isset($_POST['yt0']))
                   {
                      //The user pressed the button;
                      $model->attributes=$_POST['Itemtransfers'];
                      
                      if(isset(Yii::app()->session['__Detailmodel']))
                        $details=Yii::app()->session['__Detailmodel'];

                      $this->beforePost($model);
                      $respond=$model->save();
                      if($respond) {
                          $this->afterPost($model);
                      } else {
                          throw new CHttpException(404,'There is an error in master posting');
                      }
                      
                      if (isset($details)) {
                          $respond=$respond&&$this->saveNewDetails($details);
                      }    
                      if($respond) {
                         Yii::app()->session->remove('Itemtransfers');
                         Yii::app()->session->remove('__Detailmodel');
                         $this->redirect(array('view','id'=>$model->id));
                      }

                   } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Itemtransfers'];
                         Yii::app()->session['Itemtransfers']=$_POST['Itemtransfers'];
                         $this->redirect(array('__detailmodel/create','id'=>$model->id));
                      }
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
                
		if(!isset(Yii::app()->session['Itemtransfers']))
                   Yii::app()->session['Itemtransfers']=$model->attributes;
                else
                   $model->attributes=Yii::app()->session['Itemtransfers'];
                
                if(!isset(Yii::app()->session['__Detailmodel'])) 
                    Yii::app()->session['Detailitemtransfers']=$this->loadDetails($id);
                    
                // Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);

                if(isset($_POST)) {
                    if(isset($_POST['yt0'])) {
                        $model->attributes=$_POST['Itemtransfers'];
                        
                        $this->beforePost($model);
                        $this->tracker->modify('itemtransfers', $id);
                        $respond=$model->save();
                        if($respond) {
                          $this->afterPost($model);
                        } else {
                          throw new CHttpException(404,'There is an error in master posting');
                        }
                        
                        if(isset(Yii::app()->session['__Detailmodel']))
                            $details=Yii::app()->session['__Detailmodel'];
                        if(isset($details)) {
                            $respond=$respond&&$this->saveDetails($details);
                            if($respond) {
                            
                            } else {
                              throw new CHttpException(404,'There is an error in detail posting');
                            }
                        };
                        
                        if(isset(Yii::app()->session['Delete__detailmodel']))
                            $deletedetails=Yii::app()->session['Delete__detailmodel'];
                        if(isset($deletedetails)) {
                            $respond=$respond&&$this->deleteDetails($deletedetails);
                            if($respond) {
                            
                            } else {
                              throw new CHttpException(404,'There is an error in detail deletion');
                            }
                        };
                                                
                        if($respond) {
                            Yii::app()->session->remove('Itemtransfers');
                            Yii::app()->session->remove('__Detailmodel');
                            Yii::app()->session->remove('Delete__detailmodel');
                            $this->redirect(array('view','id'=>$model->id));
                        }
                    }
                }

                $this->render('update',array(
                        'model'=>$model,
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
                $this->beforeDelete($model);
                $this->tracker->delete('itemtransfers', $id);
                
                $detailmodels=__Detailmodel::model()->findAll('id=:id',array(':id'=>$id));
                foreach($detailmodels as $dm) {
                    $this->tracker->delete('__detailmodel', array('iddetail'=>$dm->iddetail));
                    $dm->delete();
                }
                
                $this->loadModel($id)->delete();
                $this->afterDelete($model);

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

         $dataProvider=new CActiveDataProvider('Itemtransfers',
            array(
               'criteria'=>array(
                  'order'=>'id desc'
               )
            )
         );
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
               
                $model=new Itemtransfers('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Itemtransfers']))
			$model->attributes=$_GET['Itemtransfers'];

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
                $this->tracker->restore('itemtransfers', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Itemtransfers');
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
                $this->tracker->restoreDeleted('Itemtransfers', $idtrack);
                $this->tracker->restoreDeleted('__detailmodel', $idtrack);
                $dataProvider=new CActiveDataProvider('Itemtransfers');
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
	 * @return Itemtransfers the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Itemtransfers::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Itemtransfers $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='itemtransfers-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
         public function actionCreateDetail()
        {
         //this action continues the process from the detail page
            if(Yii::app()->authManager->checkAccess($this->formid.'-Append', 
                    Yii::app()->user->id))  {
                $model=new Itemtransfers;
                $model->attributes=Yii::app()->session['Itemtransfers'];

                $details=Yii::app()->session['__Detailmodel'];
                $this->afterInsertDetail($model, $details);

                $this->render('create',array(
                    'model'=>$model,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            } 
          }
      
        public function actionUpdateDetail()
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                    Yii::app()->user->id))  {
            
                $model=new Itemtransfers;
                $model->attributes=Yii::app()->session['Itemtransfers'];

                $details=Yii::app()->session['__Detailmodel'];
                $this->afterUpdateDetail($model, $details);

                $this->render('update',array(
                    'model'=>$model,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }
      
        public function actionDeleteDetail()
        {
            if(Yii::app()->authManager->checkAccess($this->formid.'-Update', 
                    Yii::app()->user->id))  {
            
                
                $model=new Itemtransfers;
                $model->attributes=Yii::app()->session['Itemtransfers'];

                $details=Yii::app()->session['__Detailmodel'];
                $this->afterDeleteDetail($model, $details);

                $this->render('update',array(
                    'model'=>$model,
                ));
            } else {
                throw new CHttpException(404,'You have no authorization for this operation.');
            }
        }

        
        
        protected function saveNewDetails(array $details)
        {                  
            foreach ($details as $row) {
                $detailmodel=new __Detailmodel;
                $detailmodel->attributes=$row;
                $respond=$detailmodel->insert();
                if (!$respond) {
                   break;
                }
            }
            return $respond;
        }
      
        protected function saveDetails(array $details)
        {
            $idmaker=new idmaker();
            
            foreach ($details as $row) {
                $detailmodel=__Detailmodel::model()->findByPk($row['iddetail']);
                if($detailmodel==NULL) {
                    $detailmodel=new __Detailmodel;
                } else {
                    if(count(array_diff($detailmodel->attributes,$row))) {
                        $this->tracker->init();
                        $this->tracker->modify('__detailmodel', array('iddetail'=>$detailmodel->iddetail));
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
      
        protected function deleteDetails(array $details)
        {
            $respond=true;
            foreach ($details as $row) {
                $detailmodel=__Detailmodel::model()->findByPk($row['iddetail']);
                if($detailmodel) {
                    $this->tracker->init();
                    $this->trackActivity('d', $this->__DETAILFORMID);
                    $this->tracker->delete('__detailmodel', $detailmodel->id);
                    $respond=$detailmodel->delete();
                    if (!$respond) {
                      break;
                    }
                }
            }
            return $respond;
        }


        protected function loadDetails($id)
        {
         $sql="select * from __detailmodel where id='$id'";
         $details=Yii::app()->db->createCommand($sql)->queryAll();

         return $details;
        }
        
        
        protected function afterInsert(& $model)
        {
            $idmaker=new idmaker();
            $model->id=$idmaker->getCurrentID2();
            $model->idatetime=$idmaker->getDateTime();
            $model->regnum=$idmaker->getRegNum($this->formid);
            $lookup=new lookup();
            $model->status=$lookup->reverseOrderStatus('Belum Diproses');
        }
        
        protected function afterPost(& $model)
        {
            $idmaker=new idmaker();
            $idmaker->saveRegNum($this->formid, $model->regnum);    
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
        
        protected function afterInsertDetail(& $model, $details)
        {
            $total=0;
            $totaldisc=0;
            foreach ($details as $row) {
                $total=$total+$row['price']*$row['qty'];
                $totaldisc=$totaldisc+$row['discount']*$row['qty'];
            }
            $model->attributes=Yii::app()->session['Itemtransfers'];
            $model->total=$total;
            $model->discount=$totaldisc;
        }

        protected function afterUpdateDetail(& $model, $details)
        {
         $total=0;
         $totaldisc=0;
         foreach ($details as $row) {
            $total=$total+$row['price']*$row['qty'];
            $totaldisc=$totaldisc+$row['discount']*$row['qty'];
         }
         $model->attributes=Yii::app()->session['Itemtransfers'];
         $model->total=$total;
         $model->discount=$totaldisc;      
        }
        
        protected function afterDeleteDetail(& $model, $details)
        {
         $total=0;
         $totaldisc=0;
         foreach ($details as $row) {
            $total=$total+$row['price']*$row['qty'];
            $totaldisc=$totaldisc+$row['discount']*$row['qty'];
         }
         $model->attributes=Yii::app()->session['Itemtransfers'];
         $model->total=$total;
         $model->discount=$totaldisc;      
        }
        
        protected function trackActivity($action)
        {
            $this->tracker=new Tracker();
            $this->tracker->init();
            $this->tracker->logActivity($this->formid, $action);
        }
}
