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
   
   public static function voucherStatus($stat)
   {
   	switch ($stat) {
   		case '0':
   			return 'Berlaku';
   		case '1':
   			return 'Telah Digunakan';
   		case '2':
   			return 'Tidak Berlaku';
   
   	}
   }
   
   public static function getMethod($code)
   {
		switch ($code) {
   			case 'T':
   				return 'Transfer';
   			case 'C':
   				return 'Tunai';
   			case 'KD':
   				return 'Kartu Debit';
   			case 'KK':
   				return 'Kartu Kredit';
   			case 'V':
   				return 'Voucher';
   			case 'R':
   				return 'Retur';	
   			case 'BG':
   				return 'Cheque/BG';
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
   
   public static function CurrSymbolFromID($id)
   {
   		$symbol = Yii::app()->db->createCommand()
   			->select('symbol')->from('currencies')
   			->where('id = :p_id', array(':p_id'=>$id))
   			->queryScalar();
		if (!$symbol) 
			return 'Rp';
		else
			return $symbol;
   }
   
   public static function CurrRateFromID($id)
   {
   		$rate = Yii::app()->db->createCommand()
   			->select('rate')->from('currencyrates')
   			->where('id = :p_id', array(':p_id'=>$id))
   			->queryScalar();
   		if (!$rate)
   			return '-';
   		else
   			return $rate;
   }
   
   public static function ItemNameFromItemID2($id)
   {
		$sql="select concat(name, '-', code) from items where id='$id'";
   		return Yii::app()->db->createCommand($sql)->queryScalar();
   }
   
   public static function ItemNameFromItemCode($code)
   {
   		if ($code == '-')
   			return '-';
   		else {
			$data = Yii::app()->db->createCommand()
				->select('b.name')->from('itembatch a')
				->join('items b', 'b.id = a.iditem')
				->where('a.batchcode = :p_batchcode', 
					array(':p_batchcode'=>$code))
				->order('a.id desc')
				->queryScalar();
   			if (!$data) {
   				$data = Yii::app()->db->createCommand()
   				->select('a.name')->from('items a')
   				->where('a.code = :p_code',
   						array(':p_code'=>$code))
   						->queryScalar();
   			}
   			return $data;
   		}
   }
   
   public static function ItemPriceFromItemCode($code)
   {
		if ($code == '-')
	   		return '-';
	   	else {
	   		$data = Yii::app()->db->createCommand()
	   		->select('a.normalprice')->from('sellingprices a')
	   		->where('a.iditem = :p_batchcode',
	   				array(':p_batchcode'=>$code))
	   				->queryScalar();
	   		return $data;
	   	}
   }
   
   public static function ItemCodeFromItemID($id)
   {
   	$sql="select code from items where id='$id'";
   	return Yii::app()->db->createCommand($sql)->queryScalar();
   }
   
   public static function ItemTipGroupNameFromID($id)
   {
   		if ($id == '0')
   			return 'Komisi Standar';
   		else {
   			$sql="select name from itemtipgroups where id='$id'";
   			return Yii::app()->db->createCommand($sql)->queryScalar();
   		}
   	}
   	
   	public static function DetailPartnerNameFromID($id)
   	{
		$sql="select comname from detailpartners where iddetail='$id'";
   		return Yii::app()->db->createCommand($sql)->queryScalar();
   	}
   	
   	public static function PartnerNameFromID($id)
   	{
   		$sql="select name from partners where id='$id'";
   		return Yii::app()->db->createCommand($sql)->queryScalar();
   	}
   
   public static function CurrNameFromID($id)
   {
   	$sql="select name from currencies where id='$id'";
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
   
   public static function PurchasesNumFromID($id)
   {
		$sql="select left(idatetime, 10) as idate, regnum from purchases where id='$id'";
   		$data = Yii::app()->db->createCommand($sql)->queryRow();
   	
   		if ($data)
   			return 'Tanggal: '.$data['idate'].'- Nomor: '.$data['regnum'];
   		else
   			return 'Tidak Ditemukan';
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
   

   public static function SupplierCodeFromID($id)
   {
   	$sql="select code from suppliers where id='$id'";
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
		if (isset($data['checked'])) {
			if ($data['checked'] == 1) 
				return true;
			else
				return false;
		} else
			return true;
	}
	
	public static function GetOldSupplierID($name)
	{
		$sql = "select kdsupplier from t_supplier where nmsupplier = '$name'";
		$data = Go_ODBC::openSQL($sql);
		
		return $data['kdsupplier'];
	}
   
	public static function getbuyprice($code)
	{
		$price =  Yii::app()->db->createCommand()
			->select('id, buyprice')->from('itembatch')
			->where('batchcode = :p_batchcode', array(':p_batchcode'=>$code))
			->order('id desc')
			->queryRow();
		if (!$price['buyprice'])
			return 0;
		else
			return $price['buyprice'];
	}
	
	public static function ExpenseNameFromID($id)
	{
		$name = Yii::app()->db->createCommand()
			->select('name')->from('expenses')
			->where('id = :p_id', array(':p_id'=>$id))
			->queryScalar();
		
		if(!$name)
			return 'Belum Terdaftar';
		else
			return $name;
	}
	
	public static function ExpenseNameFromNum($num)
	{
		$name = Yii::app()->db->createCommand()
		->select('name')->from('expenses')
		->where('accountnum = :p_num', array(':p_num'=>$num))
		->queryScalar();
	
		if(!$name)
			return 'Belum Terdaftar';
		else
			return $name;
	}
	
	public static function CashboxNameFromID($id)
	{
		$name = Yii::app()->db->createCommand()
		->select('name')->from('cashboxes')
		->where('id = :p_id', array(':p_id'=>$id))
		->queryScalar();
	
		if(!$name)
			return 'Belum Terdaftar';
		else
			return $name;
	}
	
	public static function CashboxNameFromNum($num)
	{
		$name = Yii::app()->db->createCommand()
		->select('name')->from('cashboxes')
		->where('accountnum = :p_num', array(':p_num'=>$num))
		->queryScalar();
	
		if(!$name)
			return 'Belum Terdaftar';
		else
			return $name;
	}
	
	public static function AccountNameFromID($id)
	{
		$name = Yii::app()->db->createCommand()
		->select('name')->from('accounts')
		->where('id = :p_id', array(':p_id'=>$id))
		->queryScalar();
	
		if(!$name)
			return 'Belum Terdaftar';
		else
			return $name;
	}
	
	public static function PurchasesReturInfoFromID($id)
	{
		$info = Yii::app()->db->createCommand()
			->select('regnum, idatetime, total')
			->from('purchasesreturs')
			->where('id = :p_id', array(':p_id'=>$id))
			->queryRow();

		if (!$info)
			return 'Belum Terdaftar';
		else
			return $info['regnum'].' - '.$info['idatetime'];
	}
}


?>
