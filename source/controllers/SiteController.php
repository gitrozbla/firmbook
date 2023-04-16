<?php
/**
 * Kontroler z akcjami ogólnymi systemu.
 * 
 * @category controllers
 * @package site
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class SiteController extends Controller
{
    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    public function allowedActions()
    {
        return 'index, error, contact, cookies_alert_text, context, ui, css, login, recaptcha, signout_newsletter';
        //, send_email, send_email_to_many
    }

    /**
     * Akcja domyślna.
     * Wyświetla stronę główną.
     * Obsługuje zapisanie do newslettera z formularza na stronie głównej.
     * @throws CHttpException
     */
    public function actionIndex($context=null)
    {
     	
        /*$search = Yii::app()->session['search'];
        $search->resetForm();
        if ($context) {
            $search->context = $context;
        }
        Yii::app()->session['search'] = $search;    // necessary?*/
        // wyszukiwarka - reset
//         if ($context === null) {
//             // get old context
//             $search = Search::model()->getFromSession();
//             $context = $search->context;            
//         }
        $search = new Search();        
        if ($context === null) {
        	$search->type = 'company';
        	$search->action = 'sell';
        	$context = $search->getContext();
        }

        $search->category = null; // ta linia jest nowa wczesniej byla ta powyzej
        $search->setContext($context);
        $search->saveToSession();

        $request = Yii::app()->request;
        
        $correct_url = $search->createUrl(null, array(Search::getContextUrlType($search->type).'_context'=>Search::getContextUrlAction($search->action,$search->type)));
//         $correct_url = $correct_url.(Yii::app()->request->queryString ? '?'.Yii::app()->request->queryString : '');

        $requestUrl = $request->getUrl();
        $pathInfo = '/'.Yii::app()->request->pathInfo;

		if(
			Yii::app()->language != Yii::app()->params['defaultLanguage']
			&& strlen(Yii::app()->request->pathInfo)==2
			&& isset($requestUrl[3]) 
			&& $requestUrl[3] == '/'			
		)
        	$pathInfo .= '/';
        	
        if($pathInfo != $correct_url) {        	
        	$hostInfo = $request->getHostInfo();
//     		$correct_url = 'http://www.'.$hostInfo.$correct_url;
        	$correct_url = $hostInfo.$correct_url;
        	$canonical_url = $correct_url;
        	$correct_url = $correct_url.(Yii::app()->request->queryString ? '?'.Yii::app()->request->queryString : '');

        	Yii::app()->request->redirect(
        			$correct_url,
        			301);
        	exit();
        } else {    		
    		$hostInfo = $request->getHostInfo();    	
    		$correct_url = $hostInfo.$correct_url;
    		$canonical_url = $correct_url;
    		$correct_url = $correct_url.(Yii::app()->request->queryString ? '?'.Yii::app()->request->queryString : '');
        }       
        
        if($canonical_url != $correct_url)
        	$this->setCanonicalUrl($canonical_url);
        
        // newsletter
        $user = Yii::app()->user;
        if (!$user->isGuest) {
            $readerExist = Yii::app()->db->createCommand()
                    ->select('count(*)')
                    ->from('tbl_newsletter_reader')
                    ->where('email=:email', array(
                        ':email' => $user->getModel()->email))
                    ->queryScalar();
        }
        if (empty($readerExist)) {
            $newsletterReader = new NewsletterReader('create');
            if (isset($_POST['NewsletterReader'])) {
                $newsletterReader->attributes = $_POST['NewsletterReader'];
                // validate user input and try to login
                if ($newsletterReader->validate()) {
                    if ($newsletterReader->save()) {
                        Yii::app()->user->setFlash('success', Yii::t('newsletter', 
                                'Your email has been added to readers list. Thank You!'));
                        $newsletterReader->email = null;
                    } else {
                        throw new CHttpException(500);
                    }
                }
            }
        }
        
        $this->setPageTitle(Yii::app()->name.' - '.Yii::t('site','The first platform for business community').' - '.Yii::t('search', Search::getContextLongLabel($search->action,$search->type)));
//        echo '<br />Site.index2';
//     	exit;
        $this->render('index', compact('newsletterReader'));
    }

    /**
     * Wyświetla komunikat o błędzie.
     */
    public function actionError() {
        $error = Yii::app()->errorHandler->error;
        if ($error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        } else {
            $this->redirect(Yii::app()->homeUrl);
        }
    }

    /**
     * Wyświetla dane kontaktowe oraz formularz kontaktu.
     */
    public function actionContact() {
//        var_dump($_POST);
        $model = new Contact();
        $model->setScenario('compose');

//        if(isset($_POST['ajax']) and $_POST['ajax']==='contact-form')
        if(isset($_POST['ajax']) and $_POST['ajax']==='fromWithRecaptcha')    
        {
            echo ActiveForm::validate($model);
            Yii::app()->end();
        }
        
        if(isset($_POST['Contact']) && (Yii::app()->user->isGuest && isset($_POST['g-recaptcha-response']) && ReCaptchaAPI::checkRecaptchaResponse($_POST['g-recaptcha-response']) || !Yii::app()->user->isGuest))
        {
        
            $model->attributes = $_POST['Contact'];
            
//                if($responseData === null)
            if($model->save()) {

                // send mail
                $this->layout = 'mail';
                Yii::app()->mailer->ClearAttachments();
                Yii::app()->mailer->systemMail(
                        Yii::app()->params['admin']['email'],
                        Yii::t('contact', 'Contact form').' - '.$model->subject,
                        $this->render('contactMail', compact('model', 'titleUsername'), true),
                        array(
                            'email' => $model->email, 
                            'name' => $model->forename.' '.$model->surname
                        )
//                        ,
//                        true,
//                        'devrozbla@gmail.com'
                );

                Yii::app()->user->setFlash('success', Yii::t('contact', 'Thank You! We will contact You soon...'));
//                $this->redirect(Yii::app()->homeUrl);
                $this->redirect(array('site/contact'), true);
                
            }
        } 

        if(Yii::app()->user->isGuest)
            $this->setLoadRecaptchaAPI(true);
        $this->render('contact', compact('model'));
    }
    
            
    
    /**
     * Zwraca przetłumaczony komunikat o cookies (JSON).
     * Pobierane przez skrypt wyświetlający komunikat, 
     * jeśli nie został jeszcze zaakceptowany.
     */
    public function actionCookies_alert_text() 
    {
        $this->ajaxMode();
        
        echo CJSON::encode(
                Yii::t('site', '{cookies-alert}', array('{cookies-alert}' => 
                    'This website uses small files called cookies to help customise your '
                    . 'experience and evaluate how you use our website. <br /> '
                    . 'By continuing to use this site, you are agreeing to our privacy policy.'))
                );
    }
    
    /**
     * Zmiana kontekstu wyświetlania listy.
     * @param type $action Atrybut action obiektów Item (buy/sell).
     * @param string $type Typ obiektów Item (product/service/company).
     */
    /*public function actionContext($action=null, $type=null) {
        
        if ($action !== null and in_array($action, array('buy', 'sell'))) {
            Yii::app()->session['context.action'] = $action;
        }
        if ($type !== null and in_array($type, array('product', 'service', 'company'))) {
            Yii::app()->session['context.type'] = $type;
        }
        
        if (Yii::app()->session['lastController'] == 'categories') {
            $this->redirect(Yii::app()->user->returnUrl);
        } else {
            $this->redirect(Yii::app()->baseUrl);
        }
    }*/
    
    /**
     * Włączenie/wyłączenie trybu edycji strony.
     */
    public function actionEditor($return='')
    {
        Yii::app()->session['editor'] = ! Yii::app()->session['editor'];
        
        $this->redirect(urldecode($return));
    }
    
    /**
     * Zmiana języka UI.
     * @todo Zaimplementować. Obecnie zmiana języka 
     * znajduje się w UrlManager.
     * @see UrlManager
     * @param type $language Wybrany kod języka (pl, en...).
     */
    public function actionUI($language) 
    {   
        $this->redirect(Yii::app()->user->returnUrl);
    }
    
    
    public function actionCss()
    {
        $this->ajaxMode();
        
        header("Content-Type: text/css");
        $this->renderPartial('css');
    }
    
	/**
     * Wyświetla formularz i wysyła wiadomość email na adres uzytkownika lub firmy
     */
    public function actionSend_email() {
        $this->ajaxMode();

        try {

            if(!isset($_POST['recipientId']) && !isset($_POST['EmailForm']))
                throw new Exception;

            $email = new EmailForm('send');			

            if(isset($_POST['recipientId'])) {

                if(!isset($_POST['recipientType']))
                    throw new Exception;

                $emailData = array();				
                $recipientId = $_POST['recipientId'];	        	
                $recipientType = $_POST['recipientType'];

                if($recipientType == 'c') {	        		
                    $recipientData = Company::model()->with('item', 'user')->findByPk($recipientId);
                    if($recipientData->email) {
                        $emailData['recipientId'] = $recipientData->item->id;
                        $emailData['recipientType'] = 'c';
                        $emailData['recipientName'] = $recipientData->item->name;
                    } else {
                        $emailData['recipientId'] = $recipientData->user->id;
                        $emailData['recipientType'] = 'u';
                        $emailData['recipientName'] = $recipientData->user->username;
                        $emailData['recipientItemId'] = $recipientData->item->id;
                        $emailData['recipientItemType'] = 'c';
                        $emailData['recipientItemName'] = $recipientData->item->name;
                    }	        				

                } elseif($recipientType == 'p' || $recipientType == 's') {
                    if($recipientType == 'p') {	        			
                        $recipientData = Product::model()->with('item', 'company', 'user')->findByPk($recipientId);
                        $emailData['recipientItemType'] = 'p';
                    } else {
                        $recipientData = Service::model()->with('item', 'company', 'user')->findByPk($recipientId);
                        $emailData['recipientItemType'] = 's';
                    }		
                    $emailData['recipientItemId'] = $recipientData->item->id;		        		
                    $emailData['recipientItemName'] = $recipientData->item->name;		        	
                    if(isset($recipientData->company) && $recipientData->company->email) {
                        $emailData['recipientId'] = $recipientData->company->item_id;
                        $emailData['recipientType'] = 'c';
                        $emailData['recipientName'] = $recipientData->company->item->name;
                    } else {
                        $emailData['recipientId'] = $recipientData->user->id;
                        $emailData['recipientType'] = 'u';
                        $emailData['recipientName'] = $recipientData->user->username;
                    }	  
                } elseif($recipientType == 'u') {
                    $recipientData = User::model()->findByPk($recipientId);
                    $emailData['recipientId'] = $recipientData->id;
                    $emailData['recipientType'] = 'u';
                    $emailData['recipientName'] = $recipientData->username;
                } else			      		
                    throw new Exception;	

                $email->attributes = $emailData;	        	
            }	        				

                /*if(isset($_POST['ajax']) && $_POST['ajax']==='emailForm')
                {
                    echo CActiveForm::validate($email);
                    Yii::app()->end();
                }*/

            if(isset($_POST['EmailForm']))
            {	        	
                $email->attributes = $_POST['EmailForm'];
                if($email->validate())	
                {
                    switch($email->recipientType) {
                        case 'c':
//                            $recipientObj = Company::model()->findByPk($email->recipientId);
                            $recipientObj = Company::model()->with('item', 'user')->findByPk($email->recipientId);                            
                            $recipient = $recipientObj->item->user;
                            if($recipientObj->email)
                                $recipientEmail = $recipientObj->email;
                            else
                                $recipientEmail = $recipient->email;
                            break;
                        case 'u':
                            $recipientObj = User::model()->findByPk($email->recipientId);
                            $recipient = $recipientObj;
                            $recipientEmail = $recipientObj->email;
                            break;	
                    }

//                    if(!isset($recipientObj) || !$recipientObj || !$recipientObj->email)
                    if(!isset($recipientObj) || !$recipientObj)    
                        throw new Exception;
                    
                    $user = User::model()->findByPk(Yii::app()->user->id);
                    $this->layout = 'mail';
                    $userOrgLanguage = Yii::app()->user->language;
                    $appOrgLanguage = Yii::app()->language;
                    Yii::app()->user->language =  $recipient->language;
                    Yii::app()->language = $recipient->language;
//                Yii::app()->mailer->ClearAttachments();
                    Yii::app()->mailer->systemMail(
//                        $recipientObj->email,
                        $recipientEmail,
                        //$item->email? $item->email : $item->user->email,
                        $email->subject,
//                        $this->render('emailFormMail', compact('email', 'user', 'recipientObj', 'recipient'), true),
                        $this->render('emailFormMail', compact('email', 'user', 'recipient'), true),    
                        array(
                            'email' => $user->email, 
                            'name' => $user->forename.' '.$user->surname
                        )                        
                    );	
                    Yii::app()->user->language = $userOrgLanguage;
                    Yii::app()->language = $appOrgLanguage;
    //                $sender = $user = Yii::app()->user->getModel();
                    Yii::app()->mailer->systemMail(
                        $user->email,                        
                        Yii::t('contact', 'Copy of the message').' - '.$email->subject,
                        $this->render('emailFormCopyMail', compact('email', 'user', 'recipientObj'), true),
                        array(
                            'email' => $user->email, 
                            'name' => $user->forename.' '.$user->surname
                        )                        
                    );		 
                    echo 'close';
                    Yii::app()->end();
                }        	
            }        

            $this->renderPartial('emailForm', compact('email'));

        } catch(Exception $e) {
            echo 'close';
            Yii::app()->end();
        }
    }
    
    /**
     * Wyświetla formularz i wysyła wiadomość email na adresy uzytkowników lub firm
     */
    public function actionSend_email_to_many() {    	    	
    	$this->ajaxMode();    	
    	try {
    			
            if((!isset($_POST['recipientId']) || !isset($_POST['recipientType'])) && !isset($_POST['EmailForm']))
                throw new Exception;

            $email = new EmailForm('send_to_many');

            //wstępne wypełnienie formularza
            if(isset($_POST['recipientId'])) {
                $recipientsIds = $_POST['recipientId'];	 
                $recipientsType = $_POST['recipientType'];                        
                $recipientsEmail = array();
                $emailData = array();
                $emailData['recipientName'] = '';
                $recipientIds = array();

                for($i=0;$i<count($recipientsIds);++$i) 
                {
                    if(!isset($recipientsType[$i]))
                        continue;
                    $recipientId = $recipientsIds[$i];

                    if($recipientsType[$i]=='c' || $recipientsType[$i]=='p' || $recipientsType[$i]=='s')
                    {
                        $recipient = Item::model()->findByPk($recipientId);
                        if($recipient->cache_type == 'c') {
                            $company = Company::model()->findByPk($recipient->id);

                            if(isset($company->email) && $company->email) {	    					
                                $recipientEmail = $company->email;
                                $recipientName = $recipient->name;
                                $recipientData = array('type'=>'c', 'id'=>$company->item_id);
                            } else {	    					
                                $recipientEmail = $recipient->user->email; 
                                $recipientName = $recipient->user->username;
                                $recipientData = array(
                                    'type'=>'u',  
                                    'id'=>$recipient->user->id, 
                                    'recipientItemId'=>$recipient->id, 
                                    'recipientItemName'=>$recipient->name	    							
                                );
                            }

                            if(!in_array($recipientEmail, $recipientsEmail)) {
                                $recipientsEmail[] = $recipientEmail;
                                $emailData['recipientName'] .= ($i ? ', ' : '').$recipientName;	 
                                $recipientIds[] = $recipientData; 
                            }

                        } elseif($recipient->cache_type == 'p' || $recipient->cache_type == 's') {
                            if($recipient->cache_type == 'p') {
                                $product = Product::model()->with('company')->findByPk($recipient->id);
                            } else {
                                $product = Service::model()->with('company')->findByPk($recipient->id);
                            }

                            if($product && isset($product->company) && $product->company && isset($product->company->email) && $product->company->email) 
                            {
                                $recipientEmail = $product->company->email;
                                $recipientName = $product->company->item->name;
                                $recipientData = array(
                                    'type'=>'c', 
                                    'id'=>$product->company->item->id,
                                    'recipientItemId'=>$recipient->id,
                                    'recipientItemName'=>$recipient->name
                                );    					

                            } else {	    		

                                $recipientEmail = $recipient->user->email;
                                $recipientName = $recipient->user->username;
                                if($product && isset($product->company) && $product->company)
                                    $recipientData = array(
                                        'type'=>'u',
                                        'id'=>$recipient->user->id,
                                        'recipientItemId'=>$product->company->item->id,
                                        'recipientItemName'=>$product->company->item->name
                                    );
                                else	
                                    $recipientData = array('type'=>'u', 'id'=>$recipient->user->id);
                            }

                            if(!in_array($recipientEmail, $recipientsEmail)) {
                                $recipientsEmail[] = $recipientEmail;
                                $emailData['recipientName'] .= ($i ? ', ' : '').$recipientName;
                                $recipientIds[] = $recipientData;
                            }

                        } 
                    } elseif($recipientsType[$i]=='u')
                    {
                        $user = User::model()->findByPk($recipientId);

                        $recipientEmail = $user->email; 
                        $recipientName = $user->username;
                        $recipientData = array(
                            'type'=>'u',  
                            'id'=>$user->id                                                    	    							
                        );

                        if(!in_array($recipientEmail, $recipientsEmail)) {
                            $recipientsEmail[] = $recipientEmail;
                            $emailData['recipientName'] .= ($i ? ', ' : '').$recipientName;	 
                            $recipientIds[] = $recipientData; 
                        }
                    }    
                }
                $emailData['recipientId'] = serialize($recipientIds);    	
                $email->attributes = $emailData;
            }

            if(isset($_POST['EmailForm'])) {
                $email->attributes = $_POST['EmailForm'];
                if($email->validate())	{   				 
                    $recipients = unserialize($email->recipientId);    				
                    $user = User::model()->findByPk(Yii::app()->user->id);
                    $recipientsEmailsLinks = '';
                    foreach($recipients as $recipient) {
                        switch($recipient['type']) {
//                            case 'c':
//                                $recipientObj = Company::model()->findByPk($recipient['id']);
//                                break;
//                            case 'u':
//                                $recipientObj = User::model()->findByPk($recipient['id']);
//                                break;
                            case 'c':
//                            $recipientObj = Company::model()->findByPk($email->recipientId);
                                $recipientObj = Company::model()->with('item', 'user')->findByPk($recipient['id']);                            
                                $recipient = $recipientObj->item->user;
                                if($recipientObj->email)
                                    $recipientEmail = $recipientObj->email;
                                else
                                    $recipientEmail = $recipient->email;
                                break;
                            case 'u':
                                $recipientObj = User::model()->findByPk($recipient['id']);
                                $recipient = $recipientObj;
                                $recipientEmail = $recipientObj->email;
                                break;
                        }

//                        if(!isset($recipientObj) || !$recipientObj || !$recipientObj->email)
//                            continue;
                        if(!isset($recipientObj) || !$recipientObj)    
                            continue;                        
                        
                        $recipientsEmailsLinks .= strlen($recipientsEmailsLinks) ? ', ' : '';
//                        $recipientsEmailsLinks .= Html::link(
//                            $recipientObj->email, 
//                            'mailto:'.$recipientObj->email
//                        );
                        $recipientsEmailsLinks .= Html::link(
                            $recipientEmail, 
                            'mailto:'.$recipientEmail
                        );
                        if(isset($recipient['recipientItemId'])) {
                            $email->recipientItemId = $recipient['recipientItemId'];
                            $email->recipientItemName = $recipient['recipientItemName'];
                        }

                        $this->layout = 'mail';
                        Yii::app()->mailer->ClearAttachments();
                        Yii::app()->mailer->systemMail(
//                            $recipientObj->email,	    						
                            $recipientEmail,    
                            $email->subject,
                            $this->render('emailFormMail', compact('email', 'user', 'recipient'), true),
                            array(
                                'email' => $user->email,
                                'name' => $user->forename.' '.$user->surname
                            )
                        );    				
                    }  
                    if(isset($recipientsEmailsLinks))
                        Yii::app()->mailer->systemMail(
                            $user->email,                        
                            Yii::t('contact', 'Copy of the message').' - '.$email->subject,
                            $this->render('emailFormCopyMail', compact('email', 'user', 'recipientsEmailsLinks'), true),
                            array(
                                'email' => $user->email, 
                                'name' => $user->forename.' '.$user->surname
                            )                        
                        );
                    echo 'close';
                    Yii::app()->end();
                }
            }

            $this->renderPartial('emailFormToMany', compact('email'));
    		
    	} catch(Exception $e) {
            echo 'close';
            Yii::app()->end();
    	}
    		
    }
	
    public function actionSend_email_to_many_org() {    	
    	
    	$this->ajaxMode();
    	
    	//print_r($_POST['recipientId']);
    	
    	try {
    			
    		if(!isset($_POST['recipientId']) && !isset($_POST['EmailForm']))
    			throw new Exception;
			
    		$email = new EmailForm('send_to_many');
    			
    		//wstępne wypełnienie formularza
    		if(isset($_POST['recipientId'])) {
    						
    			    			
    			
	    		$recipientIds = $_POST['recipientId'];	    		
	    		for($i=0;$i<count($recipientIds);++$i) {
	    			$recipientIds[$i] = explode(',', $recipientIds[$i]);
	    			$recipientIds[$i] = $recipientIds[$i][1];
	    		}	    		
	    		
	    		$criteria = new CDbCriteria();
	    		$criteria->addInCondition("id", $recipientIds);
	    		$recipients = Item::model()->findAll($criteria);
	    		
	    		$recipientsEmail = array();
	    		$emailData = array();
	    		$emailData['recipientName'] = '';
	    		$recipientIds = array();
	    		
	    		for($i=0;$i<count($recipients);++$i) {
	    			
	    			$recipient = $recipients[$i];
	    			
	    			if($recipient->cache_type == 'c') {
	    				$company = Company::model()->findByPk($recipient->id);
	    				
	    				if(isset($company->email) && $company->email) {	    					
	    					$recipientEmail = $company->email;
	    					$recipientName = $recipient->name;
	    					$recipientData = array('type'=>'c', 'id'=>$company->item_id);
	    				} else {	    					
	    					$recipientEmail = $recipient->user->email; 
	    					$recipientName = $recipient->user->username;
	    					$recipientData = array(
	    						'type'=>'u',  
	    						'id'=>$recipient->user->id, 
	    						'recipientItemId'=>$recipient->id, 
	    						'recipientItemName'=>$recipient->name	    							
	    					);
	    				}
	    				
	    				if(!in_array($recipientEmail, $recipientsEmail)) {
	    					$recipientsEmail[] = $recipientEmail;
	    					$emailData['recipientName'] .= ($i ? ', ' : '').$recipientName;	 
	    					$recipientIds[] = $recipientData; 
	    				}
	    				
	    			} elseif($recipient->cache_type == 'p' || $recipient->cache_type == 's') {
	    				if($recipient->cache_type == 'p') {
	    					$product = Product::model()->with('company')->findByPk($recipient->id);
	    				} else {
	    					$product = Service::model()->with('company')->findByPk($recipient->id);
	    				}
	    					
	    				//echo 'Produkt: '.$recipient->company->email;	    				
	    				if($product && isset($product->company) && $product->company && isset($product->company->email) && $product->company->email) {
	    				//if(isset($recipient->company) && isset($recipient->company->email) && $recipient->company->email) {
	    					
	    					$recipientEmail = $product->company->email;
	    					$recipientName = $product->company->item->name;
	    					$recipientData = array(
	    							'type'=>'c', 
	    							'id'=>$product->company->item->id,
	    							'recipientItemId'=>$recipient->id,
	    							'recipientItemName'=>$recipient->name
	    					);    					
	    					
	    				} else {	    		
	    					
	    					$recipientEmail = $recipient->user->email;
	    					$recipientName = $recipient->user->username;
	    					//if(isset($recipient->company)) {
	    					if($product && isset($product->company) && $product->company)
	    						$recipientData = array(
	    							'type'=>'u',
	    							'id'=>$recipient->user->id,
	    							'recipientItemId'=>$product->company->item->id,
	    							'recipientItemName'=>$product->company->item->name
	    						);
	    					else	
	    						$recipientData = array('type'=>'u', 'id'=>$recipient->user->id);
	    				}
	    				
	    				if(!in_array($recipientEmail, $recipientsEmail)) {
	    					$recipientsEmail[] = $recipientEmail;
	    					$emailData['recipientName'] .= ($i ? ', ' : '').$recipientName;
	    					$recipientIds[] = $recipientData;
	    				}
	    				
	    			}    			
	    				
	    		}
	    		$emailData['recipientId'] = serialize($recipientIds);    		
	    		$email->attributes = $emailData;
    		}
    		
    		if(isset($_POST['EmailForm'])) {
    			    			    			
    			$email->attributes = $_POST['EmailForm'];
    		
    			if($email->validate())	{   				 
    				
    				$recipients = unserialize($email->recipientId);    				
    				
    				$user = User::model()->findByPk(Yii::app()->user->id);
    				
    				foreach($recipients as $recipient) {
	    				switch($recipient['type']) {
	    					case 'c':
	    						$recipientObj = Company::model()->findByPk($recipient['id']);
	    						break;
	    					case 'u':
	    						$recipientObj = User::model()->findByPk($recipient['id']);
	    						break;
	    				}
	    				
	    				if(!isset($recipientObj) || !$recipientObj || !$recipientObj->email)
	    					continue;
	    				
	    				if(isset($recipient['recipientItemId'])) {
		    				$email->recipientItemId = $recipient['recipientItemId'];
		    				$email->recipientItemName = $recipient['recipientItemName'];
	    				}
	    				
	    				$this->layout = 'mail';
	    				Yii::app()->mailer->ClearAttachments();
	    				Yii::app()->mailer->systemMail(
	    						$recipientObj->email,	    						
	    						$email->subject,
	    						$this->render('emailFormMail', compact('email', 'user'), true),
	    						array(
	    								'email' => $user->email,
	    								'name' => $user->forename.' '.$user->surname
	    						)
	    				);    				
    				}    				
    				
    				echo 'close';
    				Yii::app()->end();
    			}
    		}
    		
    		$this->renderPartial('emailFormToManyTest2', compact('email'));
    		
    	} catch(Exception $e) {
    		echo 'close';
    		Yii::app()->end();
    	}	
    		
    }
    
    /**
     * Logowanie poprzez EAuth.
     *
     */    
    public function actionLogin_old() {
    	
    	$serviceName = Yii::app()->request->getQuery('service');
    	print_r($serviceName);
    	echo '<br /><br />';
    	if (isset($serviceName)) {
    		/** @var $eauth EAuthServiceBase */
    		$eauth = Yii::app()->eauth->getIdentity($serviceName);
    		$eauth->redirectUrl = Yii::app()->user->returnUrl;
    		$eauth->cancelUrl = $this->createAbsoluteUrl('site/login');
    		echo '<br /><br />';
    		print_r($eauth);
    		echo '<br /><br />';
    		try {
    			echo 'actionLogin 0';
    			echo '<br /><br />';
    			//exit;
    			if ($eauth->authenticate()) {
    				echo 'actionLogin 1 b';
    				echo '<br /><br />';
    				print_r($eauth);
    				echo '<br /><br /> 1c';
    				//var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes());
    				$atrybuty = $eauth->getAttributes();
    				echo '<br /><br /> 1d';
    				print_r($atrybuty);
    				echo '<br /><br /> 1e';
    				$identity = new EAuthUserIdentity($eauth);
    				echo 'actionLogin 2';
    				echo '<br /><br />';
    				//exit;
    				// successful authentication
    				if ($identity->authenticate()) {
    					echo 'actionLogin 3';
    					echo '<br /><br />';
    					print_r($identity);
    					exit;
    					Yii::app()->user->login($identity);
    					//var_dump($identity->id, $identity->name, Yii::app()->user->id);exit;
    					exit;
    					// special redirect with closing popup window
    					$eauth->redirect();
    				}
    				else {
    					echo 'actionLogin 4';
    					exit;
    					// close popup window and redirect to cancelUrl
    					$eauth->cancel();
    				}
    			}
    
    			echo 'actionLogin 5';
    			exit;
    			// Something went wrong, redirect to login page
    			$this->redirect(array('site/login'));
    		}
    		catch (EAuthException $e) {
    			// save authentication error to session
    			Yii::app()->user->setFlash('error', 'EAuthException: '.$e->getMessage());
    			echo 'actionLogin 6';
    			exit;
    			// close popup window and redirect to cancelUrl
    			$eauth->redirect($eauth->getCancelUrl());
    		}
    	}
    
    	// default authorization code through login/password ..
    }
    
    /**
     * Zwraca przetłumaczony komunikat o cookies (JSON).
     * Pobierane przez skrypt wyświetlający komunikat, 
     * jeśli nie został jeszcze zaakceptowany.
     */
    public function actionRecaptcha($response) 
    {
        $this->ajaxMode();
        $ch = curl_init();
        // Set URL on which you want to post the Form and/or data
        curl_setopt($ch, CURLOPT_URL, Yii::app()->params['recaptcha']['url']);
        // Data+Files to be posted
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('response'=>$response, 'secret'=> Yii::app()->params['recaptcha']['secret']));
        // Pass TRUE or 1 if you want to wait for and catch the response against the request made
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // For Debug mode; shows up any error encountered during the operation
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        // Execute the request
        $response = curl_exec($ch);
        echo $response;
//        echo CJSON::encode(
//                $response
//                );
    }    
    
    public function actionSignout_newsletter($username, $verification_code) {
        
        if (Yii::app()->user->isGuest == false) {
            $this->redirect(array('site/index'));
        }
        
        $user = User::model()->find('username=:username', array(':username' => $username));
        if (!$user) {
            Yii::app()->user->setFlash('error', Yii::t('user', 'User does not exist.'));
        } elseif ($verification_code != $user->sign_out_verification_code) {
            Yii::app()->user->setFlash('error', Yii::t('user', 'Activation code is incorrect.'));
        } else {
            $user->send_emails = false;            
            $user->sign_out_verification_code = null;
            if ($user->save(false) == false) {
                throw new ChttpException(500);
            }
            
            Yii::app()->user->setFlash('success', Yii::t('common', 'You have unsubscribed from the newsletter.'));
        }
        
        $this->redirect(array('account/login'));
    }
}