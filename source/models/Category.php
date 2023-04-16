<?php
/**
 * Model podstrony (artykułu).
 * 
 * @category models
 * @package category
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Category extends ActiveRecord
{
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
        return '{{category}}';
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
     * Zachowania klasy.
     * @return array Lista zachowań wraz z konfiguracją.
     */
    public function behaviors()
    {
        return array(
            'nestedSetBehavior'=>array(
                'class' => 'ext.nested-set-behavior.NestedSetBehavior',
                'hasManyRoots' => true,
            ),
        );
    }
	
	public function attributeLabels() {
        return array(
	        	'name' => Yii::t('category', 'Name'),
	        	'alias' => Yii::t('category', 'Alias'),
	    	);
    }
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
    	// prefix wymagany, gdy tabela nie ma zdefiniowanego modelu.
    	$dbPrefix = Yii::app()->db->tablePrefix;
        return array(
            'items' => array(self::HAS_MANY, 'Item', 'category_id'),
        	'additionalForItem' => array(self::MANY_MANY, 'Item', $dbPrefix.'item_additional_category(id_category, id_item)'),        		
        );
    }
    
    public function rules()
    {
    	return array(    			
    			array('name', 'required'),
    			array('alias', 'required'), 
    			array('alias', 'unique'),
    			//array('alias', 'unique', 'on'=>'update, create'),
    			//array('imported', 'safe'),
    	);
    }
    
    /**
     * Generuję listę podkategorii w formie używanej przez CMenu.
     * @param type $maxLevel
     * @param type $parentCategory
     * @param type $in_menu
     * @param type $more
     * @return type
     */
    public static function getMenuItems($maxLevel=2, $parentCategory=null, $in_menu=true, $more=true, $id='main-menu')
    {
        $search = Search::model()->getFromSession();

        $categories = Yii::app()->db->createCommand()
            ->select('id, name, alias, level')
            ->from('tbl_category c')
            ->where('level<='.$maxLevel.
                ($in_menu ? ' and in_menu=1' : '').
                ($parentCategory ? (' and lft>'.($parentCategory->lft).' '
                . 'and rgt<'.($parentCategory->rgt).' '
                . 'and root='.($parentCategory->root)) : ''))
            //->order('order_index, root, lft')
            //->order('order_index, lft')
            ->order('root, lft')
            ->queryAll();        

        // traverse set
        $firstLevel = $level = $parentCategory ? $parentCategory->level : 1;
        $currentNode = array();
        $nodesStack = array();
        $c = Yii::app()->controller;
        $idIndex = 1;
        foreach($categories as $category)
        {
            if ($category['level'] > $level) {
                array_push($nodesStack, $currentNode);
                $currentNode = array();
                $level++;
            } else if ($category['level'] < $level) {

                $parentNode = array_pop($nodesStack);
                $lastOfParent = end($parentNode);
                // more
                if ($more) {
                    $currentNode []= '---';
                    $currentNode []= array(
                        'label' => Yii::t('category', 'more').'...',
                        'url' => $lastOfParent['url'],
                        'linkOptions' => array(
                            'class' => 'more-link', // required by EditableMenu
                        ),
                    );
                }

                $lastCurrentNode = $currentNode;
                $currentNode = $parentNode;
                //end($currentNode);    // alreade resetted above
                $currentNode[key($currentNode)]['items'] = $lastCurrentNode;
                $currentNode[key($currentNode)]['submenuOptions'] = array(
                    'id' => $id . '-submenu-' . $idIndex
                );
                $idIndex++;
                $level--;
            }
            $currentNode []= array(
            	'label' => Yii::t('category.name', $category['alias'], array($category['alias']=>$category['name']), 'dbMessages'),
            	//'label' => Yii::t('category.name', $category['alias'], array(), 'dbMessages'),
                //'label' => Yii::t('category.name', $category['name'], array(), 'dbMessages'),
//                 'url' => $search->createUrl(
//                         'categories/show', array(
//                             'name'=> Yii::t('category.alias', $category['alias'], array(), 'dbMessages')
//                         )),
            	'url' => $search->createUrl(
            			'categories/show', array(
            				'name'=> Yii::t('category.alias', $category['alias'], array(), 'dbMessages')
            			)),
            );
        }
        // step out tree
        for($level; $level>$firstLevel; $level--) {
            $parentNode = array_pop($nodesStack);
            $lastOfParent = end($parentNode);
            // more
            if ($more) {
                $currentNode []= '---';
                $currentNode []= array(
                    'label' => Yii::t('category', 'more').'...',
                    'url' => $lastOfParent['url'],
                    'linkOptions' => array(
                        'class' => 'more-link', // required by EditableMenu
                    )
                );
            }

            $lastCurrentNode = $currentNode;
            $currentNode = $parentNode;
            $currentNode[key($currentNode)]['items'] = $lastCurrentNode;
            $currentNode[key($currentNode)]['submenuOptions'] = array(
                'id' => $id . '-submenu-' . $idIndex
            );
            $idIndex++;
        }
        $items = $currentNode;//;array_pop($nodesStack);
        /*end($currentNode);
        $currentNode[key($currentNode)]['items'] = $currentNode;
        */
        //var_dump($items);
        if ($parentCategory) {
            if (!empty($currentNode)) {
                $items = $currentNode[key($currentNode)]['items'];
            }
        }        

        return $items;
    }
    
    /**
     * Generuje linki breadcrumbs.
     * @return array tablica label=>link.
     */
    public function generateBreadcrumbs($search=null) {
    	if(!$search)
            $search = Search::model()->getFromSession();
    	$ancestors = $this->ancestors()->findAll();
    	$ancestors []= $this;
    	$links = array();
    	foreach ($ancestors as $ancestor) {
            $alias = $ancestor->alias
            ? Yii::t('category.alias', $ancestor->alias, null, 'dbMessages')
            : $ancestor->id;

//             $links[Yii::t('category.name', $ancestor->alias, null, 'dbMessages')]
            $links[Yii::t('category.name', $ancestor->alias, array($ancestor->alias=>$ancestor->name), 'dbMessages')]
            //$links[Yii::t('category.name', $ancestor->name, null, 'dbMessages')]
//     		= Yii::app()->createUrl('categories/show', array('name'=>$alias));
            = $search->createUrl('categories/show', array('name'=>$alias));
    	}
    	return $links;
    }    
    
    public function createUrl(Search $search)
    {
        
        return $search->createUrl('categories/show', array('name'=>Yii::t('category.alias', $this->alias, null, 'dbMessages')), false, true);
    }        
        
    public function getNameLocal()
    {
    	return Yii::t('category.name', $this->alias, null, 'dbMessages');
        //return Yii::t('category.name', $this->name, null, 'dbMessages');
    }
    
    public function getSubcategoriesData($query, $in_menu=true)
    {
        $limit = 100;
        
        $lft = 0;
        
        if (!empty($this->id)) {
            $range = 'AND (lft > '.$this->lft.' '
                    . 'AND rgt < '.$this->rgt.' '
                    . 'AND root='.($this->root).')';
            $level = $this->level + 1;
        } else {
            $range = '';
            $level = '1';
        }
        
        $language = Yii::app()->language;
        if ($language != Yii::app()->sourceLanguage) {
            $subcategories =  Yii::app()->db->createCommand()
                    ->select('c.id as id, c.name as text, '
                            . 'm.translation as translation')
                    ->from('tbl_category c')
                    //->join('tbl_source_message s', 's.object_id=c.id')
            		->leftJoin('tbl_source_message s', 's.message=c.alias')
                    //->leftJoin('tbl_source_message s', 's.message=c.name')
                    ->leftJoin('tbl_message m', 'm.id=s.id')
                    ->where('c.level=:level '.$range.' '.'AND c.in_menu '
                            . 'AND (c.name LIKE :name OR m.translation LIKE :name)'
                            . 'AND (s.category=\'category.name\' OR s.category IS NULL) '
                            . 'AND (m.language=:language OR m.language IS NULL)', 
                            array(
                                ':level' => $level,
                                ':name' => '%'.$query.'%',
                                ':language' => $language,
                        ))
                    ->order('root, lft')
                    ->limit($limit)
                    ->queryAll();
        } else {
            $subcategories =  Yii::app()->db->createCommand()
                    ->select('c.id as id, c.name as text, '
                            . 'c.name as translation')
                    ->from('tbl_category c')
                    ->where('c.level=:level '.$range.' '.'AND c.in_menu '
                            . 'AND (c.name LIKE :name )',
                            array(
                                ':level' => $level,
                                ':name' => '%'.$query.'%',
                        ))
                    ->order('root, lft')
                    ->limit($limit)
                    ->queryAll();
        }
        
        foreach($subcategories as $key=>$value) {
            //$subcategories[$key]['text'] = Yii::t('category.name', $value['text'], null, 'dbMessages');
            if ($value['translation']) {
                $subcategories[$key]['text'] = $value['translation'];
            }
            unset($subcategories[$key]['translation']);
        }
        
        return $subcategories;
    }
    
    public static function translate($param, $value, $lang)
    {
        if ($param == 'name') {
                $param = 'alias';
        }
        
        return Yii::t('category.'.$param, $value, null, 'dbMessages', $lang);
        
    }
        

    /*
     * pobiera i przygotowuje dane pod dropdown wyboru kategorii w formularzu dodania obiektów item
     */
    public function getCategoriesToItemSelect()
    {
    	$ancestors = $this->ancestors()->findAll();
    	$ancestors []= $this;
    	$categories = array();
    	foreach ($ancestors as $ancestor) {
    		$category = array('id'=>$ancestor->id, 'text'=>Yii::t('category.name', $ancestor->alias, null, 'dbMessages'));
    		//$category = array('id'=>$ancestor->id, 'text'=>Yii::t('category.name', $ancestor->name, null, 'dbMessages'));
            $category = CJSON::encode($category);
            $categories[] = $category;
        }
        	
        return $categories;
    }
    
    public function adminDataProvider()
    {
    	
    	if (!empty($this->id)) {
    		$range = ' AND (lft > '.$this->lft.' '
    				. 'AND rgt < '.$this->rgt.' '
    				. 'AND root='.($this->root).')';
    		$level = $this->level + 1;
    		$order = 'lft';
    	} else {
            $range = '';
            $level = '1';
            $order = 'order_index';
        } 
        //echo $range;
    	
    	return new CActiveDataProvider('Category', array(
    			'criteria'=>array(    					
    					'condition'=> 'level='.$level.$range,
    					//'order'=>'name',
    					'order'=>$order,
    			),
    			'sort'=>array(
    					'attributes'=>array(
    							'name'=>array(
    									'asc'=>'name',
    									'desc'=>'name DESC'
    							),
    							'*',
    					)
    			),
    			'pagination' => false,
    	));
    
    }    
    
    
    /**
     * Generuje linki breadcrumbs.
     * @return array tablica label=>link.
     */
    public function manageCategoryBreadcrumbs() {
    	$ancestors = $this->ancestors()->findAll();
    	$ancestors []= $this;
    	$links = array();
    	foreach ($ancestors as $ancestor) {    		
    		$links[$ancestor->name]
    		= Yii::app()->controller->createGlobalRouteUrl('admin/categories', array('category'=>$ancestor->id));
    	}
    
    	return $links;
    }
    
    public function afterSave()
    {
    	if ($this->isNewRecord) {
    		$sourceMessage = new SourceMessage;
    		$sourceMessage->category = 'category.name';
    		$sourceMessage->object_id = $this->id;
    		$sourceMessage->message = $this->alias;
    		//$sourceMessage->message = $this->name;
    		//dodanie źródła tłumaczeń tytułu artykułu
    		$sourceMessage->save();
    		unset($sourceMessage);
    		$sourceMessage = new SourceMessage;
    		$sourceMessage->category = 'category.alias';
    		$sourceMessage->object_id = $this->id;
    		$sourceMessage->message = $this->alias;    		
    		//dodanie źródła tłumaczeń treści artykułu
    		$sourceMessage->save();
    	} else {
    		$sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>'category.name', 'object_id'=>$this->id));
    		if(!$sourceMessage)
    		{
    			//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
    			$sourceMessage = new SourceMessage;
    			$sourceMessage->category = 'category.name';
    			$sourceMessage->object_id = $this->id;
    			$sourceMessage->message = $this->alias;
    			//$sourceMessage->message = $this->name;
    			$sourceMessage->save();
    		} else {
    			//koniecznie pozostawic
    			$sourceMessage->message = $this->alias;
    			$sourceMessage->save('message');
    		}
    		unset($sourceMessage);
    		$sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>'category.alias', 'object_id'=>$this->id));
    		//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
    		if(!$sourceMessage)
    		{
    			//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
    			$sourceMessage = new SourceMessage;
    			$sourceMessage->category = 'category.alias';
    			$sourceMessage->object_id = $this->id;
    			$sourceMessage->message = $this->alias;
    			$sourceMessage->save();
    		} else {
    			//koniecznie pozostawic
    			$sourceMessage->message = $this->alias;
    			$sourceMessage->save('message');
    		}
    	}
    	return parent::afterSave();
    }
    
    public function beforeDelete()
    {
    	// update items package cache
    	Yii::app()->db->createCommand()
    	->update('tbl_item', array(
    			'category_id' => 0
    	), 'category_id=:category_id', array('category_id'=>$this->id));
    	
    	//remove translation
    	$sourceMessages = SourceMessage::model()->findAll(
    			'object_id=:object_id and (category=\'category.name\' or category=\'category.alias\')',
    			array(':object_id'=>$this->id)
    	);
    	foreach($sourceMessages as $source)
    	{
    		$messages = Message::model()->findAll('id=:id',	array(':id'=>$source->id));
    		foreach($messages as $message)
    			$message->delete();
    		$source->delete();
    	}
    	return parent::beforeDelete();
    }
    
    public static function translationDataProvider($object_id)
    {
    	$translations = SourceMessage::model()->with('translations')->findAll(
    			array(
    					'condition'=>'object_id=:object_id and (category="category.name" or category="category.alias")',
    					'params'=>array(':object_id'=>$object_id),
    			)
    	);
    	$articles = array();
    	foreach(Yii::app()->params->languages as $lang)
    	{
    		$article = array();
    		foreach($translations as $part) {
    			foreach($part->translations as $message) {
    				if(!array_key_exists($message->language, $articles)) {
    					$articles[$message->language] = array('object_id'=>$object_id,'language'=>$message->language);
    				}
    				if($part->category=='category.name')
    					$articles[$message->language]['title'] = $message->translation;
    				elseif($part->category=='category.alias')
    				$articles[$message->language]['content'] = $message->translation;
    			}
    		}
    	}
    	$articles = array_values($articles);
    	$dataProvider = new CArrayDataProvider($articles, array(
    			'keyField'=>'language',
    			'pagination'=>array(
    					'pageSize'=>10,
    			),
    	));
    	return $dataProvider;
    }
    
    public function followingDataProvider()
    {
        $sql = "SELECT u.id, u.username, u.forename, u.surname, u.email, u.language FROM tbl_user u"
            ." INNER JOIN tbl_follow f ON f.user_id = u.id"
            ." WHERE f.item_type = ".Follow::ITEM_TYPE_CATEGORY." AND f.item_id = ".$this->id;
//        echo '<br>'.$sql;
        return new CSqlDataProvider($sql);
    }
    
    public function itemInCategoryAdderDataProvider()
    {
        $sql = "SELECT u.id, u.username, u.forename, u.surname, u.email, u.language FROM tbl_user u"
            ." INNER JOIN tbl_item i ON i.user_id = u.id"
            ." LEFT JOIN tbl_category c ON (i.category_id=c.id) "
            ." LEFT JOIN tbl_item_additional_category additionalCategories_additionalCategories ON (i.id=additionalCategories_additionalCategories.id_item)"
            ." LEFT JOIN tbl_category additionalCategories ON (additionalCategories.id=additionalCategories_additionalCategories.id_category)"
            ." LEFT JOIN tbl_item_additional_category additionalForItem_additionalForItem ON (additionalCategories.id=additionalForItem_additionalForItem.id_category)"
            ." LEFT OUTER JOIN tbl_item additionalForItem ON (additionalForItem.id=additionalForItem_additionalForItem.id_item)"    
            ." WHERE (
                (
                    (c.root=:root and c.lft>=:lft and c.rgt<=:rgt)
                    OR
                    (additionalCategories.root IS NOT NULL 
                        and additionalCategories.root=:root 
                        and additionalCategories.lft>=:lft 
                        and additionalCategories.rgt<=:rgt)
                    ) 
                AND i.buy=1 AND i.cache_type='c' AND i.active=1)";
//        echo '<br>'.$sql;
        $params = array(
            ':root' => $this->root,
            ':lft' => $this->lft, 
            ':rgt' => $this->rgt,
        );
        return new CSqlDataProvider($sql,
            array('params' => $params)
        );        
    }
}