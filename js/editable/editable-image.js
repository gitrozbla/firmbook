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
    
    // smartUpload test
    var canvasTest = document.createElement('canvas');
    var canvasSupported = !!(canvasTest.getContext && canvasTest.getContext('2d'));
    var fileReaderSupported = !!window.FileReader;
    var base64SendSupported = fileReaderSupported;
    var canvasSendSupported = canvasSupported && fileReaderSupported;
    
    var newEmptyFieldCount = 0;
    
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
        empty: false,

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
            if (value.substring(17,22) == 'data:') {
                
                if (this.options.params['fileMode']) {
                    var ownerClass = this.options.params['ownerClass'];
                    var ownerPk = this.options.params['ownerPk'];
                    var fileHash = this.options.params['fileHash'];
                } else {
                    var ownerClass = this.options.class;
                    var ownerPk = this.options.pk;
                    var fileHash = value.substring(0, 16);
                }
                // new filename
                value = this.options.filesPath + 
                        ownerClass + '/' + 
                        ownerPk + '/' + 
                        fileHash + 
                        this.options.postfix + '.' + 
                        (this.options.convertTo ?  this.options.convertTo : this.extension) + 
                        '?v=' + Math.round((new Date()).getTime() / 1000);
                
                if (this.options.galleryMode && this.empty) {
                    // insert new input after
                    var rel = 'empty_'+newEmptyFieldCount+'_UserFile_data_';
                    $(this.$input).closest('.editable-image-wrapper').after(
                        '<span class="editable-image-wrapper">' +
                            '<a href="#" rel="'+rel+'"></a>' +
                        '</span>'
                    );
                    
                    var newHash = this.generateHash();
                    var options = $.extend({}, this.options, {
                        type: 'image',
                        pk: newHash,
                    });
                    options.params = $.extend({}, this.options.params, {
                        'fileHash': newHash
                    });
                    delete options.scope;
                    
                    $('a[rel='+rel+']').editable(options);
                    
                    newEmptyFieldCount++;
                }
            }

            if (value != '') {
                // old file
                $(element).html('<span style="display: none;">'+value+'</span>\n\
                            <img src="'+value+'" \n\
                                alt="'+this.options.imageAlt+'" />\n\
                            <i class="fa fa-upload"></i>')
                        .removeClass('editable-image-empty')
                        .addClass('editable-image');
                this.empty = false;
            } else {
                // no file
                
                if (this.options.galleryMode && this.empty == false) {
                    // remove input
                    var field = $(this.$input).closest('.editable-container').prev()
                    field.editable('destroy');
                    field.closest('.editable-image-wrapper').remove();
                }
                
                $(element).html('<i class="fa fa-picture-o"></i> '
                            + this.options.noImageText)
                        .removeClass('editable-image')
                        .addClass('editable-image-empty');
                this.empty = true;
            }
            
            reloadAlert(false);
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
                            //submitButton.parent().append('<i class="image-loading fa fa-spinner fa-spin fa-lg"></i>');
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
                                
                                reloadAlert(editableObject.options.reloadAlertText);
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
                    if (this.options.params['fileMode']) {
                        return this.options.params['fileHash'] + ';' + this.base64Value;
                    } else {
                        return this.generateHash() + ';' + this.base64Value;
                    }
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
                            .append('<input type="hidden" name="hash" value="'+this.generateHash()+'" />')
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
                   <i class="fa fa-times"></i>\n\
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
        generateHash: function() {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 16; i++ ) {
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }

            return text;
        }
    });
    EditableImage.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        // image
        filesPath: '',
        class: '',
        postfix: 'medium',
        imageAlt: '',
        // conversion
        scaleMaxWidth: false,
        scaleMaxHeight: false,
        convertTo: false,
        // messages
        noImageText: 'upload image',
        notImageText: 'This file is not an image!',
        removeText: 'Remove?',
        reloadAlertText: 'A file is still beeing uploaded!',
        emptytext: 'no information',
        title: 'Insert Data',
        //internal
        tpl: '<input type="file">',
        placeholder: null,
        removeConfirm: true,
        value: null,
        csrfToken: '',
        savenochange: true,
        onblur: 'cancel',
        ajaxOptions: {type: 'POST'},
        params:{
            // FILE_MODE options
            fileMode: false,
            ownerClass: null,
            ownerPk: null, 
            fileHash: null
        },
        datepicker: {'language': 'en'},
        name: 'data',
        mode: 'inline',
        placement: 'top',
        // attributes from editable
        url: '',
        attribute: '',
        pk: '',
        // gallery mode
        galleryMode: false
    });
    $.fn.editabletypes.image = EditableImage;
    
});
