<?php
	//echo 'id'.Yii::app()->params['packages']['dotpayId'];
	//aktualny pakiet, jeśli płatny to z tabeli historii zmian pakietów wraz z informacjami o zamówieniu, dla wpisu z tabeli pakietów
	$packageCurrent = User::model()->getCurrentPackage(Yii::app()->user->id, Yii::app()->user->package_id);
	//$packageCurrent = Package::model()->findByPk(Yii::app()->user->package_id);

	//ostatnie wpis w historii pakietów
    $packageLast = User::model()->getLastPackage(Yii::app()->user->id);
	
    if(!isset($packagePaid))
		$packagePaid = PackagePurchase::model()->exists('user_id=:user_id and status=:status', array(':user_id'=>Yii::app()->user->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID']));
    
		
	//formularz DOTPAY
    if($packageLast && ($packageLast['purchase']['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING']))
    {
		Yii::app()->request->enableCsrfValidation = false;
		$dotpayForm = CHtml::beginForm(Yii::app()->params['packages']['dotpayPaymentUrl'], 'POST', 
			array('style'=>'display:inline')	
		);
		//CHtml::clientChange('submit',)
		$dotpayForm .= CHtml::hiddenField('api_version', 'dev');
		$dotpayForm .= CHtml::hiddenField('id', Yii::app()->params['packages']['dotpayId']);
		$dotpayForm .= CHtml::hiddenField('amount', $packageLast['purchase']['price']);
		$dotpayForm .= CHtml::hiddenField('description', Yii::t('packages', 'Payment for package {package}.', array('{package}'=>$packageLast['name'])));
		$dotpayForm .= CHtml::hiddenField('URL', $this->createAbsoluteUrl('packages/paymentconfirm/id/'.$packageLast['purchase']['id']));
		$dotpayForm .= CHtml::hiddenField('type', 0);
		$dotpayForm .= CHtml::hiddenField('control', $packageLast['purchase']['id']);
		$dotpayForm .= CHtml::hiddenField('p_info', Yii::app()->name);
		//$dotpayForm .= CHtml::hiddenField('p_email', Yii::app()->email);
		
		$dotpayFormEnd = CHtml::endForm();
		Yii::app()->request->enableCsrfValidation = true;
		//echo $dotpayForm;
    }
	
   	//dane do TbDetailView   
    $tdvAttributes = array();   
    /*
     * obecny pakiet
     * jeśli nie istnieje oczekujący na uruchomienie opłacony pakiet ($packagePaid) i obency pakiet nie jest pakietem domyślnym/darmowym, to wyświetl przycisk zmiany pakietu 
     * jeśli nie istnieje oczekujący na uruchomienie opłacony pakiet ($packagePaid), to wyświetl przycisk zmiany pakietu
     *    
     */ 
    $tdvAttributes[] = array(
			'value'=> Package::badge($packageCurrent['name'], $packageCurrent['css_name'])
				.($packageCurrent['id'] != Package::$_packageDefault ? ($packageCurrent['purchase']['paid'] ? ' / '.$packageCurrent['purchase']['period'].' '.Yii::t('packages', 'months') : ' / '.Yii::t('packages', 'Test period')) : '' )
				//na podstronie szczegółów zamówienia nie wyświetlamy przycisków
				.(Yii::app()->controller->action->id != 'change' ? 
					//przycisk przedłużenia pakietu
					(!$packagePaid && $packageCurrent['id'] != Package::$_packageDefault ?  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" href="'.$this->createUrl('packages/change/package/'.$packageCurrent['id']).'">'.Yii::t('packages', 'Extension of the package').'</a>':'')
					//przycisk zmiany pakietu
					.(!$packagePaid ?  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" href="'.$this->createUrl('packages/change').'">'.Yii::t('packages', 'Package change').'</a>':'')
				:'')				,
			'label'=>Yii::t('packages', 'Current package:'),
			'type' => 'raw'	
		);	
	/*
	 * jeśli obency pakiet nie jest pakietem domyślnym/darmowym, to wyświetl datę uruchomienia i wygaśnięcia
	 */
	if($packageCurrent['id'] != Package::$_packageDefault)
	{
		$tdvAttributes[] = array(
			'value'=> $packageCurrent['id'] != Package::$_packageDefault ? Yii::app()->dateFormatter->format("yyyy-MM-dd", $packageCurrent['purchase']['date_expire']) : '', 
			'label'=>Yii::t('packages', 'Expires on').':'				
		);
		$tdvAttributes[] = array(
			'value'=> $packageCurrent['id'] != Package::$_packageDefault ? Yii::app()->dateFormatter->format("yyyy-MM-dd", $packageCurrent['purchase']['date_start']) : '', 
			'label'=>Yii::t('packages', 'Effective from:')				
		);
	}
	/*
	 * zaplanowana zmiana pakietu (oczekujący na opłacenie lub uruchomienie)
	 * jeśli oczekujący pakiet nie zostął opłacony, to wyświetl informację "nieopłacony" i przycisk do płatności DOTPAY 
	 */
	if($packageLast && 
		($packageLast['purchase']['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING']
		|| $packageLast['purchase']['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID']))
	{
		$tdvAttributes[] = array(
			'value'=> Package::badge($packageLast['name'], $packageLast['css_name']).' / '.$packageLast['purchase']['period'].' '.Yii::t('packages', 'months')
				.($packageLast['purchase']['status'] != Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'] ?
				'  <span style="color:red; margin-left:10px;">- '.Yii::t('packages', 'Unpaid').' </span>'
				.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
				.$dotpayForm
				.'<input type="submit" class="btn btn-primary" value="'.Yii::t('packages', 'Pay').'"/>'
				.$dotpayFormEnd
				//.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" id="yw1" href="'.$this->createUrl('packages/paymentconfirm/id/'.$packageLast['id']).'">'.Yii::t('packages', 'Pay').'</a>'
				:''), 
			'label'=> $packageCurrent['id']==$packageLast['id'] ? Yii::t('packages', 'Extension of the package:') : Yii::t('packages', 'Package change:'),
			'type' => 'raw'	
		);		
	}
    	
    ?>
<h1><?php echo Yii::t('packages', 'Your package:'); ?></h1>    
<?php $this->widget(
	    'bootstrap.widgets.TbDetailView',
	    array(
		    'data' => array(),	    	
	    	'htmlOptions' => array(
	    		//'style' => 'width: 800px',
	    	),
	    	'attributes' => $tdvAttributes	    	
	    )
    );
?>    
