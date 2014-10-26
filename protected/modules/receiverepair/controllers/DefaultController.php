<?php


class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC33';
	public $tracker;
	public $state;
	public $details = array();

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
		if (Yii::app ()->authManager->checkAccess ( $this->formid . '-Append', Yii::app ()->user->id )) {
			$this->state = 'create';
			$this->trackActivity ( 'c' );
			
			$model = new Receiverepairs;
			$this->afterInsert ( $model );
			$allitems = array();
			Yii::app ()->session ['master'] = 'create';
			// as the operator enter for the first time, we load the default value to the session
			if (! isset ( Yii::app ()->session ['Receiverepairs'] )) {
				Yii::app ()->session ['Receiverepairs'] = $model->attributes;
			} else {
				// use the session to fill the model
				$model->attributes = Yii::app ()->session ['Receiverepairs'];
			}
			if (isset($_POST['Receiverepairs'])) {
				$model->attributes = $_POST['Receiverepairs'];
			}
			// Uncomment the following line if AJAX validation is needed
			
			$this->performAjaxValidation ( $model );
			if (isset ( $_POST )) {
				if (isset ( $_POST ['yt0'] )) {
					// The user pressed the button;
					$details = $this->processSelectedItems($_POST['yw0_c3']);
					$allitems = $details;
					$model->attributes = $_POST ['Receiverepairs'];
					
					$this->beforePost ( $model );
					
					$respond = $model->save ();
					if (! $respond) 
						throw new CHttpException ( 404, 'There is an error in master posting ');
					
					if ($details) {
						$respond = $respond && $this->saveNewDetails ( $details );
					}
					
					if (! $respond)
						throw new CHttpException ( 404, 'There is an error in detail posting' );
					
					
					$this->afterPost ( $model );
					Yii::app ()->session->remove ( 'Receiverepairs' );
					Yii::app ()->session->remove ( 'Detailreceiverepairs' );
					Yii::app ()->session->remove ( 'Detailreceiverepairs_temp' );
					$this->redirect ( array(
						'view','id' => $model->id) );
				} else if (isset ( $_POST ['command'] )) {
					// save the current master data before going to the detail page
					if ($_POST ['command'] == 'adddetail') {
						$model->attributes = $_POST ['Receiverepairs'];
						Yii::app ()->session ['Receiverepairs'] = $_POST ['Receiverepairs'];
						$this->redirect ( array (
								'detailreceiverepairs/create',
								'id' => $model->id 
						) );
					} else if ($_POST ['command'] == 'setSendNum') {
						$model->attributes = $_POST ['Receiverepairs'];
						Yii::app ()->session ['Receiverepairs'] = $_POST ['Receiverepairs'];
						$allitems = $this->InsertSentItems($model->id, $model->sendnum);
						Yii::app()->session['Detailreceiverepairs_temp'] = $allitems;
					}
				}
			} 
			
			$this->render ( 'create', array (
					'model'=>$model, 'allitems'=>$allitems 
			) );
		} else {
			throw new CHttpException ( 404, 'You have no authorization for this operation.' );
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

             if(!isset(Yii::app()->session['Receiverepairs']))
                Yii::app()->session['Receiverepairs']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Receiverepairs'];
             
             if(isset($_POST['Receiverepairs'])) {
             	$model->attributes=$_POST['Receiverepairs'];
             }

             if(!isset(Yii::app()->session['Detailreceiverepairs'])) 
               Yii::app()->session['Detailreceiverepairs']=$this->loadDetails($id);
             
             	
             // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Receiverepairs'];
                     $this->beforePost($model);
                     $this->tracker->modify('receiverepair', $id);
                     
                     $details = $this->processSelectedItems($_POST['yw0_c3']);
                     $allitems = $details;
                     
                     $respond=$model->save();
                     if(!$respond) 
                     	throw new CHttpException(404,'There is an error in master posting ');
					
                     $this->afterPost($model);
                     
                     if ($details) {
                     	$respond = $respond && $this->saveDetails ( $details );
                     }

                     if(!$respond)
						throw new CHttpException(404,'There is an error in detail posting');
                     
					Yii::app()->session->remove('Receiverepairs');
					Yii::app()->session->remove('Detailreceiverepairs');
					Yii::app()->session->remove('Detailreceiverepairs_temp');
					$this->redirect(array('view','id'=>$model->id));
                 }
             }
             
             if (isset(Yii::app()->session['Detailreceiverepairs_temp']))
             	$allitems = Yii::app()->session['Detailreceiverepairs_temp'];
             else {
             	$allitems = $this->loadDetails($model->id);
             	foreach($allitems as & $item) {
             		$item['selected'] = '1';
             	}
             	$allitems2 = $this->InsertSentItems($model->id);
             	$allitems2 = $this->filterList($allitems, $allitems2);
             	$allitems = array_merge($allitems, $allitems2);
             	
             	Yii::app()->session['Detailreceiverepairs_temp'] = $allitems;
             }
             
             $this->render('update',array(
                     'model'=>$model, 'allitems'=>$allitems
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

            $model=$this->loadModel($id);
            $this->trackActivity('d');
            $this->beforeDelete($model);
            $this->tracker->delete('receiverepairs', $id);

            $detailmodels=Detailreceiverepairs::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailreceiverepairs', array('iddetail'=>$dm->iddetail));
               $dm->delete();
            }
            
            $model->delete();
            $this->afterDelete($model);

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

               Yii::app()->session->remove('Receiverepairs');
               Yii::app()->session->remove('Detailreceiverepairs');
               Yii::app()->session->remove('DeleteDetailreceiverepairs');
               Yii::app()->session->remove('Detailreceiverepairs_temp');
               $dataProvider=new CActiveDataProvider('Receiverepairs',
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
               
			$model=new Receiverepairs('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Receiverepairs']))
				$model->attributes=$_GET['Receiverepairs'];

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
                $this->tracker->restore('receiverepair', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Receiverepairs');
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
                $this->tracker->restoreDeleted('receiverepair', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Receiverepairs');
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
	 * @return Receiverepairs the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Receiverepairs::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Receiverepairs $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='receiverepairs-form')
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
             $model=new Receiverepairs;
             $model->attributes=Yii::app()->session['Receiverepairs'];

             $details=Yii::app()->session['Detailreceiverepairs'];
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

             $model=new Receiverepairs;
             $model->attributes=Yii::app()->session['Receiverepairs'];

             $details=Yii::app()->session['Detailreceiverepairs'];
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


             $model=new Receiverepairs;
             $model->attributes=Yii::app()->session['Receiverepairs'];

             $details=Yii::app()->session['Detailreceiverepairs'];
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
             $detailmodel=new Detailreceiverepairs;
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
             $detailmodel=Detailreceiverepairs::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailreceiverepairs;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailreceiverepairs', array('iddetail'=>$detailmodel->iddetail));
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
             $detailmodel=Detailreceiverepairs::model()->findByPk($row['iddetail']);
             if($detailmodel) {
                 $this->tracker->init();
                 $this->trackActivity('d');
                 $this->tracker->delete('detailreceiverepairs', $detailmodel->id);
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
		$sql="select * from detailreceiverepairs where id='$id'";
		$details=Yii::app()->db->createCommand($sql)->queryAll();

		return $details;
     }
     

     protected function afterInsert(& $model)
     {
         $idmaker=new idmaker();
         $model->id=$idmaker->getCurrentID2();
         $model->idatetime=$idmaker->getDateTime();
         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         $model->regnum='KR'.$idmaker->getRegNum($this->formid);
     }

     protected function afterPost(& $model)
     {
         $idmaker=new idmaker();
         if ($this->state == 'create') {
         	$idmaker->saveRegNum($this->formid, substr($model->regnum, 2)); 
         
         	Yii::import('application.modules.receiverepair.models.*');
		
         	$details = Detailreceiverepairs::model()->findByAttributes('id', $model->id);
         	foreach ($details as $detail) {
         		Action::setItemStatusinWarehouse($detail->idwarehouse, $detail->serialnum, '1');
         	}
         }
         /*$this->setStatusPO($model->idpurchaseorder,
            Yii::app()->session['Detailreceiverepairs']);
         */
     }

     protected function beforePost(& $model)
     {
         $idmaker=new idmaker();

         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         if ($this->state == 'create')
			$model->regnum='KR'.$idmaker->getRegNum($this->formid);
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

     }


     protected function afterUpdateDetail(& $model, $details)
     {

     }

     protected function afterDeleteDetail(& $model, $details)
     {
     }


     protected function trackActivity($action)
     {
         $this->tracker=new Tracker();
         $this->tracker->init();
         $this->tracker->logActivity($this->formid, $action);
     }
     
      public function actionSummary($id)
      {
      	$this->trackActivity('v');
      	$this->render('summary',array(
      			'model'=>$this->loadModel($id),
      	));
      
      }
      
      public function actionPrintsummary($id)
      {
      	if(Yii::app()->authManager->checkAccess($this->formid.'-List',
      			Yii::app()->user->id))  {     
	      	$this->trackActivity('v');
	      	Yii::import("application.vendors.tcpdf.*");
	      	require_once('tcpdf.php');
	      	$this->render('printsummary',array(
	      			'model'=>$this->loadModel($id),
	      	));
      	} else	 {
        	throw new CHttpException(404,'You have no authorization for this operation.');
        };
      }
      
	
	public function actionPrintlpb($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id)) {
			$this->trackActivity('p');
			 
			$model=$this->loadModel($id);
			$detailmodel=$this->loadDetails($id);
			$detailmodel2=$this->loadDetails2($id);
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			Yii::import('application.modules.receiverepair.components.*');
			require_once('printretur.php');
			ob_clean();
	
			execute($model, $detailmodel, $detailmodel2);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
	}
	
	private function InsertSentItems($id, $regnum)
	{
		$allitems = array();
		$warehouses = Yii::app()->db->createCommand()->select('id')->from('warehouses')->queryColumn();	
		$allitems = Yii::app()->db->createCommand()
			->select("b.iditem, b.serialnum, b.idwarehouse")
			->from('sendrepairs a')->join('detailsendrepairs b', 'b.id = a.id')
			->where('a.regnum = :p_regnum and b.exit = :p_exit', 
				array(':p_regnum'=>$regnum, ':p_exit'=>'1'))
			->queryAll();
		foreach($allitems as & $item) {
			$item['id'] = $id;
			$item['iddetail'] = idmaker::getCurrentID2();
			$item['entry'] = '0';
			$item['selected'] = '0';
			$item['userlog'] = Yii::app()->user->id;
			$item['datetimelog'] = idmaker::getDateTime();
		}
		return $allitems;
	}
      
	private function processSelectedItems(array $selectIDs)
	{
		$found = array();
		foreach($selectIDs as $id) {
			foreach(Yii::app()->session['Detailreceiverepairs_temp'] as $temp) {
				if ($temp['iddetail'] == $id) {
					$found[] = $temp;
					break;	
				}
			} 
		}	
		return $found;
	}
	
	private function filterList(array $savedlist, array $scannedlist)
	{
		$good = array();
		foreach($scannedlist as $sc) {
			$found = FALSE;
			foreach($savedlist as $sv) {
				if ($sc['serialnum'] == $sv['serialnum']) {
					$found = TRUE;
					break;
				}
			}			
			if (!$found) {
				$good[] = $sc;
			}
		}
		return $good;
	}
}