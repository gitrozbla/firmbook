<?php
/**
 * Kontroler akcji zarządzania reklamami.
 * 
 * @category controllers
 * @package promotion
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class PromotionController extends Controller 
{
    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    public function allowedActions()
    {
        return 'index, offer, contact';
    }
    

    public function actionIndex() {
        $this->redirect($this->createUrl('/promotion/offer'));
    }

    /**
     * Wyświetla ofertę banerową.
     */
    public function actionOffer() {
        $this->render('offer');
    }
    
    public function actionBuy_box($id) 
    {
    	
    	$order = new AdOrder;
    	
    	if (isset($_POST['AdOrder'])) {
    		
    		//print_r($_POST['AdOrder']);
    		$order->attributes = $_POST['AdOrder'];
    		//sprawdzamy czy istnieje okres testowy dla pakietu
    		//$order = Package::model()->findByPk($purchase->package_id);
    		//echo '<br /><br />';
    		
    		if ($order->validate()) {
    			//print_r($order->attributes);
    			$order->save();
    		}
    		
    		Yii::app()->user->setFlash('success', Yii::t('ad', 'Thank You! We will contact You soon...'));
    	
    		$this->redirect($this->createUrl('history'));
    		
    	}
    		
    	// articles
    	$articleRight1 = Article::model()->find(
    			'alias="package-change"'
    			//'alias="organize-your-business-online"'
    	);
    	$articleRight2 = Article::model()->find(
    			'alias="package-test"'
    			//'alias="create-free-account"'
    	);
    	
    	$this->render('buyBox', compact('order', 'articleRight1', 'articleRight2'));
    }
    
	/**
     * Wyświetla formularz kontaktu w sprawie boxów reklamowych.
     */
    public function actionContact($id) {
    	
		$box = AdsBox::model()->findByPk($id);		    	
    	
        $model = new AdContact();
        $model->setScenario('compose');       

        if(isset($_POST['AdContact']))
        {
            $model->attributes = $_POST['AdContact'];
            if($model->save()) {
                // send mail
                $this->layout = 'mail';
                Yii::app()->mailer->ClearAttachments();
                Yii::app()->mailer->systemMail(
                        Yii::app()->params['admin']['email'],
                        Yii::t('contact', 'Contact form').' - '.$model->subject,
                        $this->render('/site/contactMail', compact('model'), true),
                        array(
                            'email' => $model->email, 
                            'name' => $model->forename.' '.$model->surname
                        )
                );

                Yii::app()->user->setFlash('success', Yii::t('contact', 'Thank You! We will contact You soon...'));
                
                $this->redirect($this->createUrl('promotion/offer'));
            }
        }
		
		if(isset($box->label))
        	$model->subject = $box->label.' - '.$box->name;
        
        $this->render('contact', compact('model', 'box'));
    }
    
    public function actionHistory()
    {
    	$this->render('history');
    }
    
    public function actionPay($id)
    {
    	$order = AdOrder::model()->findByPk($id);    	
    	 
    	$this->render('pay', compact('order'));
    }
    
    /*
     * powrót z płatności dotpay, nie oznacza dokonania płatności
     */
    public function actionPaymentConfirm()
    {
    	
    	try {
    		if (empty($_POST) || $_POST['status'] != 'OK')
    			throw new Exception();
        		
    		$purchaseId = Yii::app()->request->getParam('id');
        
    		if(!isset($purchaseId))
    			throw new Exception();
    
    		$order = AdOrder::model()->findByPk($purchaseId);
    		
    		if(!$order)
    			throw new Exception();
    		
    		$order->modified = new CDbExpression('NOW()');
    		$order->paid = 1;
    		
    		$order->save();
    		    		
    		Yii::app()->user->setFlash('success', Yii::t('ad', 'Thank you for your interest in our offer advertising. We will contact you after receiving confirmation of the payment from DOTPAY.pl.'));
    
    		$this->redirect($this->createUrl('/promotion/history'));
    		 
    	}
    	catch(Exception $e)
    	{
    		$this->redirect($this->createUrl('/promotion/history'));
    	}    	 
    	
    }
    
    /*
     * akcja odbierająca komunikaty z dotpay, czy płatność zostąła przyjęta czy odrzucona
     */
    public function actionTransactionConfirm()
    {
    	//echo 'actionTransactionConfirm';
    	if (empty($_POST))
    		exit;
    
    	$transaction = Yii::app()->db->beginTransaction();
    	try {
    		$shopId = $_POST['id'];
    		$purchaseId = $_POST['control'];
    		$operation_number = $_POST['operation_number'];
    		$operation_status = $_POST['operation_status'];
    		$operation_amount = $_POST['operation_amount'];
    		$operation_currency = $_POST['operation_currency'];
    		$operation_original_amount = $_POST['operation_original_amount'];
    		$operation_original_currency = $_POST['operation_original_currency'];
    		$operation_datetime = $_POST['operation_datetime'];
    		$operation_email = $_POST['email'];
    		$operation_signature = $_POST['signature'];
    		//walidacja PIN
    		$pin = Yii::app()->params['packages']['dotpayPin'];
    		$sign =
    		$pin.
    		$_POST['id'].
    		$_POST['operation_number'].
    		$_POST['operation_type'].
    		$_POST['operation_status'].
    		$_POST['operation_amount'].
    		$_POST['operation_currency'].
    		$_POST['operation_original_amount'].
    		$_POST['operation_original_currency'].
    		$_POST['operation_datetime'].
    		NULL.
    		//$_POST['operation_related_number'].
    		$_POST['control'].
    		$_POST['description'].
    		$_POST['email'].
    		$_POST['p_info'].
    		$_POST['p_email'].
    		$_POST['channel'];
    		$signature = hash('sha256', $sign);
    		//pin validation
    		if($operation_signature !== $signature)
    			throw new Exception();
    
    		//sprawdzenie czy istnieje zamówienie,
    		$order = AdOrder::model()->findByPk($purchaseId);
    		
    		if(!$order)
    			throw new Exception();
    		
    		/*
    		 * Parametry które nas interesują:
    		 * $operation_status, $operation_number, $operation_datetime
    		 */    		
    		if(array_key_exists($operation_status, Yii::app()->params['packages']['dotpayStatus'])
    				&& $_POST['operation_amount'] == $_POST['operation_original_amount']
    				&& $_POST['operation_currency'] == $_POST['operation_original_currency'])
    		{
    			$order->t_id = $operation_number;
    			$order->t_status = Yii::app()->params['packages']['dotpayStatus'][$operation_status];
    			$order->t_date = $operation_datetime;
    			$order->save();
    		}    		
    
    		//throw new Exception();
    		$transaction->commit();
    		echo 'OK';
    
    	}
    	catch(Exception $e)
    	{
    		$transaction->rollback();
    	}
    	exit;
    }
}
