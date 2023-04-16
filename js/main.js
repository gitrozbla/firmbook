/**
 * Skrypty ogólne.
 * 
 * M.in.: okienko potwierdzenia operacji, 
 * obsługa linków z klasą 'cool-ajax' i inne.
 * 
 * @category scripts
 * @package site
 * @author BAI
 * @copyright (C) 2014 BAI
 */
jQuery(function(){
    
    // confirm-box
    $(document).on('click', '.confirm-box', function(e){
        var object = $(this);
        var message = object.attr('data-message');
        if (typeof message == 'undefined') {
            message = '"'+$.trim(object.text())+'"\n\nAre you really want to perform this operation?';
        }
        if(!confirm(message)) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });
    
    
    // cool-ajax
    $(document).on('click', '.cool-ajax', function(e){
        if(e.isPropagationStopped()) return;  //important, check for it!
        
        var object = $(this);
        if (object.hasClass('cool-ajax-working')) {
            e.preventDefault();
            return false;
        }
        object.addClass('cool-ajax-working');
        
        $.ajax({
            'dataType':'json',
            'success':function(data) {
                object.removeClass('cool-ajax-working');
                coolAjaxProcess(data);
            },
            'error':function(data) {
                object.removeClass('cool-ajax-working');
                coolAjaxError(data);
            },
            'url' : $(this).attr('href'),
            'cache' : false
        });
        
        e.preventDefault();
        return false;
    });
    
    $('.editor-button').popover('show');
    
    // clear-field support
    $(document).on('click', '.clear-field', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        $(this).closest('.input-append').find('input').val('');
    });
		

    // expand-button
    $('.expand-button').on('click', function(e) {
        e.preventDefault();

        $(this).hide().next().slideDown();
    });
});

function coolAjaxProcess(data) {
    if (data.redirect) {
        window.location.href = data.redirect;
        window.location.reload();
        return;
    }
    
    if (data.alerts) {
        $(data.alerts).each(function(){
            coolAjaxAlert(this.type, this.message);
        });
    }
    if (data.script) {
        $(data.script).each(function(){
            eval(this.script);
        });
    }
}

function coolAjaxError(xhr) {
    coolAjaxAlert('error', 'Wystąpił błąd: ' + xhr.status + '<br />' + xhr.statusText);
    console.error('Error response: ' + xhr.responseText);
}

function coolAjaxAlert(type, message) {
    $('.alerts').append(
        '<div class="alert in alert-block fade alert-' + type + '" style="display: none">'
            + '<a href="#" class="close" data-dismiss="alert">×</a>'
            + message
        + '</div>')
    .find('> div:last-child')
    .slideDown();
}


// search first time toggle
function searchToggleEnable(type, searchForm) {
    if (type == 'advanced' ) {
        $('.search-advanced').hide().html(searchForm);
        showAdvancedSearch();
    } else {
        $('.search-simple').hide().html(searchForm)
        showSimpleSearch();
    }
    
    // fast toggle support
    $('.search-expand').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        showAdvancedSearch();
    });
    $('.search-collapse').on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        showSimpleSearch();
    });
}

function showSimpleSearch() {
    var searchSimple = $('.search-simple');
    var searchAdvanced = $('.search-advanced');
    
    // copy query input value
    var value = searchAdvanced.find('.query-input').val();
    searchSimple.find('.query-input').val(value);
    
    // slide
    searchSimple.slideDown();
    searchAdvanced.slideUp();
    
    // popover fix
    /*$('[data-toggle="popover"]').popover('hide');*/
}

function showAdvancedSearch() {
    var searchSimple = $('.search-simple');
    var searchAdvanced = $('.search-advanced');
    
    // copy query input value
    var value = searchSimple.find('.query-input').val();
    searchAdvanced.find('.query-input').val(value);
    
    // slide
    searchSimple.slideUp();
    searchAdvanced.slideDown();
    
    // popover fix
    /*$('[data-toggle="popover"]').popover('hide');*/
}

// odświeżanie z alertem
function reload(url)
{
    $('body').append('<div class="page-reload">\n\
            <div>\n\
                <i class="fa fa-refresh fa-spin"></i>\n\
            </div>\n\
        </div>');
    
    if (typeof url !== 'undefined') {
        location.replace(url);
    } else {    
        location.reload();
    }
}

function reloadAlert(message)
{
	if (typeof message !== 'undefined' && message != false) {
        window.onbeforeunload = function (e) {
			var e = e || window.event;
			// For IE and Firefox
			if (e) {
				e.returnValue = message;
			}
			// For Safari
			return message;
		};
    } else {    
        window.onbeforeunload = null;
    }
}
