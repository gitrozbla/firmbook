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
    
    ///////////////////////
    // x-editable toggle //
    ///////////////////////
    var Toggle = function (options) {
        this.init('toggle', options, Toggle.defaults);

        var enabled = 'primary', disabled = null;
        if(this.options.red === 0) {
            var disabled = 'danger';
        } else if (this.options.blue === 0) {
            var disabled = 'primary';
        }
        if(this.options.red === 1) {
            var enabled = 'danger';
        } else if (this.options.blue === 1) {
            var enabled = 'primary';
        }
        
        $(this.options.scope).editable('toggleDisabled');

        // get pk and value
        if (options.value === null) {
            this.options.value = options.value = $(options.scope).text();
        }
        if (options.pk === null) {
            options.pk = options.scope.attr('data-pk');
        }

        // convert source text
        var yes='Yes', no='No';
        $(this.options.source).each(function(index, item){
            if (item.value == 0) {
                no = item.text;
            } else if (item.value == 1) {
                yes = item.text;
            }
        });
        
        // fix width using &nbsp; (lol)
        var yesCount = yes.length;
        var noCount = no.length;
        if (yesCount > noCount) {
            var diff = yesCount - noCount;
            for (var i=0; i<diff; i++) {
                no = '&nbsp;' + no + '&nbsp;';
            }
        } else {
            var diff = noCount - yesCount;
            for (var i=0; i<diff; i++) {
                yes = '&nbsp;' + yes + '&nbsp;';
            }
        }
        
        var $toggleButton = $('<form class="editable-switch" >' +
                    '<input type="checkbox" name="' + 
                    options.attribute + '-' + options.pk + '-1' + 
                    '" value="1" '+(options.value == 1 ? 'checked="checked"' : '') + 
                    'data-size="'+this.options.size+'" ' + 
                    'data-on-color="'+enabled+'" data-off-color="'+disabled+'" ' +
                    'data-on-text="'+yes+'" ' +
                    'data-off-text="'+no+'" />' +
                '</form>');

        $(this.options.scope).replaceWith($toggleButton);
        //$(options.scope).contents().replaceWith($toggleButton);
        var options = this.options;

        var fadeFunction = function() {
            var fade = options.fade !== null && options.value == options.fade;
            var fadeClass = 'fade-medium-' + options.fadeClassPostfix;
            if (options.fadeSelector !== null || options.closestFadeSelector) {
                if (options.fadeSelector !== null) {
                    if (fade) {
                        $(options.fadeSelector).addClass(fadeClass);
                    } else {
                        $(options.fadeSelector).removeClass(fadeClass);
                    }
                }
                if (options.closestFadeSelector !== null) {
                    if (fade) {
                        $($toggleButton).closest(options.closestFadeSelector).addClass(fadeClass);
                    } else {
                        $($toggleButton).closest(options.closestFadeSelector).removeClass(fadeClass);
                    }
                }
            } else {
               if (fade) {
                   $($toggleButton).addClass(fadeClass);
               } else {
                   $($toggleButton).removeClass(fadeClass);
               }
            }
        };

        fadeFunction();

        var $input = $toggleButton.find('input');
        $input.bootstrapSwitch();
        $input.on('switchChange.bootstrapSwitch', function(e, state){
            var element = $(this);
            element.parent().parent().next('.editable-toggle-error').remove();
            element.bootstrapSwitch('toggleReadonly');

            var newValue = state;
            if (newValue !== options.value) {
                $.ajax({
                    url: options.url,
                    context: document.body,
                    type: 'post',
                    data: {
                        'YII_CSRF_TOKEN': options.csrfToken,
                        'name': options.attribute,
                        'pk': options.pk,
                        'value': state ? 1 : 0
                    }
                }).fail(function(jqXHR, textStatus){
                    element.parent().parent().after(
                            '<div class="editable-toggle-error text-error">'+jqXHR.responseText+'</div>');
                    element.bootstrapSwitch('toggleReadonly');
                    element.bootstrapSwitch('toggleState', true);
                    options.value = element.bootstrapSwitch('state');
                }).done(function(){
                    element.bootstrapSwitch('toggleReadonly');
                });

                options.value = newValue;
                fadeFunction();
            }

        });

    };
    $.fn.editableutils.inherit(Toggle, $.fn.editabletypes.abstractinput);
    Toggle.defaults = $.extend({
        source: null,
        red: null,
        blue: null,
        fade: null,
        fadeSelector: null,
        closestFadeSelector: null,
        fadeClassPostfix: 'default',
        // attributes from editable
        url: '',
        attribute: '',
        value: null,
        pk: null,
        csrfToken: '',
        size: 'mini',
    }, $.fn.editabletypes.abstractinput.defaults, {});
    $.fn.editabletypes.toggle = Toggle;
    
});