// show error messages
// this is custom handler because default does not support
// multiple instance of same model like CreatorsPage_[i].
// @see CActiveForm->clientOptions->afterValidate
var editorErrorHandler = function(form, data, hasError) {
    var errorSummary = $(form).find('.editor-error-summary');
    errorSummary.slideUp(function(){
        if (hasError) {
            var ul = errorSummary.find('ul');
            ul.find('li').remove();
            $.each(data, function(key, value) {
                var section = $('#'+key)
                        .closest('fieldset').find('legend').text();
                ul.append('<li>'+section+': '+value+'</li>');
            });
            errorSummary.slideDown();
        }
    });

    if (hasError) {
        return false;
    } else {
        return true;
    }
}

// this function is used in preview
var previewUpdate = function() {
    $('.editor-sidebar input, .editor-sidebar textarea, .editor-sidebar select').trigger('preview-update');
};

jQuery(function($){

    // sidebar open
    var editorVisible = false;
    $('.editor-sidebar-button').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        if (editorVisible) {
            $('.editor-sidebar').css('left', '-26%');
            $('iframe').css('width', '100%');
            $('.alerts').css('left', '0');
        } else {
            $('.editor-sidebar').css('left', '0');
            $('iframe').css('width', '74%');
            $('.alerts').css('left', '26%');
        }
        editorVisible = !editorVisible;
    });


    // fieldset slide
    $('legend a').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        $(this).parent().next('.editor-block-content').slideToggle();
    });
    $('.editor-block-content').hide();
    // pages slide
    $('a.editor-page-title-link').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        $(this).parent().next('.editor-page-content').slideToggle();
    });
    $('.editor-page-content').hide();



    // monitor changes in form
    var formChanged = false;
    $('.editor-sidebar input, .editor-sidebar textarea, .editor-sidebar select').on('change', function() {
        if (!formChanged) {
            formChanged = true;
            $('.preview-warning').slideDown();
            $('.editor-cancel').fadeIn();
        }
        // stupid chrome fix
        setTimeout(function(){
            $('iframe').hide().show(0);
        }, 0);
    });


    // close window button
    window.onbeforeunload = function (e) {
        if (formChanged) {
            e = e || window.event;
            var question = $('.close-window').attr('data-confirm');
            // For IE and Firefox prior to version 4
            if (e) {
                e.returnValue = question;
            }
            // For Safari
            return question;
        } else {
            return null;
        }
    };
    $('.close-window').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        if (!formChanged || confirm($(this).attr('data-confirm'))) {
            window.onbeforeunload = null;
            window.close();
        }
    });

    // cancel link
    $('.editor-cancel').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        if (!formChanged || confirm($(this).attr('data-confirm'))) {
            window.onbeforeunload = null;
            location.reload();
        }
    });

    // allow submit form
    $('button[type="submit"]').on('click', function(){
        window.onbeforeunload = null;
    });


    // chrome fix rendering (blank page)
    var iframe = $('iframe');
    iframe.load(function() {
        setTimeout(function(){
            iframe.hide().show(0);
        }, 0);
    });
    iframe.attr('src', iframe.attr('data-src'));

    // wysihtml5 fix and general link protection
    $(document).on('click', 'a[href="#"]', function(e){
        e.preventDefault();
    });


    // file image preview and remove button
    $('input[type="file"]').each(function(){
        var object = $(this);
        object.before('<img class="input-file-preview" src="" alt="" style="display:none">');
        object.after('<a class="input-file-remove" href="#" style="display:none">&times;</a>');

        var attr = $(this).attr('data-value');
        if (!!attr) {
            object.prev().attr('src', attr).show();
            object.next().show();
        }
    });
    $('input[type="file"]').on('change', function(){
        var object = $(this);
        if (this.files && this.files[0]) {
            object.prev().hide();
            object.next().show();

            var reader = new FileReader();
            reader.onload = function (e) {
                object.prev().attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        } else if (object.attr('removed')) {
            object.prev().hide();
            var name = object.attr('data-delete-attr');
            object.prev().before('<input type="hidden" name="'+name+'" value="1">');
        }
    });
    $('.input-file-remove').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        var object = $(this);
        object.prev().prev().hide();
        object.hide();
        object.prev().val('').attr('removed','removed').trigger('change');
    });

    // sliders output
    var sliderMoved = function(input) {
        var output = input.next().next();
        if (output.hasClass('output')) {
           output.text(parseFloat(input.val()).toFixed(input.attr('data-precision')));
        }
    }
    $('input[type="range"]').on('input', function(){
        sliderMoved($(this));
    });
    $('input[type="range"]').each(function(){
        sliderMoved($(this));
    });



    // form actions
    var preview = $('#preview');

    // meta title
    $('#CreatorsWebsite_meta_title').on('change', function(){
        $('head title').text($(this).val());
    });
    // template
    $('#CreatorsWebsite_layout').on('change', function(){
        preview.attr('src', preview.attr('data-src')+'/layout/'+$(this).val());
    });
    // theme
    $('#CreatorsWebsite_theme').on('change preview-update', function(){
        var stylesheet = preview.contents().find('link.theme');
        var path = stylesheet.attr('data-path');
        if (typeof path == "undefined") {
            path = '';
        }
        stylesheet.attr('href', path+$(this).val()+'.min.css');
    });
    // favicon - BREAKING BROWSER!
    /*$('#CreatorsWebsite_favicon').on('change', function(){
        ...
    });*/
    // name
    $('#CreatorsWebsite_name').on('input preview-update', function(){
        var value = $(this).val();
        var target = preview.contents().find('#header h1');
        if (value == '') {
            target.hide();
        } else {
            target.html(value).show();
        }
    });
    // name color
    $('#CreatorsWebsite_name_color').on('change preview-update', function(){
        preview.contents().find('#header h1').css('color', $(this).val());
    });
    // header logo
    $('#CreatorsWebsite_logo').on('change preview-update', function(){
        var logo = preview.contents().find('img.header-logo');
        if (logo.length == 0) {
            preview.contents().find('#header h1').before('<img class="header-logo" alt="">');
            logo = preview.contents().find('img.header-logo');
        }
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                logo.attr('src', e.target.result);
                logo.show();
            }
            reader.readAsDataURL(this.files[0]);
        } else if ($(this).attr('removed')) {
            logo.hide();
        }
    });
    // slogan
    $('#CreatorsWebsite_slogan').on('input preview-update', function(){
        var value = $(this).val();
        var target = preview.contents().find('#header p.lead');
        if (value == '') {
            target.hide();
        } else {
            target.html(value).show();
        }
    });
    // name color
    $('#CreatorsWebsite_slogan_color').on('change preview-update', function(){
        preview.contents().find('#header p.lead').css('color', $(this).val());
    });
    // header text align
    $('#CreatorsWebsite_header_text_align').on('change preview-update', function(){
        preview.contents().find('#header').css('text-align', $(this).val());
    });
    // header bg
    $('#CreatorsWebsite_header_bg').on('change preview-update', function(){
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.contents().find('#header').css('background-image', 'url('+e.target.result+')');
            }
            reader.readAsDataURL(this.files[0]);
        } else if ($(this).attr('removed')) {
            preview.contents().find('#header').css('background-image', 'none');
        }
    });
    // extended header bg
    $('#CreatorsWebsite_extended_header_bg').on('change preview-update', function(){
        if (this.checked) {
            preview.contents().find('#header').removeClass('container');
        } else {
            preview.contents().find('#header').addClass('container');
        }
    });
    // header bg brightness
    $('#CreatorsWebsite_header_bg_brightness').on('input preview-update', function(){
        var value = $(this).val();
        if (value > 0) {
            var color = 'rgba(255,255,255,'+value+')';
        } else {
            var color = 'rgba(0,0,0,'+(-value)+')';
        }
        preview.contents().find('#header-wrapper').css('background', color);
    });
    // header height
    $('#CreatorsWebsite_header_height').on('input preview-update', function(e){
        var object = $(this);
        var value = object.val();
        if (e.type == 'preview-update' && object.hasClass('faded')) {
            return;
        }
        object.removeClass('faded');
        preview.contents().find('.header-height').css('height', value+'px');
        $('#CreatorsWebsite_header_heightAuto').removeAttr('checked');
    });
    var header_heightAutoChanged = function(){
        var slider = $('#CreatorsWebsite_header_height');
        if (this.checked) {
            slider.addClass('faded');
            preview.contents().find('.header-height').css('height', 'auto');
        } else {
            slider.removeClass('faded');
            preview.contents().find('.header-height').css('height', slider.val()+'px');
        }
    }
    $('#CreatorsWebsite_header_heightAuto').on('change', header_heightAutoChanged);
    $('#CreatorsWebsite_header_heightAuto').each(header_heightAutoChanged);
		// header social icons
		$('#CreatorsWebsite_header_social_icons').on('change preview-update', function(){
        if (this.checked) {
            preview.contents().find('#header .social-icons').fadeIn();
        } else {
            preview.contents().find('#header .social-icons').hide();
        }
    });


    // PAGES
    // page title and create support
    $('.CreatorsPage_title').on('input preview-update', function(){
        var object = $(this);
        var li = object.closest('li');
        var pageId = li.attr('data-page-id');
        var value = object.val();
        var editorPageTitle = li.find('.editor-page-title-link');
        var title = preview.contents().find('.page-'+pageId+' .page-title');
        var mainMenuPageTitle = preview.contents().find('.main-menu .page-'+pageId+' a');
        var scenario = li.find('.CreatorsPage_scenario').val();

        if (scenario == 'remove') {
            title.hide();
            mainMenuPageTitle.hide();
        } else if (value == '') {
            if (scenario == 'empty') {  // empty add message (on refreshing only)
                var message = '<i class="faded">['+object.attr('data-add-message')+']</i>';
                mainMenuPageTitle.html('');
            } else {    // no name message after deleting text
                var message = '<i class="faded">['+object.attr('data-no-name-message')+']</i>';
                mainMenuPageTitle.html(message);
            }
            editorPageTitle.html(message);
            title.text(value);
        } else {
            if (scenario == 'empty') {
                li.find('.CreatorsPage_scenario').val('create');
                li.find('.editor-page-title-options').show();
                li.closest('fieldset').find('.editor-add-page-message').slideDown();
                li.prev().find('.editor-page-move-down').removeClass('faded');
            }
            if (scenario == 'empty' || scenario == 'create') {
                mainMenuPageTitle.closest('li').show();
            }
            editorPageTitle.text(value);
            title.text(value);
            mainMenuPageTitle.text(value);
        }
    });
    // page type
    $('.CreatorsPage_type').on('change preview-update', function(){
        var object = $(this);
        var value = object.val();
        var itemsPerPageRow = object.closest('li').find('.CreatorsPage_items_per_page').parent();
        // slide items_per_page
        if (value == 'news' || value == 'products' || value == 'services') {
            itemsPerPageRow.slideDown();
        } else {
            itemsPerPageRow.slideUp(function(){
                $(this).find('.CreatorsPage_items_per_page').val('20');
            });
        }
				// slide company_data
				var companyData = object.closest('li').find('.CreatorsPage_company_data').parent().parent();
				if (value == 'about') {
					companyData.slideDown();
				} else {
					companyData.slideUp();
				}
				// slide comments and comments_from_firmbook
				var commentsAndFromFirmbook = object.closest('li')
					.find('.CreatorsPage_comments, .CreatorsPage_comments_from_firmbook').parent().parent();
				if (value == 'about' || value == 'products' || value == 'services' /*|| value == 'news'*/) {
					commentsAndFromFirmbook.slideDown();
				} else {
					commentsAndFromFirmbook.slideUp();
				}
				// slide buttons
				var buttons = object.closest('li')
					.find('.CreatorsPage_buttons');
				if (value == 'products' || value == 'services') {
					buttons.slideDown();
				} else {
					buttons.slideUp();
				}
        // content message
        var li = object.closest('li');
        var pageId = li.attr('data-page-id');
        var scenario = li.find('.CreatorsPage_scenario').val();
        var target = preview.contents().find('.page-'+pageId+' .page-type-content');
        if (value == 'custom' || scenario == 'remove') {
            // changed to custom
            target.empty();
        } else if (value != object.find('option[selected]').val()
                || target.html() == '') {
            // changed to any other but not default
            // OR default but not custom. Check if removed
            target.html('<i class="faded">['+object.attr('data-message')+']</i>');
        }
    });
    // items per page
    $('.CreatorsPage_items_per_page').on('input preview-update', function(){
        var object = $(this);
        var value = object.val();
        var li = object.closest('li')
        var pageId = li.attr('data-page-id');
        var target = preview.contents().find('.page-'+pageId+' .page-type-content');
        var pageType = li.find('.CreatorsPage_type').val();
        var scenario = li.find('.CreatorsPage_scenario').val();
        if (value != this.defaultValue && pageType != 'custom' && scenario != 'remove') {
            // change if not default
            // AND page type not custom
            target.html('<i class="faded">['+object.attr('data-message')+']</i>');
        }
    });
		$('.CreatorsPage_company_data').on('change preview-update', function(){
				var object = $(this);
				var checked = this.checked;
				var li = object.closest('li');
				var pageId = li.attr('data-page-id');
				var pageType = li.find('.CreatorsPage_type').val();
				var scenario = li.find('.CreatorsPage_scenario').val();
				var target = preview.contents().find('.page-'+pageId+' .company-data');
	      if (checked && pageType == 'about' && scenario != 'remove') {
	          target.fadeIn();
	      } else {
	          target.hide();
	      }
		});
    // page content
    var changePageContent = function(object, value) {
        var li = object.closest('li');
        var pageId = li.attr('data-page-id');
        var scenario = li.find('.CreatorsPage_scenario').val();
        var content = preview.contents().find('.page-'+pageId+' .page-content');
        if (scenario == 'remove') {
            content.html('<i class="faded">['+object.attr('data-removed-message')+']</i>');
        } else {
            content.html(value);
        }
    }
		setTimeout(function(){
        $('.CreatorsPage_content').each(function() {
            var object = $(this);
            $(this).data('wysihtml5')
            .editor.on('change', function(){
                changePageContent(object, this.textarea.element.value);
            });
        });
    },0);
    $('.CreatorsPage_content').on('preview-update', function(e){
        var object = $(this);
        changePageContent(object, object.data('wysihtml5').editor.getValue());
    });
		// comments
		$('.CreatorsPage_comments').on('change preview-update', function(){
				var object = $(this);
				var checked = this.checked;
				var li = object.closest('li');
				var pageId = li.attr('data-page-id');
				var pageType = li.find('.CreatorsPage_type').val();
				var scenario = li.find('.CreatorsPage_scenario').val();
				var commentsFromFirmbook = li.find('.CreatorsPage_comments_from_firmbook').parent().parent();
				var target = preview.contents().find('.page-'+pageId+' .comments');
	      if (checked && (pageType == 'about' || pageType == 'products' || pageType == 'services' /*|| pageType == 'news'*/) && scenario != 'remove') {
	          target.fadeIn();
						commentsFromFirmbook.slideDown();
	      } else {
	          target.hide();
						commentsFromFirmbook.slideUp();
	      }
		});
		// comments_from_firmbook
		$('.CreatorsPage_comments_from_firmbook').on('change preview-update', function(){
				var object = $(this);
				var checked = this.checked;
				var li = object.closest('li')
				var pageId = li.attr('data-page-id');
				var pageType = li.find('.CreatorsPage_type').val();
				var scenario = li.find('.CreatorsPage_scenario').val();
				var target = preview.contents().find('.page-'+pageId+' .comments .fb-comments');
	      if (target.length > 0 && scenario != 'remove') {
					if (checked) {
	          target.attr('data-href', target.attr('data-firmbook-href'));
					} else {
						target.attr('data-href', window.location.href);
					}
					if (typeof preview[0].contentWindow.FB == 'object') {
						preview[0].contentWindow.FB.XFBML.parse();
					}
	      }
		});
		// buttons
		$('.CreatorsPage_buttons input[type="checkbox"]').on('change preview-update', function(){
				var object = $(this);
				var checked = this.checked;
				var li = object.closest('li');
				var pageId = li.attr('data-page-id');
				var buttonType = object.val();
				var scenario = li.find('.CreatorsPage_scenario').val();
				var target = preview.contents().find('.page-'+pageId+' .buttons .'+buttonType);
				if (target.length > 0 && scenario != 'remove') {
					if (checked) {
	          target.fadeIn();
					} else {
						target.fadeOut();
					}
	      }
    });
    // home page
    $('.editor-page-home').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        var object = $(this);
        var li = object.closest('li');
        var pageId = li.find('.CreatorsPage_id').val();
        // update hidden input
        $('.CreatorsWebsite_home_page_id').val(pageId);
        // faded
        $('.editor-page-home').addClass('faded');
        object.removeClass('faded');
    });
    // page move up
    var swapPages = function(li, neighbour){
        if (neighbour.length == 0) {
            return;
        }
        li.slideUp(function(){
            // swap home page icons
            var homePageId = $('.CreatorsWebsite_home_page_id').val();
            if (homePageId !== null) {
                if (homePageId == li.find('.CreatorsPage_id').val()) {
                    li.find('.editor-page-home').addClass('faded');
                    neighbour.find('.editor-page-home').removeClass('faded');
                } else if (homePageId == neighbour.find('.CreatorsPage_id').val()) {
                    neighbour.find('.editor-page-home').addClass('faded');
                    li.find('.editor-page-home').removeClass('faded');
                }
            }

            // swap data
            var swapValues = function(name) {
                var input = li.find('.CreatorsPage_'+name);
                var neighbourInput = neighbour.find('.CreatorsPage_'+name);
                var value = input.val();
                input.val(neighbourInput.val());
                neighbourInput.val(value);
            }
            swapValues('id');
            swapValues('scenario');
            swapValues('title');
            swapValues('alias');
            swapValues('type');
            swapValues('items_per_page');

            // swap content
            var contentInput = li.find('.CreatorsPage_content').data('wysihtml5').editor;
            var neighbourContentInput = neighbour.find('.CreatorsPage_content').data('wysihtml5').editor;
            var content = contentInput.getValue();
            contentInput.setValue(neighbourContentInput.getValue());
            neighbourContentInput.setValue(content);

            // swap preview links with class
            var pageId = li.find('.CreatorsPage_id').val();
            var neighbourId = neighbour.find('.CreatorsPage_id').val();
            var menuLi = preview.contents().find('.main-menu .page-'+pageId);
            var menuNeighbourLi = preview.contents().find('.main-menu .page-'+neighbourId);
            var menuLink = menuLi.find('a');
            var menuNeighbourLink = menuNeighbourLi.find('a');
            var menuUrl = menuLink.attr('href');
            var menuNeighbourUrl = menuNeighbourLink.attr('href');
            menuLi.removeClass('page-'+pageId).addClass('page-'+neighbourId);
            menuNeighbourLi.removeClass('page-'+neighbourId).addClass('page-'+pageId);
            menuLink.attr('href', menuNeighbourUrl);
            menuNeighbourLink.attr('href', menuUrl);

            previewUpdate();

            // swap visibility
            neighbour.hide();
            li.show();

            neighbour.slideDown();
        });
    }
    var findNeighbourPage = function(li, back){
        var found = li;
        do {
            if (back) {
                found = found.prev();
            } else {
                found = found.next();
            }
            if (found.length == 0) {
                return found;
            }
            var scenario = found.find('.CreatorsPage_scenario').val();
        } while (scenario == 'remove' || scenario == 'empty');
        return found;
    }
    $('.editor-page-move-up').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        var li = $(this).closest('li');
        var neighbour = findNeighbourPage(li, true);
        swapPages(li, neighbour);
    });
    // page move down
    $('.editor-page-move-down').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        var li = $(this).closest('li');
        var neighbour = findNeighbourPage(li, false);
        swapPages(li, neighbour);
    });
    // page remove
    $('.editor-page-remove').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        if (confirm($(this).attr('data-message'))) {
            var li = $(this).closest('li');
            // update sorting arrows
            var prev = findNeighbourPage(li, true);
            var next = findNeighbourPage(li, false);
            if (prev.length != 0 && next.length == 0) {
                prev.find('.editor-page-move-down').addClass('faded');
            }
            if (next.length != 0 && prev.length == 0) {
                next.find('.editor-page-move-up').addClass('faded');
            }
            // remove
            li.find('.CreatorsPage_scenario').val('remove');
            li.fadeOut();
            formChanged = true;
            // update home page field and icon
            var homePageId = $('.CreatorsWebsite_home_page_id').val();
            if (homePageId !== null && homePageId == li.find('.CreatorsPage_id').val()) {
                li.find('.editor-page-home').addClass('faded');
                var firstPage = li.parent().find('.CreatorsPage_scenario')
                        .filter(function(){return this.value=='create' || this.value=='update'})
                        .first()
                        .closest('li');
                if (firstPage.length != 0) {
                    firstPage.find('.editor-page-home').removeClass('faded');
                    var pageId = firstPage.find('.CreatorsPage_id').val();
                    $('.CreatorsWebsite_home_page_id').val(pageId);
                }
            }
            // update fields to pass validation
            li.find('.CreatorsPage_title').val('none');
            li.find('.CreatorsPage_alias').val('none_'+Math.random().toString(36).substring(8));
            li.find('.CreatorsPage_type').val('custom');
            li.find('.CreatorsPage_content').val('');
            // update preview
            previewUpdate();
        }
    });


    // footer text
    setTimeout(function(){
        $('#CreatorsWebsite_footer_text')
                .data('wysihtml5')
                .editor.on('change', function(){
                    var value = this.textarea.element.value;
                    var target = preview.contents().find('#footer .footer-text');
                    if (value == '') {
                        target.hide();
                    } else {
                        target.html(value).show();
                    }
        });
    },0);
    $('.CreatorsWebsite_footer_text').on('preview-update', function(e){
        var object = $(this);
        var value = object.data('wysihtml5').editor.getValue();
        var target = preview.contents().find('#footer .footer-text');
        if (value == '') {
            target.hide();
        } else {
            target.html(value).show();
        }
    });
		$('#CreatorsWebsite_footer_social_icons').on('change preview-update', function(){
        if (this.checked) {
            preview.contents().find('#footer .social-icons').fadeIn();
        } else {
            preview.contents().find('#footer .social-icons').hide();
        }
    });


		// social icons
		$('#CreatorsWebsite_social_icons_title').on('change preview-update', function(){
        var value = $(this).val();
				var buttons = preview.contents().find('share-button');
				buttons.attr('data-button-text', value);
				if (typeof preview[0].contentWindow.createShareButtons == 'function') {
					preview[0].contentWindow.createShareButtons();
				}
    });
		$('#CreatorsWebsite_social_icons_networks input[type="checkbox"]').on('change preview-update', function(){
				var networks = [];
        var inputs = $('#CreatorsWebsite_social_icons_networks').find('input').each(function() {
					if (this.checked) networks.push(this.value);
				});
				var buttons = preview.contents().find('share-button');
				buttons.attr('data-networks', networks.join(','));
				if (typeof preview[0].contentWindow.createShareButtons == 'function') {
					preview[0].contentWindow.createShareButtons();
				}
    });
});
