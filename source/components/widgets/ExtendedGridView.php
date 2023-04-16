<?php

Yii::import('bootstrap.widgets.TbExtendedGridView', true);

class ExtendedGridView extends TbExtendedGridView
{
	/**
     * Dodatkowe dane, jeśli potrzebujemy dostepu do zmiennej innej niż this, data, row.     * 
     * 
     */
    public $extraData;
    
    
}
