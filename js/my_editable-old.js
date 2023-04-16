/**
 * Skrypt wyświetlający komunikat o cookies.
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
    
    if (typeof $.fn.editabletypes !== 'undefined') {
        
        // x-editable password fix
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
        
        // smartUpload test
        var canvasTest = document.createElement('canvas');
        var canvasSupported = !!(canvasTest.getContext && canvasTest.getContext('2d'));
        var fileReaderSupported = !!window.FileReader;
        var base64SendSupported = fileReaderSupported;
        var canvasSendSupported = canvasSupported && fileReaderSupported;
        
        
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
                if (value != '') {
                    $(element).html(value);
                    $(element).next().next('.editable-after').remove();
                    $(element).after(
                        '<span class="editable-after">&nbsp;' +
                            '<a href="mailto:'+value+'">' +
                                '<i class="icon-envelope"></i>' +
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
                                '<i class="icon-' + icon + '"></i>' +
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
                    text = '<i class="icon-check"></i>&nbsp;'+text;
                } else {
                    text = '<i class="icon-check-empty"></i>&nbsp;'+text;
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
        
        
        ///////////////////////
        // x-editable toggle //
        ///////////////////////
        var Toggle = function (options) {
            this.init('toggle', options, Toggle.defaults);
            
            var enabled = null, disabled = null;
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

            var $toggleButton = $('<form class="make-switch switch-' + this.options.size + '" ' +
                        'data-on="'+enabled+'" data-off="'+disabled+'" ' +
                        'data-on-label="'+yes+'" ' +
                        'data-off-label="'+no+'">' +
                        '<input type="checkbox" name="' + 
                        options.attribute + '-' + options.pk + '-1' + 
                        '" value="1" '+(options.value == 1 ? 'checked="checked"' : '')+' />' +
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
            
            $toggleButton.bootstrapSwitch();
            $toggleButton.on('switch-change', function(e, data){
                var element = data.el;
                element.parent().parent().next('.editable-toggle-error').remove();

                var newValue = data.value;
                if (newValue !== options.value) {
                    $.ajax({
                        url: options.url,
                        context: document.body,
                        type: 'post',
                        data: {
                            'YII_CSRF_TOKEN': options.csrfToken,
                            'name': options.attribute,
                            'pk': options.pk,
                            'value': data.value ? 1 : 0
                        }
                    }).fail(function(jqXHR, textStatus){
                        element.parent().parent().after(
                                '<div class="editable-toggle-error text-error">'+jqXHR.responseText+'</div>');
                        element.bootstrapSwitch('toggleState', {'skipOnChange': true});
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
        
        
        ///////////////////////
        // x-editable editor //
        ///////////////////////
        var EditableEditor = function (options) {
            options['editorOptions'] = $.extend(
                    EditableEditor.defaults['editorOptions'], 
                    options['editorOptions']);
            this.init('editor', options, EditableEditor.defaults);
            $(options.scope).parent().addClass('editable-editor');
            this.value2html(options.scope.text, options.scope);
        };
        $.fn.editableutils.inherit(EditableEditor, $.fn.editabletypes.textarea);
        $.extend(EditableEditor.prototype, {
            editor: null,
            editorTemplates: {
                full: [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', /*'Save', */'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                    /*{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },*/
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                    { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                    { name: 'insert', items: [ 'Image', /*'Flash',*/ 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', /*'Iframe'*/ ] },
                    '/',
                    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                    { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                    { name: 'others', items: [ '-' ] }
                    /*{ name: 'about', items: [ 'About' ] }*/
                ]
            },
            html2value: function(html) {
                return (html);
            },
            value2html: function(value, element) {
                $(element).html(value);
            },
            value2input: function(value) {
                this.$input.html(value);
                if (typeof this.options['editorOptions']['toolbar'] == 'undefined') {
                    this.options['editorOptions']['toolbar'] = 
                            this.editorTemplates[this.options['editorTemplate']];
                }
                this.editor = CKEDITOR.replace( this.$input[0], this.options['editorOptions']);
            },
            input2value: function() {
                return this.editor.getData();
            }
        });
        EditableEditor.defaults = $.extend({
            editorTemplate: 'full',
            editorOptions: {
                startupFocus: true,
                language: 'en',
                //baseHref: '../',
                //uiColor: '#8CAD18',
            }
        }, $.fn.editabletypes.textarea.defaults, {});
        $.fn.editabletypes.editor = EditableEditor;
        
        /*$(document).on('click', '.cke_dialog_background_cover, .cke_dialog_background_cover *', function(e){
            e.stopPropagation();
            e.preventBubbling();
        });*/
        
        //////////////////////
        // x-editable image //
        //////////////////////
        var EditableImage = function (options) {
            this.init('image', options, EditableImage.defaults);
            this.value2html(options.scope.text, options.scope);
        };
        $.fn.editableutils.inherit(EditableImage, $.fn.editabletypes.abstractinput);
        $.extend(EditableImage.prototype, {
            state: 'select',    // or remove, error
            base64Value: '',  // base64 data
            extension: '',
            
            init: function(type, options, defaults) {
                this.type = type;
                this.options = $.extend({}, defaults, options);
                
                // filesPath
                var path = this.options.filesPath;
                if (path != '' 
                        && path.indexOf('/', path.length - 1) === -1) {
                    this.options.filesPath += '/';
                }
                
                // first show remove icon
                if (this.options.value != '' && this.options.value != null) {
                    this.renderRemove(options.scope);
                }
                
                // this doesn't work here - so moved to EditableField.php
                
                $(options.scope).on('shown', function(e) {
                    // show 'x' icon
                    $(this).next().next('.editable-image-remove').remove();
                });
                
                var editableObject = this;
                $(options.scope).on('hidden', function(e) {
                    // remove 'x' icon
                    if ($(this).hasClass('editable-image-empty') == false) {
                        editableObject.renderRemove(this);
                    }
                    // 'select' state
                    editableObject.state = 'select';
                });
                
            },
            // shows image or 'empty' text
            value2html: function(value, element) {
                if (value.substring(0,5) == 'data:') {
                    // new filename
                    value = this.options.class + '/' + 
                            this.options.pk + '/' + 
                            this.options.csrfToken.substring(0,16) + '_' + 
                            this.options.attribute + '.' + 
                            (this.options.convertTo ?  this.options.convertTo : this.extension) + 
                            '?v=' + Math.round((new Date()).getTime() / 1000);
                }
                
                if (value != '') {
                    $(element).html('<span style="display: none;">'+value+'</span>\n\
                                <img src="'+ this.options.filesPath  + value+'" alt="" />\n\
                                <i class="icon-upload"></i>')
                            .removeClass('editable-image-empty')
                            .addClass('editable-image');
                } else {
                    $(element).html('<i class="icon-picture"></i> '
                                + this.options.noImageText)
                            .removeClass('editable-image')
                            .addClass('editable-image-empty');
                }
            },
            // clean input value
            value2input: function(value) {
                this.$input.val('');
            },
            // show select file window
            activate: function() {
                if (this.state == 'select') {
                    // do not reopen window if error
                    if (this.state != 'error') {
                        if(this.$input.is(':visible')) {
                            this.$input.focus();
                            this.$input.trigger('click');
                        }
                    }

                    this.base64Value = '';
                    this.extension = '';
                    var editableObject = this;
                    var submitButton = this.$input.parent().next().find('button[type="submit"]');
                    
                    submitButton.hide();
                    this.$input.on('change', function() {
                        if (editableObject.$input.val() == '') {
                            submitButton.hide();
                        } else {
                            submitButton.show();
                        }
                    });
                    
                    // submit click event
                    submitButton.on('click', function(e) {
                        
                        if (editableObject.$input.val() != '' && editableObject.base64Value == '') {
                             if (base64SendSupported) {

                                e.preventDefault();
                                e.stopPropagation();
                                
                                // disable form
                                submitButton.closest('form').find('input, button').attr('disabled', 'disabled');
                                // loading animation
                                //submitButton.parent().append('<i class="image-loading icon-spinner icon-spin icon-large"></i>');
                                // input file
                                var file = editableObject.$input[0].files[0];
                                if (!file) {
                                    alert('Błąd!\nInput not found!');
                                    window.location.reload();   // error
                                    return;
                                }
                                if (file.type.match('image.*')) {
                                    // extension
                                    editableObject.extension = file.type.substring(6);
                                    
                                    // load
                                    var reader = new FileReader();  
                                    reader.onload = function(e) {
                                        editableObject.base64Value = e.target.result;
                                        
                                        if (canvasSupported) {
                                            
                                            var image = new Image();
                                            //image.onload = function() {} // base64 loads instantly
                                            image.onload = function() {
                                                var convertTo = editableObject.options.convertTo;
                                                var maxWidth = editableObject.options.scaleMaxWidth;
                                                var maxHeight = editableObject.options.scaleMaxHeight;

                                                var dataTargetHeader = 'data:image/'+convertTo+';';

                                                // if converting or resizing required
                                                if (    (convertTo && 
                                                        image.src.substring(0, dataTargetHeader.length) != dataTargetHeader)
                                                        ||
                                                        ((maxWidth && maxWidth < image.width)
                                                            ||
                                                        (maxHeight && maxHeight < image.height)))
                                                {
                                                    convertTo = convertTo ? convertTo : 'jpeg';
                                                    //editableObject.extension = convertTo = convertTo ? convertTo : 'jpeg';
                                                    //var dataTargetHeader = 'data:image/'+convertTo+';';
                                                    maxWidth = maxWidth ? maxWidth : image.width;
                                                    maxHeight = maxHeight ? maxHeight : image.height;
                                                    var aspectRatio = image.width / image.height;
                                                    var maxDimensionsAspectRatio = maxWidth / maxHeight;
                                                    if (maxDimensionsAspectRatio < aspectRatio) {
                                                        maxHeight = Math.floor(maxWidth / aspectRatio);
                                                    } else {
                                                        maxWidth = Math.floor(maxHeight * aspectRatio);
                                                    }

                                                    var canvas = document.createElement('canvas');

                                                    canvas.width = maxWidth;
                                                    canvas.height = maxHeight;
                                                    var ctx = canvas.getContext("2d");

                                                    ctx.drawImage(image, 0, 0, maxWidth, maxHeight);

                                                    editableObject.base64Value = canvas.toDataURL('image/'+convertTo);
                                                    editableObject.extension = convertTo;
                                                }
                                                // enable form back
                                                editableObject.$input.parent().parent().find('input, button').removeAttr('disabled');
                                                // remove loading animation
                                                //editableObject.$input.parent().next().find('.image-loading').remove();

                                                //$(editableObject.$input).editable('submit');
                                                submitButton.trigger('click');
                                            };
                                            image.src = editableObject.base64Value;
                                            
                                            
                                        } else {

                                            // extension
                                            //editableObject.extension = editableObject.$input.val().split('.').pop();
                                            // enable form back
                                            editableObject.$input.parent().parent().find('input, button').removeAttr('disabled');
                                            // remove loading animation
                                            //editableObject.$input.parent().next().find('.image-loading').remove();

                                            //$(editableObject.$input).editable('submit');
                                            submitButton.trigger('click');
                                        }
                                    }
                                    reader.onerror = function(){
                                        alert('Błąd!\nCannot read file!');
                                    };
                                    reader.readAsDataURL(file);
                                } else {
                                    alert(editableObject.options.notImageText);
                                    // enable form back
                                    editableObject.$input.parent().parent().find('input, button').removeAttr('disabled');
                                }
                                
                            }
                        }
                    });
                }
            },
            input2value: function() {
                if(this.state == 'select') {
                    if (base64SendSupported) {
                        return this.base64Value;
                    } else {
                        // prevent of closing form (worst hack ever!)
                        $(this.options.scope).editable('option', 'validate', function(){
                            return ' ';  // empty error
                        });
                        this.state = 'error';

                        $(this.options.scope).next('.editable-container').find('form')
                                // remove all events
                                .unbind()
                                // form options
                                .attr('enctype', 'multipart/form-data')
                                .attr('method', 'POST')
                                .attr('action', this.options.url)
                                // other attributes
                                .append('<input type="hidden" name="YII_CSRF_TOKEN" value="'+this.options.csrfToken+'" />')
                                .append('<input type="hidden" name="pk" value="'+this.options.pk+'" />')
                                .append('<input type="hidden" name="name" value="'+this.options.attribute+'" />')
                                // input name
                                .find('input[type="file"]')
                                //.attr('name', 'value')
                                .attr('name', this.options.class+'['+this.options.attribute+']')
                                // submit form
                                 .closest('form')
                                .submit();
                        return '';
                    }
                } else {
                    // delete
                    return this.$input.val();
                }
            },
            renderRemove: function(element) {
                $(element).css({'display': 'inline-block'});
                $(element).next('.editable-image-remove').remove();
                $(element).after('<a class="editable-image-remove image-link" href="#">\n\
                       <i class="icon-remove"></i>\n\
                   </a>');
                
                var editableObject = this;
                $(element).next().on('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();    // to prevent immediate closing of form
                    
                    editableObject.state = 'remove';
                    
                    $(this).remove();
                    var oldTpl = editableObject.options.tpl;
                    editableObject.options.tpl = '<label>' + 
                                editableObject.options.removeText + 
                            '</label> <input type="hidden">';   // submit empty
                    $(element).editable('show');
                    editableObject.options.tpl = oldTpl;
                });
            },
        });
        EditableImage.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
            noImageText: 'upload image',
            notImageText: 'This file is not an image!',
            removeText: 'Remove?',
            tpl: '<input type="file">',
            placeholder: null,
            removeConfirm: true,
            filesPath: '',
            scaleMaxWidth: false,
            scaleMaxHeight: false,
            convertTo: false,
            // attributes from editable
            url: '',
            class: '',
            attribute: '',
            value: null,
            pk: '',
            csrfToken: '',
            hash: '',
        });
        $.fn.editabletypes.image = EditableImage;
    
    }
    
});
