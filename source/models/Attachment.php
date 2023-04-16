<?php
/**
 * Model załączonego pliku.
 * 
 * @category models
 * @package user
 */
class Attachment extends ActiveRecord
{
    public $file;
    
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
        return '{{attachment}}';
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
     * @return array
     */
    public function relations()
    {
        return array(
            'item' => array(self::BELONGS_TO, 'Item', 'item_id')            
        );
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels()
    {
        return array(
        	'file' => Yii::t('attachment', 'File'),
        	'anchor' => Yii::t('attachment', 'Name'),
        	'description' => Yii::t('attachment', 'Description'),
            //'filename' => Yii::t('attachment', 'Name'),
            'date' => Yii::t('attachment', 'Added'),
        );
    }
    
    /**
     * Lista reguł walidacji.
     * @return array
     */
    public function rules()
    {
    	return array(
    		array('anchor, description', 'safe'), //, orginal_name
    		array('file', 'userFileValidator', 'maxSize'=>5000000,
    				'safe'=>true, 'allowEmpty'=>false, 'on'=>'create'),//, 'types'=>'jpg, jpeg, gif, png', 'tooLarge'=>'Zbyt duzy plik'    			
    		
    		
    			
    	);
    }
    
    public function beforeSave()
    {
    	if ($this->isNewRecord) {
	    	// save file    	
	    	$uploadedFileObject = $this->file;
	    	$fileUploaded = !is_string($uploadedFileObject) && (
	    			get_class($uploadedFileObject) == 'CUploadedFile'
	    			|| is_subclass_of($uploadedFileObject, 'CUploadedFile')
	    		) && $uploadedFileObject->getName() != null;
	        	    	
	    	if ($fileUploaded) {
	    		$filename = Func::randomString(16).'_file.'.$uploadedFileObject->getExtensionName();
	    		Yii::app()->file->createDir(0755, 'Attachment/'.$this->item_id);
	    		$uploadedFileObject->saveAs('Attachment/'.$this->item_id.'/'.$filename);
	    		$this->filename = $filename;
	    		//$this->$attribute = $filename;
	    	}    	
	    
	    	$this->orginal_name = $this->file->name;
    	}
    	
    	// file name if not set
    	if(!$this->anchor)
    		$this->anchor = $this->orginal_name;    		
    		
    	return parent::beforeSave();
    }
    
    public function beforeDelete()
    {
    	$file = Yii::app()->file;
    	$filepath = $this->getFilePath();
    	$fileObject = $file->set($filepath);
    	if ($fileObject->exists) {
    		$fileObject->delete();
    	}
    
    	return true;
    }
    
    public function getFilePath()
    {
    	return  get_class($this).'/'.
    			$this->item_id.'/'.
    			$this->filename;    			
    }
    
    /**
     * Generuje i zwraca url do pliku.
     * @return string Url do pliku.
     */
    public function generateUrl()
    {
    	if (!empty($this->filename)) {
    		return (Yii::app()->file->filesPath).'/'.
    				'Attachment/'.
    				$this->item_id.'/'.
    				$this->filename;    			
    	} else {
    		return null;
    	}
    }
    
    public function dataProvider($itemId)
    {
    	return new ActiveDataProvider('Attachment', array(
    		'criteria' => array(
    			'condition' => 't.item_id='.(int)$itemId
    		),
    		'sort'=>array(
    			'defaultOrder' => 't.date DESC',
    		)
    	));
    }
    
    public function fileSize() 
    {
    	return filesize($this->getFilePath());
    }
    
    public function formatedFileSize() {
    	$size = $this->fileSize();
    	
    	$sizes = array(" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
    	if ($size == 0) { return('n/a'); } else {
    		return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]); }
    }
}
