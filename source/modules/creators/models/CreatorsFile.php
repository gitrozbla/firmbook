<?php
/**
 * Model pliku wygenerowanej spakowanej strony.
 * 
 * @category models
 * @package user
 */
class CreatorsFile extends ActiveRecord
{
    public $fileToSave = null;
    
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
        return '{{creators_file}}';
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
            'website' => array(self::BELONGS_TO, 'CreatorsWebsite', 'company_id'),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
        );
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'filename' => Yii::t('CreatorsModule.file', 'Filename'),
            'generated' => Yii::t('CreatorsModule.file', 'Generated'),
        );
    }
    
    /**
     * Generuje i zwraca url do pliku.
     * @return string Url do pliku.
     */
    public function generateUrl()
    {
        if (!empty($this->filename)) {
            return (Yii::app()->file->filesPath).'/'.
                'CreatorsFile/'.
                $this->company_id.'/'.
                $this->filename.
                '.zip';
        } else {
            return null;
        }
    }
    
    public function beforeSave()
    {
        if (!$this->isNewRecord) {
            // any existing file cannot be updated
            return false;
        }
        
        return true;
        
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
                $this->company_id.'/'.
                $this->filename.
                '.zip';
    }
    
    public function dataProvider($companyId)
    {
        return new ActiveDataProvider('CreatorsFile', array(
            'criteria' => array(
                'condition' => 't.company_id='.(int)$companyId
            ),
            'sort'=>array(
                'defaultOrder' => 't.generated DESC',
            )
        ));
    }
    
}
