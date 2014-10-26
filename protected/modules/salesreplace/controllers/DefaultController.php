<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC241';
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
                    
			$model=new Salesreplace;
			$this->afterInsert($model);
                
			Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
			if (!isset(Yii::app()->session['Salesreplace'])) {
				Yii::app()->session['Salesreplace']=$model->attributes;
			} else {
                // use the session to fill the model
				$model->attributes=Yii::app()->session['Salesreplace'];
			}
                
               // Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if (isset($_POST['Salesreplace'])){
				if(isset($_POST['yt0'])) {
					//The user pressed the button;
					$model->attributes=$_POST['Salesreplace'];
					$this->beforePost($model);
					$respond=$model->save();
					if(!$respond) 
						throw new CHttpException(404,'There is an error in master posting: '.print_r($model->errors));
					$this->afterPost($model);
				      
					if(isset(Yii::app()->session['Detailsalesreplace']) ) {
						$details=Yii::app()->session['Detailsalesreplace'];
						$respond=$respond&&$this->saveNewDetails($details);
					} 
                      
					if($respond) {
						Yii::app()->session->remove('Salesreplace');
						Yii::app()->session->remove('Detailsalesreplace');
						$this->redirect(array('view','id'=>$model->id));
					}
				} else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
					if($_POST['command']=='adddetail') {
						$model->attributes=$_POST['Salesreplace'];
						Yii::app()->session['Salesreplace']=$_POST['Salesreplace'];
						$this->redirect(array('detailsalesreplace/create',
							'id'=>$model->id, 'regnum'=>$model->regnum));
					} else if ($_POST['command'] == 'setInvnum') {
						$model->attributes=$_POST['Salesreplace'];
						$total = $this->loadInvoice($model->invnum);
						$model->totalcash = $total['cash'];
						$model->totalnoncash = $total['noncash'];
						$model->totaldiff = $total['diff'];
						Yii::app()->session['Salesreplace'] = $model->attributes;
						Yii::app()->session['Detailsalesreplace']=$this->loadSales($model->invnum,
							$model->id);
					} else if ($_POST['command']=='updateDetail') {
						$model->attributes=$_POST['Salesreplace'];
                        Yii::app()->session['Salesreplace']=$_POST['Salesreplace'];
					}
				}
			}
			
			$this->render('create',array('model'=>$model));
                
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

            if(!isset(Yii::app()->session['Salesreplace']))
				Yii::app()->session['Salesreplace']=$model->attributes;
            else
                $model->attributes=Yii::app()->session['Salesreplace'];
			
            if(!isset(Yii::app()->session['Detailsalesreplace'])) 
				Yii::app()->session['Detailsalesreplace']=$this->loadDetails($id);
             
             // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);

			if(isset($_POST['Salesreplace'])) {
				if(isset($_POST['yt0'])) {
					$this->beforePost($model);
                    $model->attributes=$_POST['Salesreplace'];
                    $this->tracker->modify('salesreplace', $id);
                    $respond=$model->save();
					if(!$respond) 
						throw new CHttpException(404,'There is an error in master posting');
					$this->afterPost($model);
                    
					if(isset(Yii::app()->session['Detailsalesreplace'])) {
                         $details=Yii::app()->session['Detailsalesreplace'];
                         $respond=$respond&&$this->saveDetails($details);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail posting');
                         }
                     };
                     
                     if(isset(Yii::app()->session['DeleteDetailsalesreplace'])) {
                         $deletedetails=Yii::app()->session['DeleteDetailsalesreplace'];
                         $respond=$respond&&$this->deleteDetails($deletedetails);
                         if(!$respond) {
                           throw new CHttpException(404,'There is an error in detail deletion');
                         }
                     };
                     
					Yii::app()->session->remove('Salesreplace');
					Yii::app()->session->remove('Detailsalesreplace');
					Yii::app()->session->remove('DeleteDetailsalesreplace');
					$this->redirect(array('view','id'=>$model->id));
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
            $this->tracker->delete('salesreplace', $id);

            $detailmodels=Detailsalesreplace::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailsalesreplace', array('iddetail'=>$dm->iddetail));
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
				
               Yii::app()->session->remove('Salesreplace');
               Yii::app()->session->remove('Detailsalesreplace');
               Yii::app()->session->remove('Deletedetailsalesreplace');
               $dataProvider=new CActiveDataProvider('Salesreplace',
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
               
                $model=new Salesreplace('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Salesreplace']))
			$model->attributes=$_GET['Salesreplace'];

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
                $this->tracker->restore('salesreplace', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Salesreplace');
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
                $id = Yii::app()->tracker->createCommand()->select('id')->from('salesreplace')
                	->where('idtrack = :p_idtrack', array(':p_idtrack'=>$idtrack))
                	->queryScalar();
                $this->tracker->restoreDeleted('detailsalesreplace', "id", $id );
                $this->tracker->restoreDeleted('salesreplace', "idtrack", $idtrack);
                
                $dataProvider=new CActiveDataProvider('Salesreplace');
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
	 * @return Salesreplace the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Salesreplace::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Salesreplace $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='salesreplace-form')
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
             $model=new Salesreplace;
             $model->attributes=Yii::app()->session['Salesreplace'];

             $details=Yii::app()->session['Detailsalesreplace'];
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

             $model=new Salesreplace;
             $model->attributes=Yii::app()->session['Salesreplace'];

             $details=Yii::app()->session['Detailsalesreplace'];
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


             $model=new Salesreplace;
             $model->attributes=Yii::app()->session['Salesreplace'];

             $details=Yii::app()->session['Detailsalesreplace'];
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
             $detailmodel=new Detailsalesreplace;
             $detailmodel->attributes=$row;
             $respond=$detailmodel->insert();
             if (!$respond) {
             	throw new CHttpException(404,'There is an error in detail posting');
                //break;
             }
         }
         return $respond;
     }
     
        protected function saveDetails(array $details)
        {
            $idmaker=new idmaker();
            
            $respond=true;
            foreach ($details as $row) {
                $detailmodel=Detailsalesreplace::model()->findByPk($row['iddetail']);
                if($detailmodel==NULL) {
                    $detailmodel=new Detailsalesreplace;
                } else {
                    if(count(array_diff($detailmodel->attributes,$row))) {
                        $this->tracker->init();
                        $this->tracker->modify('detailsalesreplace', array('iddetail'=>$detailmodel->iddetail));
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
                $detailmodel=Detailsalesreplace::model()->findByPk($row['iddetail']);
                if($detailmodel) {
                    $this->tracker->init();
                    $this->trackActivity('d', $this->__DETAILFORMID);
                    $this->tracker->delete('detailsalesreplace', $detailmodel->id);
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
         $sql="select * from detailsalesreplace where id='$id'";
         $details=Yii::app()->db->createCommand($sql)->queryAll();

         return $details;
        }
        
        protected function afterInsert(& $model)
        {
            $idmaker=new idmaker();
            $model->id=$idmaker->getCurrentID2();
            $model->idatetime=$idmaker->getDateTime();
            $model->regnum=$idmaker->getRegNum($this->formid);
            /*$model->totalcash = 0;
            $model->totalnoncash = 0;
            $model->totaldiff = 0;
            */$lookup=new lookup();
            //$model->status=$lookup->reverseOrderStatus('Belum Diproses');
        }
        
        protected function afterPost(& $model)
        {
            $idmaker=new idmaker();
            if ($this->state == 'create')
            	$idmaker->saveRegNum($this->formid, substr($model->regnum,2));    
            Action::setInvStatus($model->invnum, '2');
        }
        
        protected function beforePost(& $model)
        {
            $idmaker=new idmaker();
            
            $model->userlog=Yii::app()->user->id;
            $model->datetimelog=$idmaker->getDateTime();
            if ($this->state == 'create')
            	$model->regnum='FG'.$idmaker->getRegNum($this->formid);
            else if ($this->state == 'update')
            	Action::setInvStatus($model->invnum, '1');
        }
        
        protected function beforeDelete(& $model)
        {
        	Action::setInvStatus($model->invnum, '1');
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
        	$totaldisc=0;
        	foreach ($details as $row) {
        		$old=$row['price']*$row['qty'];
        		if ($row['deleted']=='1')
        			$new=($row['pricenew']-$row['discountnew'])*$row['qtynew'];
        		else if ($row['deleted']=='0')
        			$new=$old;
        		else if ($row['deleted']=='2')
        			$new=0;
        		$total=$total+($old-$new);
        		//$totaldisc=$totaldisc+$row['discount']*$row['qty'];
        	}
        	$model->attributes=Yii::app()->session['Salesreplace'];
        	$model->totaldiff=$total;
        }
        
        public static function setInvStatus($invnum, $status)
        {
        	$sql="update salespos set status = :p_status where regnum = :p_regnum ";
        	$stmt=Yii::app()->db->createCommand($sql)
        	->execute(array(':p_regnum'=>$invnum, ':p_status'=>$status));
        }
        
        protected function loadSales($invnum, $id)
        {
        	$salesdetails=Yii::app()->db->createCommand()->select('b.*')
        	->from('salespos a')->join('detailsalespos b', 'b.id = a.id')
        	->where('a.regnum = :p_regnum', array(':p_regnum'=>$invnum))
        	->queryAll();
        	foreach($salesdetails as $detail) {
        		$canceldetail['id']=$id;
        		$canceldetail['iddetail']=idmaker::getCurrentID2();
        		$canceldetail['iditem']=$detail['iditem'];
        		$canceldetail['qty']=$detail['qty'];
        		$canceldetail['price']=$detail['price'];
        		$canceldetail['discount']=$detail['discount'];
        		$canceldetail['deleted']='1';
        		$canceldetail['iditemnew']=$detail['iditem'];
        		$canceldetail['qtynew']=$detail['qty'];
        		$canceldetail['pricenew']=$detail['price'];
        		$canceldetail['discountnew']=$detail['discount'];
        		$canceldetail['userlog']=Yii::app()->user->id;
        		$canceldetail['datetimelog']=idmaker::getDateTime();
        
        		$canceldetails[]=$canceldetail;
        	};
        	return $canceldetails;
        }
        
        protected function loadInvoice($invnum)
        {
        	$id=Yii::app()->db->createCommand()
        	->select('id')->from('salespos')
        	->where('status=:p_status and regnum=:p_regnum',
        			array(':p_status'=>'1', ':p_regnum'=>$invnum))
        			->queryScalar();
        	if ($id !== FALSE) {
        		$salesCash=Yii::app()->db->createCommand()
        		->select('(cash-cashreturn) as total')
        		->from('salespos')
        		->where('regnum = :p_regnum',
        				array(':p_regnum'=>$invnum))
        				->queryScalar();
        		//echo $salesCash. ' ';
        		if (!$salesCash)
        			$salesCash = 0;
        		$salesNonCash=Yii::app()->db->createCommand()
        		->select('sum(b.amount + b.surcharge) as total')
        		->from('salespos a')
        		->join('posreceipts b', "b.idpos = a.id")
        		->where('a.regnum = :p_regnum',
        				array(':p_regnum'=>$invnum))
        				->queryScalar();
        		//echo $salesNonCash. ' ';
        		if (!$salesNonCash)
        			$salesNonCash = 0;
        		$receivableCash=Yii::app()->db->createCommand()
        		->select('(a.cash-a.cashreturn) as total')
        		->from('receivablespos a')
        		->where('a.invnum = :p_regnum',
        				array(':p_regnum'=>$invnum))
        				->queryScalar();
        		//echo $receivableCash. ' ';
        		if (!$receivableCash)
        			$receivableCash = 0;
        		$receivableNonCash=Yii::app()->db->createCommand()
        		->select('sum(b.amount+b.surcharge) as total')
        		->from('receivablespos a')
        		->join('posreceipts b', "b.idpos = a.id")
        		->where('a.regnum = :p_regnum',
        				array(':p_regnum'=>$invnum))
        				->querySCalar();
        		//echo $receivableNonCash. ' ';
        		if (!$receivableNonCash)
        			$receivableNonCash = 0;
        		$total['cash'] = $salesCash+$receivableCash;
        		$total['noncash'] = $salesNonCash+$receivableNonCash;
        		$total['diff'] = 0;
        		//echo $total;
        		return $total;
        	} else
        		return array('cash'=>0, 'noncash'=>0, 'diff'=>0);
        }
        
		public function actionPrintreplace($id)
		{
			if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
				Yii::app()->user->id)) {
			$this->trackActivity('p');
			 
			$model=$this->loadModel($id);
			$detailmodel=$this->loadDetails($id);
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			Yii::import('application.modules.salesreplace.components.*');
			require_once('printreplace.php');
			ob_clean();
	
			execute($model, $detailmodel);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
		 
		 
		 
	}
}
