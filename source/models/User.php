<?php
/**
 * Model użytkownika.
 * 
 * @category models
 * @package user
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class User extends ActiveRecord
{
    /**
     * Źródło rejestracji
     */

    const REGISTER_SOURCE_FIRMBOOK = 1;
    const REGISTER_SOURCE_CREATORS = 2;

    const REMOTE_SOURCE_GOOGLE = 1;
    const REMOTE_SOURCE_FACEBOOK = 2;

    const REMOTE_SERVICE_GOOGLE = 'google_oauth';
    const REMOTE_SERVICE_FACEBOOK = 'facebook';

    /*const REGISTER_SOURCE_LOCAL = 1;
    const REGISTER_SOURCE_FACEBOOK = 2;
    const REGISTER_SOURCE_GOOGLE = 3;*/
	
    /**
     * Email lub nazwa użytkownika.
     * Użyte w formularzu logowania
     */
    public $emailOrUsername;
    /**
     * Powtórzone hasło (potrzebne przy rejestracji i zmianie hasła).
     * @var string 
     */
    public $passwordRepeat;
    /**
     * Nowe hasło (potrzebne przy zmianie hasła).
     * @var string
     */
    public $new_password;   // required by PhpBBUserBehavior
    /**
     * Kod Captcha.
     * @var string
     */
    public $verifyCode;
    /**
     * Akceptacja regulaminu.
     * @var boolean
     */
    public $termsAccept;
    
    
    protected $lastPackage;
    protected $packageExpire;
    
    protected $lastPackageCreators;
    protected $packageExpireCreators;
    
    protected static $activeOptions = array(
        0 => 'no',
        1 => 'yes',
    );
    
    /*
     * domyslnie ustawiane haslo podczas zakladania konta dla logowania spolecznosciowego bez konta w serwisie
     */
    public static $defaultPassword = 'X.Lp1Twm.8z';
    
    /**
     * Tworzy instancję.
     * @param string $className Klasa instancji.
     * @return object Utworzona instancja zadanej klasy.
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Nazwa tabeli.
     * @return string
     */
    public function tableName()
    {
        return '{{user}}';
    }
    
    /**
     * Nazwa kolumny głównej.
     * @return string
     */
    public function primaryKey()
    {
        return 'id';    // because db has set clustered index
    }

    
    /**
     * Zachowania użytkownika.
     * Dodana integracja z PHPBB.
     * @return array
     */
    public function behaviors()
    {
        return array(
            'PhpBBUserBehavior'=>array(
                'class'=>'phpbb.components.PhpBBUserBehavior',
                'usernameAttribute'=>'username',
                'newPasswordAttribute'=>'new_password',
                'emailAttribute'=>'email',
                'avatarAttribute'=>'profile_picture',
                'avatarPath'=>'files/',
                'forumDbConnection'=>'forumDb',
                'syncAttributes'=>array(
                    'site'=>'user_website',
                    'icq'=>'user_icq',
                    'from'=>'user_from',
                    'occ'=>'user_occ',
                    'interests'=>'user_interests',
                )
            ),
        );
    }
 
    /**
     * Relacje bazodanowe.
     * @return array
     */
    public function relations()
    {    
        Yii::import('phpbb.models.*');
        return array(
            'phpBbUser'=>array(self::HAS_ONE, 'PhpBBUser', array('username'=>'username')),            
            'items' => array(self::HAS_MANY, 'Item', 'user_id'),            
            'package' => array(self::BELONGS_TO, 'Package', 'package_id', 'together'=>true),
            'packageCreators' => array(self::BELONGS_TO, 'Package', 'creators_package_id', 'together'=>true),
            'purchases' => array(self::HAS_MANY, 'PackagePurchase', 'user_id', 'together'=>true),
            'purchased' => array(self::HAS_MANY, 'Package', array('package_id'=>'id'), 'through'=>'purchases', 'together'=>true),
            //uzyteczne, gdy pobieramy aktualny i ostatnio wybrany pakiet dla konta
            'purchase' => array(self::HAS_ONE, 'PackagePurchase', 'user_id', 'together'=>true),
            //'purchase' => array(self::HAS_ONE, 'PackagePurchase', 'user_id', 'together'=>true),
            'elists' => array(self::HAS_MANY, 'Elist', 'user_id'),
            //avatar	
            'thumbnail' => array(self::BELONGS_TO, 'UserFile', 'thumbnail_file_id', 'together'=>true),
        );
    }
    
    /**
     * Lista reguł walidacji.
     * @return array
     */
    public function rules()
    {
        return array(
            // login
            array('password', 'required', 'on'=>'login'),
            array('remember_me', 'boolean', 'on'=>'login'),
            // login, accessRecovery
            array('emailOrUsername', 'required', 'on'=>'login, accessRecovery'),
            array('emailOrUsername', 'existInAttributes', 'caseSensitive'=>false,
                'inAttributes'=>'email, username', 'on'=>'login, accessRecovery',
                'message'=>Yii::t('user', 'User does not exist.')),
        	//array('recovery_code', 'safe'),
        		
            // register            
            array('username, password, passwordRepeat', 'required', 'on'=>'register, remote_register, update'),
            array('username, email', 'unique', 'caseSensitive'=>false, 'on'=>'register, remote_register, update'),
            array('username, email', 'length', 'min'=>'4', 'max'=>64, 'on'=>'register, remote_register, update'),
            array('username', 'match', 'pattern' => '/^[a-zA-Z0-9.\/$\s]+$/', 'on'=>'register, remote_register, update', 
                'message'=>Yii::t('user', 'Only standard characters and numbers were allowed.')),
            array('termsAccept', 'compare', 'compareValue'=>1, 'on'=>'register, remote_register', 
                'message'=>Yii::t('user', 'Terms must be accepted.')),
//            array('verifyCode', 'captcha', 'on'=>'register'),
            
            array('termsAccept', 'boolean', 'on'=>'register, remote_register'),
            // update
            array('language', 'required', 'on'=>'update'),
            array('forename, surname', 'required', 'on'=>'update'),
            array('forename', 'length', 'min'=>'2', 'max'=>32, 'encoding'=>false, 'on'=>'update'),
            array('surname', 'length', 'min'=>'2', 'max'=>64, 'encoding'=>false, 'on'=>'update'),
            array('active, verified, show_email, send_emails', 'boolean', 'on'=>'update'),
            array('package_id, creators_package_id', 'integrationValidate', 'on'=>'update'),
            array('package_expire, creators_package_expire', 'date', 'format'=>'yyyy-M-d', 'on'=>'update'),
            array('expire_days_msg', 'safe', 'on'=>'update'),
            array('referrer', 'safe', 'on'=>'adminSearch'),
            array('skype', 'length', 'max'=>50, 'on'=>'update'),
            //array('expire_days_msg', 'numerical', 'on'=>'update'),
        		
            // register, update
            array('email', 'email', 'on'=>'register, update, remote_register'),
            array('email', 'required', 'on'=>'register, update, remote_register'),
            array('email', 'unique', 'on'=>'register, update, remote_register'),
            
            // accessRecovery
            /*array('email', 'required', 'on'=>'accessRecovery'),
            array('email', 'exist', 'caseSensitive'=>false, 'on'=>'login, accessRecovery',
                'message'=>Yii::t('user', 'User does not exist.') 
                ),*/
            // register, typePassword
            /*array('password', 'match', 'pattern' => '/^[a-zA-Z0-9.\/$\s]+$/', 'on'=>'register, typePassword', 
                'message'=>Yii::t('user', 'Only standard characteds and numbers were allowed.')),*/
            array('passwordRepeat', 'compare', 'compareAttribute'=>'password', 'on'=>'register, typePassword',  
                'message'=>Yii::t('user', 'Passwords does not match.')),
            // register, typePassword, update
            array('password, passwordRepeat', 'required', 'on'=>'register, typePassword, update'),
            array('password', 'length', 'min'=>8, 'max'=>64, 'encoding'=>false, 'on'=>'register, typePassword, update, remote_register'),
            
            
            // search
            array('id, username, email, active, package_id, package_expire, creators_package_id, creators_package_expire, register_source', 'safe', 'on'=>'adminSearch'),
            
            // creators join
            array('creators_tou_accepted', 'required', 'on'=>'creators_join'),
            
        	//remote_register	
            array('register_source, remote_source, facebook_id, google_id, forename, surname', 'safe', 'on'=>'remote_register'),
            
        	array('thumbnail_file_id', 'safe', 'on'=>'create, update'),
            array('verification_code', 'safe', 'on'=>'update'),
        	/*array('thumbnail_file_id', 'userFileValidator', 'types'=>'jpg, jpeg, gif, png', 'maxSize'=>500000,
        			'safe'=>true, 'allowEmpty'=>true, 'on'=>'user'),*/
//            array('send_emails', 'boolean', 'on'=>'create, update'),
           
            // for testing purposes
            /*array('email', 'email'),
            array('password', 'length', 'min'=>9, 'max'=>32, 
                'on'=>'register'),
            array('website', 'length', 'min'=>4, 'max'=>128),
            array('type', 'numerical', 'min'=>1, 'max'=>2),
            array('active, ban', 'boolean'),
            array('profile_picture', 'application.components.validators.fileValidator', 
                'safe' => true,
                'mimeTypes' => 'image',
                'allowEmpty'=>true, 
                'maxSize'=>'1000000', 
                'types'=>'jpg, jpeg, gif, png'),
            array('iban', 'iban', 'country' => $this->country),
            array('born', 'dbDate'),
            array('registered', 'dbDatetime'),*/
        );
    }
    
    public function integrationValidate($attribute)
    {
        switch($attribute) {
            case 'package_id':
                if ($this->package_id != 0 && !Package::model()->exists(
                        'id=:id and !creators', 
                        array(':id'=>$this->package_id))
                        ) {
                    $this->addError($attribute, Yii::t('user', 'Package doesnot exists.'));
                }
                break;
            case 'creators_package_id':
              	if ($this->creators_package_id != 0 && !Package::model()->exists(
            		   	'id=:id and creators',
                		array(':id'=>$this->creators_package_id))
                		) {
                	$this->addError($attribute, Yii::t('user', 'Package doesnot exists.'));
                }
                break;
        }
            
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
            'emailOrUsername' => Yii::t('user', 'Email or username'),
            'username' => Yii::t('user', 'Username'),
            'password' => Yii::t('user', 'Password'),
            'passwordRepeat' => Yii::t('user', 'Repeat password'),
            'termsAccept' => Yii::t('user', 'Accept terms of use'),
            'verified' => Yii::t('user', 'Verified'),
            'verifyCode' => Yii::t('user', 'Verify code'),
            'email' => Yii::t('user', 'Email'),
            'remember_me' => Yii::t('user', 'Remember me'),
            'forename' => Yii::t('user', 'Forename'),
            'surname' => Yii::t('user', 'Surname'),
            'active' => Yii::t('user', 'Active'),
            'show_email' => Yii::t('user', 'Show email'),
            'package_id' => Yii::t('user', 'Package'),
            'package_expire' => Yii::t('user', 'Package expire'),       	
            'creators_tou_accepted' => Yii::t('user', 'I agree Creators Terms of Use'),
            'creators_package_id' => Yii::t('user', 'Creators package'),
            'creators_package_expire' => Yii::t('user', 'Creators package expire'),
            'register_source' => 'Strona',
            'referrer' => Yii::t('user', 'Referrer'),
            'registered' => Yii::t('user', 'Registered'),
            'send_emails' =>Yii::t('user', 'Send emails'),
            'language' =>Yii::t('user', 'Language'),
        );
    }
    
    /**
     * Logowanie.
     * @return boolean Czy powiodło się.
     */
    public function login($force=false) {
        
        $user = User::model()->findByEmailOrUsername($this->emailOrUsername);
        //print_r($user);	
        if ($user->verified != true) {
            $this->addError('emailOrUsername', Yii::t('user', 'Account is not verified.'));
            Yii::app()->user->setFlash('info', Yii::t('register', 'Please check your email to confirm registration.')
                    .' '.Html::link(
                            Yii::t('register', 'To resend activation email, click here.'), 
                                    array('account/register_confirm_resend', 'username'=>$user->username)
                            )
                    );
            return false;
        } else if ($user->active != true) {
            $this->addError('emailOrUsername', Yii::t('user', 'Account is not activated.'));
            return false;
        }
        
        $identity = new UserIdentity($user->username, $this->password);
        if (!$identity->authenticate($force)) {
            switch ($identity->errorCode) {
                case UserIdentity::ERROR_USERNAME_INVALID:
                    $this->addError('emailOrUsername', Yii::t('user', 'User does not exist.'));
                    break;
                case UserIdentity::ERROR_PASSWORD_INVALID:
                    $this->addError('password', Yii::t('user', 'Password is invalid.'));
                    break;
                default:
                    $this->addError('emailOrUsername', Yii::t('user', 'Please make sure you filled all fields propertly.'));
                    break;
            }
            
            return false;
        }
        
        if ($identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->remember_me ? Yii::app()->params['rememberTime'] : 0;
            Yii::app()->user->login($identity, $duration);
            
            //dodanie id uzytkownika do tabeli sessji
            Yii::app()->session->setUserId(Yii::app()->user->id);
            
            return true;
        }
        else
            return false;
    }
    
    /**
     * Znajduje model na podstawie adresu emial lub nazwy użytkownika.
     * @param string $emailOrUsername
     * $return User Znaleziony użytkownik.
     */
    public function findByEmailOrUsername($emailOrUsername) {
        return User::model()->find(
                'email=:emailOrUsername or username=:emailOrUsername', 
                array('emailOrUsername' => $emailOrUsername));
    }
    
    public function generateVerificationCode()
    {
        $verificationCode = '';
        for($i=0; $i<16; $i++) {
            $verificationCode .= chr(rand(97, 122));
        }
        return $this->verification_code = $verificationCode;
    }
    
    public function generateSignOutVerificationCode()
    {
        $verificationCode = '';
        for($i=0; $i<16; $i++) {
            $verificationCode .= chr(rand(97, 122));
        }
        return $this->sign_out_verification_code = $verificationCode;
    }
    
    public function afterFind()
    {
    	
        // for auto hashing password on update
        $this->passwordRepeat = $this->password;
                      
        /*
         * Po zmianie pakietu poprzez crona, chcemy, aby wymusic zmiane pakietu w danych sesji uzytkownika
         * o ile użytkownik jest zalogowany
         * 
         */        
        
        // for package cache invalidate in item        
        $this->lastPackage = $this->package_id;
        $this->packageExpire = $this->package_expire;
        
        $this->lastPackageCreators = $this->creators_package_id;
        $this->packageExpireCreators = $this->creators_package_expire;
        
        /*if(!Yii::app()->user->isGuest && $this->package_id != Yii::app()->user->package_id)
        	Yii::app()->user->setState('package_id', $this->package_id);*/
        	//$this->setState('package_id', $user->package_id);
    }
    
    public function save($runValidation=true, $attributes=null)
    {
        // force save package_expire on package_id change
        // @see User::beforeSave
         
        if (is_array($attributes)) {
        	 
            if($this->lastPackage != $this->package_id)
            	$attributes []= 'package_expire';
            
            if($this->packageExpire != $this->package_expire)
        		$attributes []= 'expire_days_msg';
            
            if($this->lastPackageCreators != $this->creators_package_id)
            	$attributes []= 'creators_package_expire';
            
            if($this->packageExpireCreators != $this->creators_package_expire)
            	$attributes []= 'creators_expire_days_msg';
//        	$attributes []= 'verification_code';	
        }        
        
        return parent::save($runValidation, $attributes);
    }
    
    public function beforeSave() 
    {
        // new password
        if ($this->passwordRepeat != $this->password) {
            // hash it
            $this->generatePassword($this->password);
        }
        
        if (!$this->package_id) {
        	$this->package_id = Package::$_packageDefault;
			$forceUpdateCache = true;
        }
                
        
        if ($this->lastPackage != $this->package_id || isset($forceUpdateCache)) {
        	if ($this->package_id == Package::$_packageDefault) {        		
        		// no package - no expiration date
        		$this->package_expire = null;
        	}
        
        	// update items package cache
        	Yii::app()->db->createCommand()
        	->update('tbl_item', array(
        			'cache_package_id'=>$this->package_id
        	), 'user_id=:user_id', array('user_id'=>$this->id));
        	        	
        	if(is_a(Yii::app(), 'WebApplication')) {
                Yii::app()->user->setState('package_id', $this->package_id);
			}
        	
        	//wyłączenie obecnego pakietu
        	$currentPurchase = PackagePurchase::model()->find(
        		array(
        			'condition'=>'user_id=:user_id and status=:status and !creators', 
        			'params'=>array(':user_id'=>$this->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT']),       					
        		)
        	);
        	
        	if($currentPurchase) {
        		$currentPurchase->status = Package::$_packagePurchaseStatus['PURCHASE_STATUS_EXPIRED'];
        		$currentPurchase->update(false, array('status'));
        	}
        	
        }
        
        //resetowanie progu powiadomień o wygaśnięciu pakietu
        if ($this->packageExpire != $this->package_expire) {
        	$this->expire_days_msg = 0;        	
        }
        
        
        if (!$this->creators_package_id) {
        	$this->creators_package_id = Yii::app()->params['packages']['defaultPackageCreators'];
        }
        
        if ($this->lastPackageCreators != $this->creators_package_id) {
        	if ($this->creators_package_id == Yii::app()->params['packages']['defaultPackageCreators']) {
        		// no package - no expiration date
        		$this->creators_package_expire = null;
        	}
        	
        	if(is_a(Yii::app(), 'WebApplication')) {
                Yii::app()->user->setState('creators_package_id', $this->creators_package_id);
			}
        	 
        	//wyłączenie obecnego pakietu
        	$currentPurchase = PackagePurchase::model()->find(
        		array(
        			'condition'=>'user_id=:user_id and status=:status and creators',
        			'params'=>array(':user_id'=>$this->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT']),
        		)
        	);
        	
        	if($currentPurchase) {
        		$currentPurchase->status = Package::$_packagePurchaseStatus['PURCHASE_STATUS_EXPIRED'];
        		$currentPurchase->update(false, array('status'));
        	}
        	 
        }
        
        //resetowanie progu powiadomień o wygaśnięciu pakietu
        if ($this->packageExpireCreators != $this->creators_package_expire) {
        	$this->creators_expire_days_msg = 0;
        }
        
        // wymuś zalogowanie po zmianie pakietu
        if ($this->lastPackage != $this->package_id || $this->lastPackageCreators != $this->creators_package_id)        	
        	Yii::app()->db->createCommand()
        		->delete('tbl_yii_session','user_id=:user_id',array(':user_id'=>$this->id));
        
        if ($this->isNewRecord) {
        	
	        if (empty($this->registered)) {
	        	$this->registered = new CDbExpression('NOW()');
	        }
	        
        }
        // package changed
        /*
         * wyłączone przez IC, działało w pierwotnym scenariuszu, teraz aktualizacja daty wygaśniecia
         *  ma miejsce podczas uruchamiania wykupionego pakietu
         *  pozostaje jedynie update items package cache, ale przeniesiony w inne miejsce (Package::enablePuchasedPackage) 
         *  podobnie z funkcją save
         */
        /*if ($this->lastPackage != $this->package_id) {
            if ($this->package_id != null and $this->package_id != 0) {
                // renew expiration date
                $this->package_expire = date('Y-m-d', strtotime('+1 years'));
            } else {
                // no package - no expiration date
                $this->package_expire = null;
            }
            
            // update items package cache
            Yii::app()->db->createCommand()
                    ->update('tbl_item', array(
                        'cache_package_id'=>$this->package_id
                    ), 'user_id=:user_id', array('user_id'=>$this->id));
        }*/
//        if (!$this->isNewRecord) {
//        	echo 'update 2 '.$this->verified;
//	        if ($this->verified && !empty($this->verification_code)) {
//                echo 'update 2';
////	        	$this->verification_code = null;
//                $this->verification_code = '';
////                $this->ch
////                $this->setAttribute('verification_code', null);
////                $this->attributes[] = 'verification_code';
////                $this->_attributes[] = 'verification_code';  
////                $this->attributes['verification_code'] = null;
////                $this->set
//                //yswtgukbamprmolf
//	        }
//	        
//        }
        return true;
    }
    
    public function beforeSave_old()
    {
    	// new password
    	if ($this->passwordRepeat != $this->password) {
    		// hash it
    		$this->generatePassword($this->password);
    	}
    
    	if (!$this->package_id) {
    		$this->package_id = Package::$_packageDefault;
    	}
    
    
    	if ($this->lastPackage != $this->package_id) {
    		if ($this->package_id == Package::$_packageDefault) {
    			// no package - no expiration date
    			$this->package_expire = null;
    		}
    
    		// update items package cache
    		Yii::app()->db->createCommand()
    		->update('tbl_item', array(
    				'cache_package_id'=>$this->package_id
    		), 'user_id=:user_id', array('user_id'=>$this->id));
    		 
    		//Yii::app()->user->setState('package_id', Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT']);
    		Yii::app()->user->setState('package_id', $this->package_id);
    		 
    		//wyłączenie obecnego pakietu
    		$currentPurchase = PackagePurchase::model()->find(
    				array(
    						'condition'=>'user_id=:user_id and status=:status and !creators',
    						'params'=>array(':user_id'=>$this->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT']),
    				)
    		);
    		if($currentPurchase) {
    			$currentPurchase->status = Package::$_packagePurchaseStatus['PURCHASE_STATUS_EXPIRED'];
    			$currentPurchase->update(false, array('status'));
    		}
    		 
    	}
    
    	//resetowanie progu powiadomień o wygaśnięciu pakietu
    	if ($this->packageExpire != $this->package_expire) {
    		$this->expire_days_msg = 0;
    	}
    
    
    	if (!$this->creators_package_id) {
    		$this->creators_package_id = Yii::app()->params['packages']['defaultPackageCreators'];
    	}
    
    	if ($this->lastPackageCreators != $this->creators_package_id) {
    		if ($this->creators_package_id == Yii::app()->params['packages']['defaultPackageCreators']) {
    			// no package - no expiration date
    			$this->creators_package_expire = null;
    		}
    		 
    		//Yii::app()->user->setState('package_id', Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT']);
    		Yii::app()->user->setState('creators_package_id', $this->creators_package_id);
    
    		//wyłączenie obecnego pakietu
    		$currentPurchase = PackagePurchase::model()->find(
    				array(
    						'condition'=>'user_id=:user_id and status=:status and creators',
    						'params'=>array(':user_id'=>$this->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT']),
    				)
    		);
    		if($currentPurchase) {
    			$currentPurchase->status = Package::$_packagePurchaseStatus['PURCHASE_STATUS_EXPIRED'];
    			$currentPurchase->update(false, array('status'));
    		}
    
    	}
    
    	//resetowanie progu powiadomień o wygaśnięciu pakietu
    	if ($this->packageExpireCreators != $this->creators_package_expire) {
    		$this->creators_expire_days_msg = 0;
    	}
    
    	if ($this->isNewRecord) {
    		 
    		if (empty($this->registered)) {
    			$this->registered = new CDbExpression('NOW()');
    		}
    		 
    	}
    	// package changed
    	/*
    	* wyłączone przez IC, działało w pierwotnym scenariuszu, teraz aktualizacja daty wygaśniecia
    	*  ma miejsce podczas uruchamiania wykupionego pakietu
    	*  pozostaje jedynie update items package cache, ale przeniesiony w inne miejsce (Package::enablePuchasedPackage)
    	*  podobnie z funkcją save
    	*/
    	/*if ($this->lastPackage != $this->package_id) {
    			if ($this->package_id != null and $this->package_id != 0) {
    			// renew expiration date
    			$this->package_expire = date('Y-m-d', strtotime('+1 years'));
    			} else {
    			// no package - no expiration date
    			$this->package_expire = null;
    			}
    
    			// update items package cache
    			Yii::app()->db->createCommand()
    			->update('tbl_item', array(
    					'cache_package_id'=>$this->package_id
    			), 'user_id=:user_id', array('user_id'=>$this->id));
    			}*/
    
    	return true;
    	}
    
    /*public function afterSave()
    {
    	if ($this->lastPackage != $this->package_id) {
    		
    	}
    	
    	return parent::afterSave();
    }*/
    
    public function beforeDelete()
    {
        // remove all attached items
        $items = Item::model()->findAllByAttributes(array(
            'user_id'=>$this->id,
        ));
        foreach ($items as $item) {
            $item->delete();
        }
        
        return true;
    }
    
    /**
     * Weryfikacja hasła.
     * @param string $password Podane hasło.
     * @return boolean Czy hasło poprawne.
     */
    public function validatePassword($password) {
        // salt is included in $this->password
        return crypt($password, $this->password) === $this->password;
    }
    
    /**
     * Generowanie nowego hasła (bez zapisania do bazy).
     * @param string $newPassword Nowe hasło
     * @return string Nowe hasło w formie hashu.
     */
    public function generatePassword($newPassword) {
        
        $salt = substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(), mt_rand()))), 0, 22);
        
        // $ blowfish $ cost $ salt (21 chars) + one required overflowing character
        $this->password = $this->passwordRepeat =  crypt($newPassword, '$2a$10$'.$salt);
        
        return $this->password;
    }
    
    /**
     * Rejestracja (z zapisaniem do bazy).
     * @return boolean Czy powiodło się.
     */
    public function register() {
        if ($this->validate()) {
            //$this->salt = $this->generateSalt();
            //$this->password = $this->hashPassword($this->password);
            
            $this->active = false;

            $this->verification_code = $this->generateConfirmCode();

            $this->save();
            
            return true;
        } else {
            return false;
        }
        
    }
    
    
    public function adminSearch() 
    {
        $criteria = new CDbCriteria;
        
        $criteria->together  =  true;        
        $criteria->with = array('package');
        
        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('package_id', $this->package_id, true);
        $criteria->compare('package_expire', $this->package_expire, true);
        $criteria->compare('creators_package_id', $this->creators_package_id, true);
        $criteria->compare('creators_package_expire', $this->creators_package_expire, true);
		$criteria->compare('referrer', $this->referrer, true);
        
        $activeOptionsFlipped = array_flip(self::$activeOptions);
        $activeText = Yii::t('inv.user', $this->active);
        $active = isset($activeOptionsFlipped[$activeText]) 
                ? $activeOptionsFlipped[$activeText]
                : null;
        $criteria->compare('active', $active);
        $criteria->compare('register_source', $this->register_source);
        
        return new CActiveDataProvider('User', array(
            'sort'=>array(
            	'defaultOrder'=>'t.id DESC',
                //'defaultOrder'=>'active DESC, username ASC',
            ),
            'criteria'=>$criteria,
        	'pagination' => array(
        		'pageSize' => 50,        	
        	),
        ));
    }
    
    
    public function getPackageItemClass($creators=false)
    {
        if ($creators) $package = $this->packageCreators;
        else $package = $this->package;

        return $this->creators_package_id
            ? 'package-item-'.$package->css_name
            : '';
    }

    public function badge($showFree=false, $creators=false)
    {
        if ($creators) $package = $this->packageCreators;
        else $package = $this->package;

        if (!$showFree && !$this->creators_package_id) return '';
//        if (!$showFree && $package->name=='FREE') return '';
        if (!$showFree && (!$creators && $package->name=='STARTER' || $creators && $package->name=='FREE')) return '';

        return '<span class="package-badge-'.$package->css_name.'">'
            //.$this->package->name
            .Yii::t('packages', $package->name)
            .'</span>';
    }

    public function badge2($creators=false)
    {
        if ($creators) $package = $this->packageCreators;
        else $package = $this->package;

        return $this->package_id
            ? '<span class="package-badge2-'.$package->css_name.'">'
                            //.$this->package->name
                            .Yii::t('packages', $package->name)
                    .'</span>'
            : '';
    }

    public function badgeCreators()
    {
    	return $this->creators_package_id
            ? '<span style="padding: 5px 10px; background-color: '.$this->packageCreators->color.'">'
                            .Yii::t('packages', $this->packageCreators->name)
                    .'</span>'
            : '';
    }
    
    public function packageName($creators=false)
    {
        if ($creators) $package = $this->packageCreators;
        else $package = $this->package;
        
        return Yii::t('packages', $package->name);
    }        
    
    /*
	 * Aktualny pakiet ze szczegółami zamówienia
	 */
    public static function getCurrentPackage($user_id, $package_id)
	{		
		$packageCurrent = Package::model()->with('purchase')->find(
			array(
				'condition'=>'purchase.user_id=:user_id and t.id=:package_id and purchase.status=:status',
				'params'=>array(':user_id'=>$user_id, ':package_id'=>$package_id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT']),
			)		
		);	
		if(!$packageCurrent){
			$packageCurrent = Package::model()->findByPk($package_id);
		}
		return $packageCurrent;
	}
	
	/*
	 * Ostatnio wybrany pakiet ze szczegółami zamówienia, może być aktualny lub oczekujący na opłacenie
	 */
	public static function getLastPackage($user_id, $creators=false)
	{
		$packageLast = Package::model()->with('purchase')->find(
			array(
				'condition'=>'purchase.user_id=:user_id'.($creators ? ' and t.creators' : ' and !t.creators'),
				'params'=>array(':user_id'=>Yii::app()->user->id),
				'order'=>'purchase.date_added desc',			
			)		
		);
		/*$packageLast = PackagePurchase::model()->with('package')->find(
			array(
				'condition'=>'t.user_id=:user_id',
				'params'=>array(':user_id'=>Yii::app()->user->id),
				'order'=>'t.date_added desc',			
			)		
		);*/

		return $packageLast;
	}	
	
	public static function getPurchasedPackages($user_id, $creators=false)
	{
		$purchaseList = PackagePurchase::model()->with('package')->findAll(
			array(
				'condition'=>'t.user_id=:user_id'.($creators ? ' and package.creators' : ' and !package.creators'),
				'params'=>array(':user_id'=>Yii::app()->user->id),
				'order'=>'t.date_added desc',			
			)		
		);
		
		return $purchaseList;
	}
	
	public static function paymentsDataProvider($userId, $creators=false)
	{
		return new CActiveDataProvider('PackagePurchase', array(
			'criteria'=>array(				
				'select' => 't.*, p.name as name',
				'join' => 'INNER JOIN tbl_package p on p.id=package_id',
				'condition'=>'user_id='.$userId.($creators ? ' and p.creators' : ' and !p.creators'),
			    'order'=>'t.date_added DESC',
				//'with'=>array('package'),				
			),
			/*'sort'=>array(
				'attributes'=>array(
					'name'=>array(
						'asc'=>'name',
						'desc'=>'name DESC'
					),					
					'*',	
				)				
			),*/
			'pagination' => false,            
        ));
        
    }
	public static function paymentsDataProvider_org($userId)
	{
		return new CActiveDataProvider('PackagePurchase', array(
			'criteria'=>array(				
				//'select' => 't.*, p.name as name',
				'join' => 'INNER JOIN tbl_package p on p.id=package_id',
				'condition'=>'user_id='.$userId,
			    'order'=>'t.date_added DESC',
				//'with'=>array('package'),				
			),
			/*'sort'=>array(
				'attributes'=>array(
					'name'=>array(
						'asc'=>'name',
						'desc'=>'name DESC'
					),					
					'*',	
				)				
			),*/
			'pagination' => false,            
        ));
        
    }
    
    public function itemsDataProvider($type)
    {
    	$criteria = new CDbCriteria;
    	$criteria->condition = "user_id=:user_id and cache_type=:cache_type";    	
    
    	if (Yii::app()->user->isGuest || $this->id != Yii::app()->user->id) {    		
    		$criteria->condition .= ' and active=1';
    	}	    	
    	
    	$criteria->params = array(
    							':user_id'=>$this->id,
    							':cache_type'=>$type
    						);
    
    	$sort = new CSort;
    	$sort->defaultOrder = 'date DESC';
    
    	return new CActiveDataProvider('Item', array(
    			'criteria'=>$criteria,
    			'sort'=>$sort,
    			'pagination' => array(
    					'pageSize' => 20 ,
    			),
    	));
    }
    
    public static function itemsDataProvider_old($userId, $type)
    {
    	switch($type) {
    		case 'p':
    			$with = 'product';
    			break;
    		case 's':
    			$with = 'service';
    			break;
    		case 'c':
    			$with = 'company';
    			break;
    	}
    	$additionalCriteria = array();
    	if($limit)
    		$additionalCriteria['limit'] = $limit;
    
    	return new CActiveDataProvider('Item', array(
    			'criteria' => array_merge(array(
    					'alias' => 'i',
    					//'select' => array('id', 'name', 'alias',
    					//    'thumbnail_file_id', 'cache_package_id'),
    					'with' => array(
    							$with => array(
    									'alias' => 'p',
    							),
    							'thumbnail',
    							//'item.package'
    					),
    					'condition' =>
	    					'p.company_id=:company_id '
	    					. ($active ? 'AND i.active=1' : '')
	    					. " AND i.cache_type='".$type."'",
	    					'params' => array(
	    							':company_id' => $companyId,
    						),
    					//'limit' => $limit,
    					'order' => 'i.date DESC',
    				),
    				$additionalCriteria
    			),
    			'pagination' => false,
    		)
    	);
    
    	
    }
    
    public static function checkRole($id, $checkRole='Superadmin')
    {
    	$roles = Rights::getAssignedRoles($id);    	
    	 
    	foreach($roles as $role)    		
    		if($role->name == $checkRole)    			
    			$roleAssigned = true;    	
    	 
    	if(isset($roleAssigned))    		
    		return true;    		
    	
    	return false;    		
    		
    }
	
	public function refererLink() {
		if ($this->referrer) {
			$pos = strpos($this->referrer, '://');
			$referrerHtml = CHtml::encode($this->referrer);
			if ($pos === false) return $referrerHtml;
			switch(substr($this->referrer, 0, $pos)) {
				case 'http':
				return '<a href="'.$referrerHtml.'" target="_blank" class="referrer-link">'.$referrerHtml.'</a>';
				break;

				default:
				return $referrerHtml;
			}
		}
	}
	

	public static function getAllEmails($where='true', $params=array(), $count=false) {
		if ($count) $select = 'COUNT(*)';
		else $select = 'email';

		$command = Yii::app()->db->createCommand()
			->select($select)
			->from('{{user}}')
			->where($where);
		foreach($params as $key=>$value) {
			$command->bindParam($key, $value);
		}
		if ($count) return $command->queryScalar();
		else return $command->queryColumn();
	}
	
	public function getRegisterSourceName()
	{
		if($this->register_source==self::REGISTER_SOURCE_CREATORS)
			return 'Creators';
		elseif($this->register_source==self::REGISTER_SOURCE_FIRMBOOK)
			return 'Firmbook';
		return '';
	}
}