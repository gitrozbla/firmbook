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
    
    //////////////////////
    // x-editable phone //
    //////////////////////
    var Phone = function (options) {
        this.init('phone', options, Phone.defaults);
        this.value2html(options.scope.text, options.scope);
    };
    $.fn.editableutils.inherit(Phone, $.fn.editabletypes.text);
    $.extend(Phone.prototype, {
        value2html: function(value, element){
            $(element).next().next('.editable-after').remove();
            if (value != '') {
                $(element).html(value);
                $(element).after(
                    '<span class="editable-after">&nbsp;' +
                        '<a href="tel:'+value.replace(/[^0-9]/g, '')+'">' +
                            '<i class="fa fa-phone"></i>' +
                        '</a>' +
                    '</span>');
            } else {
                $(element).empty();
            }
        },
        html2value: function(html) {
            return $.trim($('<div>').html(html).text());
        }
    });
    Phone.defaults = $.extend({}, $.fn.editabletypes.text.defaults, {});
    $.fn.editabletypes.phone = Phone;
    
});