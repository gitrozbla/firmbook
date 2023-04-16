jQuery(function($){
    "use strict";

    $.fn.select2Tree = function(options) {

        var settings = $.extend( {
          idPrefix: null,
          limit: null,
          namePrefix: null,
          placeholder: '',
          url: ''
        }, options);

        if (settings.idPrefix == null) {
            console.error('select2Tree: No idPrefix option.');
            return false;
        }

        $(this).each(function(){
            var tree = $(this);
            var mainInput = tree.find('.select2-tree-main-input');
            var lastValue = mainInput.val();

            // przycisk remove
            var removeButtonHtml = '<a class="clear-selection" href="#">&times;</a>';
            var removeFunction = function(e) {
                e.preventDefault();
                //e.stopImmediatePropagation();
                // select empty
                $(this).parent().find('input[type="hidden"]').val(null).trigger('change');
            };
            var $containers = tree.find('.select2-container');
            console.log($containers.last().parent().find('input[type="hidden"]').val());
            if ($containers.last().parent().find('input[type="hidden"]').val() == '') {
                $containers = $containers.slice(0, -1);
            }
            $containers.after(removeButtonHtml);
            tree.find('.clear-selection').on('click', removeFunction);

            // zmiana pola
            var onChangeFunction = function(){

                var input = $(this);
                var inputWrapper = input.parent();

                // kasowanie pól
                inputWrapper.nextAll('.tree-right, .select2-tree-input').remove();

                var removeButton = inputWrapper.find('.clear-selection');

                if (input.val() == '') {
                    // usuwanie przycisku remove
                    removeButton.remove();
                } else {
                    // dodawanie pola
                    var selectedValue = input.val();
                    var count = inputWrapper.parent().find('input[type="hidden"]').length - 1;
                    if (count < 2) {
                        var newId = settings.idPrefix+'-'+(count);
                        var newName = settings.namePrefix+'['+(count)+']';
                        inputWrapper.after(
                            '<span class="tree-right"> / </span>' +
                            '<span class="select2-tree-input">' +
                                '<input id="' + newId + '" type="hidden" name="' + newName + '" />' +
                            '</span>'
                        );

                        $('#' + newId).select2({
                            placeholder: settings.placeholder,
                            delay: 500,
                            ajax: {
                                url: settings.url,
                                dataType: 'json',
                                results: function(data, page) {
                                    return {results: data};
                                },
                                data: function(query) {
                                    return {
                                        id: selectedValue,
                                        query: query
                                    }
                                }
                            },
                            initSelection: function(element, callback) {
                                callback({});
                            },
                            width: 'resolve'
                        });
                        $('#' + newId).on('change', onChangeFunction);
                    }

                    if (removeButton.length == 0) {
                        var container = inputWrapper.find('.select2-container');
                        container.after(removeButtonHtml);
                        container.next().on('click', removeFunction);
                    }
                }

                // update pola głównego
                var inputs = tree.find('.select2-tree-input input[type="hidden"]');
                var length = inputs.length;
                if (length > 1) {
                    var value = inputs.eq(length-1).val();
                    if (value == '') { // last is not selected
                        value = inputs.eq(length-2).val();
                    }
                } else {
                    var value = inputs.first().val();
                }
                mainInput.val(value);
                mainInput.trigger('change');
            }

            tree.find('.select2-tree-input input[type="hidden"]')
                    .not('.select2-tree-main-input')
                    .on('change', onChangeFunction);

        });
    }

});
