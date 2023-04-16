<?php

Yii::import('bootstrap.widgets.TbButton', true);

class EditorButton extends TbButton
{
    public $icon = 'fa fa-wrench';
    
    public $label = null;
    
    public function run()
    {
        if (empty($this->label)) {
            $this->label = Yii::t('editor', 'Edit');
        }
        
        if (empty($this->label)) {
            $this->label = Yii::t('editor', 'Edit');
        }
        
        $c = Yii::app()->controller;
        $editor = Yii::app()->session['editor'];
        
        if (!isset($c->editorEnabled) 
                || !$c->editorEnabled 
                || Yii::app()->user->isGuest) {
            return false;
        }
        
        echo '<div class="editor-button pull-right">';
        parent::run();
        if ($editor) {
            echo '<br />
            <span class="page-editor-button-info">'
                .Yii::t('editor', 'click to turn off')
            .'</span>';
        }
        echo '</div>';
    }
}

