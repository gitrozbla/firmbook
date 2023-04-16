<?php

Yii::import('bootstrap.widgets.TbActiveForm');

/**
 * Komponent formularza na stronie.
 * 
 * Dzięki wraperowi klasy pochodzącej z Yii-Booster nie jest
 * potrzebne Yii::import przy każdym użyciu.
 * @see http://yiibooster.clevertech.biz/
 * @see Yii::import
 * 
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class ActiveForm extends TbActiveForm
{
    /**
     * Drzewo pól wyboru.
     * @param type $model
     * @param type $attribute
     * @param array $widgetOptions
     * @throws Exception
     */
    public function select2Tree($model, $attribute, $widgetOptions=array()) {
        
        if (isset($widgetOptions['attributeIndex'])) {
            $attributeIndex = $widgetOptions['attributeIndex'];
        }
        $className = get_class($model);
        if (isset($attributeIndex)) {
            $wrapperId = $className.'-'.$attribute.'-'.$attributeIndex;
        } else {
            $wrapperId = $className.'-'.$attribute;
        }
        
        // build select2 options
        $select2Options = array(
            'form' => $this,
            'asDropDownList' => false,
            'options' => array(
                'placeholder' => ''
            )
        );
        if (isset($widgetOptions['url'])) {
            $mode = 'ajax';
            $select2Options['data'] = null;
            $select2Options['options']['delay'] = 500;
            $select2Options['options']['ajax'] = array(
                'url' => $widgetOptions['url'],
                'dataType' => 'json',
                'results' => 'js: function(data, page) {
                    return {results: data};
                }',
            );
        } else {
            throw new Exception('Mode not supported');
        }
        
        $labelAttribute = $widgetOptions['labelAttribute'];
        
        // zbieranie danych
        $data = array();
        if ($widgetOptions['tree'] === null) {
            $widgetOptions['tree'] = array();
        }
        foreach($widgetOptions['tree'] as $node) {
            $nodeId = $node->getPrimaryKey();
            if (is_array($nodeId)) {
                $nodeId = implode('-', $nodeId);
            }
            $data[$nodeId] = $node->$labelAttribute;
        }
		
        $nodesCount = count($widgetOptions['tree']);

        // puste pole na końcu
        if (!isset($widgetOptions['limit']) || $nodesCount < $widgetOptions['limit']) {
            $data[0] = null;
        }
        
        // generowanie drzewa
        $parentId = 0;
        $parts = array();
        $count = 0;
        foreach($data as $id=>$label) {
            // id
            if (isset($attributeIndex)) {
                $inputId = $className.'-'.$attribute.'-'.$attributeIndex.'-'.$count;
                $name = $className.'['.$attribute.'_array]['.$attributeIndex.']['.$count.']';
            } else {
                $inputId = $className.'-'.$attribute.'-'.$count;
                $name = $className.'['.$attribute.'_array]['.$count.']';
            }
            if (!isset($select2Options['htmlOptions'])) {
                $select2Options['htmlOptions'] = array();
            }
            $select2Options['id'] = $select2Options['htmlOptions']['id'] = $inputId;
            $select2Options['name'] = $name;
            // label
            $select2Options['options']['placeholder'] = 
                    ($count == 0 && isset($widgetOptions['rootEmptyText']))
                    ? $widgetOptions['rootEmptyText'] 
                    : $widgetOptions['emptyText'];
            
            switch($mode) {
                case 'ajax':
                    $select2Options['data'] = array($id => $label);
                    $select2Options['options']['data'] = array($id => $label);
                    $select2Options['val'] = $id;
                    $select2Options['options']['ajax']['data'] = 'js: function(query) {
                        return {
                            id: '.$parentId.',
                            query: query
                        }
                    }';
                    $select2Options['options']['initSelection'] = 'js: function(element, callback) {
                        callback({id: '.$id.', text: "'.$label.'"});
                    }';
                    break;
            }
            // generate
            $parts []= '<span class="select2-tree-input">'.
                $this->widget(
                        'bootstrap.widgets.TbSelect2', 
                        $select2Options,
                        true).
                '</span>';
            
            $parentId = $id;
            $count++;
        }

        echo '<div id="'.$wrapperId.'" class="select2-tree-wrapper">';
        echo implode('<span class="tree-right"> / </span>', $parts);
        
        if (isset($attributeIndex)) {
            $name = $className.'['.$attribute.']['.$attributeIndex.']';
            $valueArray = $model->$attribute;
            if (count($valueArray) > $attributeIndex) {
                $value = $valueArray[$attributeIndex]->getPrimaryKey();
            } else {
                $value = null;
            }
        } else {
            $name = $className.'['.$attribute.']';
            $value = $model->$attribute;
        }
        echo '<input type="hidden" class="select2-tree-main-input" 
                name="'.$name.'" 
                value="'.$value.'" />';
        
        echo '</div>';
        
        // register script
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile('js/select2-tree.js',
            ClientScript::POS_HEAD);    // fix
        if (isset($attributeIndex)) {
            $idPrefix = $className.'-'.$attribute.'-'.$attributeIndex;
            $namePrefix = $className.'['.$attribute.']['.$attributeIndex.']';
        } else {
            $idPrefix = $className.'-'.$attribute;
            $namePrefix = $className.'['.$attribute.']';
        }
        $cs->registerScript(
                'select2-tree-'.$wrapperId, 
                '$("#'.$wrapperId.'").select2Tree({
                        idPrefix: "'.$idPrefix.'",
						limit: "'.(isset($widgetOptions['limit']) ? $widgetOptions['limit'] : null).'",
                        namePrefix: "'.$namePrefix.'",
                        placeholder: "'.$widgetOptions['emptyText'].'",
                        url: "'.$widgetOptions['url'].'"
                    });');
    }
    
    public function select2TreeRow($model, $attribute, $widgetOptions=array(), $rowOptions=array()) {
    
        $this->initRowOptions($rowOptions);
        
        $fieldData = array(array($this, 'select2Tree'), array($model, $attribute, $widgetOptions));
        
        return $this->customFieldRowInternal($fieldData, $model, $attribute, $rowOptions);
    }
    
    
    /**
     * Lista drzew pól wyboru.
     * @param type $model
     * @param type $attribute
     * @param type $widgetOptions
     */
    public function select2Trees($model, $attribute, $widgetOptions=array()) {
        
        $className = get_class($model);
        $wrapperId = $className.'-'.$attribute;
        
        echo '<div id="'.$wrapperId.'" class="select2-trees-wrapper">';
        
        if (!isset($widgetOptions['limit'])) {
            $widgetOptions['limit'] = 99;
        }
        
        // zbieranie danych
        $data = array();
        foreach($widgetOptions['trees'] as $tree) {
            
            $data []= $tree;
        }
        $data []= null;
        
        // generowanie
        $treeOptions = $widgetOptions;
        $count = count($data);
        foreach($data as $index=>$tree) {
            $treeOptions['attributeIndex'] = $index;
            $treeOptions['tree'] = $tree;
            
            echo '<div class="select2-trees-input">';
            $this->select2Tree($model, $attribute, $treeOptions);
            echo '</div>';
            if ($index < $count-1) {
                echo '<hr />';
            }
        }
        
        echo '</div>';
        
        // register script
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile('js/select2-trees.js',
            ClientScript::POS_HEAD);    // fix
        $cs->registerScript(
                'select2-tree-'.$wrapperId, 
                '$("#'.$wrapperId.'").select2Trees({
                        idPrefix: "'.$className.'-'.$attribute.'",
                        namePrefix: "'.$className.'['.$attribute.']",
                        placeholder: "'.$widgetOptions['emptyText'].'",
                        url: "'.$widgetOptions['url'].'", 
                        limit: "'.$widgetOptions['limit'].'"
                    });');
    }
    
    public function select2TreesRow($model, $attribute, $widgetOptions=array(), $rowOptions=array()) {
    
        $this->initRowOptions($rowOptions);
        
        $fieldData = array(array($this, 'select2Trees'), array($model, $attribute, $widgetOptions));
        
        return $this->customFieldRowInternal($fieldData, $model, $attribute, $rowOptions);
    }
}
