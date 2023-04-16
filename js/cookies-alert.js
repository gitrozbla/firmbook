/**
 * Skrypt wyświetlający komunikat o cookies.
 * 
 * @category scripts
 * @package cookies_alert
 * @author BAI
 * @copyright (C) 2014 BAI
 */
jQuery(function($) {

    var cookiesAccepted = getCookie("cookiesAccepted");
    if (cookiesAccepted == null || cookiesAccepted != "1") {
        
        $('body').append('<div class="cookies-alert">' +
            '<div class="cookies-alert-wrap">' +
                    '<a href="#" class="cookies-accept">&times;</a>' +
                    (cookiesAlert 
                    ? 
                        cookiesAlert 
                    :
                        '<strong>This website uses small files called cookies</strong> to help customise' +
                        'your experience and evaluate how you use our website. <br />' +
                        'If you do not accept the use of cookies please leave this website.'
                    ) +
                '</div>' +
            '</div>');

        if ($('.cookies-alert-css').length == 0) {
            $('head').append('<link class="cookies-alert-css" rel="stylesheet" type="text/css" href="css/cookies-alert.css">');
        }

        $('.cookies-accept').click(function() {
            $('.cookies-alert').slideUp('fast', function() {
                $(this).remove();
            });

            // save
            setCookie('cookiesAccepted', '1', 365);

            return false;
        });

    }

});


function setCookie(c_name, value, exdays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
    //document.cookie = c_name + "=" + c_value+ ";domain=.firmbookeu.localhost;path=/";
    document.cookie = c_name + "=" + c_value;
}

function getCookie(c_name)
{
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++)
    {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name)
        {
            return unescape(y);
        }
    }
}
