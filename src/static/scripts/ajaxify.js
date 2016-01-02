/* global ajax */
/* exported loadContent */

var loadContent = (function() {
    var pageCache = {};

    function createRequest(url, callback) {
        function errorCallback() {
            console.log('error');
        }
        function httpErrorCallback() {
            console.log('http error');
        }
        function successCallback() {
            console.log('success');
            callback(this.responseText);
        }
        ajax(url)
            .error(errorCallback)
            .httpError(httpErrorCallback)
            .success(successCallback)
            .requestHeader('X-Ajax-Request', true)
            .send();
    }

    function fillContent(element, data) {
        element.html(data);
    }

    return function(url, element) {
        if (pageCache.hasOwnProperty(url)) {
            fillContent(element, pageCache[url]);
        } else {
            createRequest(url, function(data) {
                pageCache[url] = data;
                fillContent(element, data);
            });
        }
    };

})();
