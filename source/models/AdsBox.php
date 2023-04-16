<?php
/**
 * Model boxu reklamowego.
 * 
 * @category models
 * @package ad
 * @author 
 * @copyright (C) 
 */
class AdsBox extends ActiveRecord
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
        return '{{ad_box}}';
    }
    
    /**
     * Nazwa kolumny głównej.
     * @return string
     */
    public function primaryKey()
    {
        return 'id';    // because db has set clustered index
    }
    
    public function rules()
    {
    	return array(    	
    		array('alias, label, name, period, price', 'required', 'on'=>'update, insert'),
    		array('size, height, description, carousel', 'safe')    		
    	);
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
    	return array(
    		'label' => Yii::t('ad', 'Label'),    
    	);
    }
    
    public static function adminDataProvider()
    {    	    	 
    	return new CActiveDataProvider('AdsBox', array(    			
    			'pagination' => false,
    	));    
    }
    
    public static function offerDataProvider()
    {
    	return new CActiveDataProvider('AdsBox', array(
    			'pagination' => false,
    	));
    }
    
    
    public static function boxesToSelect()
    {
    	$boxes = self::model()->findAll();
    	
    	$boxesToSelect = array();
    	foreach($boxes as $box)    		
    		$boxesToSelect[$box['alias']] = $box['label'];
    	
    	return $boxesToSelect;
    }
    
    public function afterSave()
    {    	
    	 
    	if ($this->isNewRecord) {
    		$sourceMessage = new SourceMessage;
    		$sourceMessage->category = 'adsbox.name';
    		$sourceMessage->object_id = $this->id;
    		$sourceMessage->message = $this->name;
    		//dodanie źródła tłumaczeń tytułu artykułu
    		$sourceMessage->save();
    		unset($sourceMessage);
    		$sourceMessage = new SourceMessage;
    		$sourceMessage->category = 'adsbox.description';
    		$sourceMessage->object_id = $this->id;
    		//$sourceMessage->message = $this->description;
    		$sourceMessage->message = '{'.$this->alias.'}';
    		//dodanie źródła tłumaczeń treści artykułu
    		$sourceMessage->save();
    	} else {
    		$sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>'adsbox.name', 'object_id'=>$this->id));
    		if(!$sourceMessage)
    		{
    			//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
    			$sourceMessage = new SourceMessage;
    			$sourceMessage->category = 'adsbox.name';
    			$sourceMessage->object_id = $this->id;
    			$sourceMessage->message = $this->name;
    			$sourceMessage->save();
    		} else {
    			//koniecznie pozostawic
    			$sourceMessage->message = $this->name;
    			$sourceMessage->save('message');
    		}
    		unset($sourceMessage);
    		$sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>'adsbox.description', 'object_id'=>$this->id));
    		//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
    		if(!$sourceMessage)
    		{
    			//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
    			$sourceMessage = new SourceMessage;
    			$sourceMessage->category = 'adsbox.description';
    			$sourceMessage->object_id = $this->id;
    			$sourceMessage->message = '{'.$this->alias.'}';
    			//$sourceMessage->message = $this->description;    			
    			$sourceMessage->save();
    		} else {
    			//koniecznie pozostawic
    			$sourceMessage->message = '{'.$this->alias.'}';
    			//$sourceMessage->message = $this->description;
    			$sourceMessage->save('message');
    		}
    	}
    	 
    	return parent::afterSave();
    }
    
    public function beforeDelete()
    {
    	//remove translation
    	$sourceMessages = SourceMessage::model()->findAll(
    			'object_id=:object_id and (category=\'adsbox.name\' or category=\'adsbox.description\')',
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
    					'condition'=>'object_id=:object_id and (category="adsbox.name" or category="adsbox.description")',
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
    				if($part->category=='adsbox.name')
    					$articles[$message->language]['title'] = $message->translation;
    				elseif($part->category=='adsbox.description')
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
    
}