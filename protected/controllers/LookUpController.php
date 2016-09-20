<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LookUpController
 *
 * @author teddy
 */
class LookUpController extends Controller {
   //put your code here
   
	public function actionGetModel($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->selectDistinct('model')->from('items')
			->where('model like :p_model', array(':p_model'=>'%'.$term.'%'))
			->order('model')
			->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
        };
		
	}	
	
	public function actionGetBrand($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->selectDistinct('brand')->from('items')
			->where('brand like :p_brand', array(':p_brand'=>'%'.$term.'%'))
			->order('brand')
			->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};	
	}
	
	public function actionGetBatchcode($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->selectDistinct('concat(a.code,\'-\', a.name) as label, a.code as value')
				->from('items a')
				->where('a.code like :p_code', array(':p_code'=>$term.'%'))
				->order('a.code')
				->queryAll();
			if( !$data ) {
			$data=Yii::app()->db->createCommand()
				->selectDistinct('concat(a.code,\'-\', a.name) as label, a.code as value')
				->from('items a')
				->where('a.name like :p_name', array(':p_name'=>$term.'%'))
				->order('a.code')
				->queryAll();
			}
			//if( !$data ) {
			$data2=Yii::app()->db->createCommand()
				->selectDistinct('concat(a.batchcode,\'-\', b.name) as label, a.batchcode as value')
				->from('itembatch a')
				->join('items b', 'b.id = a.iditem')
				->where('a.batchcode like :p_batchcode', array(':p_batchcode'=>$term.'%'))
				->order('a.batchcode')
				->queryAll();
			//}
			if( !$data2 ) {
				$data2=Yii::app()->db->createCommand()
					->selectDistinct('concat(a.batchcode,\'-\', b.name) as label, a.batchcode as value')
					->from('itembatch a')
					->join('items b', 'b.id = a.iditem')
					->where('b.name like :p_name', array(':p_name'=>$term.'%'))
					->order('a.batchcode')
					->queryAll();
			}
			
			$data = array_merge($data, $data2);
			
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetExpenses($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->selectDistinct("concat(a.accountnum, ' -> ', a.name) as label, a.id as value")
				->from('expenses a')
				->where('a.name like :p_name', array(':p_name'=>$term.'%'))
				->order('a.name')
				->queryAll();
				
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetCashboxes($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->selectDistinct("concat(a.accountnum, ' -> ', a.name) as label, a.id as value")
			->from('cashboxes a')
			->where('a.name like :p_name', array(':p_name'=>$term.'%'))
			->order('a.name')
			->queryAll();
	
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetCashInCredit($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->selectDistinct("concat(a.id, ' -> ', a.name) as label, a.id as value")
				->from('accounts a')
				->where('a.name like :p_name and (a.kind like :p_kind1 or a.kind like :p_kind2 or a.kind like :p_kind3)', 
					array(':p_name'=>$term.'%', ':p_kind3'=>'Q1', ':p_kind2'=>'R%', ':p_kind1'=>'L%'))
				->order('a.name')
				->queryAll();
		
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetObjects($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->selectDistinct('objects')->from('items')
				->where('objects like :p_objects', array(':p_objects'=>'%'.$term.'%'))
				->order('objects')
				->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
        };
	}
	
	public function actionGetOldSupplier($term)
	{
		if (!Yii::app()->user->isGuest) {
			
			$sql = <<<EOS
	select nmsupplier from t_supplier
	where nmsupplier like '%$term%' and kosinyasi = '1'			
EOS;
			$data=Go_ODBC::openSQL($sql);
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$result[$key]=$value['nmsupplier'];
				}
			} else
				$result[0]='NA';
			echo json_encode($result);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetItemName($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('items')
			->where('name like :p_name', array(':p_name'=>'%'.$term.'%'))
			->order('name')
			->limit(12)
			->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetCurrName($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('currencies')
			->where('name like :p_name', array(':p_name'=>'%'.$term.'%'))
			->order('name')
			->limit(12)
			->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetItemName2($id)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('items')
			->where('id = :p_id', array(':p_id'=>$id))
			->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetItemFromBatchcode($batchcode)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->selectDistinct('a.iditem, b.name')
				->from('itembatch a')
				->join('items b', 'b.id = a.iditem')
				->where('a.batchcode = :p_batchcode', array(':p_batchcode'=>$batchcode))
				->queryRow();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetSalesName($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select('concat(firstname, \' \', lastname) as nama')->from('salespersons')
				->where('firstname like :p_firstname or lastname like :p_lastname', 
						array(':p_firstname'=>'%'.$term.'%', ':p_lastname'=>'%'.$term.'%'))
				->order('nama')
				->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetSalesID($name)
	{
		if (!Yii::app()->user->isGuest) {
			//print_r($name);
			$name=rawurldecode($name);
			list($firstname, $lastname) = explode(' ', $name);
			$data=Yii::app()->db->createCommand()->selectDistinct('id')->from('salespersons')
			->where("firstname = :p_firstname or lastname = :p_lastname", 
				array(':p_firstname'=> $firstname, ':p_lastname'=>$lastname))
			->order('id')
			->queryScalar();
			echo $data;
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	
	public function actionGetWareHouse($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()->select('code')->from('warehouses')
			->where('code like :p_code', array(':p_code'=>'%'.$term.'%'))
			->order('code')
			->queryColumn();
			if(count($data)) {
				foreach($data as $key=>$value) {
					//$data[$key]=rawurlencode($value);
					$data[$key]=$value;
				}
			} else
				$data[0]='NA';
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
   public function actionGetItem($name)
   {
		if (!Yii::app()->user->isGuest) {
	   		/*
	   			$data=Yii::app()->db->createCommand()->selectDistinct('concat(code, \'-\', name)')->from('items')
	              ->where('code like :p_code and type = :p_type', 
	              	array(':p_code'=>$name.'%',':p_type'=>1))
	              ->order('code, name')
	              ->queryColumn();
	   		*/
	   		//if (!$data) 
	   			/*$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('items')
	   				->where('name like :itemname and type = :p_type',
	   					array(':itemname'=>'%'.$name.'%',':p_type'=>1))
	   				->order('name')
	   				->queryColumn();*/
	   			$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('items')
	   			->where('name like :itemname',
	   					array(':itemname'=>'%'.$name.'%'))
	   					->order('name')
	   					->queryColumn();
	      
	      	if(count($data)) { 
	         	foreach($data as $key=>$value) {
	            	$data[$key]=rawurlencode($value);
	         	}
	      	} else {
	         $data[0]='NA';
	      	}
	      	echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
   }
   
   public function actionGetCItem($name)
   {
   	if (!Yii::app()->user->isGuest) {
   		/*$data=Yii::app()->db->createCommand()->selectDistinct('concat(code, \'-\', name)')->from('items')
   			->where('code like :p_code and type = :p_type',
   				array(':p_code'=>$name.'%', ':p_type'=>2))
   			->order('code, name')
   			->queryColumn();
		*/
   		//if (!$data) 
	   		/*$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('items')
   				->where('name like :itemname and type = :p_type',
   					array(':itemname'=>'%'.$name.'%', ':p_type'=>2))
   				->order('name')
   				->queryColumn();
			*/   		 
	   		$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('items')
	   		->where('name like :itemname',
	   				array(':itemname'=>'%'.$name.'%'))
	   				->order('name')
	   				->queryColumn();
	   		
   		if(count($data)) {
   			foreach($data as $key=>$value) {
   				$data[$key]=rawurlencode($value);
   			}
   		} else {
   			$data[0]='NA';
   		}
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetItemAll($name)
   {
   	if (!Yii::app()->user->isGuest) {
   		$data=Yii::app()->db->createCommand()->selectDistinct('concat(code, \'-\', name)')->from('items')
   			->where('code like :p_code',
   				array(':p_code'=>$name.'%'))
   			->order('code, name')
   			->queryColumn();
   		
   		if (!$data)
   			$data=Yii::app()->db->createCommand()->selectDistinct('concat(code, \'-\', name)')->from('items')
   				->where('name like :itemname',
   					array(':itemname'=>'%'.$name.'%'))
   				->order('code, name')
   				->queryColumn();
   		 
   		if(count($data)) {
   			foreach($data as $key=>$value) {
   				$data[$key]=rawurlencode($value);
   			}
   		} else {
   			$data[0]='NA';
   		}
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetConsignedItem($name)
   {
   	if (!Yii::app()->user->isGuest) {
   		/*$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('items')
   			->where('name like :itemname and type = :p_type', 
   			array(':itemname'=>'%'.$name.'%', ':p_type'=>2))
   			->order('name')
   			->queryColumn();
   		*/
   		
   		$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('items')
   		->where('name like :itemname',
   				array(':itemname'=>'%'.$name.'%'))
   				->order('name')
   				->queryColumn();
   		 
   		if(count($data)) {
   			foreach($data as $key=>$value) {
   				$data[$key]=rawurlencode($value);
   			}
   		} else {
   			$data[0]='NA';
   		}
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetCurr($name)
   {
   	if (!Yii::app()->user->isGuest) {
   		$data=Yii::app()->db->createCommand()->selectDistinct('name')->from('currencies')
   		->where('name like :p_name', array(':p_name'=>'%'.$name.'%'))
   		->order('name')
   		->queryColumn();
   		 
   		if(count($data)) {
   			foreach($data as $key=>$value) {
   				$data[$key]=rawurlencode($value);
   			}
   		} else {
   			$data[0]='NA';
   		}
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetItem2($term)
   {
   	if (!Yii::app()->user->isGuest) {
   		$data=Yii::app()->db->createCommand()
   		->select('name as label, id as value')
   		->from('items')
   		->where('name like :p_name',
   				array(':p_name'=>"%$term%"))
   		->limit(10)
   				->queryAll();
   		/*echo Yii::app()->db->createCommand()->select('a.donum, b.id')->from('stockentries a')
   		 ->leftJoin('purchasesreceipts b','b.donum = a.donum' )
   		->where("a.idsupplier = :idsupplier and b.id = NULL", array(':idsupplier'=>$idsupplier))->text;
   		*/
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetItem3($term)
   {
   	if (!Yii::app()->user->isGuest) {
   		$data=Yii::app()->db->createCommand()
   		->select("concat(name, ' - ', id) as label, id as value")
   		->from('items')
   		->where('name like :p_name',
   				array(':p_name'=>"%$term%"))
   				->limit(10)
   				->queryAll();
   		/*echo Yii::app()->db->createCommand()->select('a.donum, b.id')->from('stockentries a')
   		 ->leftJoin('purchasesreceipts b','b.donum = a.donum' )
   		->where("a.idsupplier = :idsupplier and b.id = NULL", array(':idsupplier'=>$idsupplier))->text;
   		*/
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetSupplier($term)
   {
   	if (!Yii::app()->user->isGuest) {
   		$data=Yii::app()->db->createCommand()
   			->select("concat(code, ' - ', firstname) as label, code as value")
   			->from('suppliers')
   			->where('firstname like :p_name',
   				array(':p_name'=>"%$term%"))
   				->limit(10)
   			->queryAll();
   		if (! $data) {
   			$data=Yii::app()->db->createCommand()
   				->select("concat(code, ' - ', firstname) as label, code as value")
   				->from('suppliers')
   				->where('code like :p_code',
   					array(':p_code'=>"$term%"))
   				->limit(10)
   				->queryAll();	
   		}
   		/*echo Yii::app()->db->createCommand()->select('a.donum, b.id')->from('stockentries a')
   		 ->leftJoin('purchasesreceipts b','b.donum = a.donum' )
   		 ->where("a.idsupplier = :idsupplier and b.id = NULL", array(':idsupplier'=>$idsupplier))->text;
   		*/
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetPartner($term)
   {
   	if (!Yii::app()->user->isGuest) {
   		$data=Yii::app()->db->createCommand()
   		->select("concat(name, ' - ', id) as label, id as value")
   		->from('partners')
   		->where('name like :p_name',
   				array(':p_name'=>"%$term%"))
   				->limit(10)
   				->queryAll();
   		/*echo Yii::app()->db->createCommand()->select('a.donum, b.id')->from('stockentries a')
   		 ->leftJoin('purchasesreceipts b','b.donum = a.donum' )
   		 ->where("a.idsupplier = :idsupplier and b.id = NULL", array(':idsupplier'=>$idsupplier))->text;
   		*/
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
	public function actionGetItemPrice($iditem)
   	{
		if (!Yii::app()->user->isGuest) {
	   		$data=Yii::app()->db->createCommand()->select('normalprice')->from('sellingprices')
	              ->where('iditem = :p_iditem', array(':p_iditem'=>$iditem))
	              ->order('idatetime desc')
	              ->queryScalar();
	      
	      	if($data==FALSE) { 
	           $data=-1;
	      	}
	      	echo $data;
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
   
   public function actionGetItemID($name)
   {
		if (!Yii::app()->user->isGuest) {  	
			//print_r($name);	
      		$name=rawurldecode($name);
      		$data=Yii::app()->db->createCommand()->select('id')->from('items')
              ->where("name = :p_name", array(':p_name'=>$name))
              ->order('id')
              ->queryScalar();
      		echo $data; 
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
   }
   
   public function actionGetItemID2($name)
   {
   	if (!Yii::app()->user->isGuest) {
   		//print_r($name);
   		$name=rawurldecode($name);
   		$data = substr($name, 0, strpos($name,'-'));
   		$data=Yii::app()->db->createCommand()->select('id')->from('items')
   		->where("code = :p_code", array(':p_code'=>$data))
   		->order('id')
   		->queryScalar();
   		echo $data;
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetItemCode($codename)
   {
   	if (!Yii::app()->user->isGuest) {
   		//print_r($name);
   		$data = substr($codename, 0, strpos($codename,'-'));
   		echo $data;
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetCurrID($name)
   {
   	if (!Yii::app()->user->isGuest) {
   		//print_r($name);
   		$name=rawurldecode($name);
   		$data=Yii::app()->db->createCommand()->select('id')->from('currencies')
   		->where("name = :p_name", array(':p_name'=>$name))
   		->order('id')
   		->queryScalar();
   		echo $data;
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetWareHouseID($name)
   {
   	if (!Yii::app()->user->isGuest) {
   		//print_r($name);
   		$name=rawurldecode($name);
   		$data=Yii::app()->db->createCommand()->select('id')->from('warehouses')
   		->where('code = :p_code', array(':p_code'=>$name))
   		->order('id')
   		->queryScalar();
   		echo json_encode($data);
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public function actionGetUndonePO($idsupplier)
   {
		if (!Yii::app()->user->isGuest) {
      		$idsupplier=rawurldecode($idsupplier);
      		$data=Yii::app()->db->createCommand()->select('id, regnum')->from('purchasesorders')
         		->where("status <> '2' and idsupplier = :p_idsupplier", array(':p_idsupplier'=>$idsupplier))
         		->queryAll();
      		echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
   }
   
   public function actionGetUnsettledPO($idsupplier)
   {
   		if (!Yii::app()->user->isGuest) {
   			$idsupplier=rawurldecode($idsupplier);
      		$data=Yii::app()->db->createCommand()->select('id, regnum')->from('purchasesorders')
         		->where("paystatus <> '2' and idsupplier = :idsupplier", array(':idsupplier'=>$idsupplier))
         		->queryAll();
      		echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
   }        
   
   public function actionGetUndoneDO($idsupplier)
   {
   		if (!Yii::app()->user->isGuest) {	   	
	   		$idsupplier=rawurldecode($idsupplier);
		   	/*echo Yii::app()->db->createCommand()->select('a.donum, b.id')->from('stockentries a')
		   	->leftJoin('purchasesreceipts b','b.donum = a.donum' )
		   	->where("a.idsupplier = :idsupplier and b.id = NULL", array(':idsupplier'=>$idsupplier))->text;
		   	*/			
		   	$data=Yii::app()->db->createCommand()->select('a.donum, c.id')
		   		->from('stockentries a')
		   		->join('purchasesorders b', 'b.regnum = a.transid')
		   		->leftJoin('purchasesreceipts c','c.donum = a.donum' )
		   		->where("b.idsupplier = :p_idsupplier and c.id is NULL",
      				array(':p_idsupplier' => $idsupplier))
		   		->queryAll();
		   	
		   	echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
   }
   
	public function actionGetUserOperation($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->authdb->createCommand()
				->select('description as label, name as value')
				->from('AuthItem')
				->where('type=:p_type and description like :p_desc', 
     				array(':p_type'=>'0', ':p_desc'=>"%$term%"))
				->queryAll();
   		/*echo Yii::app()->db->createCommand()->select('a.donum, b.id')->from('stockentries a')
   		 ->leftJoin('purchasesreceipts b','b.donum = a.donum' )
   		->where("a.idsupplier = :idsupplier and b.id = NULL", array(':idsupplier'=>$idsupplier))->text;
   		*/   		   
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
   		};
	}
   
	public function actionGetUserTask($term)
 	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->authdb->createCommand()
   				->select('description as label, name as value')
   				->from('AuthItem')
   				->where('type=:p_type and description like :p_desc',
   					array(':p_type'=>'1', ':p_desc'=>"%$term%"))
   				->queryAll();
		echo json_encode($data);
   		} else {
   			throw new CHttpException(404,'You have no authorization for this operation.');
   		};
	}
 
	public function actionGetUserRole($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->authdb->createCommand()
			->select('description as label, name as value')
			->from('AuthItem')
			->where('type=:p_type and description like :p_desc',
					array(':p_type'=>'2', ':p_desc'=>"%$term%"))
					->queryAll();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetUser($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->authdb->createCommand()
				->select('loginname as label, id as value')
				->from('users')
				->where('loginname like :p_loginname',
					array(':p_loginname'=>"%$term%"))
				->queryAll();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetTrans($id)
	{
		if (!Yii::app()->user->isGuest) {
			$prefix = substr($id, 0, 2);
			
			if ($prefix == 'RD') {
				$sql=<<<EOS
				select a.id, a.regnum, 'NA' as invnum,
				concat( 'Penukaran Pengiriman Barang - ',a.deliverynum, ' - ', a.receivername, ' - ', a.idatetime) as transinfo,
				'AC34' as transname
				from deliveryreplaces a
				where regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else if ($prefix == 'SJ') {
				$sql=<<<EOS
				select a.id, a.regnum, a.invnum,
				concat( 'Pengiriman Barang - ', a.invnum, ' - ', a.receivername, ' - ', a.idatetime) as transinfo,
				'AC13' as transname
				from deliveryorders a
				where regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'MD') {
				$sql=<<<EOS
				select a.id, a.regnum, 'NA' as invnum,
				concat( 'Permintaan Barang Display - NA - ', concat(b.firstname, ' ', b.lastname), 
					' - ', a.idatetime) as transinfo,
				'AC16' as transname
				from requestdisplays a
				join salespersons b on b.id = a.idsales
				where regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'PB') {
				$sql=<<<EOS
				select a.id, a.regnum, a.invnum,
				concat( 'Pengambilan Barang Pembeli - ',a.invnum,' - ', a.receivername, ' - ', a.idatetime) as transinfo,
				'AC19' as transname
				from orderretrievals a 
				where regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'PR') {
				$sql=<<<EOS
				select a.id, a.regnum, '-' as invnum,
				concat( 'Pengembalian Barang ke Pemasok - ', a.regnum,' - ', concat(c.firstname, ' ', c.lastname), ' - ', a.idatetime) as transinfo,
				'AC50' as transname
				from returstocks a
				join detailreturstocks b on b.id = a.id
				join suppliers c on c.id = a.idsupplier
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'TB') {
				$sql=<<<EOS
				select a.id, a.regnum, 'NA' as invnum,
				concat( 'Pemindahan Barang - NA - ', b.code, ' - ', a.idatetime) as transinfo,
				'AC18' as transname
				from itemtransfers a
				join warehouses b on b.id = a.idwhsource
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'SM') {
				$sql=<<<EOS
				select a.id, a.regnum, 'NA' as invnum,
				concat( 'Pengiriman Barang Tanpa Transaksi - ',a.receivername,' - ', a.idatetime) as transinfo,
				'AC14' as transname
				from deliveryordersnt a
				join detaildeliveryordersnt b on b.id = a.id
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'FB') {
				$sql=<<<EOS
				select a.id, a.regnum, a.invnum,
				concat( 'Pembatalan Faktur - ',a.invnum,' - ', a.idatetime) as transinfo,
				'AC23' as transname
				from salescancel a
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'FG') {
				$sql=<<<EOS
				select a.id, a.regnum, a.invnum,
				concat( 'Ganti Barang Faktur - ',a.invnum,' - ', a.idatetime) as transinfo,
				'AC24' as transname
				from salesreplace a
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'RE') {
				$sql=<<<EOS
				select a.id, a.regnum, a.retrievalnum, '-' as invnum, 
				concat( 'Penukaran Pengambilan Barang - ',a.retrievalnum,' - ', a.idatetime) as transinfo,
				'AC29' as transname
				from retrievalreplaces a
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'KS') {
				$sql=<<<EOS
				select a.id, a.regnum, '-' as invnum,
				concat( 'Pengiriman Barang utk Perbaikan - ',a.regnum,' - ', a.idatetime) as transinfo,
				'AC25' as transname
				from sendrepairs a
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else
			if ($prefix == 'KR') {
				$sql=<<<EOS
				select a.id, a.regnum, '-' as invnum,
				concat( 'Penerimaan Barang dari Perbaikan - ',a.regnum,' - ', a.idatetime) as transinfo,
				'AC33' as transname
				from receiverepairs a
				where a.regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			} else {
				$sql=<<<EOS
				select a.id, a.regnum,
				concat( 'Penerimaan Barang - ', b.firstname, ' ', b.lastname, ' - ', a.idatetime) as transinfo,
				'AC12' as transname
				from purchasesstockentries a
				join suppliers b on b.id = a.idsupplier
				where regnum=:p_regnum
EOS;
				$command=Yii::app()->db->createCommand($sql);
				$command->bindParam(':p_regnum', $id, PDO::PARAM_STR);
				$data=$command->queryAll();
			}
			
			if ($data !== FALSE)
				echo json_encode($data);
			else 
				echo json_encode(array(array('id'=>'NA')));
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionGetBankName($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->select('name')
			->from('salesposbanks')
			->where('name like :p_name',
					array(':p_name'=>"%$term%"))
					->queryColumn();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
		
	}
	
	public function actionGetBankID($name)
	{
		$name=rawurldecode($name);
		
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->select('id')
			->from('salesposbanks')
			->where('name = :p_name',
					array(':p_name'=>$name))
					->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionGetExpenseName($id)
	{
		$name=rawurldecode($id);
	
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select("concat(accountnum, ' -> ', name)")
				->from('expenses')
				->where('id = :p_id',
					array(':p_id'=>$id))
				->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionGetCashboxName($id)
	{
		$name=rawurldecode($id);
	
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->select("concat(accountnum, ' -> ', name)")
			->from('cashboxes')
			->where('id = :p_id',
					array(':p_id'=>$id))
					->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionGetAccountName($id)
	{
		$name=rawurldecode($id);
	
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select("concat(id, ' -> ', name)")
				->from('accounts')
				->where('id = :p_id',
					array(':p_id'=>$id))
				->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionGetSCAddress($id)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->select('address')
			->from('servicecenters')
			->where('id = :p_id',
					array(':p_id'=>$id))
					->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionCheckItemSerial($iditem, $serialnum, $idwh, $avail = '1')
	{
		//$iditem=rawurldecode($iditem);
		//$serialnum=rawurldecode($serialnum);
		
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select('count(*) as total')
				->from('stockentries a')
				->join('detailstockentries b', 'b.id = a.id')
				->where('b.iditem = :p_iditem and b.serialnum = :p_serialnum and a.idwarehouse = :p_idwh',
					array(':p_iditem'=>$iditem, ':p_serialnum'=>$serialnum, ':p_idwh'=>$idwh))
				/*->where('b.iditem = :p_iditem and b.serialnum = :p_serialnum',
					array(':p_iditem'=>$iditem, ':p_serialnum'=>$serialnum))*/
				->queryScalar();
			if ($data == FALSE) {
				$data=Yii::app()->db->createCommand()
					->select('count(*) as total')
					->from('wh'.$idwh.' a')
					->where('a.iditem = :p_iditem and a.serialnum = :p_serialnum and a.avail = :p_avail',
						array(':p_iditem'=>$iditem, ':p_serialnum'=>$serialnum, ':p_avail'=>$avail))
					/*->where('a.iditem = :p_iditem and a.serialnum = :p_serialnum',
				 		array(':p_iditem'=>$iditem, ':p_serialnum'=>$serialnum))*/
						->queryScalar();
			} 
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionCheckSerial($serialnum, $idwh)
	{
		//$idwh=rawurldecode($idwh);
		$serialnum=rawurldecode($serialnum);

		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select('iditem, avail, status')
				->from('wh'.$idwh.' a')
				->where('a.serialnum = :p_serialnum',
						array(':p_serialnum'=>$serialnum))
						/*->where('a.iditem = :p_iditem and a.serialnum = :p_serialnum',
						 array(':p_iditem'=>$iditem, ':p_serialnum'=>$serialnum))*/
				->queryRow();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	
	}
	
	public function actionGetExitedItemFromSerial($serialnum, $retrievalnum)
	{
		//$invnum = rawurldecode($invnum);
		$serialnum = rawurldecode($serialnum);
		
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select('b.iditem, c.name')
				->from('stockexits a')
				->join('detailstockexits b', 'b.id = a.id')
				->join('items c', 'c.id = b.iditem')
				->where('b.serialnum = :p_serialnum and a.transid = :p_transid',
						array(':p_serialnum'=>$serialnum, ':p_transid'=>$retrievalnum))
				->queryRow();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionCheckItemQty($iditem, $idwh)
	{
		//$invnum = rawurldecode($invnum);
		//$serialnum = rawurldecode($serialnum);
	
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select('count(*)')
				->from('wh'.$idwh)
				->where('iditem = :p_iditem and avail = :p_avail and status = :p_status',
					array(':p_iditem'=>$iditem, 'p_avail'=>'1', 'p_status'=>'1'))
				->queryScalar();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionCheckStickerInfo($stickerdate, $stickernum) 
	{
		if (!Yii::app()->user->isGuest) {
			$data = Yii::app()->db->createCommand()
				->select('count(*) as availnum')
				->from('salespos')->where('idsticker = :p_stickernum and idatetime like :p_stickerdate',
					[':p_stickernum'=>$stickernum, ':p_stickerdate'=>$stickerdate.'%'])
				->queryScalar();	
			
			if ($data == 0) { 
				echo json_encode(0);
				return;
			} else if ($data > 0) {
				$data = Yii::app()->db->createCommand()
					->select('count(*) as listednum')
					->from('stickertoguides')->where('stickernum = :p_stickernum',
						[':p_stickernum'=>$stickernum ])
					->queryScalar();
				if ($data == 0) {
					$data = Yii::app()->db->createCommand()
						->select('count(*) as listednum')
						->from('tippayments')->where('idsticker = :p_idsticker and ddatetime like :p_ddatetime',
							[':p_idsticker'=>$stickernum, ':p_ddatetime'=>$stockerdate.'%' ])
							->queryScalar();
					if ($data == 0)
						echo json_encode(2);
					else
						echo json_encode(1);
					return;
				} else {
					echo json_encode(1);
					return;
				}
			}
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		} 
	}
	
	public function actionGetGuideName($id)
	{
		$name=rawurldecode($id);
	
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
				->select("concat(firstname, ' ', lastname)")
				->from('guides')
				->where('id = :p_id', array(':p_id'=>$id))
				->queryScalar();
			if ($data == FALSE)
				echo json_encode(0);
			else 
				echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
	
	public function actionCompleteGuide($term)
	{
		if (!Yii::app()->user->isGuest) {
			$data=Yii::app()->db->createCommand()
			->select("concat(firstname,' - ',lastname) as label, id as value")
			->from('guides')
			->where("firstname like :p_term or lastname like :p_term or phone like :p_term",
					array(':p_term'=>"%$term%"))
					->queryAll();
			echo json_encode($data);
		} else {
			throw new CHttpException(404,'You have no authorization for this operation.');
		};
	}
}
