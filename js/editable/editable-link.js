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
    
    /////////////////////
    // x-editable link //
    /////////////////////
    var Link = function (options) {
        this.init('link', options, Link.defaults);
        this.value2html(options.scope.text, options.scope);
    };
    $.fn.editableutils.inherit(Link, $.fn.editabletypes.text);
    $.extend(Link.prototype, {
        value2html: function(value, element){
            if (value != '') {
                if (value.substring(0, 7) == 'http://') {
                    var blank = 'target="_blank"';
                    var icon = 'external-link';
                } else {
                    var blank = '';
                    var icon = 'arrow-right';
                }
                $(element).html(value);
                $(element).next().next('.editable-after').remove();
                $(element).after(
                    '<span class="editable-after">&nbsp;' +
                        '<a href="'+value+'" ' + blank + '>' +
                            '<i class="fa fa-' + icon + '"></i>' +
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
    Link.defaults = $.extend({}, $.fn.editabletypes.text.defaults, {});
    $.fn.editabletypes.link = Link;
    
});