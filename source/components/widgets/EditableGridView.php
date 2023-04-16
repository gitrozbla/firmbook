<?php

/**
 * GridView z obsługą EditableField.
 * 
 * Wyświetla tabelę z możliwościa edycji pól.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableGridView extends GridView 
{
    /**
     * Czy pole jest edytowalne.
     * Nadpisuje Editable::$apply jeśli nieustalona.
     * @var boolean
     */
    public $editableApply = null;
    /**
     * Url dla edycji pola.
     * Nadpisuje Editable::url jeśli nieustalona.
     * @see EditableSaver
     * @var string|null
     */
    public $editableUrl = null;
    
    /**
     * Inicjuje widget.
     * Dodaje klasę wygenerowaną na podstawie id obiektu.
     * Potrzebne dla działania edytowalnych pól.
     */
    public function init()
    {
        $expression = '"'.$this->id.'-".$data->id';
        if (isset($this->rowCssClassExpression)) {
            $this->rowCssClassExpression .= '." ".'.$expression;
        } else {
            $this->rowCssClassExpression = $expression;
        }
        
        parent::init();
        
    }
    
    /**
     * Inicjuje kolumnę.
     * Tworzy domyślnie edytowalne pole i przekazuje parametry.
     */
    protected function initColumns()
    {
        $columnsArray = $this->columns;
        
        foreach($this->columns as $i=>$column) {
            if (is_string($column)) {
                $this->columns[$i] = array(
                    'class' => 'EditableColumn',
                    'name' => $column,
                );
            } else if (!isset($column['class'])) {
                $this->columns[$i]['class'] = 'EditableColumn';
            }
        }
        
        parent::initColumns();
        
        // pass editable options
        foreach($this->columns as $i=>$column) {
            if (get_class($column) == 'EditableColumn' 
                    or is_subclass_of($column, 'EditableColumn')) 
            {
                if ($this->editableApply !== null
                        and !isset($column->editable['apply'])) {
                    $column->editable['apply'] = $this->editableApply;
                }
                if ($this->editableUrl !== null
                        and !isset($column->editable['url'])) {
                    $column->editable['url'] = $this->editableUrl;
                }
                /*if (!isset($column->editable['closestSelector'])) {
                    $column->editable['closestSelector'] = '#'.$this->id;
                }*/
            }
        }
    }
    
}
