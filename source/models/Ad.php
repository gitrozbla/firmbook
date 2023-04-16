<?php
/**
 * Model reklamy.
 * 
 * @category models
 * @package ad
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Ad extends ActiveRecord
{
	public $file_resource;
	
	public static $_type = array(
		'image',
		'youtube'		
	);
	
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
        return '{{ad}}';
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
    		array('group_id, type', 'required', 'on'=>'update, insert'),
    		array('text, text_css, alt, link, enabled, order_id, date_from, date_to, no_limit', 'safe'),
			array('file_resource', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true, 'safe' => false),
			array('resource', 'safe'),
    		//array('group_id, type, resource, text, text_css, alt, link, enabled, order_id, date_from, date_to, no_limit', 'safe'),
    		//array('order_id', 'numerical', 'min'=>1),
    	);
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
    	return array(
    		//'enabled' => Yii::t('ad', 'Enabled'),    		
    	);
    }
    
    public function beforeSave()
    {
		if(empty($this->order_id))
    		$this->order_id = NULL;
    	
    	if (empty($this->date_from))
    		$this->date_from = NULL;
    	 
    	if(!empty($this->date_from))
    		$this->date_from = Yii::app()->dateFormatter->format('yyyy-MM-dd', $this->date_from);
    	
    	if (empty($this->date_to))
    		$this->date_to = NULL;
    	
    	if(!empty($this->date_to))
    		$this->date_to = Yii::app()->dateFormatter->format('yyyy-MM-dd', $this->date_to);
		
		// save filename
		if (!empty($_FILES['Ad']) && 
			!empty($_FILES['Ad']['type']) && 
			!empty($_FILES['Ad']['type']['file_resource'])
		) {
			$this['resource'] = 'banner.jpg?v='.substr(md5(time()), 0, 8);
		}
		
    	return parent::beforeSave();
    }
    
    public function afterSave()
    { 	
		Yii::app()->file->filesPath;    // force set cwd
		$dirPath = getcwd().'/Add/'.$this->id;
		
		if (!is_dir($dirPath)) {
    		mkdir($dirPath, 0755, true);
    	}  
		
		// save file
		if (!empty($_FILES['Ad']) && 
			!empty($_FILES['Ad']['type']) && 
			!empty($_FILES['Ad']['type']['file_resource'])
		) {
			$image = Yii::app()->image->load($_FILES['Ad']['tmp_name']['file_resource']);
			/*$box = AdsBox::model()->find(array('alias' => $this->group_id));
			if ($box) {
				$size = explode('x', $box->size);
				$image->resize($size[0], $size[1]);
			}*/
			$image->save($dirPath.'/banner.jpg', CImageHandler::IMG_JPEG, 90);
		}
    	
    	if ($this->isNewRecord) {
    		$sourceMessage = new SourceMessage;
    		$sourceMessage->category = 'ad';
    		$sourceMessage->object_id = $this->id;
    		$sourceMessage->message = $this->text;
    		//dodanie źródła tłumaczeń tytułu artykułu
    		$sourceMessage->save();
    		unset($sourceMessage);
    		$sourceMessage = new SourceMessage;
    		$sourceMessage->category = 'ad.alt';
    		$sourceMessage->object_id = $this->id;
    		$sourceMessage->message = $this->id;
    		//$sourceMessage->message = '{'.$this->id.'}';
    		//dodanie źródła tłumaczeń treści artykułu
    		$sourceMessage->save();
    	} else {
    		$sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>'ad', 'object_id'=>$this->id));
    		if(!$sourceMessage)
    		{
    			//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
    			$sourceMessage = new SourceMessage;
    			$sourceMessage->category = 'ad';
    			$sourceMessage->object_id = $this->id;
    			$sourceMessage->message = $this->text;
    			$sourceMessage->save();
    		} else {
    			//koniecznie pozostawic
    			$sourceMessage->message = $this->text;
    			$sourceMessage->save('message');
    		}
    		unset($sourceMessage);
    		$sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>'ad.alt', 'object_id'=>$this->id));
    		//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
    		if(!$sourceMessage)
    		{
    			//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
    			$sourceMessage = new SourceMessage;
    			$sourceMessage->category = 'ad.alt';
    			$sourceMessage->object_id = $this->id;
    			$sourceMessage->message = $this->id;
    			//$sourceMessage->message = '{'.$this->id.'}';
    			$sourceMessage->save();
    		} else {
    			//koniecznie pozostawic
    			$sourceMessage->message = $this->id;
    			//$sourceMessage->message = '{'.$this->id.'}';
    			$sourceMessage->save('message');
    		}	
    	}
    	
    	return parent::afterSave();
    }
    
    /*public function afterFind()
    {
    	if(!empty($this->date_from))
    		$this->date_from = Yii::app()->dateFormatter->format('MM/dd/yyyy', $this->date_from);
    	    	
    }*/
    
    public static function adminDataProvider()
    {
    	return new CActiveDataProvider('Ad', array(
    			'pagination' => false,
    	));
    }
    
    public function beforeDelete()
    {    	 
    	//remove translation
    	$sourceMessages = SourceMessage::model()->findAll(
    			'object_id=:object_id and (category=\'ad\' or category=\'ad.alt\')',
    			array(':object_id'=>$this->id)
    	);
    	foreach($sourceMessages as $source)
    	{
    		$messages = Message::model()->findAll('id=:id',	array(':id'=>$source->id));
    		foreach($messages as $message)
    			$message->delete();
    		$source->delete();
    	}
		
		// remove file
		// save file
		Yii::app()->file->filesPath;    // force set cwd
		$dirPath = getcwd().'/Add/'.$this->id;
		if (is_dir($dirPath)) {        	 
			array_map('unlink', glob("$dirPath/*.*"));
			rmdir($dirPath);
		}
		
    	return parent::beforeDelete();
    }
    
    public static function translationDataProvider($object_id)
    {
    	$translations = SourceMessage::model()->with('translations')->findAll(
    			array(
    					'condition'=>'object_id=:object_id and (category="ad" or category="ad.alt")',
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
    				if($part->category=='ad')
    					$articles[$message->language]['title'] = $message->translation;
    				elseif($part->category=='ad.alt')
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