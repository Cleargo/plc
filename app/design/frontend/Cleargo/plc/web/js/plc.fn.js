var isChrome = navigator.userAgent.indexOf('Chrome') > -1;
var isIE = navigator.userAgent.indexOf('MSIE') > -1;
var isFirefox = navigator.userAgent.indexOf('Firefox') > -1;
var isSafari = navigator.userAgent.indexOf("Safari") > -1;
var isOpera = navigator.userAgent.indexOf("Presto") > -1;
var isiPhone = navigator.userAgent.toLowerCase().indexOf("iphone");
var isiPad = navigator.userAgent.toLowerCase().indexOf("ipad");
var isiPod = navigator.userAgent.toLowerCase().indexOf("ipod");
if ((isChrome) && (isSafari)) {
    isSafari = false;
}

//---- Detect Mobile / Tablet ----------------
jQuery.isDesktop = function () {
    if (navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)) {
        return false;
    } else {
        return true;
    }
};