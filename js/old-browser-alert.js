/**
 * Skrypt tworzący komunikat informujący o 
 * przestarzałej przeglądarce.
 * 
 * @category scripts
 * @package site
 * @author BAI
 * @copyright (C) 2014 BAI
 */
jQuery(function($) {
    
    // simple fast detection (approximate)
    if('querySelector' in document == false
            || 'localStorage' in window == false
            || 'addEventListener' in window == false) {
        var oldBrowserAccepted = getCookie("oldBrowserAccepted");
        if (oldBrowserAccepted == null || oldBrowserAccepted != "1") {

                    $('.alerts').prepend(
                    '<div class="alert alert-warning alert-old-browser">' + 
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + 
                        (oldBrowserAlert 
                        ? 
                            oldBrowserAlert 
                        :
                            '<strong>Your browser is out of date.</strong> It has known security flaws and may not ' + 
                            'display all features of this and other websites. <br />Recommended browsers for this website:'
                        ) + 
                        '<a href="https://www.google.com/intl/en/chrome/" target="_blank">' + 
                            'Chrome&nbsp;<img src="images/browser-icons/chrome.png" alt="Chrome" />' + 
                        '</a>,&nbsp;' + 
                        '<a href="http://www.mozilla.org/pl/firefox/new/" target="_blank">' + 
                            'Firefox&nbsp;<img src="images/browser-icons/firefox.png" alt="Firefox" />' + 
                        '</a>' + 
                    '</div>'
                    );

            $('.alert-old-browser .close').click(function() {
                setCookie('oldBrowserAccepted', '1', 365);
            });

        }
    }

});


function setCookie(c_name, value, exdays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
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
