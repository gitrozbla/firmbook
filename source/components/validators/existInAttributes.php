<?php
/**
 * Walidator potwierdzający istnieje wartości w kilku kolumnach.
 * 
 * @category components
 * @package components\validators
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class existInAttributes extends CExistValidator
{
    /**
     * Operator przy szukaniu (and/or).
     * @var string
     */
    public $operator = 'or';
    /**
     * Attributes to match.
     * Array or string with attributes, separeted by ','.
     * @var array|string
     */
    public $inAttributes = array();

    /**
     * Weryfikacja atrybutu po stronie serwera
     * W przypadku nie przejścia testu dodaje błąd.
     * @see CValidator::addError()
     * @param object $object Instancja zawierająca sprawdzany atrybut.
     * @param string $attribute Nazwa atrybutu.
     */
    protected function validateAttribute($object,$attribute)
    {
        $value=$object->$attribute;
        if($this->allowEmpty && $this->isEmpty($value))
            return;

        if(is_array($value))
        {
            // https://github.com/yiisoft/yii/issues/1955
            $this->addError($object,$attribute,Yii::t('yii','{attribute} is invalid.'));
            return;
        }
        
        if (is_string($this->inAttributes)) {
            $this->inAttributes = explode(',', $this->inAttributes);
            foreach($this->inAttributes as $itemKey=>$itemValue) {
                $this->inAttributes[$itemKey] = trim($itemValue);
            }
        }

        // find column names
        $className=$this->className===null?get_class($object):Yii::import($this->className);
        $finder=$this->getModel($className);
        $table=$finder->getTableSchema();
        $columnNames = array();
        foreach ($this->inAttributes as $attributeName) {
            if(($column=$table->getColumn($attributeName))===null)
                throw new CException(Yii::t('yii','Table "{table}" does not have a column named "{column}".',
                    array('{column}'=>$attributeName,'{table}'=>$table->name)));
            $columnNames []= $column->rawName;
        }
        
        // additiona criteria
        $criteria=new CDbCriteria();
        if($this->criteria!==array())
            $criteria->mergeWith($this->criteria);
        $tableAlias = empty($criteria->alias) ? $finder->getTableAlias(true) : $criteria->alias;
        
        // general search criteria
        $criteriaArray = array();
        foreach($columnNames as $columnName) {
            $valueParamName = CDbCriteria::PARAM_PREFIX.CDbCriteria::$paramCount++;
            $criteriaArray []= $this->caseSensitive ? "{$tableAlias}.{$columnName}={$valueParamName}" : "LOWER({$tableAlias}.{$columnName})=LOWER({$valueParamName})";
            $criteria->params[$valueParamName] = $value;
        }
        $criteria->addCondition('('.implode(' '.$this->operator.' ', $criteriaArray).')');
            
        if(!$finder->exists($criteria))
        {
            $message=$this->message!==null?$this->message:Yii::t('yii','{attribute} "{value}" is invalid.');
            $this->addError($object,$attribute,$message,array('{value}'=>CHtml::encode($value)));
        }
    }
}
