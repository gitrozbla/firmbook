<?php

/**
 * Edytowalne pole.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableTree extends EditableField 
{
    //public $labelAttribute = 'name';
    
    public $secondModel = null;
    
    public $secondModelAttribute = null;
    
    public $treeMode = 'adjacencyList';
    
    public $emptytext = '...';
    
    public $source = null;
    
    
    /**
     * Nazwa dodatkowego skryptu (dla klas dziedziczących).
     * @var string
     */
    //protected $additionalScript = 'tree';
    
    /**
     * Inicjuje widget edytowalnego pola.
     */
    /*public function init() 
    {
        //$this->type = 'tree';
        //$this->renderField();   // same as non-active
        
        //parent::init();
    }*/
    
    public function run()
    {
        if ($this->apply) {
            // nodes
            $allNodes = $this->getAllNodes();
            
            /*$labelAttribute = $this->labelAttribute;
            $translationCategory = get_class($this->secondModel).'.'.$labelAttribute;
            var_dump($translationCategory);*/
            if (strpos($this->source, '?') === false) {
                $this->source .= '?';
            } else {
                $this->source .= '&';
            }
            
            $secondModelAttribute = $this->secondModelAttribute;
            
            $widgetOptions = array(
                'type' => 'select2',
                'model' => $this->model,
                'attribute' => $this->attribute,                
                'url' => $this->url,
                'emptytext' => $this->emptytext,
                'success' => 'js: function() {reload();}',
            );
            
            $parts = array();
            $parentNodeId = 0;
            foreach($allNodes as $node) {
                $parts []= $this->widget(
                        'EditableSelect3', 
                        array_merge($widgetOptions, array(
                            /*'text' => Yii::t($translationCategory, 
                                    $node->$labelAttribute, null, 'dbMessages'),*/
                            'text' => $node->$secondModelAttribute,
                            'source' => $this->source.'id='.$parentNodeId,
                            )
                        ), 
                        true);
                
                $previousNode = $node;
                $parentNodeId = $node->id;
            }
            
            if (!$previousNode->isLeaf()) {
                $parts []= $this->widget(
                        'EditableSelect3', 
                        array_merge($widgetOptions, array(
                            'text' => Yii::t('editable', 'Select'),
                            'source' => $this->source.'id='.$parentNodeId,
                            )
                        ), 
                        true);
            }

            echo implode(' > ', $parts);
        } else {
            echo $this->renderField();
        }
    }
    
    /**
     * Renderuje zawartość pola zgodnie z zadanymi parametrami.
     * @return string Zawartość pola.
     */
    public function renderField() 
    {
        $allModels = $this->getAllNodes();
        $attribute = $this->secondModelAttribute;
        
        $parts = array();
        foreach ($allModels as $model) {
            $parts []= $model->$attribute;
        }
        
        return implode(' > ', $parts);
    }
    
    public function getAllNodes()
    {
        $model = $this->model;
        $attribute = $this->attribute;
        $model2 = $this->secondModel;
        $node = $model2::model()->findByPk($model->$attribute);
        
        if ($node == null) {
            return $this->emptytext;
        }
        
        $allNodes = $node->ancestors()->findAll();
        $allNodes []= $node;
        
        return $allNodes;
    }
    
}