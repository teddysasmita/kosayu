<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lookup
 *
 * @author teddy
 */
class lookup extends CComponent {
   //put your code here
   
   public static function orderStatus($stat)
   {
      switch ($stat) {
         case '0':
            return 'Belum Diproses';
         case '1':
            return 'Diproses Sebagian';
         case '2':
            return 'Selesai Diproses';
            
      }
   }
   
   public static function reverseOrderStatus($wstat)
   {
      switch ($wstat) {
         case 'Belum Diproses':
            return '0';
         case 'Telah Diproses':
            return '1';
      }
   }
   
   public static function invoiceStatus($stat)
   {
      switch ($stat) {
         case '0':
            return 'Belum Dibayar';
         case '1':
            return 'Dibayar Sebagian';
         case '2':
            return 'Dibayar Lunas';
      }
   }
   
   public static function reverseInvoiceStatus($wstat)
   {
      switch ($wstat) {
         case 'Belum Dibayar':
            return '0';
         case 'Dibayar Sebagian':
            return '1';
         case 'Dibayar Lunas':
            return '2';
      }
   }
   
   public static function paymentStatus($status)
   {
   	switch ($status) {
	   	case '0':
	   		return 'Belum Diproses';
	   	case '1':
	   		return 'Terbayar dgn Tunai';
	   	case '2':
	   		return 'Terbayar dgn Transfer';
	   	case '3':
	   		return 'Terbayar dgn Cek/Giro';
   	}
   }
   
   public static function activeStatus($status)
   {
   		switch ($status) {
   			case '0':
   				return 'Tidak Aktif';
   			case '1':
   				return 'Aktif';
   		}   
   }
   
   public static function ItemNameFromItemID($id)
   {
      $sql="select name from items where id='$id'";
      return Yii::app()->db->createCommand($sql)->queryScalar();
   }
   
   public static function ItemIDFromItemName($id)
   {
   	$sql="select id from items where name='$name'";
   	return Yii::app()->db->createCommand($sql)->queryScalar();
   }
    
   
   public static function SalesPersonNameFromID($id)
   {
   	$sql="select concat(firstname, ' ', lastname) as name from salespersons where id='$id'";
   	return Yii::app()->db->createCommand($sql)->queryScalar();
   }
   
   public static function InventoryTakingLabelFromID($id)
   {
   	$sql="select operationlabel from inventorytakings where id='$id'";
   	return Yii::app()->db->createCommand($sql)->queryScalar();
   }
   
   public static function UserNameFromUserID($id)
   {
      $sql="select fullname from users where id='$id'";
      return Yii::app()->authdb->createCommand($sql)->queryScalar();
   }
   
   public static function UserIDFromName($name)
   {
		if(strlen($name)>0)
			$name='%'.$name.'%';
		$sql="select id from users where fullname like '$name'";
		$data=Yii::app()->authdb->createCommand($sql)->queryScalar();
   	
		if($data===false)
   			return '';
   		else
   			return $data;
   }
   
   public static function SalesInvoiceNumFromInvoiceID($id)
   {
      $sql="select regnum from salesinvoices where id='$id'";
      return Yii::app()->db->createCommand($sql)->queryScalar();
   }
   
   public static function PurchasesInvoiceNumFromInvoiceID($id)
   {
      $sql="select regnum from purchasesinvoices where id='$id'";
      return Yii::app()->db->createCommand($sql)->queryScalar();
   }
   
   public static function PurchasesOrderNumFromID($id)
   {
      $sql="select regnum from purchasesorders where id='$id'";
      return Yii::app()->db->createCommand($sql)->queryScalar();
   }
   
   public static function CustomerNameFromCustomerID($id)
   {
      $sql="select concat(firstname, ' ', lastname) as name from customers where id='$id'";
      return Yii::app()->db->createCommand($sql)->queryScalar();
   }
   
   public static function SupplierNameFromSupplierID($id)
   {
      $sql="select concat(firstname, ' ', lastname) as name from suppliers where id='$id'";
      return Yii::app()->db->createCommand($sql)->queryScalar();
   }
   
   public static function SalesNameFromID($id)
   {
   	$sql="select concat(firstname, ' ', lastname) as name from salespersons where id='$id'";
   	return Yii::app()->db->createCommand($sql)->queryScalar();
   }
   
   public static function SupplierIDFromLastName($name)
   {
   		if(strlen($name)>0) {
   			$name='%'.$name.'%';
   		} else
   			$name='%';
   		$data=Yii::app()->db->createCommand()
   			->select('id')->from('suppliers')->where("lastname like :boom", array(':boom'=>$name))
			->queryScalar();
		
		if($data==false)
			return 'NAN';
   		else
   			return $data;
   }
   public static function SupplierIDFromFirstName($name)
	{	
		if(strlen($name)>0) {
			$name='%'.$name.'%';
		} else
			$name='%';
	   	$data=Yii::app()->db->createCommand()
			->select('id')->from('suppliers')->where("firstname like :boom", array(':boom'=>$name))
			->queryScalar();
	   	
   		if($data==false)
   			return 'NAN';
   		else
   			return $data;	
	   	/*
   	for($i=1; $i<count($suppliername)-1; $i++) {
   		$command->bindParam(2, $suppliername[$i]);
		$data=$command->queryColumn();
		print_r($data);
		die();
		if (count($data)==1)
			break;
   	};
   	if (count($data)>0)
		return $data[0];
   	else
   		return '';
   	*/	
   }
   
   public static function WarehouseNameFromWarehouseID($id)
   {
      $sql="select code as name from warehouses where id='$id'";
      $name=Yii::app()->db->createCommand($sql)->queryScalar();
      if(!$name) {
         return 'Tidak Terdaftar';
      } else
         return $name;
   }
   
   public static function WarehouseNameFromIpAddr($ipaddr)
   {
   		$found = array();
   		
      	$sql="select id, code, ipaddr from warehouses";
      	$names=Yii::app()->db->createCommand($sql)->queryAll();
		foreach( $names as $name ) {
			$ipaddrs = explode(';', $name['ipaddr'] );
			$key = array_search($ipaddr, $ipaddrs);
			if ($key !== FALSE) {
				$found[] = array('id'=>$name['id'], 'code'=>$name['code']);
			}
      	}
		
      	return $found;
   }
   
   public static function CompanyNameFromServiceCenterID($id)
   {
   	$sql="select companyname from servicecenters where id = '$id'";
   	$name=Yii::app()->db->createCommand($sql)->queryScalar();
   	return $name;
   }
   
   public static function WarehouseIDFromCode($code)
   {
   	$sql="select id from warehouses where code='$code'";
   	$name=Yii::app()->db->createCommand($sql)->queryScalar();
   	if(!$name) {
   		return 'NA';
   	} else
   		return $name;
   }
   
   public static function TypeToName($type)
   {
      switch ($type) {
         case 1:
            return 'Tunggal';
         case 2:
            return 'Paket';
         case 3:
            return 'Jasa';
      } 
   }
   
   public static function StockAvailName($type)
   {
   	switch ($type) {
   		case 1:
   			return 'Ada';
   		case 0:
   			return 'Keluar';
   	}
   }
   
   public static function StockStatusName($type)
   {
   	switch ($type) {
   		case 3:
   			return 'Retur';
   		case 2:
   			return 'Servis';
   		case 1:
   			return 'Bagus';
   		case 0:
   			return 'Rusak';
   	}
   }
   
   public static function BankNameFromID($id)
   {
   	$sql="select name from salesposbanks where id='$id'";
   	$name=Yii::app()->db->createCommand($sql)->queryScalar();
   	if(!$name) {
   		return 'NA';
   	} else
   		return $name;
   }
   
   public static function CardType($symbol)
   {
   		switch ($symbol) {
   		case 'KK':
   			return 'Kartu Kredit';
   		case 'KD':
   			return 'Kartu Debit';
   		}	
   }
   
   public static function SalesreplaceNameFromCode($code)
   {
   		switch ($code) {
   			case '0':
   				return 'Tetap';
   			case '1':
   				return 'Dirubah';
   			case '2':
   				return 'Dihapus';
   		} 
   }
   
   public static function GetSupplierNameFromSerialnum($serialnum)
   {
   		return Yii::app()->db->createCommand()->select("concat(d.firstname, ' ', d.lastname) as suppliername")
   			->from('detailstockentries a')
   			->join('stockentries b', 'a.id = b.id')
   			->join('purchasesstockentries c', 'c.regnum = b.transid')
   			->join('suppliers d', 'd.id = c.idsupplier')
   			->where('a.serialnum = :p_serialnum', array(':p_serialnum'=>$serialnum))
   			->queryScalar();
   }

   public static function OwnerNameFromCode($code)
   {
   	switch ($code) {
   		case '0':
   			return 'Ibu Linda T';
   		case '1':
   			return 'Bp Welly T';
   		case '2':
   			return 'Bp Sandy T';
   		case '3':
   			return 'Ibu Vera T';
   	}
   }
   
   public static function getTrans($data)
   {
   		switch($data['transname']) {
   			case 'AC16':
   				return Yii::app()->createUrl("requestdisplays/default/viewRegnum", 
   					array('regnum'=>$data['transid']));
   			case 'AC13':
   				return Yii::app()->createUrl("deliveryorders/default/viewRegnum",
   					array('regnum'=>$data['transid']));
   			case 'AC19':
   				return Yii::app()->createUrl("orderretrievals/default/viewRegnum",
   					array('regnum'=>$data['transid']));
   			case 'AC18':
   				return Yii::app()->createUrl("itemtransfers/default/viewRegnum",
   					array('regnum'=>$data['transid']));
   			case 'AC12':
   				return Yii::app()->createUrl("purchasesstockentries/default/viewRegnum",
   					array('regnum'=>$data['transid']));
   			case 'AC50':
   				return Yii::app()->createUrl("returstocks/default/viewRegnum",
   					array('regnum'=>$data['transid']));
   			case 'AC28':
   				return Yii::app()->createUrl("displayentries/default/viewRegnum",
   					array('regnum'=>$data['transid']));
   		};
   }
   
	public static function SendRepairexit($data)
	{
		switch($data['exit']) {
			case '1': {
				echo "<span class='money'>OK</span>";
				break;
			}
			case '0': {
				echo "<span class='errorMessage'>Belum</span>";
				break;
			}
		}
	}
	
	public static function ReceiveRepairexit($data)
	{
		switch($data['entry']) {
			case '1': {
				echo "<span class='money'>OK</span>";
				break;
			}
			case '0': {
				echo "<span class='errorMessage'>Belum</span>";
				break;
			}
		}
	}
	
	public static function RepairCheck($data)
	{
		if ($data['selected'] == '1') 
			return true;
		else
			return false;
	}
   
}


?>
