<?php
/**
 * Kontroler z akcjami ogólnymi systemu Creators.
 * 
 * @category controllers
 * @package site
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class SiteController extends Controller
{
   // public $layout = '/layouts/main';
    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    public function allowedActions()
    {
        return 'index, error, contact, under_construction, logout, remote_login';
    }

    /**
     * Akcja domyślna.
     * Wyświetla stronę główną.
     * Obsługuje zapisanie do newslettera z formularza na stronie głównej.
     * @throws CHttpException
     */
    public function actionIndex()
    {       	  	
        if (Yii::app()->user->isGuest == false) {        	
            $this->redirect(array('companies/list'));
            exit;
        }
        
        $user = new User('login');
        
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            //$user->attributes = $_POST['User']; //?
            echo ActiveForm::validate($user);
            Yii::app()->end();
        }       

        // collect user input data
        if (isset($_POST['User'])) {
            $user->attributes = $_POST['User'];
            // validate user input and try to login
            if ($user->validate() && $user->login()) {
            	$this->redirect(Yii::app()->user->returnUrl);
                exit;
            }
        }
        
        if (Yii::app()->request->isAjaxRequest) {
        	$this->redirect(array('index'));   // force reload
            exit;
        }
        if (Yii::app()->user->isGuest == false) {   // already logged in        	
            $this->redirect(Yii::app()->user->returnUrl);
            exit;
        }
        
        // blowfish required by crypt()
        if (!defined('CRYPT_BLOWFISH') || !CRYPT_BLOWFISH) {
            Yii::app()->user->setFlash('warning', Yii::t('account', 'For technical reasons, login option has been temporarily disabled.'));
            $this->redirect(Yii::app()->homeUrl, true, 302);
            exit;
        }
        
        $articleDescription = Article::model()->find(
                'alias="creators-description"'
        );
        
        if (Yii::app()->request->getParam('please_log_in')) {
            // to nie powinno być tutaj...
            Yii::app()->user->setFlash('warning', Yii::t('CreatorsModule.site', 'Please log in to your account to access this page.'));
        }
        
        $this->noContainer = true;
        
    	$this->render('index', compact('user', 'articleDescription'));
    }
    
    /**
     * Wylogowywanie.
     */
    public function actionLogout() 
    {
        if (Yii::app()->user->isGuest) {   // already logged out
            $this->redirect(Yii::app()->homeUrl);
        }
        
        Yii::app()->user->logout(false);    // save cookies
        // flash will not be visible becase session is destroyed
        $this->redirect(Yii::app()->homeUrl);
    }
    
    
    public function actionError() 
    {
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
    
    
    public function actionContact() 
    {
        $model = new Contact();
        $model->setScenario('compose');

        if(isset($_POST['ajax']) and $_POST['ajax']==='contact-form')
        {
            echo ActiveForm::validate($model);
            Yii::app()->end();
        }

        if(isset($_POST['Contact']) && (Yii::app()->user->isGuest && isset($_POST['g-recaptcha-response']) && ReCaptchaAPI::checkRecaptchaResponse($_POST['g-recaptcha-response']) || !Yii::app()->user->isGuest))
        {
            $model->attributes = $_POST['Contact'];
            if($model->save()) {
                // send mail
                $this->layout = 'mail';
                Yii::app()->mailer->ClearAttachments();
                Yii::app()->mailer->systemMail(
                        Yii::app()->params['admin']['email'],
                        Yii::t('contact', 'Contact form').' - '.$model->subject,
                        $this->render('//site/contactMail', compact('model'), true),
                        array(
                            'email' => $model->email, 
                            'name' => $model->forename.' '.$model->surname
                        )
                );

                Yii::app()->user->setFlash('success', Yii::t('contact', 'Thank You! We will contact You soon...'));
//                $this->redirect(Yii::app()->homeUrl);
                $this->redirect(array('site/contact'), true);
            }
        }
                
        if(Yii::app()->user->isGuest)
            $this->setLoadRecaptchaAPI(true);
        $this->render('//site/contact', compact('model'));
    }
	
	
	public function actionCss()
    {
        $this->ajaxMode();

        header("Content-Type: text/css");
        $this->renderPartial('//site/css');
    }
    
    
    public function actionUnder_construction() 
    {
        $this->render('under_construction');
    }
    
    
    public function actionAccept_terms_of_use() 
    {
        $user = Yii::app()->user->getModel();
        $user->scenario = 'creators_join';
        
        if ($user->creators_tou_accepted) {
            $this->redirect(Yii::app()->homeUrl, true, 301);
        }
        
        if(isset($_POST['ajax']))
        {
            echo ActiveForm::validate($user);
            Yii::app()->end();
        }

        if(isset($_POST['User']))
        {
            $user->attributes = $_POST['User'];
            if($user->validate() && $user->save()) {
                Yii::app()->user->setFlash('success', Yii::t('CreatorsModule.site', 'Welcome to Creators platform!'));
                $this->redirect(Yii::app()->homeUrl);
            }
        }
        
        $tou = Article::model()->find('alias="creators-terms-of-use" and visible=1');
        
        $user = Yii::app()->user->getModel();
        
        $this->render('accept_tou', compact('tou', 'user'));
    }
    
    public function actionRemote_login()
    {
    	//obslugujemy tylko zapytania od niezalogowanego uzytkownika
    	/*if (!Yii::app()->user->isGuest)
    		throw new CHttpException(500);*/
    
    	$serviceName = Yii::app()->request->getQuery('service');
    	 
    	/*if(!isset($serviceName))
    	 throw new CHttpException(500);*/
    
    	if (isset($serviceName)) {
    		/** @var $eauth EAuthServiceBase */
    		$eauth = Yii::app()->eauth->getIdentity($serviceName);
    		$eauth->setRedirectUrl(Yii::app()->homeUrl);
    		$eauth->cancelUrl = $this->createAbsoluteUrl('site/index', array('test'=>1));
    
    		try {
    			if ($eauth->authenticate()) {
    
    				$attributes = $eauth->getAttributes();
    				 
    				$user = User::model()->findByAttributes(array('email'=>$attributes['email']));
    				 
    				if($user) {
    					//jesli konto nieaktywne, a tu mamy potwierdzenie, to traktujemy jak aktywacje konta poprzez email
    					if(!$user->active) {
    						$user->active = true;
    						$user->save();
    					}
    					 
    					$user->emailOrUsername = $attributes['email'];
    					$user->login(true); // force login without password
    					Yii::app()->user->setFlash('success', Yii::t('account', 'Correct login.'));
    					 
    				} else {
    					 
    					//jeśli użytkownik nie istnieje, to musimy utworzyć dla niego konto
    					//inaczej bedzie zalogowany na fb, ale nie na naszej stronie
    					$user = new User('remote_register');
    					 
    					$user->attributes = $attributes;
    					$user->passwordRepeat = User::$defaultPassword;
    					 
    					if ($user->validate()) {
    
    						$user->generatePassword($user->password);
    						$user->active = 1;
    						$user->verified = 1;
    						 
    						$user->package_id = Package::$_packageDefault;
    						$user->creators_package_id = Yii::app()->params['packages']['defaultPackageCreators'];
    						
    						$user->register_source = User::REGISTER_SOURCE_CREATORS;
    						
    						if ($user->save() == false) {
    							throw new CHttpException(500);
    						}
    						 
    						// assign default role
    						Rights::assign('Authenticated', $user->id);
    						 
    						$user->emailOrUsername = $user->email;
    						
    						
    						
    						/*    						
    						switch ($serviceName) {
    							case User::REMOTE_SERVICE_FACEBOOK:
    								$user->remote_source = User::REMOTE_SOURCE_FACEBOOK;
    								//$user->facebook_id = $fbid;
    								
    								break;
    							case User::REMOTE_SERVICE_GOOGLE:
    								$user->remote_source = User::REMOTE_SOURCE_GOOGLE;
    								//$user->google_id = $fbid;
    								
    								break;
    							default:
    								throw new CHttpException(500);
    						}*/
    						
    						$user->login(true); // force login without password
    						Yii::app()->user->setFlash('success', Yii::t('account', 'Correct login.'));
    					}
    					 
    				} // end of register
    				 
    				$eauth->redirect($eauth->getRedirectUrl());
    
    			} // end of if ($eauth->authenticate())
    			 
    			$eauth->redirect($eauth->getCancelUrl());    			
    			 
    		}
    		catch (EAuthException $e) {
    			// save authentication error to session
    			Yii::app()->user->setFlash('error', 'EAuthException: '.$e->getMessage());
    			 
    			// close popup window and redirect to cancelUrl
    			$eauth->redirect($eauth->getCancelUrl());
    		}
    	}
    }
    
    /**
     * Włączenie/wyłączenie trybu edycji strony.
     */
    public function actionEditor($return='')
    {
    	Yii::app()->session['editor'] = ! Yii::app()->session['editor'];
    
    	$this->redirect(urldecode($return));
    }
    
}