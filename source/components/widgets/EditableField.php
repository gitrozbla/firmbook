<?php

Yii::import('bootstrap.widgets.TbEditableField');

/**
 * Edytowalne pole.
 * 
 * Na bazie YIi-booster <- X-Editable for YII <- X-Editable
 * @see http://yiibooster.clevertech.biz/
 * @see http://x-editable.demopage.ru/
 * @see http://vitalets.github.io/x-editable/
 * EditableField współpracuje ściśle ze skryptem my_editable.js
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableField extends TbEditableField 
{
    /**
     * Opcje edytora, zgodnie api X-Editable
     * @see http://vitalets.github.io/x-editable/
     * @var array
     */
    public $editorOptions = array();
    
    public $mode = 'inline';    // force to render editor inline
    
    /**
     * Opcja zaznaczona na czerwono.
     * @var string
     */
    public $red = null;
    /**
     * Opcja zaznaczona na niebiesko.
     * @var string
     */
    public $blue = null;
    
    public $autoCheckAccess = null;
    
    /**
     * Wartość pola.
     * @var string
     */
    protected $_value = null;
    
    /**
     * Nazwa dodatkowego skryptu (dla klas dziedziczących).
     * @var string
     */
    protected $additionalScript = null;
    
    /**
     * Inicjuje widget edytowalnego pola.
     */
    public function init() {
        
        // empty text
        if ($this->emptytext === 'Empty') {
            $this->emptytext = Yii::t('editable', 'no information');
        }
        
        if ($this->value) {
            $this->_value = $this->value;
        } else if ($this->text) {
            $this->_value = $this->text;
            if (get_class($this->model) == 'UserFile'
                    || is_subclass_of($this->model, 'UserFile')) {
                $this->attribute = 'data';
            }
        } else if ($this->model) {
            if (get_class($this->model) == 'UserFile'
                    || is_subclass_of($this->model, 'UserFile')) {
                // this is managed file (UserFile)
                $this->attribute = 'data';
                $this->_value = $this->model;
            } else {
                $attribute = $this->attribute;
                if ($attribute == null) {
                    $this->_value = null;
                    $this->attribute = 'nothing';
                } else {
                    $this->_value = $this->model->$attribute;
                }
            }
        }
        
        // auto check access
        if ($this->autoCheckAccess) {
            $this->apply = $this->apply && Yii::app()->user
                    ->checkAccess($this->autoCheckAccess, array(
                        'record'=>$this->model, 
                        'attribute'=>$this->attribute));
        }
        
        if ($this->apply == false or 
                (!empty($this->model) and $this->model->isAttributeSafe($this->attribute) == false)) {
            // not safe or disabled
            $this->text = $this->renderField();
            $this->encode = false;
            $this->type = 'text';
        } else {
            
            if (!isset($this->options['params'])) {
                $this->options['params'] = array();
            }
            
            // Csrf token
            $this->options['params']['YII_CSRF_TOKEN'] = Yii::app()->request->csrfToken;
        }
		
        // onblur - default to submit
        if (!isset($this->options['onblur'])) {
            $this->options['onblur'] = 'submit';
        }
        
        parent::init();
    }
    
    /**
     * Renderuje zawartość pola zgodnie z zadanymi parametrami.
     * @return string Zawartość pola.
     */
    public function renderField() 
    {
        $value = $this->_value;
        
        switch ($this->type) {
            case 'date':
                if ($value == null || $value == '0000-00-00') {
                    return $this->emptytext;
                } else {
                    return $value;
                }
                break;
                
            default:
                if (!empty($value)) {
                    return $value;
                } else {
                    return $this->emptytext;
                }
        }
        
    }
    
    
    /**
     * Dodaje skrypt do html.
     * @return string Skrypt.
     */
    public function registerClientScript()
    {
        $script = parent::registerClientScript();
        
        // ajax support
        if (Yii::app()->request->isAjaxRequest) {
            echo Html::tag('div', array(
                'class' => 'ajax-script',
                'data-script' => $script,
                ));
        }
        
        return $script;
    }
    
    /**
     * Rejestruje zasoby wtyczki X_Editable (przenosi do folderu assets i dodaje do html).
     */
    public function registerAssets() 
    {
        
        // screw assets! js moved to public_html/js
        /*$am = Yii::app()->getAssetManager();
        $assetsUrl = $am->publish(
                Yii::getPathOfAlias('application.components.widgets.assets'), 
                false, 
                -1, 
                YII_DEBUG
                );*/
        
       /* Yii::app()->clientScript->registerScriptFile(
                Yii::app()->homeUrl.'js/editable.js', 
                CClientScript::POS_END
                );*/
        
        if ($this->additionalScript) {
            Yii::app()->clientScript->registerScriptFile(
                    Yii::app()->homeUrl.'js/editable/editable-'.$this->additionalScript.'.js', 
                    CClientScript::POS_END
            );
        }
        
        Yii::app()->clientScript->registerCssCrushFile(
            Yii::app()->basePath.'/../css/editable.css', '', true
        );
        
        parent::registerAssets();
    }
    
    /**
     * Generuje selektor css na podstawie id widgetu.
     * @return string Selektor.
     */
    public function getSelector() 
    {
        return ($this->id).'_'.parent::getSelector();
    }
    
    public static function getClassForType($type) 
    {
        if (!in_array($type, 
                array('text', 'textarea', 'select', 'select2', 
                    'date', 'datetime', 'dateui', 'combodate', 
                    'checklist', 'typeahead', 'html5types', 'wysihtml5'))) {
            return 'Editable'.ucfirst($type);
        } else {
            return 'EditableField';
        }
    }
    
    public function renderLink()
    {
        echo Html::openTag('span', array(
            'class' => 'editable-'.$this->type.'-wrapper'
        ));
        echo Html::openTag('a', $this->htmlOptions);
        $this->renderText();
        echo Html::closeTag('a');
        echo Html::closeTag('span');
    }
}