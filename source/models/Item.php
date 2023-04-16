<?php
/**
 * Model cech wspólnych produktu, firmy, usługi.
 * 
 * @category models
 * @package item
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Item extends ActiveRecord
{
    protected $translationUpdate = false;
    protected static $fullTextAttributes = array('name', 'description');
    
    
    //użyte w formularzu nowej firmy
    // old
    /*public $category_parent_id;    
    public $category2_id;
    public $category2_parent_id;    
    public $category3_id;
    public $category3_parent_id;
    public $category4_id;
    public $category4_parent_id;*/
    //czy kupujący czy sprzedający
    public $account_type;
   
    
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
        return '{{item}}';
    }
    
    /**
     * Nazwa kolumny głównej.
     * @return string
     */
    public function primaryKey()
    {
        return 'id';    // because db has set clustered index
    }
	
	
	//public $youtube_link = null;
    public static $youtubeLinkPattern = '/^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]{1,11}).*/i';
    public static $facebookLinkPattern = '/(?:https?:\/\/)?(?:www\.)?facebook\.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-\.%]{3,100})/i';
//    public static $googlePlusLinkPattern = '/\+[^\/]+|\d{21}/i';
    public static $facebookProfileLinkPattern = '/(?:https?:\/\/)?(?:www\.)?facebook\.com\/([\.?\w=]+)/i';
    
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
    	// prefix wymagany, gdy tabela nie ma zdefiniowanego modelu.
    	$dbPrefix = Yii::app()->db->tablePrefix;
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'additionalCategories' => array(self::MANY_MANY, 'Category', $dbPrefix.'item_additional_category(id_item, id_category)'),
            'product' => array(self::HAS_ONE, 'Product', 'item_id'),
            'service' => array(self::HAS_ONE, 'Service', 'item_id'),
            'company' => array(self::HAS_ONE, 'Company', 'item_id'),
            'thumbnail' => array(self::BELONGS_TO, 'UserFile', 'thumbnail_file_id', 'together'=>true),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'package' => array(self::BELONGS_TO, 'Package', 'cache_package_id'),
            'elists' => array(self::HAS_MANY, 'Elist', 'item_id'),
            'news' => array(self::HAS_MANY, 'News', 'item_id'),
            'files' => array(self::HAS_MANY, 'UserFile', 'data_id',
            'condition' => 'class="Item"', 'order' => 'position ASC'),
            'attachments' => array(self::HAS_MANY, 'Attachment', 'item_id')
        );
    }
    
    /**
     * Lista reguł walidacji.
     * @return array
     */
    public function rules()
    {
        $descLimit = Yii::app()->user->getModel()->package_id == 4 ? null : 10000;
		
    	return array(
            // update
            array('name, category_id', 'required', 'on'=>'update, create'),    
    	
            array('name', 'length', 'min'=>3, 'max'=>64, 'on'=>'update, create', 'encoding'=>false),
            array('alias', 'length', 'min'=>3, 'max'=>64, 'on'=>'update, create'),
            array('alias', 'unique', 'on'=>'update, create'),
            array('alias', 'match', 'pattern'=>'/^[a-zA-Z0-9\-_.]+$/', 'on'=>'update, create', 
                'message'=>Yii::t('item', 'Alias cannot contain any special characters except "-", "_" and ".".')),
            array('alias', 'match', 'pattern'=>'/^[a-zA-Z0-9\-_.]*[a-zA-Z]+[a-zA-Z0-9\-_.]*$/', 'on'=>'update, create', 
                'message'=>Yii::t('item', 'Alias must haveat least one letter.')),
            array('active, buy, sell', 'boolean', 'on'=>'update, create'),
            array('name, active', 'integrationValidate', 'on'=>'update, create'),
            array('description', 'length', 'max'=>$descLimit, 'on'=>'update, create',
				'tooLong'=>Yii::t('item', 'The limit of characters in this package is {limit}.', array('{limit}' => $descLimit))),
    		// dla testów rozmiar obrazu ustawiony na 5MB	
            array('thumbnail_file_id', 'userFileValidator', 'types'=>'jpg, jpeg, gif, png', 'maxSize'=>5000000,
                'safe'=>true, 'allowEmpty'=>true, 'on'=>'update'),
            array('category_id', 'numerical', 'min'=>1, 'on'=>'update, create'),
            array('category_id', 'categoryExists', 'on'=>'update, create'),
            
            /*array('category2_id, category3_id, category4_id', 'numerical', 'min'=>1, 'on'=>'update, create'),
            array('category2_id, category3_id, category4_id', 'categoryExists', 'on'=>'update, create'),*/
            
            
            /*array('category_parent_id, category2_parent_id, category3_parent_id, 
            	category4_parent_id, description, www', 'safe'),*/
            
            array('account_type', 'required', 'on'=>'update, create'),
    		
            array('youtube', 'length', 'min'=>1, 'max'=>11, 'on'=>'update, create'),
            array('youtube_link', 'match',
                'pattern'=>self::$youtubeLinkPattern,
                'message'=>Yii::t('item', 'This link is incorrect or not supported. Please try using different format.')),

            array('facebook', 'length', 'min'=>3, 'max'=>100, 'on'=>'update, create'),
            array('facebook_link', 'match',
                'pattern'=>self::$facebookLinkPattern,
                'message'=>Yii::t('item', 'This link is incorrect or not supported. Please try using different format.')),

            array('facebook_profile', 'length', 'min'=>3, 'max'=>100, 'on'=>'update, create'),
            array('facebook_profile_link', 'match',
                    'pattern'=>self::$facebookLinkPattern,
                    'message'=>Yii::t('item', 'This link is incorrect or not supported. Please try using different format.')),    			

//            array('google_plus', 'length', 'min'=>3, 'max'=>21, 'on'=>'update, create'),
//            array('google_plus_link', 'match',
//                'pattern'=>self::$googlePlusLinkPattern,
//                'message'=>Yii::t('item', 'This link is incorrect or not supported. Please try using different format.')),
				
            array('color', 'length', 'is'=>6, 'on'=>'update, create'),
            /*array('youtube', 'match', 'pattern'=>'/^[a-zA-Z0-9]{11}$/', 'on'=>'update, create',
                                    'message'=>Yii::t('item', 'The string must contain 11 alphanumeric characters.')),*/


            array('www', 'url', 'defaultScheme' => 'http', 'on'=>'update, create'),
            //array('www', 'safe', 'on'=>'update, create'),
            
        );
        /*return array(
            // update
            array('name', 'required'),
            
            array('name', 'length', 'min'=>3, 'max'=>64, 'on'=>'create, update', 'encoding'=>false),
            array('alias', 'length', 'min'=>3, 'max'=>64, 'on'=>'update'),
            array('alias', 'unique', 'on'=>'update'),
            array('alias', 'match', 'pattern'=>'/^[a-zA-Z0-9\-_.]+$/', 'on'=>'update', 
                'message'=>Yii::t('item', 'Alias cannot contain any special characters except "-", "_" and ".".')),
            array('alias', 'match', 'pattern'=>'/^[a-zA-Z0-9\-_.]*[a-zA-Z]+[a-zA-Z0-9\-_.]*$/', 'on'=>'update', 
                'message'=>Yii::t('item', 'Alias must have at least one letter.')),
            array('active, buy, sell', 'boolean', 'on'=>'update'),
            array('name, active', 'integrationValidate', 'on'=>'update'),
            array('description', 'length', 'max'=>10000, 'on'=>'update'),
            array('thumbnail_file_id', 'userFileValidator', 'types'=>'jpg, jpeg, gif, png', 'maxSize'=>500000,
                'safe'=>true, 'allowEmpty'=>true, 'on'=>'update'),
            array('category_id', 'numerical', 'min'=>1, 'on'=>'update'),
            array('category_id', 'categoryExists', 'on'=>'update'),
        );*/
        /*	oryginał
         	return array(
            // update
            array('name', 'length', 'min'=>3, 'max'=>64, 'on'=>'update', 'encoding'=>false),
            array('alias', 'length', 'min'=>3, 'max'=>64, 'on'=>'update'),
            array('alias', 'unique', 'on'=>'update'),
            array('alias', 'match', 'pattern'=>'/^[a-zA-Z0-9\-_.]+$/', 'on'=>'update', 
                'message'=>Yii::t('item', 'Alias cannot contain any special characters except "-", "_" and ".".')),
            array('alias', 'match', 'pattern'=>'/^[a-zA-Z0-9\-_.]*[a-zA-Z]+[a-zA-Z0-9\-_.]*$/', 'on'=>'update', 
                'message'=>Yii::t('item', 'Alias must haveat least one letter.')),
            array('active, buy, sell', 'boolean', 'on'=>'update'),
            array('name, active', 'integrationValidate', 'on'=>'update'),
            array('description', 'length', 'max'=>10000, 'on'=>'update'),
            array('thumbnail_file_id', 'userFileValidator', 'types'=>'jpg, jpeg, gif, png', 'maxSize'=>500000,
                'safe'=>true, 'allowEmpty'=>true, 'on'=>'update'),
            array('category_id', 'numerical', 'min'=>1, 'on'=>'update'),
            array('category_id', 'categoryExists', 'on'=>'update'),
        );*/
    }
    
    public function integrationValidate($attribute)
    {
        switch($attribute) {
            case 'active':
                if ($this->$attribute) {
                    if (empty($this->name)) {
                        $this->addError($attribute, Yii::t('item', 'Type name before activating.'));
                    } else if (!$this->categoryAllowed()) {
                        $this->addError($attribute, Yii::t('item', 
                                'To make this object visible in menu, you need '
                                . 'to select second level of category.'));
                    }
                }
                
                break;
                
            case 'name':
                if (empty($this->$attribute) && $this->active == true) {
                    $this->addError($attribute, Yii::t('item', 'Name is required.'));
                }
                break;
        }
            
    }
    
    public function behaviors()
    {
    	return array(
                'activerecord-relation' => array(
                'class' => 'ActiveRecordRelationBehavior'
            )
    	);
    }
    
    public function attributeLabels() {
        
        return array(
            'name' => Yii::t('item', 'Name'),
            'description' => Yii::t('item', 'Description'),
            /*'category_parent_id' => Yii::t('item', 'Category'),
            'category_id' => '',
            'category2_parent_id' => Yii::t('item', 'Category').' 2',
            'category2_id' => '',
            'category3_parent_id' => Yii::t('item', 'Category').' 3',
            'category3_id' => '',
            'category4_parent_id' => Yii::t('item', 'Category').' 4',
            'category4_id' => '',*/
            'category_id' => Yii::t('item', 'Primary category'),
            'additionalCategories' => Yii::t('item', 'Additional categories'),
            'account_type' => Yii::t('item', 'Offer'),      
            'youtube_link' => Yii::t('item', 'Link to Youtube video'),
            'facebook_link' => Yii::t('item', 'Link to Facebook fanpage'),
            'facebook_profile_link' => Yii::t('item', 'Link to Facebook profile'),
//            'google_plus_link' => Yii::t('item', 'Link to Google+ profile'),
            'allegro_link' => Yii::t('company', 'Link to shop on Allegro'),
            'www' => 'WWW',        	        	        	    
            'active' => Yii::t('item', 'Active'),
            'date' => Yii::t('common', 'Date'),
            'color' => Yii::t('common', 'Color'),
            'alias' => Yii::t('item', 'Alias - the most important SEO phrase'),
        );
    }
    
    public function categoryExists($attribute)
    {
        $value = $this->$attribute;
        
        if (!empty($value) && Category::model()->findByPk($value) == null) {
            $this->addError($attribute, Yii::t('item', 'Category does not exists!'));
        }
    }
    
    public function __set($name, $value)
    {
      if(in_array($name,$this->tableSchema->columnNames) 
              && in_array($name, self::$fullTextAttributes)) {
            if (!is_array($this->translationUpdate)) {
                $this->translationUpdate = array();
            }
            $this->translationUpdate []= $name;
      }
      parent::__set($name, $value);
    }
	

    public function getYoutube_link() {
        return /*!empty($this->youtube_link)
            ? $this->youtube_link
            : */!empty($this->youtube)
                ? 'http://www.youtube.com/watch?v=' . $this->youtube
                : null;
    }

    public function setYoutube_link($value) {
        // extract Youtube id from link
        if ($value !== null) {
            if (empty($value)) $this->youtube = null;
            else {
                preg_match(self::$youtubeLinkPattern, $value, $matches);
                //var_dump($matches);exit();
                if (isset($matches[2])) {
                    $this->youtube = $matches[2];
                }
            }
        }
    }


    public function getFacebook_link() {
        return !empty($this->facebook)
                ? 'https://facebook.com/' . $this->facebook
                : null;
    }

    public function setFacebook_link($value) {
        if ($value !== null) {
            if (empty($value)) $this->facebook = null;
            else {
                preg_match(self::$facebookLinkPattern, $value, $matches);
                //var_dump($matches);exit();
                if (isset($matches[1])) {
                    $this->facebook = $matches[1];
                }
            }
        }
    }
    
    public function getFacebook_profile_link() {
    	return !empty($this->facebook_profile)
    	? 'https://facebook.com/' . $this->facebook_profile
    	: null;
    }
    
    public function setFacebook_profile_link($value) {
    	//     	$value = 'http://www.facebook.com/profile.php?id=100002596300213';
    	//     	$value = 'http://www.facebook.com/tomaszwyka';
    	// //     	$value = 'facebook.com/profie.php?id=100002596300213';
    	//     	$pattern = '/(?:https?:\/\/)?(?:www\.)?facebook\.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-\.%]{3,100})/i';
    	//     	$pattern = '/(?:https?:\/\/)?(?:www\.)?facebook\.com\/(?:(?:\w)*#!\/)?(?:profile\/)?(?:[\w\-]*\/)*([\w\-\.%]{3,100})([\?\w\=]+)/i';
    	//     	$pattern = '/(?:https?:\/\/)?(?:www\.)?facebook\.com\/([\.?\w=]+)/i';
    	//     	preg_match($pattern, $value, $matches);
    	//     	var_dump($matches);exit();
    	if ($value !== null) {
    		if (empty($value)) $this->facebook_profile = null;
    		else {
    			preg_match(self::$facebookProfileLinkPattern, $value, $matches);
    			//var_dump($matches);exit();
    			if (isset($matches[1])) {
    				$this->facebook_profile = $matches[1];
    			}
    		}
    	}
    }

//    public function getGoogle_plus_link() {
//        return !empty($this->google_plus)
//                ? 'https://plus.google.com/' . $this->google_plus
//                : null;
//    }

//    public function setGoogle_plus_link($value) {
//        if ($value !== null) {
//            if (empty($value)) $this->google_plus = null;
//            else {
//                preg_match(self::$googlePlusLinkPattern, $value, $matches);
//                if (isset($matches[0])) {
//                    $this->google_plus = $matches[0];
//                }
//            }
//        }
//    }

    
    
    public function beforeSave()
    {
        if ($this->isNewRecord) {
            //echo '<br/><br/>nowy rekord<br/><br/>';
            // defaults
            
            if (empty($this->user)) {
                $user = Yii::app()->user;
                if ($user->isGuest) {
                    return false;
                }
                //$this->user_id = $user->id;
                $this->user = $user->getModel();
            }
            
            $search = Search::model()->getFromSession();
            
            if ($this->buy == 0 && $this->sell == 0) {
                $buy = $search->action == 'buy'
                        ? 1 : 0;
                $this->buy = $buy;
                $this->sell = 1 - $buy;
            }

            if (empty($this->category)) {
                if ($search->category) {
                    $category = Category::model()->findByAttributes(
                            array('alias'=>$search->category));
                }
                if (!empty($category)) {
                    $this->category_id = $category->id;
                } else {
                    Yii::app()->user->setFlash('warning', Yii::t('categories', 'First select a category.'));
                    Yii::app()->controller->redirect(YIi::app()->createUrl('/categories'));
                    return false;
                }
            }

            if (empty($this->date)) {
                $this->date = date('Y-m-d', time());
            }
            
            if (empty($this->active)) {
                $this->active = 0;
            }
            
            if (empty($this->cache_type)) {
                return false;
            }
        }
        
        return parent::beforeSave();
    }
    
    public function afterSave()
    {
		$save = false;
		
		// update translations
        if ($this->translationUpdate) {
            $db = Yii::app()->db;
            foreach($this->translationUpdate as $name) {
                $db->createCommand()
                        ->insertIgnore('tbl_source_message', array(
                            'category' => 'item',
                            'object_id' => $this->id,
                            'message' => '{'.$name.'}',
                        ));
                $messageId = $db->getLastInsertID();
                if (!$messageId) {
                    $messageId = $db->createCommand()
                            ->select('id')
                            ->from('tbl_source_message')
                            ->where('category="item"'
                                    . 'and object_id=:object_id '
                                    . 'and message=:message', array(
                                        ':object_id' => $this->id,
                                        ':message' => '{'.$name.'}',
                                    ))
                            ->queryScalar();
                }
                if ($messageId) {
                    $db->createCommand()
                            ->insertUpdate('tbl_message', array(
                                'id' => $messageId,
                                'language' => 'en',
                                'translation' => strip_tags($this->$name),
                            ), array(
                                    'translation' => strip_tags($this->$name),
                            ));
                }
            }
        }
        
        if ($this->active == true) {
            if (!$this->categoryAllowed()) {
                $this->active = false;
                $save = true;
                Yii::app()->user->setFlash('warning', Yii::t('item', 
                        'To make this object visible i nemu, you need '
                        . 'to select at least second level of category.'));
            }
        }
        
        if (empty($this->alias)) {
            // generate alias
            $this->alias = $this->id;
            $save = true;
        }
        
        if ($save) {
            $this->setIsNewRecord(false);
            $this->setScenario('none'); // cannot be 'update' because the validation
            $this->save(); // again
        }
        
        /*
         * powiadomienia
         */
        
        
        return parent::afterSave();
    }
    
    public function categoryAllowed()
    {
        $settings = Settings::model()->find();
        return $this->category && $this->category->level >= $settings->items_from_level;
    }
    
    
    
    /**
     * Dostarcza najnowsze produkty/usługi/firmy.
     * @param string $type Typ (product, service, company).
     * @param string $action Akcja (buy, sell).
     * @return \ActiveDataProvider
     */
    public function newestDataProvider($type, $action)
    {
        return new ActiveDataProvider('Item', array(
            'criteria' => array(
                'order' => 't.id DESC',// same order as sorting by date
                'limit' => 40,
                'with' => array(
                    //$type
                    'thumbnail',
                ),
                'together' => true,
                'condition' => 't.'.$action.'=1'
                    .' and t.active=1'
                    ." and t.cache_type='".$type[0]."'",
            ),
            'pagination' => false,
            'groupSize' => 8,
        ));
    }
    
    public function promotedDataProvider($type, $action)
    {
        return new ActiveDataProvider('Item', array(
            'criteria' => array(
                'order' => 't.cache_package_id DESC, t.id DESC',// same order as sorting by date
                'limit' => 40,
                'with' => array(
                    //$type
                    'thumbnail',
                ),
                'together' => true,
                'condition' => 't.'.$action.'=1'
                    .' and t.active=1'
                    ." and t.cache_type='".$type[0]."'"
            		." and t.cache_package_id != 1",
                    //." and t.cache_package_id != 0",
            ),
            'pagination' => false,
            'groupSize' => 8,
        ));
    }
    
    public function searchDataProvider($search, $category=null) {
        $itemAdditionalCondition = array();
        $categoryAdditionalCondition = array();
        $additionalParams = array();
        $additionalWith = array();
        
        if ($category) {
            // from all children
            //$categoryAdditionalCondition []= 'c.root=:root and c.lft>=:lft and c.rgt<=:rgt';
            $categoryAdditionalCondition []= 
                    '((c.root=:root and c.lft>=:lft and c.rgt<=:rgt)
                        OR
                     (additionalCategories.root IS NOT NULL 
                        and additionalCategories.root=:root 
                        and additionalCategories.lft>=:lft 
                        and additionalCategories.rgt<=:rgt))';
            $additionalParams = array_merge(
                    $additionalParams,
                    array(
                        ':root' => $category->root,
                        ':lft' => $category->lft, 
                        ':rgt' => $category->rgt,
                        ));
        }
        
        // search by query
        if  (!empty($search->query)) {
            // wyszukiwanie musi być w tabeli MYISAM (FULL TEXT SEARCH)
            $itemAdditionalCondition []= 'i.id in ('
                    . 'select s.object_id '
                    . 'from tbl_source_message s '
                    . 'left join tbl_message m '
                    . 'on s.id=m.id '
                    . 'where category="item" '
                    . 'and match(translation) against (:query IN BOOLEAN MODE) )'; 
//             		. 'and match(translation) against (:query) )';
           $additionalParams [':query'] = '*'.str_replace(' ', '* *', $search->query).'*';
        }
        // search by username
        if (!empty($search->username)) {
            $itemAdditionalCondition []= 'i.user_id in ('
                    . 'select id '
                    . 'from tbl_user '
                    . 'where username like :username )';
            $additionalParams [':username'] = '%'.$search->username.'%';
        }
        
        // active filter
        if (!Yii::app()->user->isGuest) {
            //if (!Yii::app()->user->isAdmin) {
                $itemAdditionalCondition []= 
                        '(i.active=1 or i.user_id=:user_id)';
                $additionalParams [':user_id'] = Yii::app()->user->id;
            //}
        } else {
            $itemAdditionalCondition []= 'i.active=1';
        }
        
        //$defaultSort = 'i.cache_package_id DESC, i.id';
        $defaultSort = 'i.cache_package_id DESC, i.id DESC';
        $sort=new CSort;
		
        if($search->action) {
            // w creatorsach action nie występuje
            if($category)
                $url_params = array(Search::getContextUrlType($search->type).'_context'=>Yii::t('url', Search::getContextUrlAction($search->action, $search->type)), 'name'=> Yii::t('category.alias', $category->alias, null, 'dbMessages'));
            else
                $url_params = array(Search::getContextUrlType($search->type).'_context'=>Yii::t('url', Search::getContextUrlAction($search->action, $search->type)));
        }
                
        $sort->attributes = array(
            'name' => array(
                    'asc' => 'i.name, '.$defaultSort,
                    'desc' => 'i.name DESC, '.$defaultSort,
                    'label' => Yii::t('common', 'Name'),
                ),
        );
	if($search->type[0]=='c') {            
            $sort->attributes = array_merge($sort->attributes,
	    		array(
                                'views' => array(
				    	'asc' => 'i.view_count, '.$defaultSort,
				        'desc' => 'i.view_count DESC, '.$defaultSort,
				        'label' => Yii::t('common', 'Popularity'),
				    ),
			    )
	    );    
        } elseif($search->type[0]=='p') {
            $sort->attributes = array_merge($sort->attributes,
	    		array(
			    	'price' => array(
				    	'asc' => 'p.price, '.$defaultSort,
				        'desc' => 'p.price DESC, '.$defaultSort,
				        'label' => Yii::t('common', 'Price'),
				    ),
//                                'views' => array(
//				    	'asc' => 'i.view_count, '.$defaultSort,
//				        'desc' => 'i.view_count, '.$defaultSort,
//				        'label' => Yii::t('common', 'Views'),
//				    ),
			    )
	    );
            $sort->attributes = array_merge($sort->attributes,
	    		array(
//			    	'price' => array(
//				    	'asc' => 'p.price, '.$defaultSort,
//				        'desc' => 'p.price DESC, '.$defaultSort,
//				        'label' => Yii::t('common', 'Price'),
//				    ),
                                'views' => array(
				    	'asc' => 'i.view_count, '.$defaultSort,
				        'desc' => 'i.view_count DESC, '.$defaultSort,
				        'label' => Yii::t('common', 'Popularity'),
				    ),
			    )
	    );
	} elseif($search->type[0]=='s') {
	    	$sort->attributes = array_merge($sort->attributes,
	    		array(
	    			'price' => array(
	    				'asc' => 's.price, '.$defaultSort,
	    				'desc' => 's.price DESC, '.$defaultSort,
	    				'label' => Yii::t('common', 'Price'),
	    			),
//                                'views' => array(
//				    	'asc' => 'i.view_count, '.$defaultSort,
//				        'desc' => 'i.view_count, '.$defaultSort,
//				        'label' => Yii::t('common', 'Views'),
//				    ),
	    		)
	    	);
                $sort->attributes = array_merge($sort->attributes,
	    		array(
//	    			'price' => array(
//	    				'asc' => 's.price, '.$defaultSort,
//	    				'desc' => 's.price DESC, '.$defaultSort,
//	    				'label' => Yii::t('common', 'Price'),
//	    			),
                                'views' => array(
				    	'asc' => 'i.view_count, '.$defaultSort,
				        'desc' => 'i.view_count DESC, '.$defaultSort,
//				        'label' => Yii::t('common', 'Views'),
                        'label' => Yii::t('common', 'Popularity'),
				    ),
	    		)
	    	);
	    }
	    
        $sort->defaultOrder = $defaultSort;
        
        if($search->type[0]=='p') {
        	$additionalWith['product'] = array(
                'select' => 'p.price, p.promotion',
                'alias' => 'p',
            );
        	$additionalWith['product.company'] = array(
//        				'select' => 'pco.item_id, pco.map_lat, pco.map_lng, pco.city, pco.street, pco.postcode, pco.phone',
                'select' => 'pco.item_id, pco.map_lat, pco.map_lng, pco.city, pco.street, pco.postcode, pco.phone, pco.business_reliable',
                'alias' => 'pco',
        	);
            $additionalWith['product.company.item'] = array(
//        				'select' => 'pco.item_id, pco.map_lat, pco.map_lng, pco.city, pco.street, pco.postcode, pco.phone',
                'select' => 'pcoi.name, pcoi.alias',
                'alias' => 'pcoi',
        	);
        } elseif($search->type[0]=='s') {
        	$additionalWith['service'] = array(
                'select' => 's.price, s.promotion',
                'alias' => 's',
            );
        	$additionalWith['service.company'] = array(
                'select' => 'sco.item_id, sco.map_lat, sco.map_lng, sco.city, sco.street, sco.postcode, sco.phone, sco.business_reliable',
                'alias' => 'sco',
        	);
            $additionalWith['service.company.item'] = array(
//        				'select' => 'pco.item_id, pco.map_lat, pco.map_lng, pco.city, pco.street, pco.postcode, pco.phone',
                'select' => 'scoi.name, scoi.alias',
                'alias' => 'scoi',
        	);
        } elseif($search->type[0]=='c') {
        	$additionalWith['company'] = array(
                'select' => 'co.item_id, co.map_lat, co.map_lng, co.city, co.street, co.postcode, co.phone, co.business_reliable',
                'alias' => 'co',
            ); 
        }  
        
        /*if (!Yii::app()->user->isGuest) {
        	$additionalWith['elists'] = array(
                        'select' => 'e.type, e.user_id',
                        'alias' => 'e',
                    );
                    
            $itemAdditionalCondition []= 
                        'e.user_id=:user_id';
                //$additionalParams [':user_id'] = Yii::app()->user->id;        
        }*/
        
        $get_params = $_GET;
        if(isset($url_params))
        	$pager_params = $url_params + $get_params;
        else 
        	$pager_params = $get_params;
        $sort->params = $pager_params;
        
        $dataProvider = new CActiveDataProvider('Item', array(
            'criteria' => array(
                'alias' => 'i',
                'with' => array_merge(array(
                    'thumbnail',
                    /*$search->type,/* => array(
                        'condition'=>'item_id=i.id',
                    ),*/
                    /*'user' => array(
                        'select' => 'id, package_id',
                        'alias' => 'u',
                    ),*/
                    /*'user.package' => array(
                        'select' => 'color',
                        'alias' => 'p',
                    ),*/ // to slow!
                    'category' => array(
                        'select' => '',
                        'alias' => 'c',
                    ),
                    'additionalCategories.additionalForItem' => array(
                        'select' => '',
                        //'alias' => 'ac',    // nie działa, alias to 'additionalCategories'
                    ),
                ), $additionalWith),
                'together' => true,
                /*'condition' => '(i.category_id IS NULL '.
                        'OR i.category_id in '.
                        '(select id from tbl_category as c '.
                        'where '.implode(' AND ', $categoryAdditionalCondition).')'.
                    ')'.
                    'AND i.'.$search->action.'=1 AND '.
                    implode(' AND ', $itemAdditionalCondition),*/ // slower
                'condition' => (!empty($categoryAdditionalCondition)
                        ? /*'(i.category_id IS NULL '.
                            'OR ('.implode(' AND ', $categoryAdditionalCondition).')'.
                        ') AND '*/  // slow?
                        implode(' AND ', $categoryAdditionalCondition).' AND '
                        : '')
                    .($search->action != null 
                        ? 'i.'.$search->action.'=1 AND ' 
                        : '')
                    ."i.cache_type='".($search->type[0])."'"
                    .' AND '.implode(' AND ', $itemAdditionalCondition),
                'params' => $additionalParams,
                //'order' => 'i.cache_package_id DESC, i.id DESC',// same order as sorting by date//'u.package_id DESC, i.date DESC',
            ),
            
            'sort'=>$sort,
            //'sort' => false,
            'pagination' => array(
                'pageSize' => 20,
                //'route' => '/list',
//                 'route' => '/site/index',
            	'params' => $pager_params, //array('kloc'=>ikupa)	
            		 
            ),
        ));
        
        if(isset($dataProvider->getSort()->params[$dataProvider->getPagination()->pageVar]))
			unset($dataProvider->getSort()->params[$dataProvider->getPagination()->pageVar]);
        
        return $dataProvider;
    }
    
    
    public function userItemsDataProvider($userId, $type, $limit=false)
    {
        return new CActiveDataProvider('Item', array(
            'criteria' => array(
                'alias' => 'i',
                'select' => array('id', 'name', 'alias',
                    'thumbnail_file_id', 'cache_package_id'),
                'with' => array(
                    'package' => array(
                        'alias' => 'p',
                    ),
                    'thumbnail',
                ),
                'condition' => 'i.user_id=:user_id '
                . 'AND i.cache_type=:cache_type '
                . 'AND i.active=1',
                'params' => array(
                    ':user_id' => $userId,
                    ':cache_type' => $type[0]
                ),
                'limit' => $limit,
                'order' => 'i.cache_package_id DESC, i.id DESC',
                ),
            'pagination' => false,
            )
        );
    }
    
    public function beforeDelete()
    {
        $classes = array(
            'p' => 'Product',
            's' => 'Service',
            'c' => 'Company',
        );
        
        // remove product/service/company
        $class = $classes[$this->cache_type];
        
        
        $condition = 'item_id=:item_id';
        $params = array(':item_id' => $this->id);
        if($class::model()->exists($condition,$params))
	        $class::model()->findByAttributes(array(
	            'item_id'=>$this->id,
	        ))->delete();	        
        
        /* niestety nie działa         
        // remove website configuration
        $website = CreatorsWebsite::model()->findByPk($this->id);
        if ($website) {
            $website->delete();
        }
        */
        
        // remove files
        $files = UserFile::model()->findAllByAttributes(array(
            'class' => 'Item',
            'data_id' => $this->id,
        ));
        foreach($files as $file) {
            $file->delete();
        }
        Yii::app()->file->filesPath;    // force set cwd
        $dirPath = getcwd().'/Item/'.$this->id;
        if (is_dir($dirPath)) {        	 
        	array_map('unlink', glob("$dirPath/*.*"));
        	rmdir($dirPath);
        }
        
        // remove translations
        $db = Yii::app()->db;
        $messageId = $db->createCommand()
                ->select('id')
                ->from('tbl_source_message')
                ->where('category="item" and object_id=:object_id',
                        array(':object_id' => $this->id))
                ->queryScalar();
        if ($messageId) {
            $db->createCommand()
                ->delete('tbl_message', 'id=:id', array(':id'=>$messageId));
            $db->createCommand()
                ->delete('tbl_source_message', 'id=:id', array(':id'=>$messageId));
        }
        
        // remove alerts
        Yii::app()->db->createCommand()
        	->delete('tbl_alert', 
        		'(context_id='.$this->id.' and context_type=1)'
        		.' or '	
        		.'(item_id='.$this->id.' and (item_type=1 or item_type=2 or item_type=3))'       			
        	);
        
        // remove elist items
        Yii::app()->db->createCommand()
	        ->delete('tbl_elist',
	        	'item_id='.$this->id.' and item_type=1'
	        );
        
        // remove followed items
        Yii::app()->db->createCommand()
	        ->delete('tbl_follow',
	        	'item_id='.$this->id.' and item_type=1'
	        );
        
        // remove additional categories
        Yii::app()->db->createCommand()
	        ->delete('tbl_item_additional_category',
	        	'id_item='.$this->id
	        );
        
        // remove news
        Yii::app()->db->createCommand()
	        ->delete('tbl_news',
	        	'item_id='.$this->id
	        );
        
        // remove likes
        Yii::app()->db->createCommand()
	        ->delete('tbl_post_like',
	        	'post_id='.$this->id.' and post_type="item"'
	        );
        
        /*
         * Dodać usuwanie załączników (attachment)
         */
        
        return parent::beforeDelete();
    }
    
    public function getPackageItemClass()
    {    	
        return $this->cache_package_id
            ? 'package-item-'.$this->package->css_name
            : '';
    }
    
    public function badge($showFree=false, $creators=false)
    {
        if ($creators) return '';

        if (!$this->cache_package_id) return '';
//        if (!$showFree && $this->package->name=='FREE') return '';
        if (!$showFree && (!$creators && $this->package->name=='STARTER' || $creators && $this->package->name=='FREE')) return '';
    	return $this->cache_package_id
            ? '<span class="package-badge-'.$this->package->css_name.'">'
                            //.$this->package->name
            .Yii::t('packages', $this->package->name)
            .'</span>'
                            : '';
    }
	
    public function badge2($showFree=false, $creators=false)
    {
        if ($creators) return '';

        if (!$this->cache_package_id) return '';
//		if (!$showFree && $this->package->name=='FREE') return '';
        if (!$showFree && (!$creators && $this->package->name=='STARTER' || $creators && $this->package->name=='FREE')) return '';
        return $this->cache_package_id
            ? '<span class="package-badge2-'.$this->package->css_name.'">'
                            //.$this->package->name
            .Yii::t('packages', $this->package->name)
            .'</span>'
            : '';
    }

    
    public function getItemStyle()
    {
    	if($this->color) {
            list($r,$g,$b) = array_map('hexdec',str_split(ltrim($this->color, '#'),2));
            //echo $r.' '.$g.' '.$b;    		 
            return 'background-color: rgba('.$r.','.$g.','.$b.',0.3);';
            //return 'background-color: rgba(34,140,6,0.3);';
    	} else
            return $this->cache_package_id
                ? $this->package->item_css
                : '';
    }
    
    public function countUserItems($userId)
    {
        return Item::model()->count(
            'user_id=:user_id', 
            array(':user_id'=>$userId)
            );
    }
    /*
     * zwraca klasę ikonki fa dla zadanego typu obiektu
     * '<i class="fa fa-'.Item::faIconClass ...
     */
    public static function faIconClass($itemType)
    {
    	switch($itemType) {
            case 'c':
                return 'building-o';    		
            case 's':
                return 'truck';
            case 'p':
            default:
                return 'shopping-cart';			
    	}
    }
    
    /*public function getLetterIcon($nr) 
    {
    	return Yii::app()->params['google']['map']['markers'][$nr];
    }*/
    
    public function url($absoluteUrl=false) 
    {   	
        switch($this->cache_type) {
            case 'p':
                $url_part = 'products';
                break;
            case 's':
                $url_part = 'services';
                break;
            default:
                $url_part = 'companies';
        }
        if($absoluteUrl)
            return Yii::app()->createAbsoluteUrl("$url_part/show",
                array("name"=>$this->alias));
        return Yii::app()->createUrl("$url_part/show",
            array("name"=>$this->alias)); 
    }
    
    public function typeName()
    {   	
        switch($this->cache_type) {
            case 'p':
                return 'product';
            case 's':
                return 'service';
            default:
                return 'company';
        }         
    }        
}
