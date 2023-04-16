<?php

/**
 * Klasa powiadomienia o zdarzeniu dla obserwowanych obiektów
 * @author inter
 *
 */

class Alert extends ActiveRecord
{   
	// typ śledzonego obiektu
	const CONTEXT_TYPE_COMPANY = 1;
	const CONTEXT_TYPE_USER = 2;
	const CONTEXT_TYPE_CATEGORY = 3;
	
	// typ obiektu, którego dotyczy akcja w kontekście śledzonego obiektu.
//	const ITEM_TYPE_COMPANY = 1;
//	const ITEM_TYPE_PRODUCT = 2;
//	const ITEM_TYPE_SERVICE = 3;
    
    const ITEM_TYPE_ITEM = 1;	
    const ITEM_TYPE_USER = 2;
	
	// czas wyświetlania alertu w dniach
	const EXPIRE_AFTER = 30; 
	
    
//    const EVENT = [
//        'add' => 'add',
//        'like' => 'like',
//        'follow' => 'follow'
//    ];
    const EVENT_ADD = 'add';
    const EVENT_LIKE = 'like';
    
	/*
	 * public event: add - nowy element w kontekście obiektu
	 */
    
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
        return '{{alert}}';
    }
    
	/**
     * Nazwa kolumny głównej.
     * @return string
     */
	public function primaryKey()
    {
    	return 'id';        
    }
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
        return array(
        	'user' => array(self::BELONGS_TO, 'User', 'user_id')        	
        );
    }
    
    public function rules()
    {
        return array(        	
        	array('user_id, context_id, context_type, item_id, item_type, event, message', 'safe')
        );
    }   
    
//    public function afterFind() {
//        $this->displayed = 1;
//        $this->save();
//        parent::afterFind();
//    }
        
    public function alertsDataProviderUnion()
    {
    	// alerty dla obserwowanej firmy to dodanie nowego produktu lub usługi
    	//$dateTo = date('Y-m-d', strtotime('-1 months'));
    	$dateTo = date('Y-m-d', strtotime('-'.self::EXPIRE_AFTER.' days'));
    	$sql = '(SELECT a.id, a.context_id, a.context_type'.
      		', a.item_id, a.item_type, a.event, a.date'.
      		', i.name as context_name, i.alias as context_alias'.
      		', ii.name as item_name, ii.alias as item_alias, ii.cache_type as item_cache_type'.
    		' FROM tbl_alert a'.
    		' inner join tbl_item i on i.id=a.context_id'.    		
	    	' left join tbl_item ii on ii.id=a.item_id'.
	    	' where a.user_id='.$this->user_id.' and a.context_type='.self::CONTEXT_TYPE_COMPANY.' and a.date > \''.$dateTo.'\''.
	    	' and a.item_type='.self::ITEM_TYPE_ITEM.')'.
	    	' UNION ALL'.
	    	// alerty dla obserwowanego użytkownika to dodanie nowej firmy, produktu lub udługi
	    	' (SELECT a.id, a.context_id, a.context_type'.
	    	', a.item_id, a.item_type, a.event, a.date'.
	    	', u.username as context_name, \'\' as context_alias'.
	    	', ii.name as item_name, ii.alias as item_alias, ii.cache_type as item_cache_type'.
	    	' FROM tbl_alert a'.
	    	' inner join tbl_user u on u.id=a.context_id'.	    	
	    	' left join tbl_item ii on ii.id=a.item_id'.
	    	' where a.user_id='.$this->user_id.' and a.context_type='.self::CONTEXT_TYPE_USER.' and a.date > \''.$dateTo.'\''.
	    	' and a.item_type='.self::ITEM_TYPE_ITEM.')'.
	    	' UNION ALL'.
    		// alerty dla obserwowanej kategorii to dodanie nowej firmy, produktu lub udługi
	    	' (SELECT a.id, a.context_id, a.context_type'.
	    	', a.item_id, a.item_type, a.event, a.date'.
	    	', c.name as context_name, c.alias as context_alias'.
	    	', ii.name as item_name, ii.alias as item_alias, ii.cache_type as item_cache_type'.
	    	' FROM tbl_alert a'.
	    	' inner join tbl_category c on c.id=a.context_id'.	    	
	    	' left join tbl_item ii on ii.id=a.item_id'.
	    	' where a.user_id='.$this->user_id.' and a.context_type='.self::CONTEXT_TYPE_CATEGORY.' and a.date > \''.$dateTo.'\''.
            ' and a.item_type='.self::ITEM_TYPE_ITEM.')'.
            ' UNION ALL'.	
            ' (SELECT a.id, a.context_id, a.context_type'.
	    	', a.item_id, a.item_type, a.event, a.date'.
	    	', u.username as context_name, \'\' as context_alias'.
	    	', ii.username as item_name, \'\' as item_alias, \'u\' as item_cache_type'.
	    	' FROM tbl_alert a'.
	    	' inner join tbl_user u on u.id=a.context_id'.	    	
	    	' left join tbl_user ii on ii.id=a.item_id'.
	    	' where a.user_id='.$this->user_id.' and a.context_type='.self::CONTEXT_TYPE_USER.' and a.date>\''.$dateTo.'\''.
//            ' and a.event=\'like\' and a.item_type='.self::ITEM_TYPE_USER.')'        
    	    ' and a.item_type='.self::ITEM_TYPE_USER.')';        	

    	$count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM ('.$sql.') AS count_alias')->queryScalar();
    	
    	// Create a custom sort
    	$sort=new CSort;    	
    	$sort->defaultOrder = 'date DESC';
    	
		return new CSqlDataProvider($sql, array(
		    'totalItemCount'=>$count,
		    'sort'=> $sort,		    
		    /*'pagination'=>array(
		        'pageSize'=>40,
		    ),*/
		));    
    }
    
    public function alertsDataProviderUnion_org()
    {
    	// alerty dla obserwowanej firmy to dodanie nowego produktu lub usługi
    	//$dateTo = date('Y-m-d', strtotime('-1 months'));
    	$dateTo = date('Y-m-d', strtotime('-'.self::EXPIRE_AFTER.' days'));
    	$sql = '(SELECT a.id, a.context_id, a.context_type'.
      		', a.item_id, a.item_type, a.event, a.date'.
      		', i.name as context_name, i.alias as context_alias'.
      		', ii.name as item_name, ii.alias as item_alias, ii.cache_type as item_cache_type'.
    		' FROM tbl_alert a'.
    		' inner join tbl_item i on i.id=a.context_id'.    		
	    	' left join tbl_item ii on ii.id=a.item_id'.
	    	' where a.user_id='.$this->user_id.' and a.context_type='.self::CONTEXT_TYPE_COMPANY.' and a.date>\''.$dateTo.'\')'.
	    	
	    	' UNION ALL'.
	    	// alerty dla obserwowanego użytkownika to dodanie nowej firmy, produktu lub udługi
	    	' (SELECT a.id, a.context_id, a.context_type'.
	    	', a.item_id, a.item_type, a.event, a.date'.
	    	', u.username as context_name, \'\' as context_alias'.
	    	', ii.name as item_name, ii.alias as item_alias, ii.cache_type as item_cache_type'.
	    	' FROM tbl_alert a'.
	    	' inner join tbl_user u on u.id=a.context_id'.	    	
	    	' left join tbl_item ii on ii.id=a.item_id'.
	    	' where a.user_id='.$this->user_id.' and a.context_type='.self::CONTEXT_TYPE_USER.' and a.date>\''.$dateTo.'\''.
	    	' and a.event=\'add\')'.
	    	' UNION ALL'.
    		// alerty dla obserwowanej kategorii to dodanie nowej firmy, produktu lub udługi
	    	' (SELECT a.id, a.context_id, a.context_type'.
	    	', a.item_id, a.item_type, a.event, a.date'.
	    	', c.name as context_name, c.alias as context_alias'.
	    	', ii.name as item_name, ii.alias as item_alias, ii.cache_type as item_cache_type'.
	    	' FROM tbl_alert a'.
	    	' inner join tbl_category c on c.id=a.context_id'.	    	
	    	' left join tbl_item ii on ii.id=a.item_id'.
	    	' where a.user_id='.$this->user_id.' and a.context_type='.self::CONTEXT_TYPE_CATEGORY.' and a.date>\''.$dateTo.'\')';
        
            ' UNION ALL'.
	    	// alerty o polubieniu uzytkownika przez innego uzytkownika
	    	' (SELECT a.id, a.context_id, a.context_type'.
	    	', a.item_id, a.item_type, a.event, a.date'.
	    	', u.username as context_name, \'\' as context_alias'.
	    	', ii.username as item_name, \'\' as item_alias, \'\' as item_cache_type'.
	    	' FROM tbl_alert a'.
	    	' inner join tbl_user u on u.id=a.context_id'.	    	
	    	' left join tbl_user ii on ii.id=a.item_id'.
	    	' where a.user_id='.$this->user_id.' and a.context_type='.self::CONTEXT_TYPE_USER.' and a.date>\''.$dateTo.'\''.
            ' and a.event=\'like\')';        
    	    	    	
    	$count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM ('.$sql.') AS count_alias')->queryScalar();
    	
    	// Create a custom sort
    	$sort=new CSort;    	
    	$sort->defaultOrder = 'date DESC';
    	
		return new CSqlDataProvider($sql, array(
		    'totalItemCount'=>$count,
		    'sort'=> $sort,		    
		    /*'pagination'=>array(
		        'pageSize'=>40,
		    ),*/
		));    
    }
    
    public static function getMessage($data) 
    {
        $className = 'Alert'.ucfirst($data['event']);
        $msg = $className::getMessage($data);
    	return $msg;
    }
    
    public static function getMessage_old($data) 
    {
    	$msg = '';
    	switch($data['item_type']) {
    		case Alert::ITEM_TYPE_COMPANY:
    			$msg .= Yii::t('alerts', 'New company').' ';
    			$msg .= CHtml::link($data['item_name'], Yii::app()->createUrl("companies/show",
    					array("name"=>$data['item_alias'])));
    			break;
    		case Alert::ITEM_TYPE_PRODUCT:
    			$msg .= Yii::t('alerts', 'New product').' ';
    			$msg .= CHtml::link($data['item_name'], Yii::app()->createUrl("products/show",
    					array("name"=>$data['item_alias'])));
    			break;
    		case Alert::ITEM_TYPE_SERVICE:
    			$msg .= Yii::t('alerts', 'New service').' ';
    			$msg .= CHtml::link($data['item_name'], Yii::app()->createUrl("services/show",
    					array("name"=>$data['item_alias'])));
    			break;
    		default:
    			$msg .= $data['item_name'];
    	}
    	switch($data['context_type']) {
    		case Alert::CONTEXT_TYPE_COMPANY:
    			$msg .= ', '.Yii::t('alerts', 'company').' ';
    			$msg .= CHtml::link($data['context_name'], Yii::app()->createUrl("companies/show",
    					array("name"=>$data['context_alias'])));
    			break;
    		case Alert::CONTEXT_TYPE_USER:
    			$msg .= ', '.Yii::t('alerts', 'user').' ';
    			$msg .= CHtml::link($data['context_name'], Yii::app()->createUrl("user/profile",
    					array("username"=>$data['context_name'])));
    			break;
    		case Alert::CONTEXT_TYPE_CATEGORY:
    			$msg .= ', '.Yii::t('alerts', 'category').' ';
    			
    			$alias = $data['context_alias']
    			? Yii::t('category.alias', $data['context_alias'], null, 'dbMessages')
    			: $data['context_id'];    			
    			
    			$search = Search::model()->getFromSession();
    			$msg .= CHtml::link(
//     					Yii::t('category.name', $data['context_name'], null, 'dbMessages'),
    					Yii::t('category.name', $data['context_alias'], array($data['context_alias']=>$data['context_name']), 'dbMessages'),
//     					Yii::app()->createUrl('categories/show', array('name'=>$alias)));
    					$search->createUrl('categories/show', array('name'=>$alias)));
    			
    			break;
    		default:	
    			$msg .= $data['context_name'];
    	}   	
    	
    	$msg .= '<br />'.Yii::app()->dateFormatter->format("yyyy-MM-dd", $data['date']);    	
    	return $msg;
    }
}
?>