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
    
    /////////////////////////
    // x-editable checkbox //
    /////////////////////////
    var Checkbox = function (options) {
        this.init('checkbox', options, Checkbox.defaults);
        this.sourceData = this.options.source;
        this.value2htmlFinal(options.scope.text, options.scope);
    };
    $.fn.editableutils.inherit(Checkbox, $.fn.editabletypes.select);
    $.extend(Checkbox.prototype, {
        value2htmlFinal: function(value, element) {
            var text = '', 
                items = $.fn.editableutils.itemsByValue(value, this.sourceData);
            if(items.length) {
                text = items[0].text;
            }
            if (value != '0') {
                text = '<i class="fa fa-check-square-o"></i>&nbsp;'+text;
            } else {
                text = '<i class="fa fa-square-o"></i>&nbsp;'+text;
            }

            if (this.options.red !== null && value == this.options.red) {
                text = '<span class="red">'+text+'</span>';
            } else if (this.options.blue !== null && value == this.options.blue) {
                text = '<span class="blue">'+text+'</span>';
            }

            var fade = this.options.fade !== null && value == this.options.fade;
            var fadeClass = 'fade-medium-' + this.options.fadeClassPostfix;
            if (this.options.fadeSelector !== null) {
                if (fade) {
                    $(this.options.fadeSelector).addClass(fadeClass);
                } else {
                    $(this.options.fadeSelector).removeClass(fadeClass);
                }
            } else {
                if (fade) {
                    text = '<span class="'+fadeClass+'">'+text+'</span>';
                }
            }

            $(element).html('<span data-value="'+value+'">'+text+'</span>');
        },
        html2value: function(html) {
            return $(html).attr('data-value');
        }
    });
    Checkbox.defaults = $.extend({
        red: null,
        blue: null,
        fade: null,
        fadeSelector: null,
        fadeClassPostfix: 'default',
    }, $.fn.editabletypes.select.defaults, {});
    $.fn.editabletypes.checkbox = Checkbox;
    
});