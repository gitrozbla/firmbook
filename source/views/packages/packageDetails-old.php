<?php
	
	//$packageCurrent = Package::model()->findByPk(Yii::app()->user->package_id);
	$packageCurrent = User::model()->getCurrentPackage(Yii::app()->user->id, Yii::app()->user->package_id);    
    $packageLast = User::model()->getLastPackage(Yii::app()->user->id);

    //formularz DOTPAY
    if($packageLast && ($packageLast['purchase']['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING']))
    {
		Yii::app()->request->enableCsrfValidation = false;
		$dotpayForm = CHtml::beginForm('https://ssl.dotpay.pl/test_payment/', 'POST', 
			array('style'=>'display:inline')	
		);
		//CHtml::clientChange('submit',)
		$dotpayForm .= CHtml::hiddenField('id', Yii::app()->params['packages']['dotpayId']);
		$dotpayForm .= CHtml::hiddenField('amount', $packageLast['price']);
		$dotpayForm .= CHtml::hiddenField('description', 'Testowanie płatności');
		$dotpayForm .= CHtml::hiddenField('URL', $this->createAbsoluteUrl('packages/paymentconfirm/id/'.$packageLast['id']));
		$dotpayForm .= CHtml::hiddenField('type', 3);
		$dotpayForm .= CHtml::hiddenField('control', $packageLast['id']);
		$dotpayForm .= CHtml::hiddenField('p_info', Yii::app()->name);
		//$dotpayForm .= CHtml::hiddenField('p_email', Yii::app()->email);
		
		$dotpayFormEnd = CHtml::endForm();
		Yii::app()->request->enableCsrfValidation = true;
		//echo $dotpayForm;
    }
	
    	
    $tdvData = array();	
    $tdvAttributes = array();    	
	if($packageLast && $packageLast['purchase']['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING'])
	{
		
		/*$tdvAttributes[] = array(
			'value'=> Package::badge($packageLast['name'], $packageLast['css_name']).' / '.$packageLast['purchase']['period'].' miesiące  <span style="color:red; margin-left:10px;">- Nieopłacony </span>'
				.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" id="yw1" href="'.$this->createUrl('packages/paymentconfirm/id/'.$packageLast['id']).'">'.Yii::t('packages', 'Pay').'</a>', 
			'label'=> $packageCurrent['id']==$packageLast['id'] ? 'Przedłużenie pakietu:' : 'Wybrany pakiet:',
			'type' => 'raw'	
		);	*/	
		$tdvAttributes[] = array(
			'value'=> Package::badge($packageCurrent['name'], $packageCurrent['css_name'])
				.($packageCurrent['id'] != Package::$_packageDefault ? ' / '.$packageCurrent['purchase']['period'].' '.Yii::t('packages', 'months'):''), 
			'label'=>Yii::t('packages', 'Current package:'),
			'type' => 'raw'	
		);
	} else {
		//attr Aktualny pakiet:
		$tdvAttributes[] = array(
			'value'=> Package::badge($packageCurrent['name'], $packageCurrent['css_name'])
				.($packageCurrent['id'] != Package::$_packageDefault ? ' / '.$packageCurrent['purchase']['period'].' '.Yii::t('packages', 'months'):'')
				.(!$packagePending && $packageCurrent['id'] != Package::$_packageDefault ?  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" id="yw1" href="'.$this->createUrl('packages/beforbuy/package_id/'.$packageCurrent['id'].'/typ/przedluzenie').'">'.Yii::t('packages', 'Extension of the package:').'</a>':'') 
				.(!$packagePending && $packageCurrent['id'] != Package::$_packageDefault ?  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" id="yw1" href="'.$this->createUrl('packages/beforbuy').'">'.Yii::t('packages', 'Package change:').'</a>':''),
			'label'=>Yii::t('packages', 'Current package:'),
			'type' => 'raw'	
		);
	}
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
	if($packageLast && ($packageLast['purchase']['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING']
		|| $packageLast['purchase']['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID']))
	{
		$tdvAttributes[] = array(
			'value'=> Package::badge($packageLast['name'], $packageLast['css_name']).' / '.$packageLast['purchase']['period'].' '.Yii::t('packages', 'months')
				.($packageLast['purchase']['status'] != Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'] ?
				'  <span style="color:red; margin-left:10px;">- '.Yii::t('packages', 'Unpaid').' </span>'
				.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
				.$dotpayForm
				.'<input type="submit" class="btn btn-primary" id="yw1" value="'.Yii::t('packages', 'Pay').'"/>'
				.$dotpayFormEnd
				//.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" id="yw1" href="'.$this->createUrl('packages/paymentconfirm/id/'.$packageLast['id']).'">'.Yii::t('packages', 'Pay').'</a>'
				:''), 
			'label'=> $packageCurrent['id']==$packageLast['id'] ? Yii::t('packages', 'Extension of the package:') : Yii::t('packages', 'Package change:'),
			'type' => 'raw'	
		);		
		/*$tdvAttributes[] = array(
			'value'=> Package::badge($packageCurrent['name'], $packageCurrent['css_name'])
				.($packageCurrent['id'] != Package::$_packageDefault ? ' / '.$packageCurrent['purchase']['period'].' miesiące':''), 
			'label'=>'Aktualny pakiet:',
			'type' => 'raw'	
		);*/
	}
    	
    ?>
<h1><?php echo Yii::t('packages', 'Your package:'); ?></h1>    
<?php $this->widget(
	    'bootstrap.widgets.TbDetailView',
	    array(
		    'data' => array(
			    /*'id' => 1,
			    'firstName' => 'Mark',
			    'lastName' => 'Otto',
			    'language' => 'CSS'*/
	    	),
	    	//'htmlOptions' => array('width'=>'300'),
	    	'htmlOptions' => array(
	    		'style' => 'width: 700px',
	    	),
	    	'attributes' => $tdvAttributes
	    	/*'attributes' => array(
			    array('name' => 'firstName', 'label' => 'First name'),
			    array('name' => 'lastName', 'label' => 'Last name'),
			    array(
			    	//'name' => 'language', 
			    	'label' => 'Language',
			    	'type' => 'raw',
			    	'value' => 'Platynowy &nbsp;&nbsp;&nbsp;<a class="btn btn-primary" id="yw1" href="/_packages/paymentconfirm/package_id">Opłać</a>',*/
			    	//'value' => array($this,'renderButtons'), 
			    	/*CHtml::link('Jakis link',
                                 array('city/view','id'=>22))*/ 
			    	
			    	/*$this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',			                	 
			                    'label' => Yii::t('packages', 'Pay'),
			                    'type' => 'primary',
			                    
			                	'url' => $this->createUrl('packages/paymentconfirm/package_id/'),
			                	
			                )
			            )*/
			    /*),
		    ),*/
	    )
    );
?>    
