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
    // x-editable email //
    //////////////////////
    var Email = function (options) {
        this.init('email', options, Email.defaults);
        this.value2html(options.scope.text, options.scope);
    };
    $.fn.editableutils.inherit(Email, $.fn.editabletypes.text);
    $.extend(Email.prototype, {
        value2html: function(value, element){
            $(element).next().next('.editable-after').remove();
            if (value != '') {
                $(element).html(value);
                $(element).after(
                    '<span class="editable-after">&nbsp;' +
                        '<a href="mailto:'+value+'">' +
                            '<i class="fa fa-envelope"></i>' +
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
    Email.defaults = $.extend({}, $.fn.editabletypes.text.defaults, {});
    $.fn.editabletypes.email = Email;
    
});