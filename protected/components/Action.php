<?php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of action
 *
 * @author teddy
 */
class Action extends CComponent {
   
   //put your code here
   
	public static function getTransName($id)
	{
		return Yii::app()->db->createCommand()->select('remark')
			->from('information')->where('id = :p_id', array(':p_id'=>$id))
			->queryScalar();
	}
    
	public static function decodePrintStockCardUrl($data)
	{
		//return print_r($data);
		return Yii::app()->createUrl('inventorytaking/default/printstockcard', array('iditem'=>$data['iditem'], 
					'idwarehouse'=>$data['idwarehouse']));
	}
	
   public static function decodeDeleteDetailSalesOrderUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('salesorder/detailsalesorders/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailSalesOrderUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('salesorder/detailsalesorders/update', array('iddetail'=>$data['iddetail']))  ;
   }
   
   public static function decodeViewDetailSalesOrderUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('salesorder/detailsalesorders/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailIdguidePrintUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('idguideprint/detailidguideprints/delete', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeUpdateDetailIdguidePrintUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('idguideprint/detailidguideprints/update', array('iddetail'=>$data['iddetail']))  ;
   }
    
   public static function decodeViewDetailIdguidePrintUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('idguideprint/detailidguideprints/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailAcquisitionsUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('acquisition/detailacquisitions/delete', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeUpdateDetailAcquisitionsUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('acquisition/detailacquisitions/update', array('iddetail'=>$data['iddetail']))  ;
   }
    
   public static function decodeViewDetailAcquisitionsUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('acquisition/detailacquisitions/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailAcquisitionsnsnUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('acquisition/detailacquisitionsnsn/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailAcquisitionsnsnUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('acquisition/detailacquisitionsnsn/update', array('iddetail'=>$data['iddetail']))  ;
   }
   
   public static function decodeViewDetailAcquisitionsnsnUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('acquisition/detailacquisitionsnsn/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodePrintStockCard2($data)
   {
   		return $data['iditem'].'-'.$data['idwarehouse'];
   	
   }
   
   public static function decodeRestoreHistoryDetailSalesOrderUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('salesorder/detailsalesorders/restore', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreDeletedDetailSalesOrderUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('salesorder/detailsalesorders/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreDeletedSalesorderUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('salesorder/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistorySalesorderUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('salesorder/default/restore', array('idtrack'=>$data['idtrack']));
   }

   public static function decodeUpdateDetailInputInventoryTakingUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('inputinventorytaking/detailinputinventorytakings/update', array('iddetail'=>$data['iddetail']))  ;
   }
   
   public static function decodeUpdate2DetailInputInventoryTakingUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('inputinventorytaking/detailinputinventorytakings/update2', array('iddetail'=>$data['iddetail']))  ;
   }
   
   public static function decodeViewDetailInputInventoryTakingUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('inputinventorytaking/detailinputinventorytakings/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeRestoreHistoryDetailInputInventoryTakingUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('inputinventorytaking/detailinputinventorytakings/restore', array('idtrack'=>$data['idtrack'], 'iddetail'=>$data['iddetail']));
   }
    
   public static function decodeRestoreDeletedDetailInputInventoryTakingUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('inputinventorytaking/detailinputinventorytakings/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreDeletedInputInventoryTakingUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('inputinventorytaking/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
    
   public static function decodeDeleteDetailInputInventoryTakingUrl($data)
   {
   	return Yii::app()->createUrl('inputinventorytaking/detailinputinventorytakings/delete2', array('iddetail'=>$data['iddetail']));	
   	
   }
   
   public static function decodeRestoreHistoryInputInventoryTakingUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('inputinventorytaking/default/restore', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeDeleteDetailSalesInvoiceUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('detailsalesinvoices/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailSalesInvoiceUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('detailsalesinvoices/update', array('iddetail'=>$data['iddetail']))  ;
   }
   
   public static function decodeViewDetailSalesInvoiceUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('detailsalesinvoices/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailPurchasesInvoiceUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('detailpurchasesinvoices/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailPurchaseInvoiceUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('detailpurchasesinvoices/update', array('iddetail'=>$data['iddetail']))  ;
   }
   
   public static function decodeViewDetailPurchasesInvoiceUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('detailpurchasesinvoices/view', array('iddetail'=>$data['iddetail']));
   }
      
   public static function decodeDeleteDetailPurchasesUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('purchases/detailpurchases/delete', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeUpdateDetailPurchasesUrl($data, $regnum)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('purchases/detailpurchases/update',
   			array('iddetail'=>$data['iddetail'], 'regnum'=>$regnum ));
   }
    
   public static function decodeViewDetailPurchasesUrl($data, $regnum)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('purchases/detailpurchases/view',
   			array('iddetail'=>$data['iddetail'], 'regnum'=>$regnum));
   }
   
   public static function decodeDeleteDetailPaysalaryUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('paysalaries/detailpaysalaries/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailPaysalaryUrl($data, $regnum)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('paysalaries/detailpaysalaries/update',
   			array('iddetail'=>$data['iddetail'], 'regnum'=>$regnum ));
   }
   
   public static function decodeViewDetailPaysalaryUrl($data, $regnum)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('paysalaries/detailpaysalaries/view',
   			array('iddetail'=>$data['iddetail'], 'regnum'=>$regnum));
   }
   
   public static function decodeDeleteDetailPurchasesReturUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('paysalariesretur/detailpaysalariesreturs/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailPurchasesReturUrl($data, $regnum)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('purchasesretur/detailpurchasesreturs/update',
   			array('iddetail'=>$data['iddetail'], 'regnum'=>$regnum ));
   }
   
   public static function decodeViewDetailPurchasesReturUrl($data, $regnum)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('purchasesretur/detailpurchasesreturs/view',
   			array('iddetail'=>$data['iddetail'], 'regnum'=>$regnum));
   }
   
   public static function decodeDeleteDetailConsignPurchasesUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('consignpurchases/detailconsignpurchases/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailConsignPurchasesUrl($data, $regnum)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('consignpurchases/detailconsignpurchases/update',
   			array('iddetail'=>$data['iddetail'], 'regnum'=>$regnum ));
   }
   
   public static function decodeViewDetailConsignPurchasesUrl($data, $regnum)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('consignpurchases/detailconsignpurchases/view',
   			array('iddetail'=>$data['iddetail'], 'regnum'=>$regnum));
   }
   
   public static function decodeDeleteDetailPurchasesOrderUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('purchasesorder/detailpurchasesorders/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailPurchasesOrderUrl($data, $regnum)
   {
      //return print_r($data);
      return Yii::app()->createUrl('purchasesorder/detailpurchasesorders/update', 
         array('iddetail'=>$data['iddetail'], 'regnum'=>$regnum ));
   }
   
   public static function decodeViewDetailPurchasesOrderUrl($data, $regnum)
   {
      //return print_r($data);
      return Yii::app()->createUrl('purchasesorder/detailpurchasesorders/view', 
         array('iddetail'=>$data['iddetail'], 'regnum'=>$regnum));
   }
   
   public static function decodeDeleteDetailPurchasesOrder2Url($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('purchasesorder/detailpurchasesorders2/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailPurchasesOrder2Url($data, $regnum)
   {
      //return print_r($data);
      return Yii::app()->createUrl('purchasesorder/detailpurchasesorders2/update', 
         array('iddetail'=>$data['iddetail'], 'regnum'=>$regnum))  ;
   }
   
   public static function decodeViewDetailPurchasesOrder2Url($data, $regnum)
   {
      //return print_r($data);
      return Yii::app()->createUrl('purchasesorder/detailpurchasesorders2/view', 
         array('iddetail'=>$data['iddetail'], 'regnum'=>$regnum));
   }
   
   public static function decodeDeleteDetailPurchaseMemoUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('purchasesmemo/detailpurchasesmemos/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailPurchaseMemoUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('purchasesmemo/detailpurchasesmemos/update', array('iddetail'=>$data['iddetail']))  ;
   }
   
   public static function decodeViewDetailPurchaseMemoUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('purchasesmemo/detailpurchasesmemos/view', array('iddetail'=>$data['iddetail']));
   }
   
   
   public static function decodeRestoreDeletedStockEntryUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/stockentries/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryStockEntryUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/stockentries/default/restorehistory', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeDeleteDetailStockEntryUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('/stockentries/detailstockentries/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeRestoreHistoryTipPaymentUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/tippayment/default/restorehistory', array('idtrack'=>$data['idtrack']));
   }
    
   public static function decodeDeleteDetailTipPaymentUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/tippayment/detailtippayment/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailStockEntryUrl($data, $idwh, $transname, $transid)
   {
      return Yii::app()->createUrl('/stockentries/detailstockentries/update', 
      		array('iddetail'=>$data['iddetail'], 'idwh'=>$idwh, 'transname'=>$transname,
      			'transid'=>$transid));
   }
   
   public static function decodeViewDetailStockEntryUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('/stockentries/detailstockentries/view', array('iddetail'=>$data['iddetail']));
   }
   
	public static function decodeDeleteDetailBarcodePrintUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('/barcodeprint/detailbarcodeprints/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailBarcodePrintUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('/barcodeprint/detailbarcodeprints/update', array('iddetail'=>$data['iddetail']))  ;
   }
   
   public static function decodeViewDetailBarcodePrintUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('/barcodeprint/detailbarcodeprints/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailItemtipGroupUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/itemtipgroup/detailitemtipgroups/delete', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeUpdateDetailItemtipGroupUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/itemtipgroup/detailitemtipgroups/update', array('iddetail'=>$data['iddetail']))  ;
   }
    
   public static function decodeViewDetailItemtipGroupUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/itemtipgroup/detailitemtipgroups/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailPartnerUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/partner/detailpartners/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailPartnerUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/partner/detailpartners/update', array('iddetail'=>$data['iddetail']))  ;
   }
   
   public static function decodeViewDetailPartnerUrl($data)
   {
   	//return Yii::app()->createUrl('/partner/detailpartners/view', array('iddetail'=>'boom'));
   	  return Yii::app()->createUrl('/partner/detailpartners/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailPurchasesReceiptUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/purchasesreceipt/detailpurchasesreceipts/delete', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeUpdateDetailPurchasesReceiptUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/purchasesreceipt/detailpurchasesreceipts/update', array('iddetail'=>$data['iddetail']))  ;
   }
    
   public static function decodeViewDetailPurchasesReceiptUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/purchasesreceipt/detailpurchasesreceipts/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailPurchasesPaymentUrl($data)
   {
   	//return print_r($data);
		return Yii::app()->createUrl('/purchasespayment/detailpurchasespayments/update', 
			array('iddetail'=>$data['iddetail']))  ;
   }
   
   public static function decodeViewDetailPurchasesPaymentUrl($data)
   {
   	//return print_r($data);
   		return Yii::app()->createUrl('/purchasespayment/detailpurchasespayments/view', 
   			array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailPurchasesPayment3Url($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/purchasespayment/detailpurchasespayments3/update',
   			array('id'=>$data['id']))  ;
   }
    
   public static function decodeDeleteDetailPurchasesPayment3Url($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/purchasespayment/detailpurchasespayments3/delete',
   			array('id'=>$data['id']));
   }

   public static function decodeUpdateDetailConsignPayment2Url($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/consignpayment/detailconsignpayments2/update',
   			array('id'=>$data['id']))  ;
   }
    
   public static function decodeDeleteDetailConsignPayment2Url($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/consignpayment/detailconsignpayments2/delete',
   			array('id'=>$data['id']));
   }
    
   public static function decodeDeleteDetailStockExitUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/stockexits/detailstockexits/delete', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeUpdateDetailStockExitUrl($data, $idwh, $transname, $transid)
   {
   	//return Yii::app()->createUrl('/stockexits/detailstockexits/update', array('idwh'=>$idwh));
   	return Yii::app()->createUrl('/stockexits/detailstockexits/update', array('iddetail'=>$data['iddetail'], 
   			'idwh'=>$idwh, 'transname'=>$transname, 'transid'=>$transid));
   }
    
   public static function decodeViewDetailStockExitUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/stockexits/detailstockexits/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeRestoreDeletedStockExitUrl($data)
   {
   		return Yii::app()->createUrl('/stockexits/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeDeleteDetailStockDamageUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/stockdamage/detailstockdamage/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailStockDamageUrl($data, $idwh)
   {
   	//return Yii::app()->createUrl('/stockdamage/detailstockdamage/update', array('idwh'=>$idwh));
   	return Yii::app()->createUrl('/stockdamage/detailstockdamage/update', array('iddetail'=>$data['iddetail'], 'idwh'=>$idwh));
   }
   
   public static function decodeViewDetailStockDamageUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/stockdamage/detailstockdamage/view', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeRestoreDeletedStockDamageUrl($data)
   {
   	return Yii::app()->createUrl('/stockdamage/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeDeleteDetailPaymentUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('detailpayments/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailPaymentUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('detailpayments/update', array('iddetail'=>$data['iddetail']))  ;
   }
   
   public static function decodeViewDetailPaymentUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('detailpayments/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailReceiptUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('detailreceipts/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailReceiptUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('detailreceipts/update', array('iddetail'=>$data['iddetail']))  ;
   }
   
   public static function decodeViewDetailReceiptUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('detailreceipts/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailItemUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('item/detailitems/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailItemUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('item/detailitems/update', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeRestoreHistoryCustomerUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('customer/default/restore', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreDeletedCustomerUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('customer/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryExpenseUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('expenses/default/restore', array('idtrack'=>$data['idtrack']));
   }
    
   public static function decodeRestoreDeletedExpenseUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('expenses/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   

   public static function decodeRestoreHistoryCashboxUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('cashboxes/default/restore', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreDeletedCashboxUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('cashboxes/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryItemtipGrouprUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('itemtipgroup/default/restore', array('idtrack'=>$data['idtrack']));
   }
    
   public static function decodeRestoreDeletedItemtipGroupUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('itemtipgroup/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryPartnerrUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('partner/default/restore', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreDeletedPartnerUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('partner/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistorySupplierUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('supplier/default/restore', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreDeletedSupplierUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('supplier/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
    
   public static function decodeRestoreHistoryItemUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('item/default/restore', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreDeletedItemUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('item/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryStockAdjustmentUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('stockadjustments/default/restore', array('idtrack'=>$data['idtrack']));
   }
    
   public static function decodeRestoreDeletedStockAdjustmentUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('stockadjustments/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryEmployeeUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('employees/default/restore', array('idtrack'=>$data['idtrack']));
   }
    
   public static function decodeRestoreDeletedEmployeeUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('employees/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryJobgroupUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('jobgroups/default/restore', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreDeletedJobgroupUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('jobgroups/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryItemBatchUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('itembatch/default/restore', array('idtrack'=>$data['idtrack']));
   }
    
   public static function decodeRestoreDeletedItemBatchUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('itembatch/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryCashOutUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('cashouts/default/restore', array('idtrack'=>$data['idtrack']));
   }
    
   public static function decodeRestoreDeletedCashOutUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('cashouts/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryCashInUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('cashins/default/restore', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreDeletedCashInUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('cashins/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryDeliveryOrderUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('deliveryorders/default/restore', array('idtrack'=>$data['idtrack']));
   }
    
   public static function decodeRestoreDeletedDeliveryOrderUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('deliveryorders/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function setStatusPO($idpo, $status)
   {
      Yii::app()->db->createCommand()
         ->update('purchasesorders', array('status'=>$status), 'regnum=:id', array(':id'=>$idpo));
   }
   
   	public static function updatePaymentStatusPurchase($idpurchase)
   	{
   		$total = Yii::app()->db->createCommand()
   			->select("(total - discount) as grandtotal")->from('purchases')
   			->where("id = :p_id", array(':p_id'=>$idpurchase))
   			->queryScalar();
   		
   		$paid = Yii::app()->db->createCommand()
   			->select("sum('amount') as total")->from('detailpurchasespayments')
   			->where("idpurchase = :p_idpurchase", array(':p_idpurchase'=>$idpurchase))
   			->queryScalar();
   		
   		if ($paid == $total)
   			$this->setPaymentStatusPurchase($idpurchase, '2');
   		else if ($paid > 0)
   			$this->setPaymentStatusPurchase($idpurchase, '1');
   		else 
   			$this->setPaymentStatusPurchase($idpurchase, '0');
   	}
	
	public static function setPaymentStatusPurchase($idpurchase, $status)
	{
		Yii::app()->db->createCommand()
   			->update('purchases', array('paystatus'=>$status), 'id=:id', array(':id'=>$idpurchase));
	}
    
	public static function setStatusRetur($idpurchaseretur, $status)
	{
		Yii::app()->db->createCommand()
			->update('purchasesreturs', array('status'=>$status), 'id=:id', array(':id'=>$idpurchaseretur));
	}
   
   public static function decodeRestoreHistoryWarehouseUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('warehouse/default/restore', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryPurchaseMemoUrl($data)
   {
      //return print_r($data);
      return Yii::app()->createUrl('warehouse/default/restore', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreDeletedPurchasesStockEntryUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/purchasesstockentries/default/restoreDeleted', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeRestoreHistoryPurchasesStockEntryUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('/purchasesstockentries/default/restore', array('idtrack'=>$data['idtrack']));
   }
   
   public static function decodeViewDetailPurchasesStockEntryUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('purchasesstockentries/detailpurchasesstockentries/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailPurchasesStockEntryUrl($data)
   {
   		return Yii::app()->createUrl('purchasesstockentries/detailpurchasesstockentries/update', array('iddetail'=>$data['iddetail']));
   }

   public static function decodeDeleteDetailPurchasesStockEntryUrl($data)
   {
   	return Yii::app()->createUrl('purchasesstockentries/detailpurchasesstockentries/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeViewDetailDeliveryOrderNtUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('deliveryordersnt/detaildeliveryordersnt/view', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeUpdateDetailDeliveryOrderNtUrl($data)
   {
   	return Yii::app()->createUrl('deliveryordersnt/detaildeliveryordersnt/update', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailDeliveryOrderNtUrl($data)
   {
   	return Yii::app()->createUrl('deliveryordersnt/detaildeliveryordersnt/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeViewDetailRequestDisplayUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('requestdisplays/detailrequestdisplays/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailRequestDisplayUrl($data)
   {
   	return Yii::app()->createUrl('requestdisplays/detailrequestdisplays/update', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeDeleteDetailRequestDisplayUrl($data)
   {
   	return Yii::app()->createUrl('requestdisplays/detailrequestdisplays/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeViewDetailDeliveryOrderUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('deliveryorders/detaildeliveryorders/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailDeliveryOrderUrl($data)
   {
   	return Yii::app()->createUrl('deliveryorders/detaildeliveryorders/update', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeDeleteDetailReturStockUrl($data)
   {
   	return Yii::app()->createUrl('returstocks/detailreturstocks/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeViewDetailReturStockUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('returstocks/detailreturstocks/view', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeUpdateDetailReturStock2Url($data)
   {
   	return Yii::app()->createUrl('returstocks/detailreturstocks2/update', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailReturStock2Url($data)
   {
   	return Yii::app()->createUrl('returstocks/detailreturstocks2/delete', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeViewDetailReturStock2Url($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('returstocks/detailreturstocks2/view', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeUpdateDetailReturStockUrl($data)
   {
   	return Yii::app()->createUrl('returstocks/detailreturstocks/update', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailDeliveryOrderUrl($data)
   {
   	return Yii::app()->createUrl('deliveryorders/detaildeliveryorders/delete', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeViewDetailOrderRetrievalUrl($data)
   {
   	//return print_r($data);
   	return Yii::app()->createUrl('orderretrievals/detailorderretrievals/view', array('iddetail'=>$data['iddetail']));
   }
    
   public static function decodeUpdateDetailOrderRetrievalUrl($data)
   {
   	return Yii::app()->createUrl('orderretrievals/detailorderretrievals/update', array('iddetail'=>$data['iddetail']));
   }
   
   public static function decodeDeleteDetailOrderRetrievalUrl($data)
   {
   	return Yii::app()->createUrl('orderretrievals/detailorderretrievals/delete', array('iddetail'=>$data['iddetail']));
   }
   
	public static function decodeUpdateDetailSalesReplaceUrl($data)
	{
		return Yii::app()->createUrl('salesreplace/detailsalesreplace/update', 
			array('iddetail'=>$data['iddetail']));
	}
	

	public static function decodeUpdateDetailSendRepairUrl($data)
	{
		return Yii::app()->createUrl('sendrepair/detailsendrepair/update', array('iddetail'=>$data['iddetail']));
	}
	
	public static function decodeDeleteDetailSendRepairUrl($data)
	{
		return Yii::app()->createUrl('sendrepair/detailsendrepair/delete', array('iddetail'=>$data['iddetail']));
	}
	 
	public static function decodeViewDetailSendRepairUrl($data)
	{
		//return print_r($data);
		return Yii::app()->createUrl('sendrepair/detailsendrepair/view', array('iddetail'=>$data['iddetail']));
	}
	
   public static function addFinancePayment($receipientname, $date, $duedate, $amount)
   {
		if (!Yii::app()->user->isGuest) {
   			Yii::app()->db->createCommand()
				->insert('financepayments', array(
				'id'=>idmaker::getCurrentID2(),
				'idatetime'=>$date,
				'receipient'=>$receipientname,
				'method'=>'0',
				'duedate'=>$date,
				'amount'=>$amount,
				'remark'=>'Belum dilaksanakan. Mohon diisi dengan informasi yang diperlukan.',
				'status'=>'0',
				'userlog'=>Yii::app()->user->id,
				'datetimelog'=>idmaker::getDateTime()
			));	
		}  else {
			throw new CHttpException(404,'You have no authorization for this operation.');
        };
   }
   
   public static function addItemToWarehouse($idwh, $iddetail, $iditem, $serialnum, $status = '1')
   {
   		if (!Yii::app()->user->isGuest) {
			Yii::app()->db->createCommand()
				->insert('wh'.$idwh, array(
					'iddetail'=>$iddetail,
					'iditem'=>$iditem,
					'serialnum'=>$serialnum,
					'avail'=>'1',
					'status'=>$status
				));
   		} else {
   			throw new CHttpException(405,'You have no authorization for this operation.');
   		};
   }
   
   public static function modifyIDItemInWarehouse($idwh, $serialnum, $iditemprevious, $iditemnext)
   {
   	if (!Yii::app()->user->isGuest) {
   		Yii::app()->db->createCommand()
   			->update('wh'.$idwh, array('iditem'=>$iditemnext), 'iditem = :p_iditem and serialnum = :p_serialnum',
   				array(':p_iditem'=>$iditemprevious, ':p_serialnum'=>$serialnum));
   	} else {
   		throw new CHttpException(405,'You have no authorization for this operation.');
   	};
   }
   
   public static function checkItemToWarehouse($idwh, $iditem, $serialnum, $avail = '1')
   {
   	if (!Yii::app()->user->isGuest) {
   		$data =  Yii::app()->db->createCommand()
   		->select("count(*) as total")->from('wh'.$idwh)
   		->where("iditem = :p_iditem and serialnum = :p_serialnum and avail like :p_avail", 
   			array( ':p_iditem'=>$iditem, ':p_serialnum'=>$serialnum, ':p_avail'=>$avail))
   		->queryScalar();
   		return $data;
   	} else {
   		throw new CHttpException(405,'You have no authorization for this operation.');
   	};
   }
   
   public static function setItemAvailinWarehouse($idwh, $serialnum, $avail = '1')
   {
	   	if (!Yii::app()->user->isGuest) {
	   		Yii::app()->db->createCommand()
	   			->update('wh'.$idwh, array('avail'=>$avail), 'serialnum = :p_serialnum', array(
	   				':p_serialnum'=>$serialnum
	   			));
	   	} else {
	   		throw new CHttpException(404,'You have no authorization for this operation.');
	   	};
   }
   
   public static function setItemStatusinWarehouse($idwh, $serialnum, $status)
   {
   	if (!Yii::app()->user->isGuest) {
   		Yii::app()->db->createCommand()
   		->update('wh'.$idwh, array('status'=>$status), 'serialnum = :p_serialnum', array(
   		':p_serialnum'=>$serialnum
   		));
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public static function checkItemStatusInWarehouse($idwh, $serialnum )
   {
	   	if (!Yii::app()->user->isGuest) {
	   		return Yii::app()->db->createCommand()
	   			->select("status")->from('wh'.$idwh)
	   			->where("serialnum = :p_serialnum",
	   				array( ':p_serialnum'=>$serialnum))
	   			->queryScalar();
	   	} else {
	   		throw new CHttpException(405,'You have no authorization for this operation.');
	   	};
   }
   
   
   public static function deleteItemFromWarehouse($idwh, $serialnum)
   {
   	if (!Yii::app()->user->isGuest) {
   		Yii::app()->db->createCommand()
   		->delete('wh'.$idwh, 'serialnum = :p_serialnum', array(
   			':p_serialnum'=>$serialnum
   		));
   	} else {
   		throw new CHttpException(404,'You have no authorization for this operation.');
   	};
   }
   
   public static function checkItemQty($iditem, $idwh)
   {
		$qty = Yii::app()->db->createCommand()->select('count(*)')
	   		->from('wh'.$idwh)->where('iditem = :p_iditem and avail = :p_avail',
	   			array(':p_iditem'=>$iditem, ':p_avail'=>'1'))
	   		->queryScalar();
	   
	   	return $qty;
   }
   
   public static function sendRepairOut($regnum, $serialnum) 
   {
   		$id = Yii::app()->db->createCommand()->select('id')->from('sendrepairs')
   			->where('regnum = :p_regnum', array(':p_regnum'=>$regnum))
   			->queryScalar();
   		
   		if ($id !== FALSE) {
   			Yii::app()->db->createCommand()->update('detailsendrepairs', 
   				array('exit'=>'1'), 'id = :p_id and serialnum = :p_serialnum', 
   					array(':p_id'=>$id, ':p_serialnum'=>$serialnum) );
   		}
   }
   
   public static function receiveRepairOut($regnum, $serialnum)
   {
   	$id = Yii::app()->db->createCommand()->select('id')->from('receiverepairs')
   	->where('regnum = :p_regnum', array(':p_regnum'=>$regnum))
   	->queryScalar();
   	 
   	if ($id !== FALSE) {
   		Yii::app()->db->createCommand()->update('detailreceiverepairs',
   		array('entry'=>'1'), 'id = :p_id and serialnum = :p_serialnum',
   		array(':p_id'=>$id, ':p_serialnum'=>$serialnum) );
   	}
   }
   
	public static function setInvStatus($invnum, $status)
	{
   		Yii::app()->db->createCommand()->update('salespos', array('status'=>$status ), 
   			'regnum = :p_regnum', array(':p_regnum'=>$invnum));
   	}
   	
   	public static function saveItemBatch($id, $iditem, $batchcode, $buyprice, $userlog, 
   			$datetimelog, $sellprice)
   	{
   		Yii::app()->db->createCommand()
   			->insert('itembatch', 
   				array('id'=>$id, 'iditem'=>$iditem, 'batchcode'=>$batchcode, 
   					 'buyprice'=>$buyprice, 'sellprice'=>$sellprice, 
   					'userlog'=>$userlog, 'datetimelog'=>$datetimelog ));
   	}
   	
   	public static function deleteItemBatch($id)
   	{
   		Yii::app()->db->createCommand()
   			->delete('itembatch', 'id = :p_id',
   				array(':p_id'=>$id));
   	}
   	
   	public static function addStock($id, $idatetime, $regnum, $transtype)
   	{
   		Yii::app()->db->createCommand()
   			->insert('stocks', array('id'=>$id, 'idatetime'=>$idatetime, 'regnum'=>$regnum,
   			'transtype'=>$transtype));	
   	}
   	
   	public static function deleteStock($id)
   	{
   		Yii::app()->db->createCommand()
   			->delete('stocks', 'id = :p_id', array(':p_id'=>$id));	
   	}
   	
   	public static function updateStock($id, $idatetime)
   	{
   		Yii::app()->db->createCommand()
   			->update('stocks', array('idatetime'=>$idatetime), array('id'=>$id));
   	}
   	
   	public static function addDetailStock($iddetail, $id, $iditem, $batchcode, $qty)
   	{
   		Yii::app()->db->createCommand()
   			->insert('detailstocks', array('iddetail'=>$iddetail, 'id'=>$id, 
   				'iditem'=>$iditem, 'batchcode'=>$batchcode, 'qty'=>$qty ));   		
   	}
   	
   	public static function deleteDetailStock($iddetail)
   	{
   		Yii::app()->db->createCommand()
   			->delete('detailstocks', 'iddetail = :p_iddetail', 
   				array(':p_iddetail'=>$iddetail));
   	}
   	
   	public static function deleteDetailStock2($id)
   	{
   		Yii::app()->db->createCommand()
   			->delete('detailstocks', 'id = :p_id',
   				array(':p_id'=>$id));
   	}
   	
   	public static function updateDetailStock($iddetail, $iditem, $batchcode, $qty)
   	{
   		Yii::app()->db->createCommand()
   			->update('detailstocks', array('iditem'=>$iditem, 'batchcode'=>$batchcode, 'qty'=>$qty), 
   				array('iddetail'=>$iddetail));
   	}
   	
   	public static function addLabelPrintJob($id, $batchcode)
   	{
   		Yii::app()->db->createCommand()
   			->insert('labelprintjobs', array('id'=>$id, 'num'=>$batchcode));
   	}
}

?>
