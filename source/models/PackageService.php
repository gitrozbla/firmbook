<?php

class PackageService extends ActiveRecord
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
        return '{{package_service}}';
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
        	//'packages_mn'=>array(self::HAS_MANY,'PackageServiceMN','service_id'),
        	//'packages' => array(self::HAS_MANY, 'Package', array('package_id'=>'id'), 'through'=>'packages_mn'),
        	//'packages' => array(self::MANY_MANY, 'Package', 'tbl_package_service(package_id, service_id'),
           
        );
    }  
    
	public function rules()
    {
        return array(        	
        	array('order_index', 'numerical', 'integerOnly'=>true),
        	array('name, role', 'required'),   
        	array('role', 'unique'),
        	array('description, instruction, value_type, creators, active', 'safe'),     	
        );
    }
    
	/**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(            
            'name' => Yii::t('packages', 'Name'),
        	'order_index' => '#',
            //'order_index' => Yii::t('packages', 'Order'),
        	'description' => Yii::t('packages', 'Description'),
        	'instruction' => Yii::t('packages', 'Instruction'),
        	'value_type' => Yii::t('packages', 'Value'),
        	'role' => Yii::t('packages', 'Role'),
        );
    }
    
	public static function servicesDataProvider($creators=false)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = $creators ? 'creators' : '!creators';	
		$criteria->order = 'order_index';
		
		return new CActiveDataProvider('PackageService', array(
			'criteria' => $criteria,
			/*'criteria'=>array(				
				//'select' => 't.*, p.name as name',
				//'join' => 'INNER JOIN tbl_package p on p.id=package_id',
				//'condition'=>'user_id='.$userId,
			    'order'=>'order_index',
				//'with'=>array('package'),				
			),*/
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
    
	public static function translationDataProvider($object_id)
	{
		$translations = SourceMessage::model()->with('translations')->findAll(
			array(
				'condition'=>'object_id=:object_id and (category="package.service.title" or category="package.service.content")',
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
					if($part->category=='package.service.title')	
						$articles[$message->language]['title'] = $message->translation;     	
					elseif($part->category=='package.service.content')
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
    
    public function afterSave()
    {
   		if ($this->isNewRecord) {
        	$sourceMessage = new SourceMessage;
        	$sourceMessage->category = 'package.service.title';
        	$sourceMessage->object_id = $this->id;
        	$sourceMessage->message = $this->name;
        	//dodanie źródła tłumaczeń tytułu artykułu
        	$sourceMessage->save();
        	unset($sourceMessage);
        	$sourceMessage = new SourceMessage;
        	$sourceMessage->category = 'package.service.content';
        	$sourceMessage->object_id = $this->id;
        	$sourceMessage->message = '{'.$this->name.'}';
        	//$sourceMessage->message = '{description}';
        	//$sourceMessage->message = '{'.$article->alias.'}';
        	//dodanie źródła tłumaczeń treści artykułu
        	$sourceMessage->save();
        } else {
        	$sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>'package.service.title', 'object_id'=>$this->id));
        	if(!$sourceMessage)
        	{
        		//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
        		$sourceMessage = new SourceMessage;
        		$sourceMessage->category = 'package.service.title';
        		$sourceMessage->object_id = $this->id;
        		$sourceMessage->message = $this->name;
        		$sourceMessage->save();
        	} else {
        		//koniecznie pozostawic
        		$sourceMessage->message = $this->name;
        		$sourceMessage->save('message');
        	}	
        	unset($sourceMessage);
        	$sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>'package.service.content', 'object_id'=>$this->id));
        	//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
        	if(!$sourceMessage)
        	{
        		//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
        		$sourceMessage = new SourceMessage;
        		$sourceMessage->category = 'package.service.content';
        		$sourceMessage->object_id = $this->id;
        		$sourceMessage->message = '{'.$this->name.'}';
        		$sourceMessage->save();
        	} else {
        		//koniecznie pozostawic
	        	$sourceMessage->message = '{'.$this->name.'}';
	        	$sourceMessage->save('message');
        	}	
        }
    	return parent::afterSave();
    }
    
    public function beforeDelete()
    {
    	//remove translation
    	$sourceMessages = SourceMessage::model()->findAll(
							'object_id=:object_id and (category=\'package.service.title\' or category=\'package.service.content\')',
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
