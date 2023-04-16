<?php

Yii::import('bootstrap.widgets.TbEditableDetailView');

/**
 * Podmiana aliasu.
 * @see Yii::setAliasOfAlias()
 * @see WidgetFactory::createWidget()
 */
Yii::setAliasOfAlias('TbEditableField', 'application.components.widgets.EditableField');

/**
 * DetailView z obsługą EditableField.
 * 
 * Wyświetla informacje szczegółowe o obiekcie z możliwością edycji.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableDetailView extends TbEditableDetailView {

    /**
     * Czy pole jest edytowalne.
     * Nadpisuje Editable::$apply jeśli nieustalona.
     * @var boolean
     */
    public $editableApply = false;
    /**
     * Url dla edycji pola.
     * Nadpisuje Editable::url jeśli nieustalona.
     * @see EditableSaver
     * @var string|null
     */
    public $editableUrl = null;
    /**
     * Typ pola
     * Nadpisuje EditableField::type jeśli nieustalona.
     * @var string|null
     */
    public $editableType = null;
    /**
     * Dane dla kontrolki typu select lub switch.
     * Nadpisuje EditableField::source jeśli nieustalona.
     * @var array|null
     */
    public $editableSource = null;
    
    public $editableAutoCheckAccess = null;
    
    /**
     * Rysuje jedno pole atrybutu modelu.
     * Uwaga! Wymaga zmiany widoczności TbDetailView::$_data na protected.
     * 
     * Dodatkowe opcje dla konfiguracji (podobnie jak atrybuty):
     * editableApply
     * editableUrl
     * editableType
     * editableSource
     * 
     * @param array $options Konfiguracja zgodnie z frameworkiem.
     * @param array $templateData Dane szablonu, zgodnie z frameworkiem.
     */
    protected function renderItem($options, $templateData) {
            
        if (!isset($options['editable'])) {
            $options['editable'] = array();
        }

        $variables = array(
            'editableApply' => 'apply', 
            'editableUrl' => 'url', 
            'editableType' => 'type', 
            'editableSource' => 'source',
            'editableAutoCheckAccess' => 'autoCheckAccess',
            );
        foreach($variables as $key=>$value) {
            if (!isset($options['editable'][$value])) { // no direct editable option
                if (isset($options[$key])) {// option in Item
                    $options['editable'][$value] = $options[$key];
                } else if ($this->$key !== null) { // option in DetailView
                    $options['editable'][$value] = $this->$key;
                }   
            }
        }

        //merge options with defaults: url, params, etc.
        // requires TbEditableDetailView::_data to be protected
        $options['editable'] = CMap::mergeArray($this->_data, $options['editable']);

        //options to be passed into EditableField (constructed from $options['editable'])
        $widgetOptions = array(
            'model' => $this->data,
            'attribute' => $options['name'],
            //'closestFadeSelector' => '#'.$this->id,
        );

        //if value in detailview options provided, set text directly (as value here means text)
        if (isset($options['value']) && $options['value'] !== null) {
            $widgetOptions['text'] = $templateData['{value}'];
            $widgetOptions['encode'] = false;
        }

        $widgetOptions = CMap::mergeArray($widgetOptions, $options['editable']);
        /** @var $widget TbEditableField */
        
        if (isset($widgetOptions['type'])) {
            $class = EditableField::getClassForType($widgetOptions['type']);
        } else {
            $class = 'EditableField';
        }        
        
        $widget = $this->controller->createWidget($class, $widgetOptions);

        // always apply - EditableField will render formatted data
        //if ($widget->apply) {
        ob_start();
        $widget->run();
        $templateData['{value}'] = ob_get_clean();

        TbDetailView::renderItem($options, $templateData);
    }

}
