/* 原作：https://qa.1r1g.com/sf/ask/1257635361 */
var cookieToday = new Date();
var expiryDate = new Date(cookieToday.getTime() + (1 * 86400000));
function setCookie(name, value, expires, path, theDomain, secure) {
    value = escape(value); var theCookie = name + "=" + value +
        ((expires) ? "; expires=" + expires.toGMTString() : "") +
        ((path) ? "; path=" + path : "") +
        ((theDomain) ? "; domain=" + theDomain : "") +
        ((secure) ? "; secure" : ""); document.cookie = theCookie;
}
function getCookie(Name) {
    var search = Name + "="
    if (document.cookie.length > 0) {
        var offset = document.cookie.indexOf(search)
        if (offset != -1) {
            offset += search.length
            var end = document.cookie.indexOf(";", offset)
            if (end == -1) end = document.cookie.length
            return unescape(document.cookie.substring(offset, end))
        }
    }
}
function delCookie(name, path, domain) {
    if (getCookie(name)) document.cookie = name + "=" +
        ((path) ? ";path=" + path : "") +
        ((domain) ? ";domain=" + domain : "") +
        ";expires=Thu, 01-Jan-70 00:00:01 GMT";
}