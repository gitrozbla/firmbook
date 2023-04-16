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
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            
            'product' => array(self::HAS_ONE, 'Product', 'item_id'),
            'service' => array(self::HAS_ONE, 'Service', 'item_id'),
            'company' => array(self::HAS_ONE, 'Company', 'item_id'),
            
            'thumbnail' => array(self::BELONGS_TO, 'UserFile', 'thumbnail_file_id', 'together'=>true),
            
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            
            'package' => array(self::BELONGS_TO, 'Package', 'cache_package_id'),
        );
    }
    
    /**
     * Lista reguł walidacji.
     * @return array
     */
    public function rules()
    {
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
        );
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
                                'To make this object visible i nemu, you need '
                                . 'to select at least second level of category.'));
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
    
    public function beforeSave()
    {
        if ($this->isNewRecord) {
            
            // defaults
            
            if (empty($this->user)) {
                $user = Yii::app()->user;
                if ($user->isGuest) {
                    return false;
                }
                $this->user_id = $user->id;
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
        
        return parent::beforeSave();
    }
    
    public function afterSave()
    {
        $save = false;
        
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
        
        return parent::afterSave();
    }
    
    public function categoryAllowed()
    {
        $settings = Settings::model()->find();
        return $this->category->level >= $settings->items_from_level;
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
                'limit' => 24,
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
                'limit' => 24,
                'with' => array(
                    //$type
                    'thumbnail',
                ),
                'together' => true,
                'condition' => 't.'.$action.'=1'
                    .' and t.active=1'
                    ." and t.cache_type='".$type[0]."'"
                    ." and t.cache_package_id != 0",
            ),
            'pagination' => false,
            'groupSize' => 8,
        ));
    }
    
    public function searchDataProvider($search, $category) {
        
        $itemAdditionalCondition = array();
        $categoryAdditionalCondition = array();
        $additionalParams = array();
        
        if ($category) {
            // from all children
            $categoryAdditionalCondition []= 'c.root=:root and c.lft>=:lft and c.rgt<=:rgt';
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
        
        return new CActiveDataProvider('Item', array(
            'criteria' => array(
                'alias' => 'i',
                'with' => array(
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
                ),
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
                    .'i.'.$search->action.'=1'
                    ." AND i.cache_type='".($search->type[0])."'"
                    .' AND '.implode(' AND ', $itemAdditionalCondition),
                'params' => $additionalParams,
                'order' => 'i.cache_package_id DESC, i.id DESC',// same order as sorting by date//'u.package_id DESC, i.date DESC',
            ),
            //'sort' => false,
            'pagination' => array(
                'pageSize' => 20,
                //'route' => '/list',
            ),
        ));
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
        $class::model()->findByAttributes(array(
            'item_id'=>$this->id,
        ))->delete();
        
        // remove files
        $files = UserFile::model()->findAllByAttributes(array(
            'class' => 'Item',
            'data_id' => $this->id,
        ));
        foreach($files as $file) {
            $file->delete();
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
        
        return parent::beforeDelete();
    }
    
    public function getPackageItemClass()
    {
        return $this->cache_package_id
                ? 'package-item-'.$this->package->css_name
                : '';
    }
    
    public function countUserItems($userId)
    {
        return Item::model()->count(
            'user_id=:user_id', 
            array(':user_id'=>$userId)
            );
    }
}
