/**
 * 
 * 
 * @see EditableField
 * Skrypt powinien być w plikach assets komponentu
 * EditableField, ale w trakcie pisania skryptu
 * ciągłe wymuszone usuwanie assets jest irytujące...
 * 
 * @category scripts
 * @package editable
 * @author BAI
 * @copyright (C) 2014 BAI
 */
jQuery(function($){
    "use strict";
    
    /////////////////////////////
    // x-editable password fix //
    /////////////////////////////
    $.fn.editabletypes.password.prototype.value2html = function(value, element) {
        if (value) {
            $(element).text('*******');
        } else {
            $(element).empty();
        }
    };
    $.fn.editabletypes.password.prototype.str2value = function(str) {
        return '';
    };
    
});