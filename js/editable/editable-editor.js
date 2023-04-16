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
    
});