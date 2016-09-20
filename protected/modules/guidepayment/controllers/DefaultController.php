<?php

function cmp($a, $b)
{
	return strcmp($a['iditem'], $b['iditem']);
}

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $formid='AC67';
	public $tracker;
	public $state;
	
	private $grosssales = array();
	private $totaldiscount;

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
                $compositions = array();
                
                $this->state='create';
                $this->trackActivity('c');    
                    
                $model=new Guidepayments;
                $this->afterInsert($model);
                
                Yii::app()->session['master']='create';
                //as the operator enter for the first time, we load the default value to the session
                if (!isset(Yii::app()->session['Guidepayments'])) {
                   Yii::app()->session['Guidepayments']=$model->attributes;
                } else {
                // use the session to fill the model
                    $model->attributes=Yii::app()->session['Guidepayments'];
                }
                if (isset($_POST['Guidepayments'])) {
                	$model->attributes=$_POST['Guidepayments'];
                }
               // Uncomment the following line if AJAX validation is needed
               $this->performAjaxValidation($model);
				
				if (isset($_POST)){
					
					if(isset($_POST['yt1'])) {
						$model->attributes=$_POST['Guidepayments'];
                      //The user pressed the button;
						$this->beforePost($model);
						
						$respond=$model->save();
						if(!$respond) {
							throw new CHttpException(5002,'There is an error in master posting: '.serialize($model->errors));
	                    }

	                    if(isset(Yii::app()->session['Detailguidepayments']) ) {
	                    	$details=Yii::app()->session['Detailguidepayments'];
	                    	$respond=$this->saveDetails($details);
	                    	if (!$respond)
	                    		throw new CHttpException(5002,'There is an error in detail posting');
	                    }
	                     
	                    
						$this->afterPost($model);
						Yii::app()->session->remove('Guidepayments');
						$this->redirect(array('view','id'=>$model->id));

					} else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
						if ($_POST['command']=='countTip') {
							$model->attributes=$_POST['Guidepayments'];
                         	
							$details = array();
							$this->calculateGuide($model, $details);
                         	Yii::app()->session['Guidepayments']=$model->attributes;
                         	Yii::app()->session['Detailguidepayments']=$details;
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

			$this->state='update';
			$this->trackActivity('u');

			$model=$this->loadModel($id);
            $this->afterEdit($model);
             
			Yii::app()->session['master']='update';

			if(!isset(Yii::app()->session['Guidepayments']))
                Yii::app()->session['Guidepayments']=$model->attributes;
			else
                $model->attributes=Yii::app()->session['Guidepayments'];

			if(!isset(Yii::app()->session['Detailguidepayments'])) 
				Yii::app()->session['Detailguidepayments']=$this->loadDetails($id);
			if(!isset(Yii::app()->session['Detailguidepayments2']))
				Yii::app()->session['Detailguidepayments2']=$this->loadDetails2($id);
             
             // Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if(isset($_POST)) {
				if(isset($_POST['yt0'])) {
                      //The user pressed the button;
					$model->attributes=$_POST['Guidepayments'];
                       
					$this->beforePost($model);
					$respond=$this->checkWarehouse($model->idwarehouse);
					if (!$respond)
						throw new CHttpException(5000,'Lokasi anda tidak terdaftar');
					$respond = $this->checkSerialNum(Yii::app()->session['Detailguidepayments'], $model->idwarehouse);
					if (!$respond)
						throw new CHttpException(5001,'Nomor Seri yg anda daftarkan ada yg sdh terdaftar: '. $respond);
	                      
					$respond=$model->save();
					if(!$respond) {
						if (count($model->error) > 0 )
							$error = implode(',', $model->error);
						else
							$error = $model->error;
						throw new CHttpException(5002,'There is an error in master posting: '.$error);
	                }
	
					if(isset(Yii::app()->session['Detailguidepayments']) ) {
						$details=Yii::app()->session['Detailguidepayments'];
						$respond=$this->saveDetails($details, $model->idwarehouse);
						if (!$respond)
							throw new CHttpException(5002,'There is an error in detail posting');
					} 
	
					$this->afterPost($model);
					Yii::app()->session->remove('Guidepayments');
					Yii::app()->session->remove('Detailguidepayments');
					Yii::app()->session->remove('Deletedetailguidepayments');
					
					$this->redirect(array('view','id'=>$model->id));

				} else if (isset($_POST['command'])){
                      // save the current master data before going to the detail page
						if($_POST['command']=='adddetail') {
							$model->attributes=$_POST['Guidepayments'];
							Yii::app()->session['Guidepayments']=$_POST['Guidepayments'];
							$this->redirect(array('detailguidepayments/create',
                            	'id'=>$model->id));
                      	} else if ($_POST['command']=='getPO') {
                        	$model->attributes=$_POST['Guidepayments'];
                         	Yii::app()->session['Guidepayments']=$_POST['Guidepayments'];
                         	$this->loadLPB($model->transid, $model->id, $model->idwarehouse);
                      	} else if ($_POST['command']=='updateDetail') {
							$model->attributes=$_POST['Guidepayments'];
                         	Yii::app()->session['Guidepayments']=$_POST['Guidepayments'];
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

            $model=$this->loadModel($id);
            $this->trackActivity('d');
            $this->beforeDelete($model);
            $this->tracker->delete('guidepayments', $id);

            $detailmodels=Detailguidepayments::model()->findAll('id=:id',array(':id'=>$id));
            foreach($detailmodels as $dm) {
               $this->tracker->init();
               $this->tracker->delete('detailguidepayments', array('iddetail'=>$dm->iddetail));
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

               Yii::app()->session->remove('Guidepayments');
               Yii::app()->session->remove('Detailguidepayments');
               Yii::app()->session->remove('Deletedetailguidepayments');
               Yii::app()->session->remove('Detailguidepayments2');
               Yii::app()->session->remove('Deletedetailguidepayments2');
               $dataProvider=new CActiveDataProvider('Guidepayments',
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
               
                $model=new Guidepayments('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Guidepayments']))
			$model->attributes=$_GET['Guidepayments'];

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
                $this->tracker->restore('guidepayments', $idtrack);
                
                $dataProvider=new CActiveDataProvider('Guidepayments');
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
                $id = Yii::app()->tracker->createCommand()->select('id')->from('guidepayments')
                	->where('idtrack = :p_idtrack', array(':p_idtrack'=>$idtrack))
                	->queryScalar();
                
                $this->tracker->restoreDeleted('detailguidepayments', "id", $id );
                $this->tracker->restoreDeleted('guidepayments', "idtrack", $idtrack);
                
                $dataProvider=new CActiveDataProvider('Guidepayments');
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
	 * @return Guidepayments the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Guidepayments::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Guidepayments $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='guidepayments-form')
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
             $model=new Guidepayments;
             $model->attributes=Yii::app()->session['Guidepayments'];

             $details=Yii::app()->session['Detailguidepayments'];
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

             $model=new Guidepayments;
             $model->attributes=Yii::app()->session['Guidepayments'];

             $details=Yii::app()->session['Detailguidepayments'];
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


             $model=new Guidepayments;
             $model->attributes=Yii::app()->session['Guidepayments'];

             $details=Yii::app()->session['Detailguidepayments'];
             $this->afterDeleteDetail($model, $details);

             $this->render('update',array(
                 'model'=>$model,
             ));
         } else {
             throw new CHttpException(404,'You have no authorization for this operation.');
         }
      }
      
	public function actionShowDetail($id)
	{
		if(Yii::app()->authManager->checkAccess($this->formid.'-List',
				Yii::app()->user->id))  {
						
			$model= $this->loadModel($id);
		
			$this->getSales($model->id, $model->idsticker, $model->ddatetime);
	
			$temp = $this->getSalesDetail2($model->id, $model->idpartner, $model->idcomp,
					$model->idsticker, $model->ddatetime);
			
			$this->render('showdetail',array(
				'model'=>$model, 'detail'=>$temp
			));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		}
	}

     protected function saveNewDetails(array $details)
     {                  
         foreach ($details as $row) {
             $detailmodel=new Detailguidepayments;
             $detailmodel->attributes=$row;
             $respond=$detailmodel->insert();
             if (!$respond) {
                break;
             }
         }
         return $respond;
     }
     
     protected function saveNewDetails2(array $details)
     {
     	foreach ($details as $row) {
     		$detailmodel=new Detailguidepayments2;
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
             $detailmodel=Detailguidepayments::model()->findByPk($row['iddetail']);
             if($detailmodel==NULL) {
                 $detailmodel=new Detailguidepayments;
             } else {
                 if(count(array_diff($detailmodel->attributes,$row))) {
                     $this->tracker->init();
                     $this->tracker->modify('detailguidepayments', array('iddetail'=>$detailmodel->iddetail));
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
     
     protected function saveDetails2(array $details)
     {
     	$idmaker=new idmaker();
     
     	$respond=true;
     	foreach ($details as $row) {
     		$detailmodel=Detailguidepayments2::model()->findByPk($row['iddetail']);
     		if($detailmodel==NULL) {
     			$detailmodel=new Detailguidepayments2;
     		} else {
     			if(count(array_diff($detailmodel->attributes,$row))) {
     				$this->tracker->init();
     				$this->tracker->modify('detailguidepayments', array('iddetail'=>$detailmodel->iddetail));
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
             $detailmodel=Detailguidepayments::model()->findByPk($row['iddetail']);
             if($detailmodel) {
                 $this->tracker->init();
                 $this->trackActivity('d', $this->__DETAILFORMID);
                 $this->tracker->delete('detailguidepayments', $detailmodel->iddetail);
                 $respond=$detailmodel->delete();
                 if (!$respond) {
                   break;
                 }
             }
         }
         return $respond;
     }
     
     protected function deleteDetails2(array $details)
     {
     	$respond=true;
     	foreach ($details as $row) {
     		$detailmodel=Detailguidepayments2::model()->findByPk($row['iddetail']);
     		if($detailmodel) {
     			$this->tracker->init();
     			$this->trackActivity('d', $this->__DETAILFORMID);
     			$this->tracker->delete('detailguidepayments', $detailmodel->iddetail);
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
      $sql="select * from detailguidepayments where id='$id'";
      $details=Yii::app()->db->createCommand($sql)->queryAll();

      return $details;
     }
     
     protected function loadDetails2($id)
     {
     	$sql="select * from detailguidepayments2 where id='$id'";
     	$details=Yii::app()->db->createCommand($sql)->queryAll();
     
     	return $details;
     }


     protected function afterInsert(& $model)
     {
         $idmaker=new idmaker();
         $model->id=$idmaker->getCurrentID2();
         $model->idatetime=$idmaker->getDateTime();
         $model->regnum=$idmaker->getRegNum($this->formid);
         //$model->idwarehouse=lookup::WarehouseNameFromIpAddr($_SERVER['REMOTE_ADDR']);
         $model->amount = 0;
         $model->deposit = 0;
         $model->commission = 0;
         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
     }

     protected function afterPost(& $model)
     {
         $idmaker=new idmaker();
         if ($this->state == 'create') {
         	$idmaker->saveRegNum($this->formid, $model->regnum);
         } 
     }

     protected function beforePost(& $model)
     {
         $idmaker=new idmaker();

         $model->userlog=Yii::app()->user->id;
         $model->datetimelog=$idmaker->getDateTime();
         if ($this->state == 'create')
         	$model->regnum=$idmaker->getRegNum($this->formid);
         else if ($this->state == 'update') {
       
         }
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
     
      
      private function getCompositions($idpartner)
      {
      	return Yii::app()->db->createCommand()
      		->select('count(*)')
      		->from('detailpartners')
      		->where("id = :p_id", array(':p_id'=>$idpartner))
      		->queryScalar();   	
      }
      
    private function getVoucherNRetur($idsales)
    {
    	$voucheramount = Yii::app()->db->createCommand()
    		->select('sum(amount) as vamount')
    		->from('posreceipts')
    		->where("idpos = :p_idpos and (method = 'V' or method = 'R')",
    			array(':p_idpos'=>$idsales))	
    		->queryScalar();
    	return $voucheramount;
    }
    
    private function getSales($id, $idsticker, $ddatetime)
    {
    	$select1 = <<<EOS
 	a.id as iddetail, a.regnum as invoicenum, (a.total - a.tax) as amount, a.totaldiscount,
    a.idatetime, a.userlog as idcashier, a.datetimelog as cashierlog,
    a.discount
EOS;
   		$salesdata = Yii::app()->db->createCommand()
   			->select($select1)->from('salespos a')
   			->where("a.idsticker = :p_idsticker and a.idatetime like :p_datetime",
   				array(':p_idsticker'=>$idsticker, ':p_datetime'=>$ddatetime.'%'))
   			->order('a.regnum')
   			->queryAll(); 	
    	
    	$sql2 = <<<EOS
    	select sum(a.total) as totalretur
    	from salesposreturs a
    	where a.invoicenum = :p_invoicenum
EOS;

    	$salesreturs = Yii::app()->db->createCommand($sql2);
    	$invoicenum = '';
    	$salesreturs->bindParam(":p_invoicenum", $invoicenum);
    	
    	$select2 = <<<EOS
    	select sum(b.qty*b.price) as totalnondisc from 	
EOS;
    	$tempdata = Yii::app()->db->createCommand()
    		->select('a.regnum as invoicenum, sum(b.qty*b.price) as totalnondisc') 
    		->from('salespos a')
    		->join('detailsalespos b', 'b.id = a.id')
    		->where("a.idsticker = :p_idsticker and a.idatetime like :p_datetime",
    				array(':p_idsticker'=>$idsticker, ':p_datetime'=>$ddatetime.'%'))
			->group('a.regnum')
    		->order('a.regnum')
    		->queryAll();
   		
   		foreach($salesdata as & $sd) {
   			foreach($tempdata as $t) {
   				if ($t['invoicenum'] == $sd['invoicenum']) {
   					$sd['totalnondisc'] = $t['totalnondisc']; 
   					break;
   				}	
   			}
   			$sd['id'] = $id;	
   			$sd['iddetail'] = idmaker::getCurrentID2();
   			$sd['totaldiscount'] = $sd['totaldiscount'] + $this->getVRDisc($sd['invoicenum'], $sd['id'], $salesdata);
   			
   			$invoicenum = $sd['invoicenum'];
   			$totalretur = $salesreturs->queryScalar();
   			$sd['amount'] -= $totalretur;
   		};
   		
   		return $salesdata;
    }
    
    private function getUnSeenDisc($regnum, $salesdata)
    {
 		$disc = 0;
    	foreach($salesdata as $sd) {
    		if ($sd['invoicenum'] == $regnum) {
    			$disc = $sd['discount'] / $sd['totalnondisc'];
    			break;
    		}	
    	}
    	return $disc;
    }
    
    private function getVRDisc($regnum, $id, $salesdata)
    {
    	$disc = 0;
    	foreach($salesdata as $sd) {
    		if ($sd['invoicenum'] == $regnum) {
    	// Because voucher or/and retur deduction take place after total
    			$disc = $this->getVoucherNRetur($id) / $sd['amount'];
    			break;
    		}
    	}
    	return $disc;
    }
    
    private function getSalesDetail($id, array $guide, $idsticker, $ddatetime)
    {
    	$tip = $guide['commission'];
    	$tip2 = 1;
    	$sql1 = <<<EOS
    	SELECT a.id, b.iddetail, a.regnum, b.iditem, b.qty, b.price, b.discount, c.pct, c.id as idtipgroup
		FROM detailsalespos b
		JOIN salespos a ON a.id = b.id
		LEFT JOIN (
		detailitemtipgroups d 
		JOIN itemtipgroups c ON c.id = d.id
		) ON d.iditem = b.iditem
    	where a.idsticker = '$idsticker' and a.idatetime like '$ddatetime%' 
    	order by a.regnum
EOS;
    	$detailsales = array();
		$detailsales = Yii::app()->db->createCommand($sql1)
			->queryAll();
    
		$salesdata = $this->getSales($id, $idsticker, $ddatetime);
		
    	$sql2 = <<<EOS
    	select sum(b.qty) as totalretur, b.iditem
    	from detailsalesposreturs b 
    	join salesposreturs a on a.id = b.id
    	where a.invoicenum = :p_invoicenum
    	group by b.iditem
EOS;
    	$salesreturs = Yii::app()->db->createCommand($sql2);
    	$invoicenum = '';
    	$salesreturs->bindParam(":p_invoicenum", $invoicenum);
    	
    	
    	foreach($detailsales as & $ds) {
    		if ($ds['discount'] == 0) {
    			$ds['discount'] = $this->getUnSeenDisc($ds['regnum'], $salesdata) * $ds['price'];
    		}
    		
    		if ($invoicenum !== $ds['regnum']) {
    			$invoicenum = $ds['regnum'];
    			$returs = $salesreturs->queryAll();
    		};
    		
    		foreach($returs as $sr) {
    			if(isset($sr['iditem'])) {
    				if ($sr['iditem'] == $ds['iditem'])
    					$ds['qty'] -= $sr['totalretur'];
    			}
    		}
    		
    		$ds['discount'] += $this->getVRDisc($ds['regnum'], $ds['id'], $salesdata) * ($ds['price'] - $ds['discount']);
    		if ( is_null($ds['pct']) ) {
    			$ds['pct'] = $tip;
    			$ds['idtipgroup'] = '0';
    		} else
    			$ds['pct'] = $ds['pct'] * $tip2;
    		$ds['amount'] = ($ds['price'] - $ds['discount']) * $ds['qty'] * $ds['pct'] / 100;
    		$this->totaldiscount += $ds['discount'] * $ds['qty'];
    	};
    	unset($ds);
    	//print_r($detailsales);
    	//echo "<br>";
    	$detailcommission = array();
    	foreach($detailsales as $ds) {
    		$found = FALSE;
    		if (count($detailcommission) > 0) {
    			foreach($detailcommission as & $d) {
    				if ($d['idtipgroup'] == $ds['idtipgroup'] ) {
    					//echo $d['amount'] . ' + '. $ds['amount'] . "<br>";
    					$d['amount'] = $d['amount'] + $ds['amount'];	
    					$found = TRUE;
    					break;
    				} 
    			}
    		};
    		if ($found === FALSE) {
    			$temp = array();
    			$temp['id'] = $id;
    			$temp['iddetail'] = idmaker::getCurrentID2();
    			$temp['idtipgroup'] = $ds['idtipgroup'];
    			$temp['amount'] = $ds['amount'];
    			$detailcommission[] = $temp;
    		};
    	}
    	//print_r($detailcommission);
    	//die;
    	//return $detailsales;
    	return $detailcommission;
    }
    
    private function getSalesDetail2($id, $guide, $idsticker, $ddatetime)
    {
    	$tip = $guide['commission'];
    	$tip2 = 1;
    	$sql1 = <<<EOS
    	SELECT b.iddetail, a.regnum, a.idsticker, a.idatetime, a.userlog as idcashier,
    	b.iditem, b.qty, b.price, b.discount, c.pct, c.id as idtipgroup
		FROM detailsalespos b
		JOIN salespos a ON a.id = b.id
		LEFT JOIN (
		detailitemtipgroups d
		JOIN itemtipgroups c ON c.id = d.id
		) ON d.iditem = b.iditem
    	where a.idsticker = '$idsticker' and a.idatetime like '$ddatetime%'
    	order by a.regnum
EOS;
    	$detailsales = Yii::app()->db->createCommand($sql1)
    		->queryAll();
    	
    	$sql2 = <<<EOS
    	select sum(b.qty) as totalretur, b.iditem
    	from detailsalesposreturs b
    	join salesposreturs a on a.id = b.id
    	where a.invoicenum = :p_invoicenum
    	group by b.iditem
EOS;
    	$salesreturs = Yii::app()->db->createCommand($sql2);
    	$invoicenum = '';
    	$salesreturs->bindParam(":p_invoicenum", $invoicenum);
    
    	foreach($detailsales as & $ds) {
    		if ($ds['discount'] == 0) {
    			$ds['discount'] = $this->getUnSeenDisc($ds['regnum']) * $ds['price'];
    		}
    		
    		if ($invoicenum !== $ds['regnum']) {
    			$invoicenum = $ds['regnum'];
    			$returs = $salesreturs->queryAll();
    		};
    		
    		foreach($returs as $sr) {
    			if (isset($sr['iditem'])) {
    				if ($sr['iditem'] == $ds['iditem'])
    					$ds['qty'] -= $sr['totalretur'];
    			}
    		}
    
    		$ds['discount'] += $this->getVRDisc($ds['regnum'], $ds['id']) * ($ds['price'] - $ds['discount']);
    		if ( is_null($ds['pct']) ) {
    			$ds['pct'] = $tip;
    			$ds['idtipgroup'] = '0';
    		} else
    	 		$ds['pct'] = $ds['pct'] * $tip2;
			$ds['amount'] = ($ds['price'] - $ds['discount']) * $ds['qty'] * $ds['pct'] / 100;
			$ds['id'] = $id;    	
    	}
        unset($ds);
        
    	return $detailsales;
    }

    public function actionPrint($id)
    {
    	if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
    			Yii::app()->user->id)) {
    				$this->trackActivity('p');
    
    		$model=$this->loadModel($id);
    		$detailmodel=$this->loadDetails($id);
    		$detailmodel2=$this->loadDetails2($id);
			Yii::import('application.vendors.tcpdf.*');
			require_once ('tcpdf.php');
			Yii::import('application.modules.guidepayment.components.*');
    		require_once('print_guidepayment.php');
			ob_clean();
    
    		execute($model, $detailmodel, $detailmodel2);
    	} else {
    		throw new CHttpException(404,'You have no authorization for this operation.');
    	}
    }

    
    public function actionPrintastext($id)
    {
    	if(Yii::app()->authManager->checkAccess($this->formid.'-Append',
    			Yii::app()->user->id)) {
			$this->trackActivity('p');
    
    		$model=$this->loadModel($id);
    		$detailmodel=$this->loadDetails($id);
			$detailmodel2=$this->loadDetails2($id);
			
			Yii::import('application.modules.guidepayment.components.*');
			require_once('printtext.php');
			execute($model, $detailmodel, $detailmodel2);
    	
    	} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
    	}
    }
    
    private function calculateGuide(& $model, & $details)
    {
    	$idguide = $model->idguide;
    	$guide = Yii::app()->db->createCommand()
    		->select()->from('guides')->where('id = :p_id', [':p_id'=>$idguide])
    		->queryRow();
    	
    	$stickers = Yii::app()->db->createCommand()
    		->select()->from('stickertoguides')
    		->where('idguide = :p_idguide and paid = :p_paid',
    			[':p_idguide'=>$idguide, ':p_paid'=>0])
    		->queryAll();
    	
    	print_r($stickers);
    	$guideSalesSummary = array();
    	foreach($stickers as $stk) {
    		$sales = $this->getSales($model->id, $stk['stickernum'], $stk['stickerdate']);
    		$guideSalesSummary = array_merge($guideSalesSummary, $sales);
    		unset($sales);
    	}
    	
    	$guideDetailCommission = array();
    	foreach($stickers as $stk) {
    		$commission = $this->getSalesDetail($model->id, $guide, $stk['stickernum'], $stk['stickerdate']);
    		$guideDetailCommission = array_merge($guideDetailCommission, $commission);
    		unset($commission);
    	}
    	
    	$totalcommission = 0;
    	foreach($guideDetailCommission as $cms) {
    		$totalcommission =+ $cms['amount'];
    	}
    	$model->commission = $totalcommission;
    	
    	foreach($stickers as $stk) {
    		Yii::app()->db->createCommand()
    			->update('stickertoguides',['paid'=>'1'],
    				'stickernum = :p_stickernum and stickerdate like :p_stickerdate',
    				[':p_stickerdate'=>$stk['stickerdate'].'%',':p_stickernum'=>$stk['stickernum']]);
    	}
    	
    	$totaldeposit = Yii::app()->db->createCommand()
    			->select('(deposit+commission-amount) as totaldeposit')
    			->from('guidepayments')
    			->where('idguide = :p_idguide', [':p_idguide'=>$guide['id']])
    			->order('id')
    			->queryScalar();
    	if (!$totaldeposit)
    		$model->deposit = 0;
    	else 	
    		$model->deposit = $totaldeposit;
    	
    	$stickerdetail = array();
    	foreach($stickers as $stk) {
    		$stickerdetail = $this->getSalesDetail2($model->id, $guide, $stk['stickernum'], $stk['stickerdate']);
    		$details = array_merge($details, $stickerdetail);
    	}
    }
}