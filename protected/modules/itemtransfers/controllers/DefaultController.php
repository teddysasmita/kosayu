<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC18';
	public $tracker;
	public $state;
	private $recapdetails = array();

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
	public function actionViewRegnum($regnum)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			$this->trackActivity('v');
			$this->render('view',array(
					'model'=>$this->loadModelRegnum($regnum),
			));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
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
                $error = '';
                    
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
                   if(isset($_POST['yt0'])) {
                      //The user pressed the button;
                      $model->attributes=$_POST['Itemtransfers'];
                      
                      $this->beforePost($model);
                      
                      $respond=$model->save();
                      if($respond) {
                          $this->afterPost($model);
                      } else {
                          throw new CHttpException(404,'There is an error in master posting');
                      }
                      
                      if(isset(Yii::app()->session['Detailitemtransfers']) ) {
                        $details=Yii::app()->session['Detailitemtransfers'];
                        $respond=$respond&&$this->saveNewDetails($details);
                      } 
                      
                      if($respond) {
                         Yii::app()->session->remove('Itemtransfers');
                         Yii::app()->session->remove('Detailitemtransfers');
                         $this->redirect(array('view','id'=>$model->id));
                      }
                   }  
                   if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Itemtransfers'];
                         Yii::app()->session['Itemtransfers']=$_POST['Itemtransfers'];
                         $this->redirect(array('detailitemtransfers/create',
                            'id'=>$model->id, 'idwhsource'=>$model->idwhsource, 'regnum'=>$model->regnum));
                      } else if ($_POST['command']=='updateDetail') {
                         $model->attributes=$_POST['Itemtransfers'];
                         Yii::app()->session['Itemtransfers']=$_POST['Itemtransfers'];
                      }
                   }
                }

                $this->render('create',array(
                    'model'=>$model, 'form_error'=>$error
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
             
             Yii::app()->session['master']='update';

             if(!isset(Yii::app()->session['Itemtransfers']))
                Yii::app()->session['Itemtransfers']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Itemtransfers'];

             if(!isset(Yii::app()->session['Detailitemtransfers'])) 
               Yii::app()->session['Detailitemtransfers']=$this->loadDetails($id);
             

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Itemtransfers'];
                     // Uncomment the following line if AJAX validation is needed
                     $this->performAjaxValidation($model);
                     $this->beforePost($model);
                     $this->tracker->modify('itemtransfers', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                       throw new CHttpException(404,'There is an error in master posting');
                     }

                     if(isset(Yii::app()->session['Detailitemtransfers'])) {
                         $details=Yii::app()->session['Detailitemtransfers'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     
                     if(isset(Yii::app()->session['DeleteDetailitemtransfers'])) {
                         $deletedetails=Yii::app()->session['DeleteDetailitemtransfers'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                     
                     if($respond) {
                         Yii::app()->session->remove('Itemtransfers');
                         Yii::app()->session->remove('Detailitemtransfers');
                         Yii::app()->session->remove('DeleteDetailitemtransfers');
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
            $model=$this->loadModel($id);    
            $this->beforeDelete($model);
            $this->tracker->delete('itemtransfers', $id);

            $detailmodels=Detailitemtransfers::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailitemtransfers', array('iddetail'=>$dm->iddetail));
               $dm->delete();
            }

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

               Yii::app()->session->remove('Itemtransfers');
               Yii::app()->session->remove('Detailitemtransfers');
               Yii::app()->session->remove('DeleteDetailitemtransfers');
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
                $this->tracker->restoreDeleted('itemtransfers', $idtrack);
                
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
	
	public function loadModelRegnum($regnum)
	{
		$model=Itemtransfers::model()->findByAttributes(array('regnum'=>$regnum));
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

             $details=Yii::app()->session['Detailitemtransfers'];
             $this->afterInsertDetail($model, $details);

             $this->render('create',array(
                 'model'=>$model, 'form_error'=>''
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

             $details=Yii::app()->session['Detailitemtransfers'];
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

             $details=Yii::app()->session['Detailitemtransfers'];
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
             $detailmodel=new Detailitemtransfers;
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
            
            $respond=true;
            foreach ($details as $row) {
                $detailmodel=Detailitemtransfers::model()->findByPk($row['iddetail']);
                if($detailmodel==NULL) {
                    $detailmodel=new Detailitemtransfers;
                } else {
                    if(count(array_diff($detailmodel->attributes,$row))) {
                        $this->tracker->init();
                        $this->tracker->modify('detailitemtransfers', array('iddetail'=>$detailmodel->iddetail));
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
                $detailmodel=Detailitemtransfers::model()->findByPk($row['iddetail']);
                if($detailmodel) {
                    $this->tracker->init();
                    $this->trackActivity('d', 'detailitemtransfers');
                    $this->tracker->delete('detailitemtransfers', $detailmodel->id);
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
         $sql="select * from detailitemtransfers where id='$id'";
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
        }
        
        protected function afterPost(& $model)
        {
            $idmaker=new idmaker();
            if ($this->state == 'create') {
            	$idmaker->saveRegNum($this->formid, substr($model->regnum, 2));  
            }
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
            if ($this->state == 'create')	
            	$model->regnum='TB'.$idmaker->getRegNum($this->formid);
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
        
        protected function afterInsertDetail(& $model, $details)
        {
            //$this->sumDetail($model, $details);
        }

        protected function afterUpdateDetail(& $model, $details)
        {
        	//$this->sumDetail($model, $details);
        }
        
        protected function afterDeleteDetail(& $model, $details)
        {
        	$this->sumDetail($model, $details);
        }
        
        protected function trackActivity($action)
        {
            $this->tracker=new Tracker();
            $this->tracker->init();
            $this->tracker->logActivity($this->formid, $action);
        }
        
        private function sumDetail(& $model, $details)
        {
        	$total=0;
        	$totaldisc=0;
        	foreach ($details as $row) {
        		$total=$total+(($row['price']+$row['cost1']+$row['cost2'])*$row['qty']);
        		$totaldisc=$totaldisc+$row['discount']*$row['qty'];
        	}
        	$model->attributes=Yii::app()->session['Itemtransfers'];
        	$model->total=$total;
        	$model->discount=$totaldisc;
        }
        
		public function actionPrinttb($id)
        {
        	if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
        			Yii::app()->user->id)) {
        		$this->trackActivity('p');
        	
        		$model=$this->loadModel($id);
        		$detailmodel=$this->loadDetails($id);
        			
        		Yii::import('application.vendors.tcpdf.*');
        		require_once ('tcpdf.php');
        		Yii::import('application.modules.itemtransfers.components.*');
        		require_once('printtb.php');
        		ob_clean();
        		
        		execute($model, $detailmodel);
        	} else {
        		throw new CHttpException(404,'You have no authorization for this operation.');
        	}
        }    
        
        public function moveItem($iddetail, $iditem, $serialnum, $idwhsource, $idwhdest)
        {
        	$existinsource = Action::checkItemToWarehouse($idwhsource, $iditem, $serialnum) > 0;
        	if ($existinsource) {
        		$existindest = Action::checkItemToWarehouse($idwhdest, $iditem, $serialnum, '%') > 0;
        		Action::exitItemFromWarehouse($idwhsource, $serialnum);
        		if (!$existindest) {
        			Action::addItemToWarehouse($idwhdest, $iddetail, $iditem, $serialnum);
        		} else
        			Action::setItemStatusinWarehouse($idwhdest, $serialnum, '1');        	
      		}
        }
	
}
