<?php

Yii::import('bootstrap.widgets.TbEditableColumn');

/**
 * Podmienia alias.
 * @see Yii::setAliasOfAlias()
 * @see WidgetFactory::createWidget()
 */
Yii::setAliasOfAlias('TbEditableField', 'application.components.widgets.EditableField');

/**
 * Kolumna GridView w formie input.
 * @see http://x-editable.demopage.ru/
 * 
 * function path
 * requires _isScriptRendered to be protected
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableColumn extends TbEditableColumn {
    /* public function init() {
      if ((isset($this->editable['apply']) and $this->editable['apply'] === false)) {
      $type = isset($this->editable['type']) ? $this->editable['type'] : 'text';
      $attribute = $this->name;
      $this->value = 'MyEditableField::renderField("'.$type.'", $data, "'.$attribute.'")';
      $this->type = 'raw';
      }

      return parent::init();
      } */

    /* protected function renderDataCellContent($row, $data)
      {
      if ((isset($this->editable['apply'])
      and is_string($this->editable['apply'])
      and eval($this->editable['apply']) == false)
      or
      ($data->isAttributeSafe($this->name) == false)) {
      if (!isset($this->editable['type'])) {
      $this->editable['type'] = 'text';
      }
      echo MyEditableField::renderField($this->editable['type'], $data, $this->name);
      return;
      }
      return parent::renderDataCellContent($row, $data);
      } */
    
    /**
     * Czy pole jest edytowalne
     * @var bool
     */
    public $editableApply = null;
    /**
     * Rodzaj edytowalnego pola.
     * @var string
     */
    public $editableType = null;
    /**
     * Konfiguracja dla pól typu dropdown i przełącznik.
     * @var array
     */
    public $editableSource = null;
    /**
     * Url akcji update.
     * @see EditableSaver
     * @var string
     */
    public $editableUrl = null;

    // function path
    // requires _isScriptRendered to be protected
    /**
     * Renderuje zawartość komórki tabeli.
     * M.in. przekazuje do komórki ustawienia editable i 
     * generuje nieaktywne pole editable gdy editableApply==false.
     * @param int $row Numer wiersza.
     * @param object $data Dana w komórce.
     */
    protected function renderDataCellContent($row, $data) {
        
        $options = CMap::mergeArray(
            $this->editable, array(
                'model' => $data,
                'attribute' => $this->name,
                //'closestSelector' => '.'.($this->grid->id).'-'.$data->primaryKey,
            )
        );

        //if value defined for column --> use it as element text
        if (strlen($this->value)) {
            ob_start();
            parent::renderDataCellContent($row, $data);
            $text = ob_get_clean();
            $options['text'] = $text;
            $options['encode'] = false;
        }
        
        $variables = array(
            'editableApply' => 'apply', 
            'editableUrl' => 'url', 
            'editableType' => 'type', 
            'editableSource' => 'source'
            );
        foreach($variables as $key=>$value) {
            if ($this->$key !== null 
                    and !isset($options[$value])) { // no direct editable option
                $options[$value] = $this->$key;
            }
        }

        /** @var $widget TbEditableField */
        $widget = $this->grid->controller->createWidget('EditableField', $options);

        //if editable not applied --> render original text
        if (!$widget->apply) {
            /*if (isset($text)) {
                echo $text;
            } else {
                parent::renderDataCellContent($row, $data);
            }*/
            
            // force run
            $widget->run();
            
            return;
        }

        //manually make selector non unique to match all cells in column
        $selector = str_replace('\\', '_', get_class($widget->model)) . '_' . $widget->attribute;
        $widget->htmlOptions['rel'] = $selector;

        //can't call run() as it registers clientScript
        $widget->renderLink();

        //manually render client script (one for all cells in column)
        // function path
        // requires _isScriptRendered to be protected
        if (!$this->_isScriptRendered) {
            $script = $widget->registerClientScript();
            //use parent() as grid is totally replaced by new content
            Yii::app()->getClientScript()->registerScript(
                    __CLASS__ . '#' . $this->grid->id . $selector . '-event', '
							   $("#' . $this->grid->id . '").parent().on("ajaxUpdate.yiiGridView", "#' . $this->grid->id . '", function() {' . $script . '});
            '
            );
            // requires TbEditableColumn::$_isScriptRendered to be protected
            $this->_isScriptRendered = true;
        }
    }

}
