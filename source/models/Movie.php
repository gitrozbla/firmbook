<?php
/**
 * Model filmu.
 * 
 * @category models
 * @package news
 * @author
 * @copyright (C) 2015
 */
class Movie extends ActiveRecord
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
        return '{{movie}}';
    }
        
    /**
     * Nazwa kolumny głównej.
     * @return string
     */
    public function primaryKey()
    {
    	return 'id';    // because db has set clustered index
    }
	
	
	public static $youtubeLinkPattern = '/^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/';
	
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
    	return array(
    			'item' => array(self::BELONGS_TO, 'Item', 'item_id', 'together'=>true),
    			'user' => array(self::BELONGS_TO, 'User', 'user_id', 'together'=>true),
    			//'user' => array(self::HAS_ONE, 'User', 'user_id', 'together'=>true)    			    			
    	);
    }
    
	public function rules()
    {
        return array(
        	array('url', 'required', 'on'=>'update, create'),
        	array('url', 'length', 'is'=>11, 'on'=>'update, create'),
        	array('title', 'length', 'max'=>500, 'on'=>'update, create'),
        	array('description', 'length', 'max'=>500, 'on'=>'update, create'),    
			array('youtube_link', 'match',
                'pattern'=>self::$youtubeLinkPattern,
                'message'=>Yii::t('movies', 'This link is incorrect or not supported. Please try using different format.')),			
        );
    }
    
	/**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
        	'title' => Yii::t('common', 'Title'),
            'description' => Yii::t('movies', 'Description'),    	
			'url' => Yii::t('movies', 'Youtube ID'),
			'youtube_link' => Yii::t('movies', 'Link to Youtube video'),
        	//'date' => Yii::t('common', 'Date')
        );
    }
	
	
	public function getYoutube_link() {
        return /*!empty($this->youtube_link)
            ? $this->youtube_link
            : */!empty($this->url)
                ? 'http://www.youtube.com/watch?v=' . $this->url
                : '';
    }

    public function setYoutube_link($value) {
        // extract Youtube id from link
        if ($value !== null) {
            if (empty($value)) $this->url = null;
            else {
                preg_match(self::$youtubeLinkPattern, $value, $matches);
                if (isset($matches[2]) && strlen($matches[2]) == 11) {
                    $this->url = $matches[2];
                }
            }
        }
    }
	
    
    public function beforeSave()
    {
    	if ($this->isNewRecord) {
    		if (empty($this->user_id)) {
    			$user = Yii::app()->user;
    			if ($user->isGuest) {
    				return false;
    			}
    			 
    			$this->user_id = $user->id;
    		}
    	}
    	 
    	return parent::beforeSave();
    }
    
    public static function moviesDataProvider($item_id)
    {
    	$criteria = new CDbCriteria;
    	$criteria->condition = "item_id = ".$item_id;
    
    	/*if (!Yii::app()->user->isGuest) {
    		$criteria->condition .= ' and (active=1 or user_id=:user_id)';
    		$criteria->params = array(':user_id'=>Yii::app()->user->id);
    	} else {
    		$criteria->condition .= ' and active=1';
    	}*/
    
    	$sort = new CSort;
    	$sort->defaultOrder = 'date DESC';
    
    	return new CActiveDataProvider('Movie', array(
    			'criteria'=>$criteria,
    			'sort'=>$sort,
    			'pagination' => array(
    					'class' => 'Pagination',
    					'pageSize' => 20,
    			),
    	));
    }
}