jQuery(function($){
    "use strict";
    
    $.fn.select2Trees = function(options) {
        
        var settings = $.extend( {
          idPrefix: null,
          namePrefix: null,
          placeholder: '',
          url: '',
          limit: 99
        }, options);
        
        if (settings.idPrefix == null) {
            console.error('select2Tree: No idPrefix option.');
            return false;
        }
        
        $(this).each(function(){
            var trees = $(this);
            var lastfieldIndex = trees.find('.select2-trees-input').length - 1;
            var noEmpty = lastfieldIndex >= settings.limit;
            
            // zmiana pola
            var onChangeFunction = function(){
                var input = $(this);
                var inputWrapper = input.closest('.select2-trees-input');
                
                // sprawdzenie czy wiersz jest ostatni
                var inputs = trees.find('.select2-trees-input');
                var length = inputs.length;
                var index = inputs.index(inputWrapper);
                if (index == length-1) { 
                    var last = true;
                } else {
                    var last = false;
                }
                
                var insertNew = false;
                if (input.val() == '') {
                    if (!last) {
                        // usuwanie wiersza, o ile nie jest ostatni
                        inputWrapper.next('hr').remove();
                        inputWrapper.remove();
                        
                        if (noEmpty) {
                            // zwolniło się miejsce
                            insertNew = true;
                        }
                    }
                    noEmpty = false;
                } else {
                    if (last) {
                        if (length < settings.limit) {
                            // wybrano - dodajemy nowe puste pole
                            insertNew = true;
                        } else {
                            noEmpty = true;
                        }
                    } 
                }
                
                if (insertNew) {
                    // dodawanie wiersza, jeśli był ostatni lub 
                    // usunięty a zwolniło się miejsce na puste pole
                    lastfieldIndex++;
                    var treeId = settings.idPrefix + '-' + lastfieldIndex;
                    var treeName = settings.namePrefix + '[' + lastfieldIndex + ']';
                    trees.append(
                            '<hr />' +
                            '<div class="select2-trees-input">' +
                                '<div id="' + treeId + '" class="select2-tree-wrapper">' +
                                    '<span class="select2-tree-input">' +
                                        '<input id="' + treeId + '-0" type="hidden" name="' + treeName + '[0]">' +
                                    '</span>' +
                                    '<input type="hidden" class="select2-tree-main-input" name="' + treeName + '">' +
                                '</div>' +
                            '</div>'
                            );

                    $('#'+treeId+'-0').select2({
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
                                    id: 0,
                                    query: query
                                }
                            }
                        },
                        initSelection: function(element, callback) {
                            callback({});
                        },
                        width: 'resolve'
                    });
                    var newSettings = $.extend({}, settings, {
                        idPrefix: settings.idPrefix + '-' + lastfieldIndex, 
                        namePrefix: settings.namePrefix + '[' + lastfieldIndex + ']'
                    });
                    $('#'+treeId).select2Tree(newSettings);
                    $('#'+treeId).find('.select2-tree-main-input').on('change', onChangeFunction);
                    
                    var length = trees.find('.select2-trees-input').length - 1;
                    noEmpty = length >= settings.limit;
                }
            }
            trees.find('.select2-tree-main-input').on('change', onChangeFunction);
        });
    }

});