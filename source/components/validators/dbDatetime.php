<?php
/**
 * Walidator poprawnie wpisanej daty (z godziną).
 *
 * @category components
 * @package components\validators
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class dbDatetime extends CDateValidator
{
    /**
     * Format daty i godziny.
     * @var string
     */
    public $format = 'yyyy-M-d H:m';
    
}
