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
    // x-editable money //
    //////////////////////
    var Money = function (options) {
        this.init('money', options, Money.defaults);
        this.value2html(options.scope.text, options.scope);
    };
    $.fn.editableutils.inherit(Money, $.fn.editabletypes.text);
    $.extend(Money.prototype, {
        value2html: function(value, element){
            var value = parseFloat(value.replace(",", ".")) / 100.0;
            $(element).html(value.toFixed(2)+'&nbsp;'+this.options['currency']);
        },
        html2value: function(html) {
            var currencyLength = this.options['currency'].length+1;
            var text = $('<div>').html(html).text();
            return (text.substring(0, text.length-currencyLength));
        },
        input2value: function() {
            var value = this.$input.val().replace(',','.');
            var dotPos = value.indexOf('.');
            if (dotPos != -1) {
                return (Math.floor(parseFloat(value) * 100.0)).toString();
            } else {
                return (value * 100).toString();
            }
        },
        value2input: function(value) {
            value = value.replace(',','.');
            var dotPos = value.indexOf('.');
            if (dotPos == -1) {
                var length = value.length;
                this.$input.val(value.substring(0, length-2)+','+value.substring(length-2));
            } else {
                this.$input.val(parseFloat(value));
            }

        }
    });
    Money.defaults = $.extend({
        currency: 'zł'
    }, $.fn.editabletypes.text.defaults, {});
    $.fn.editabletypes.money = Money;
    
});