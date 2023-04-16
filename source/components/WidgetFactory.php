<?php
/**
 * Fabryka widgetów.
 * 
 * Obiekt tej klasy tworzy i inicjuje widgety.
 *
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class WidgetFactory extends CWidgetFactory 
{
    /**
     * Tworzy widget.
     * Dodana obsługa podmiany aliasu.
     * @see Yii::getAliasOfAlias()
     * @param CBaseController $owner Właściciel (obiekt zlecający).
     * @param string $className Klasa widgetu.
     * @param array $properties Konfiguracja widgetu.
     * @return CWidget UTworzony widget.
     */
    public function createWidget($owner,$className,$properties=array()) {
    
        $className = Yii::getAliasOfAlias($className);
        
        return parent::createWidget($owner,$className,$properties);
    
    }
}
