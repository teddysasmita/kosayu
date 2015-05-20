<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC64';
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
                    
                $model=new Paysalaries;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Paysalaries'])) {
                   Yii::app()->session['Paysalaries']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Paysalaries'];
                }
                
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);

                if (isset($_POST)){
                   if(isset($_POST['yt1'])) {
                      //The user pressed the button;
                      $model->attributes=$_POST['Paysalaries'];
                      
                      $this->beforePost($model);
                      $respond=$model->save();
                      
                      
                      if(isset(Yii::app()->session['Detailpaysalaries']) ) {
                        $details=Yii::app()->session['Detailpaysalaries'];
                        $respond=$respond&&$this->saveNewDetails($details);
                      } 
                      
                      if($respond) {
                      	$this->afterPost($model);
                         Yii::app()->session->remove('Paysalaries');
                         Yii::app()->session->remove('Detailpaysalaries');
                         $this->redirect(array('view','id'=>$model->id));
                      }                      
                   } else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
                      if ($_POST['command']=='adddetail') {
                         $model->attributes=$_POST['Paysalaries'];
                         Yii::app()->session['Paysalaries']=$_POST['Paysalaries'];
                         $this->redirect(array('detailpaysalaries/create',
                            'id'=>$model->id, 'regnum'=>$model->regnum));
                      } else if($_POST['command']=='countWage') {
                         $model->attributes=$_POST['Paysalaries'];
                         $data = $this->setComponents($model);
                         Yii::app()->session['Detailpaysalaries'] = $data;
                         Yii::app()->session['Paysalaries']=$model->attributes;
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
             
             Yii::app()->session['master']='update';

             if(!isset(Yii::app()->session['Paysalaries']))
                Yii::app()->session['Paysalaries']=$model->attributes;
             else
                $model->attributes=Yii::app()->session['Paysalaries'];

             if(!isset(Yii::app()->session['Detailpaysalaries'])) 
               Yii::app()->session['Detailpaysalaries']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
             $this->performAjaxValidation($model);

             if(isset($_POST)) {
                 if(isset($_POST['yt0'])) {
                     $model->attributes=$_POST['Paysalaries'];
                     $this->beforePost($model);
                     $this->tracker->modify('paysalaries', $id);
                     $respond=$model->save();
                     if($respond) {
                       $this->afterPost($model);
                     } else {
                       throw new CHttpException(404,'There is an error in master posting');
                     }

                     if(isset(Yii::app()->session['Detailpaysalaries'])) {
                         $details=Yii::app()->session['Detailpaysalaries'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     

                     if(isset(Yii::app()->session['DeleteDetailpaysalaries'])) {
                         $deletedetails=Yii::app()->session['DeleteDetailpaysalaries'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                     
                     if($respond) {
                         Yii::app()->session->remove('Paysalaries');
                         Yii::app()->session->remove('Detailpaysalaries');
                         Yii::app()->session->remove('DeleteDetailpaysalaries');
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
            $this->tracker->delete('paysalaries', $id);

            $detailmodels=Detailpaysalaries::model()->findAll('id=:p_id',array(':p_id'=>$id));
        
            
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailpaysalaries', array('iddetail'=>$dm->iddetail));
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

               Yii::app()->session->remove('Paysalaries');
               Yii::app()->session->remove('Detailpaysalaries');
               Yii::app()->session->remove('DeleteDetailpaysalaries');
               $dataProvider=new CActiveDataProvider('Paysalaries',
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
               
                $model=new Paysalaries('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Paysalaries']))
			$model->attributes=$_GET['Paysalaries'];

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
                $this->tracker->restore('paysalaries', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Paysalaries');
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
                $this->tracker->restoreDeleted('paysalaries', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Paysalaries');
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
	 * @return Paysalaries the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Paysalaries::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Paysalaries $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='paysalaries-form')
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
             $model=new Paysalaries;
             $model->attributes=Yii::app()->session['Paysalaries'];

             $details=Yii::app()->session['Detailpaysalaries'];
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

             $model=new Paysalaries;
             $model->attributes=Yii::app()->session['Paysalaries'];

             $details=Yii::app()->session['Detailpaysalaries'];
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


             $model=new Paysalaries;
             $model->attributes=Yii::app()->session['Paysalaries'];

             $details=Yii::app()->session['Detailpaysalaries'];
             $this->afterDeleteDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      
	public function actionPrint($id) 
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
			
			$masterdata = $this->loadModel($id);
			if ($masterdata) {
				$detaildata = $this->loadDetails($id);	
			};
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			Yii::import('application.modules.paysalaries.components.*');
			require_once('print_paysalary.php');
			ob_clean();
			
			execute($masterdata, $detaildata);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
	}
	
	public function actionGetreport()
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
			Yii::app()->user->id))  {
			$this->trackActivity('v');
			$this->render('report');
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionReportprint($startdate, $enddate, $order)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
						
			
			$dataSQL = Yii::app()->db->createCommand()
				->select('a.idatetime, a.regnum, a.idsupplier, b.iditem, b.batchcode, b.qty, b.price, b.discount')
				->from('paysalaries a')->join('detailpaysalaries b', 'b.id = a.id')
				->where('a.idatetime >= :p_startdate and a.idatetime <= :p_enddate',
					array(':p_startdate'=>$startdate, ':p_enddate'=>$enddate));
			switch ($order) {
				case 'B':
					$dataSQL->order('b.batchcode, a.idatetime');
				break;
				case 'S':
					$dataSQL->order('a.idsupplier, a.idatetime');
				break;
				case 'T':
					$dataSQL->order('a.idatetime, b.batchcode');
				break;
			}
			$reportdata = $dataSQL->queryAll();
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			Yii::import('application.modules.paysalaries.components.*');
			require_once('print_purchasereport.php');
			ob_clean();
				
			execute($reportdata);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
	
	}
      
     protected function saveNewDetails(array $details)
     {                  
         foreach ($details as $row) {
             $detailmodel=new Detailpaysalaries;
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
                $detailmodel=Detailpaysalaries::model()->findByPk($row['iddetail']);
                if($detailmodel==NULL) {
                    $detailmodel=new Detailpaysalaries;
                } else {
                    if(count(array_diff($detailmodel->attributes,$row))) {
                        $this->tracker->init();
                        $this->tracker->modify('detailpaysalaries', array('iddetail'=>$detailmodel->iddetail));
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
                $detailmodel=Detailpaysalaries::model()->findByPk($row['iddetail']);
                if($detailmodel) {
                    $this->tracker->init();
                    $this->trackActivity('d', $this->__DETAILFORMID);
                    $this->tracker->delete('detailpaysalaries', $detailmodel->id);
                    $respond=$detailmodel->delete();
                    if (!$respond) {
                      break;
                    }
                    Action::deleteDetailStock($detailmodel->iddetail);
                    
                }
            }
            return $respond;
        }

        protected function loadDetails($id)
        {
         $sql="select * from detailpaysalaries where id='$id'";
         $details=Yii::app()->db->createCommand($sql)->queryAll();

         return $details;
        }
        
        protected function afterInsert(& $model)
        {
            $idmaker=new idmaker();
            $model->id=$idmaker->getCurrentID2();
            $model->idatetime=$idmaker->getDateTime();
            $model->regnum=$idmaker->getRegNum($this->formid);
            $model->total = 0;
            $model->pmonth = date('n');
            $model->pyear = date('Y');
            $model->overtime = 0;
            $model->late = 0;
            $model->presence = 0;
            $model->nonworkingdays = 4;
            $model->isthr = '0';
            $model->bpjs = 0;
            $model->payment = 0;
            $model->transport = 0;
            $model->lunch = 0;
            $model->receivable = 0;
        }
        
        protected function afterPost(& $model)
        {
        	if ($this->state == 'c') {
        		$idmaker=new idmaker();
        		$idmaker->saveRegNum($this->formid, $model->regnum);
        		Action::addStock($model->id, $model->idatetime, $model->regnum, 'Konsinyasi');
        	}    
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
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
        
        protected function afterInsertDetail(& $model, $details)
        {
            $this->sumDetail($model, $details);
        }
        

        protected function afterUpdateDetail(& $model, $details)
        {
        	$this->sumDetail($model, $details);
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
        	foreach ($details as $row) {
        		$total=$total+($row['amount']);
        	}
        	$model->total=$total;
        }
        
        private function setComponents(& $model)
        {        	
        	$employeeinfo = Yii::app()->db->createCommand()
        		->select()->from('employees')
        		->where('id = :p_id', array(':p_id'=>$model->idemployee))
        		->queryRow();
        	$jginfo = Yii::app()->db->createCommand()
        		->select()->from('jobgroups')
        		->where('id = :p_id', array(':p_id'=>$employeeinfo['idjobgroup']))
        		->queryRow();
        	$daysnum = cal_days_in_month(CAL_GREGORIAN, $model->pmonth, $model->pyear) - 4;
        	$minutewage = $employeeinfo['wageamount'] / ($daysnum * 8 * 60);

        	//--- wager ---
        	if ($employeeinfo['wageamount'] > 0) {
        		unset($temp);
        		$temp['id'] = $model->id;
        		$temp['iddetail'] = idmaker::getCurrentID2();
        		$temp['componentname'] = '1';
	        	if ($jginfo['wager'] == '1') {
	        		$temp['amount'] = $employeeinfo['wageamount'];	
	        	} else if ($jginfo['wager'] == '2') {
	        		$temp['amount'] = ($employeeinfo['wageamount'] / $daysnum) * $model->presence;
	         	} 
	         	$details[] = $temp;
        	}
        	//--- bonus ---
        	if ($jginfo['bonus'] !== '0') {
	         	unset($temp);
	         	$temp['id'] = $model->id;
	         	$temp['iddetail'] = idmaker::getCurrentID2();
	         	$temp['componentname'] = '2';
	         	if ($daysnum - $model->presence <= 4) 
	         		$temp['amount'] = $jginfo['bonusamount'];
	         	else
	         		$temp['amount'] = 0;
	         	$details[] = $temp;
        	}
        	//--- thr ---
         	if (($jginfo['thr'] !== '0') && ($model->isthr == '1')) {
	        	unset($temp);
	         	$temp['id'] = $model->id;
	         	$temp['iddetail'] = idmaker::getCurrentID2();
	         	$temp['componentname'] = '3';
	         	$temp['amount'] = $jginfo['thrqty'] * $employeeinfo['wageamount'];
	         	$details[] = $temp;
         	}
         	//--- cashier ---
         	if ($jginfo['cashier'] !== '0') {
	         	unset($temp);
	         	$temp['id'] = $model->id;
	         	$temp['iddetail'] = idmaker::getCurrentID2();
	         	$temp['componentname'] = '4';
	         	$temp['amount'] = $jginfo['cashieramount'];
	         	$details[] = $temp;
         	}
         	//--- overtime ---
         	if ($model->overtime > 0) {
	         	unset($temp);
	         	$temp['id'] = $model->id;
	         	$temp['iddetail'] = idmaker::getCurrentID2();
	         	$temp['componentname'] = '5';
	         	$temp['amount'] = floor($model->overtime / 30) * 30 * $minutewage;
	         	$details[] = $temp;
         	}
         	//--- late charges ---
         	if ($model->late > 0) {
	         	unset($temp);
	         	$temp['id'] = $model->id;
	         	$temp['iddetail'] = idmaker::getCurrentID2();
	         	$temp['componentname'] = '6';
	         	$temp['amount'] = - floor($model->late) * $minutewage;
	         	$details[] = $temp;
         	}
         	//--- receivable ---
         	if ($model->receivable > 0) {
		        unset($temp);
	         	$temp['id'] = $model->id;
	         	$temp['iddetail'] = idmaker::getCurrentID2();
	         	$temp['componentname'] = '7';
	         	$temp['amount'] = $model->receivable;
	         	$details[] = $temp;
         	}
         	//--- transport ---
         	if ($model->transport > 0) {
			    unset($temp);
	         	$temp['id'] = $model->id;
	         	$temp['iddetail'] = idmaker::getCurrentID2();
	         	$temp['componentname'] = '8';
	         	$temp['amount'] = $model->transport;
	         	$details[] = $temp;
         	}
         	//--- payment ---
         	if ($model->payment > 0) {
			    unset($temp);
	         	$temp['id'] = $model->id;
	         	$temp['iddetail'] = idmaker::getCurrentID2();
	         	$temp['componentname'] = '9';
	         	$temp['amount'] = - $model->payment;
	         	$details[] = $temp;
         	}
         	//--- bpjs ---
         	if ($model->bpjs > 0) {
			    unset($temp);
	         	$temp['id'] = $model->id;
	         	$temp['iddetail'] = idmaker::getCurrentID2();
	         	$temp['componentname'] = 'A';
	         	$temp['amount'] = $model->bpjs;
	         	$details[] = $temp;
         	}
         	//--- lunch ---
         	if ($model->lunch > 0) {
		    	unset($temp);
	         	$temp['id'] = $model->id;
	         	$temp['iddetail'] = idmaker::getCurrentID2();
	         	$temp['componentname'] = 'B';
	         	$temp['amount'] = $model->lunch;
	         	$details[] = $temp;
         	}
        	foreach($details as $d) 
        		$model->total += $d['amount'];
        	
        	$model->total = idmaker::cashRound($model->total, 1000); 
         	return $details;
		}
}
