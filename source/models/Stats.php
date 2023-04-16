<?php

class Stats extends ActiveRecord
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
        return '{{stats}}';
    }
    
    /**
     * Nazwa kolumny głównej.
     * @return string
     */
    public function primaryKey()
    {
        return 'id';    // because db has set clustered index
    }
    
    
    public function afterFind()
    {
        $this->package_owners = CJSON::decode($this->package_owners);
    }
    
    public function beforeSave()
    {
        if (is_array($this->package_owners)) {
            $this->package_owners = CJSON::encode($this->package_owners);
        }
    }
    
    public function findLast()
    {
        $date = date('Y-m-d');
        return $this->find('date=:date', array(':date' => $date));
    }
    
    public function findCurrentPackageAsArray()
    {
        return Yii::app()->db->createCommand()
                ->select('package_id, count(*)')
                ->from('tbl_user')
                ->where('active=1')
                ->group('package_id')
                ->order('package_id ASC')
                ->queryAll();
    }
    
    public function findLastMonthAsArray()
    {
        $monthEalier = date('Y-m-d', strtotime('-30 days'));
        
        $result =  Yii::app()->db->createCommand()
                ->select('date, users, package_owners')
                ->from('tbl_stats')
                ->where('date>:date', array('date'=>$monthEalier))
                ->order('date ASC')
                ->queryAll();
        
        foreach($result as $key=>$resultRow) {
            $result[$key]['package_owners'] 
                    = CJSON::decode($resultRow['package_owners']);
        }
        
        return $result;
    }
    
}