<?php
/**
 * Model podstrony (artykułu).
 * 
 * @category models
 * @package article
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Article extends ActiveRecord
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
        return '{{article}}';
    }
    
    public static function translate($param, $value, $lang)
    {
        if ($param == 'name') {
                $param = 'alias';
        }
        
        return Yii::t('article.'.$param, $value, null, 'dbMessages', $lang);
        
    }
    
	public function rules()
    {
        return array(      	
        	array('alias, title, content', 'required'),
        	array('id, label, visible, creators', 'safe'),
			array('alias', 'unique'),
        	//array('id, label, alias, title, content, visible', 'safe'),
        );
    }
    
	/**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
        	'label' => 'Label',
            'alias' => 'Alias',
        	'title' => 'Title',
        	'content' => 'Content',
        	'visible' => 'Visible'       	    
        );
    }
    
	public static function articlesDataProvider($creators=false)
	{
		return new CActiveDataProvider('Article', array(
			'criteria'=>array(				
				'condition'=>$creators ? 'creators' : '!creators'
			),			
			'pagination' => false,            
        ));
        
    }
	public static function translationDataProvider($object_id)//, $category
	{
		$translations = SourceMessage::model()->with('translations')->findAll(
			array(
				'condition'=>'object_id=:object_id and (category="article.title" or category="article.content")',
				'params'=>array(':object_id'=>$object_id),//, 'category'=>$category
				//'order'=>'purchase.date_added desc',			
			)
		);	
		
		//foreach()
		$articles = array();
		foreach(Yii::app()->params->languages as $lang)
		{
			$article = array();
			foreach($translations as $part) {			
				foreach($part->translations as $message) {
					if(!array_key_exists($message->language, $articles)) {
						$articles[$message->language] = array('object_id'=>$object_id,'language'=>$message->language);
						
					}	
						
					if($part->category=='article.title')	
						$articles[$message->language]['title'] = $message->translation;     	
					elseif($part->category=='article.content')
						$articles[$message->language]['content'] = $message->translation;						
				}
				/*if($part->category=='article.title')
					$article['title'] =
				elseif($part->category=='article.content'
					$article['content'] =*/
					
				//$article['lang'] = $lang;
			} 
			
		}
		
		//print_r($articles);
		$articles = array_values($articles);
		//print_r($articles);
		
		
		$dataProvider = new CArrayDataProvider($articles, array(
		    //'id'=>$object_id,
			'keyField'=>'language',
		    /*'sort'=>array(
		        'attributes'=>array(
		             'id', 'username', 'email',
		        ),
		    ),*/
		    'pagination'=>array(
		        'pageSize'=>10,
		    ),
		));
		
		return $dataProvider;
		/*return new CActiveDataProvider('SourceMessage', array(
			'criteria'=>array(				
							
			),			
			'pagination' => false,            
        ));*/
        
    }
    
    public function afterSave()
    {
    	if ($this->isNewRecord) {
    		$sourceMessage = new SourceMessage;
    		$sourceMessage->category = 'article.title';
    		$sourceMessage->object_id = $this->id;
    		$sourceMessage->message = $this->title;
    		//dodanie źródła tłumaczeń tytułu artykułu
    		$sourceMessage->save();
    		unset($sourceMessage);
    		$sourceMessage = new SourceMessage;
    		$sourceMessage->category = 'article.content';
    		$sourceMessage->object_id = $this->id;
    		$sourceMessage->message = '{'.$this->alias.'}';
    		//dodanie źródła tłumaczeń treści artykułu
    		$sourceMessage->save();
    	} else {
    		$sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>'article.title', 'object_id'=>$this->id));
			if ($sourceMessage) {
    			$sourceMessage->message = $this->title;
    			$sourceMessage->save('message');
    		}
    		$sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>'article.content', 'object_id'=>$this->id));
			if ($sourceMessage) {
    			$sourceMessage->message = '{'.$this->alias.'}';
    			$sourceMessage->save('message');
			}
    	}
    	return parent::afterSave();
    }
    
	public function beforeDelete()
    {
    	//remove translation
    	$sourceMessages = SourceMessage::model()->findAll(
							'object_id=:object_id and (category=\'article.title\' or category=\'article.content\')',
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
}