/**
 * Fix dla datepicker (użytany w edytowalnych polach).
 * 
 * @category scripts
 * @package other
 * @author BAI
 * @copyright (C) 2014 BAI
 */
(function($){
    $.fn.datepicker.dates = $.fn.bdatepicker.dates;
}(jQuery));
