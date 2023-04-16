<?php
/**
 * Kontroler z akcjami zarządzania kontem.
 * 
 * @category controllers
 * @package account
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class AccountController extends Controller 
{
    public $defaultAction = 'login';
    
    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    public function allowedActions()
    {
        return 'login, logout, register, captcha, '
            . 'register_confirm, access_recovery, '
            . 'register_confirm_resend, type_password, '
            . 'captcha, login_back, remote_login, remote_login_ajax';
    }
    
    /**
     * Akcje dodatkowe.
     * @return array
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'transparent' => true,
            ),
        );
    }
    
    /**
     * Logowanie.
     */
    public function actionLogin() { 
        
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
            }
        }
        
        // display login page
        
        if (Yii::app()->request->isAjaxRequest) {
            $this->redirect(array('account/login'));   // force reload
        }
        if (Yii::app()->user->isGuest == false) {   // already logged in
            $this->redirect(Yii::app()->user->returnUrl);
        }
        
        // blowfish required by crypt()
        if (!defined('CRYPT_BLOWFISH') || !CRYPT_BLOWFISH) {
            Yii::app()->user->setFlash('warning', Yii::t('account', 'For technical reasons, login option has been temporarily disabled.'));
            $this->redirect(Yii::app()->homeUrl, true, 302);
        }
        
        $this->breadcrumbs = array(Yii::t('account', 'Login'));
        
        // articles
        $articleLeft = Article::model()->find(
                'alias="organize-your-business-online"'
        );
        $articleRight = Article::model()->find(
                'alias="professional-e-business-card"'
        );
        
        
        $this->render('login', compact('user', 'articleLeft', 'articleRight'));
    }
    
    /**
     * Wylogowywanie.
     */
    public function actionLogout() {
        
        if (Yii::app()->user->isGuest) {   // already logged out
            $this->redirect(Yii::app()->homeUrl);
        }
        
        Yii::app()->user->logout(false);    // save cookies
        // flash will not be visible becase session is destroyed
        $this->redirect(Yii::app()->homeUrl);
        //$this->render('logout');
    }
    
    
    /**
     * Rejestracja.
     * @param string $referrer Osoba polecająca.
     * @throws CHttpException
     */
    public function actionRegister($referrer='') 
    {
//        var_dump(Yii::app()->params->languages);
//        $langs = array_map('Html::languageMap',
//                    array_keys(Yii::app()->params->languages),
//                    Yii::app()->params->languages
//                    );
//        echo '<br>';
//        var_dump($langs);
//        echo '<br>Yii::app()->language: '.Yii::app()->language;
        if (Yii::app()->user->isGuest == false) {
            $this->redirect(array('site/index'));
        }
        
        $user = new User('register');
//        if(isset($_POST['g-recaptcha-response']) && ReCaptchaAPI::checkRecaptchaResponse($_POST['g-recaptcha-response']) && $model->save()) {
        if (isset($_POST['User']) && isset($_POST['g-recaptcha-response']) && ReCaptchaAPI::checkRecaptchaResponse($_POST['g-recaptcha-response'])) {
            $user->attributes = $_POST['User'];

            // validate user input and redirect to the previous page if valid
            if ($user->validate()) {
                //powinno być opakowane w transakcje razem z dodaniem pakietu
                $user->generatePassword($user->password);
                //wylaczony mechanizm weryfikacji poprzez link aktywacyjny
//                $user->active = true;
//                $user->verified = true;
				
                if (isset($_COOKIE['referrer'])) {
                    $user->referrer = $_COOKIE['referrer'];
                }
                
                $user->active = true;
                $user->verified = false;
                
                $user->generateVerificationCode();
                //$user->package_id = 1;
                $user->package_id = Package::$_packageDefault;
                $user->creators_package_id = Yii::app()->params['packages']['defaultPackageCreators'];
                
                $user->register_source = User::REGISTER_SOURCE_FIRMBOOK;
                $user->language = Yii::app()->language;
                
                if ($user->save(false) == false) {	// already validated
                    throw new CHttpException(500);
                }
                
                // assign default role
                Rights::assign('Authenticated', $user->id);
                                
                // confirm email
                $this->layout = 'mail';
                Yii::app()->mailer->systemMail(
                    $user->email,
                    Yii::t('register', 'Registration'),
                    $this->render('registerConfirmEmail', 
                            compact('user'), true)
                );                           
                 
                Yii::app()->user->setFlash('success', Yii::t('register', '<strong>You made it!</strong> You can log in now :).'));
                //wylaczony mechanizm weryfikacji poprzez link aktywacyjny
                //Yii::app()->user->setFlash('info', Yii::t('register', 'Please check your email to confirm registration.'));

                $this->redirect(array('login'));
            }
        }
        
        // articles
        $articleRight1 = Article::model()->find(
                'alias="organize-your-business-online"'
        );
        $articleRight2 = Article::model()->find(
                'alias="create-free-account"'
        );        
        
        $this->setLoadRecaptchaAPI(true);
        $this->render('register', compact('user', 'articleRight1', 'articleRight2'));
    }
    
    public function actionRegister_org($referrer='') {
        
        if (Yii::app()->user->isGuest == false) {
            $this->redirect(array('site/index'));
        }
        
        $user = new User('register');
        
        if (isset($_POST['User'])) {
            $user->attributes = $_POST['User'];
            // validate user input and redirect to the previous page if valid
            if ($user->validate()) {
                
                $user->generatePassword($user->password);
                $user->active = false;
                $user->verified = false;
                $user->generateVerificationCode();
                if ($user->save() == false) {
                    throw new CHttpException(500);
                }
                
                // assign default role
                Rights::assign('Authenticated', $user->id);
                
                // assign default package
                Package::addPurchase($user->id, Package::$_packageDefault);//, Package::$_packageDefaultPeriod
                //$packages = new Package();
				//$packages->addPurchase($nextId, PACKAGE_DEFAULT);
                
                // confirm email
                $this->layout = 'mail';
                Yii::app()->mailer->systemMail(
                    $user->email,
                    Yii::t('register', 'Registration'),
                    $this->render('registerConfirmEmail', 
                            compact('user'), true)
                );

                Yii::app()->user->setFlash('info', Yii::t('register', 'Please check your email to confirm registration.'));
                $this->redirect(array('login'));
            }
        }
        
        // articles
        $articleRight1 = Article::model()->find(
                'alias="organize-your-business-online"'
        );
        $articleRight2 = Article::model()->find(
                'alias="create-free-account"'
        );
        
        $this->render('register', compact('user', 'articleRight1', 'articleRight2'));
    }
    
    /**
     * Potwierdzenie rejestracji kodem z wiadomości email.
     * @param string $email Adres email, dla którego weryfikowane jest konto.
     * @param string $verification_code Kod weryfikujący.
     * @throws ChttpException
     */
    public function actionRegister_confirm($username, $verification_code) {
        
        if (Yii::app()->user->isGuest == false) {
            $this->redirect(array('site/index'));
        }
        
        $user = User::model()->find('username=:username', array(':username' => $username));
        if (!$user) {
            Yii::app()->user->setFlash('error', Yii::t('user', 'User does not exist.'));
        } elseif ($verification_code != $user->verification_code) {
            Yii::app()->user->setFlash('error', Yii::t('user', 'Activation code is incorrect.'));
        } else {
            $user->active = true;
            $user->verified = true;
            $user->verification_code = null;
            if ($user->save(false) == false) {
                throw new ChttpException(500);
            }
            
            Yii::app()->user->setFlash('success', Yii::t('register', '<strong>You made it!</strong> You can log in now :).'));
        }
        
        $this->redirect(array('login'));
    }
    
    /**
     * Ponowne wysłanie potwierdzenia na email (po akcji register).
     * @param string $email Adres email.
     */
    public function actionRegister_confirm_resend($username) {
        
        if (Yii::app()->user->isGuest == false) {
            $this->redirect(array('site/index'));
        }
        
        $user = User::model()->findByAttributes(array('username' => $username));
        if (!$user) {
            Yii::app()->user->setFlash('error', Yii::t('user', 'User does not exist.'));
            $this->redirect(array('login'));
        }
        
        if ($user->verified) {
            Yii::app()->user->setFlash('info', Yii::t('register', 'Your account is already verified.'));
            $this->redirect(array('login'));
        }
        
        // wrong code?
        /*if ($user->active) {
            Yii::app()->user->setFlash('info', Yii::t('register', 'Please check your email to confirm registration.')
                    .' '.Html::link(
                            Yii::t('register', 'To resend activation email, click here.'), 
                                    array('account/register_confirm_resend', 'email'=>$user->email)
                            )
                    );
            $this->redirect(array('login'));
        }*/
        
        if (empty($user->verification_code)) {
            $user->generateVerificationCode();
            if ($user->save(false) == false) {
                throw new ChttpException(500);
            }
        }

        // confirm email
        $this->layout = 'mail';
        Yii::app()->mailer->systemMail(
            $user->email,
            Yii::t('register', 'Registration'),
            $this->render('registerConfirmEmail', 
                    compact('user'), true)
        );

        Yii::app()->user->setFlash('info', Yii::t('register', 'Please check your email to confirm registration.'));
        $this->redirect(array('login'));
        
    }
    
    /**
     * Przywracanie dostępu poprzez wysłanie linku do zmiany hasłą na email.
     */
    public function actionAccess_recovery() {
        
        if (Yii::app()->user->isGuest == false) {
            $this->redirect(Yii::app()->homeUrl);
        }
        
        $model = new User('accessRecovery');
        
        // step 1
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];            
            if ($model->validate()) {
            	
                // send confirmation email
                $model = User::model()->findByEmailOrUsername($model->emailOrUsername);
                
                $recoveryCode = '';
                for($i=0; $i<16; $i++) {
                    $recoveryCode .= chr(rand(97, 122));
                }                
                $model->recovery_code = $recoveryCode;                
                $model->save(false);               
                
                // mail
                $this->layout = 'mail';
                Yii::app()->mailer->systemMail(
                    $model->email,
                    Yii::t('account', 'Access recovery'),
                    $this->render('accessRecoveryEmail', 
                            compact('model', 'recoveryCode'), true)
                );
                
                Yii::app()->user->setFlash('success', Yii::t('accessRecovery', 'Access has been granded. Please check your email.'));
                
                $this->redirect(array('login'));
            }
        }
        
        $this->render('accessRecovery', array(
            'model' => $model
        ));
    }    
    
    /**
     * Zmiana hasła (po akcji access_recovery).
     * @param string $email Adres, dla którego ma zostać zmienione hasło.
     * @param string $recovery_code Kod weryfikujący.
     */
    public function actionType_password($username, $recovery_code) {
        
        if (Yii::app()->user->isGuest == false) {
            $this->redirect(Yii::app()->homeUrl);
        }
        
        $model = User::model()->findByAttributes(
            compact('username','recovery_code'));
        if ($model == null) {
            Yii::app()->user->setFlash('error', Yii::t('accessRecovery', 'Wrong code or user does not exist!').' '.
                    CHtml::link('('.Yii::t('accessRecovery', 
                            'To try again, click here').')', 
                            array('user/access_recovery')));
            $this->redirect(array('login'));
        }
        $model->setScenario('typePassword');
        $model->password = '';
        $model->passwordRepeat = '';
        
        // step 2
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                
                $model->generatePassword($model->password);
                
                $model->recovery_code = null;
                $model->save();
                
                Yii::app()->user->setFlash('success', 'Hasło zostało zmienione!');
                
                $this->redirect(array('login'));
            }
        }
        
        $this->render('typePassword', array(
            'model' => $model
        ));
    }
    
    public function actionLogin_as($username)
    {
    	//zabezpiecznie przed logowaniem jako superadmin
    	$user = User::model()->findByEmailOrUsername($username);
    	if(User::checkRole($user->id) && !Yii::app()->user->isAdmin)
    		throw new CHttpException(500);
    	    	
        $user = Yii::app()->user;
        
        $realUsername = $user->getState('realUsername');
        if ($realUsername == null) {
            $realUsername = $user->name;
            $firstSwap = true;
        }
        
        $user->logout(false);    // save cookies
        
        $user = new User('login');
        $user->emailOrUsername = $username;
        $user->login(true); // force login without password
        
        if ($firstSwap) {
            Yii::app()->user->setState('realUsername', $realUsername);
        } else {
            if ($username == $realUsername) {
                Yii::app()->user->setState('realUsername', null);
            }
        }
        
        $this->redirect($this->createUrl('/user/desktop'));
    }
    
    public function actionLogin_back()
    {
        $user = Yii::app()->user;
        
        $realUsername = $user->getState('realUsername');
        if ($realUsername == null) {
            throw new CHttpException(500);
        }
        
        $user->logout(false);    // save cookies
        
        $user = new User('login');
        $user->emailOrUsername = $realUsername;
        $user->login(true); // force login without password
        Yii::app()->user->setState('realUsername', null);
        
        $this->redirect($this->createUrl('/user/desktop'));
    }
    
    public function actionRemote_login_ajax()
    {
    	//obslugujemy tylko zapytania jaxowe od niezalogowanego uzytkownika 
    	if (!Yii::app()->user->isGuest || !Yii::app()->request->isAjaxRequest) 
    		throw new CHttpException(500);
    	   	   	 
    	if(!isset($_POST['name']) || !$_POST['name'] || !isset($_POST['email']) || !$_POST['email'] 
    		|| !isset($_POST['id']) || !$_POST['id'] || !isset($_POST['source']) || !$_POST['source'])
    		throw new CHttpException(500);

    	$name = $_POST['name'];
    	$email = $_POST['email'];
    	$fbid = $_POST['id'];
    	$source = $_POST['source'];   	    	 
    		
    	$user = User::model()->findByAttributes(array('email'=>$email));
    		
    	if($user) {
    		//jesli konto nieaktywne, a tu mamy potwierdzenie, to traktujemy jak aktywacje konta poprzez email    		
    		if(!$user->active) {
    			$user->active = true;
    			$user->save();
    		}	
    		
	    	$user->emailOrUsername = $email;	    	
	    	$user->login(true); // force login without password	    	
	    	Yii::app()->user->setFlash('success', Yii::t('account', 'Correct login.'));
	    	
    	} else {
    		
    		//jeśli użytkownik nie istnieje, to musimy utworzyć dla niego konto
    		//inaczej bedzie zalogowany na fb, ale nie na naszej stronie
    		$user = new User('remote_register');
    		
    		$userData = array(
    			'username' => $fbid,
    			'email' => $email,
    			'password' => User::$defaultPassword,
    			//'passwordRepeat' => User::$defaultPassword,
    			'termsAccept' => true,
    			//'facebook_id' => $fbid    			
    		);    		
    		
    		
    		switch ($source) {
    			case User::REGISTER_SOURCE_FACEBOOK:
    				$userData['register_source'] = User::REGISTER_SOURCE_FACEBOOK;
    				$userData['facebook_id'] = $fbid;
    				break;
    			case User::REGISTER_SOURCE_GOOGLE:
    				$userData['register_source'] = User::REGISTER_SOURCE_GOOGLE;
    				$userData['google_id'] = $fbid;
    				break;
    			default:
    				throw new CHttpException(500);
    		}    		
    		
    		
    		if(isset($_POST['first_name']) && $_POST['first_name'])
    			$userData['forename'] = $_POST['first_name'];
    			
    		if(isset($_POST['last_name']) && $_POST['last_name'])
    			$userData['surname'] = $_POST['last_name'];
   		
    		$user->attributes = $userData;
    		$user->passwordRepeat = User::$defaultPassword;    		
    		
            // validate user input and redirect to the previous page if valid
            if ($user->validate()) {

                //powinno być opakowane w transakcje razem z dodaniem pakietu
                $user->generatePassword($user->password);
                $user->active = 1;
                $user->verified = 1;
                //$user->generateVerificationCode();
                
                $user->package_id = Package::$_packageDefault;
                if ($user->save() == false) {
                    throw new CHttpException(500);
                }
                
                // assign default role
                Rights::assign('Authenticated', $user->id);
                
                $user->emailOrUsername = $email;
                $user->login(true); // force login without password	    	
	    		Yii::app()->user->setFlash('success', Yii::t('account', 'Correct login.'));
            }    
    	}
    }
    
    public function actionRemote_login()
    {
    	//obslugujemy tylko zapytania od niezalogowanego uzytkownika
    	if (!Yii::app()->user->isGuest)
    		throw new CHttpException(500);
    
    	$serviceName = Yii::app()->request->getQuery('service');
    	
    	/*if(!isset($serviceName))
    		throw new CHttpException(500);*/
    
    	if (isset($serviceName)) {
    		/** @var $eauth EAuthServiceBase */
    		$eauth = Yii::app()->eauth->getIdentity($serviceName);
    		$eauth->setRedirectUrl(Yii::app()->homeUrl);    		
    		$eauth->cancelUrl = $this->createAbsoluteUrl('account/login');
    		//$eauth->cancelUrl = $this->createAbsoluteUrl('account/login', array('test'=>1));    		
    		
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
	    					
	    					$user->register_source = User::REGISTER_SOURCE_FIRMBOOK;
	    					
	    					if ($user->save() == false) {
	    						throw new CHttpException(500);
	    					}
	    
	    					// assign default role
	    					Rights::assign('Authenticated', $user->id);
	    
	    					$user->emailOrUsername = $user->email;
	    					$user->login(true); // force login without password
	    					Yii::app()->user->setFlash('success', Yii::t('account', 'Correct login.'));
	    				}
	    				
    				} // end of register
    			
    				$eauth->redirect($eauth->getRedirectUrl());
    				
    			} // end of if ($eauth->authenticate())
					    				
    			$eauth->redirect($eauth->getCancelUrl());
    			//$eauth->redirect(array('account/login'));    			
    			
    		}
    		catch (EAuthException $e) {
    			// save authentication error to session
    			Yii::app()->user->setFlash('error', 'EAuthException: '.$e->getMessage());
    			
    			// close popup window and redirect to cancelUrl
    			$eauth->redirect($eauth->getCancelUrl());
    		}		
    	}		
    }

    
    
}
