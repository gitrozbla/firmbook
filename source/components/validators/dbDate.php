<?php
/**
 * Walidator poprawnie wpisanej daty (bez godziny).
 *
 * @category components
 * @package components\validators
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class dbDate extends CDateValidator {
    
    /**
     * Format daty.
     * @var string
     */
    public $format = 'yyyy-M-d';
    
}
