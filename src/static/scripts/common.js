/* exported getSlug, escapeHtml, unescapeHtml, notify, ajax */

/**
 * Get slug for given string
 *
 * @param  {string} str
 * @return {string}
 */
function getSlug(str) {
    return str
        .toLowerCase()
        .replace(/[^a-z0-9]/g, '-')
        .replace(/-+/g, '-')
        .replace(/-$/, '')
        .replace(/^-/, '');
}

/**
 * php `htmlspecialchars` equivalent, escapes HTML special characters
 *
 * @param  {string} text String to be escaped
 * @return {string}      Escaped string
 */
function escapeHtml(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

/**
 * unescapes HTML special characters escaped by `escapeHtml`
 *
 * @param  {string} text String to be unescaped
 * @return {string}      Unescaped string
 */
function unescapeHtml(text) {
  return text
      .replace(/&amp;/g, "&")
      .replace(/&lt;/g, "<")
      .replace(/&gt;/g, ">")
      .replace(/&quot;/g, "\"")
      .replace(/&#039;/g, "'");
}

/*
 * Notification utils
 */
function notify(msg) {
    return new Notif(msg);
}

var Notif = (function() {
    var notifArea = document.createElement('div');
    notifArea.className = 'notif-area';

    var notifAreaAppended = false;

    function Notif(msg) {
        var notifDiv = document.createElement('div');
        notifDiv.className = 'notif';

        var msgDiv = document.createElement('div');
        msgDiv.className = 'notifMsg';
        msgDiv.innerHTML = msg;

        var btnDiv = document.createElement('div');
        btnDiv.className = 'notif-btn';

        notifDiv.appendChild(msgDiv);
        notifDiv.appendChild(btnDiv);

        this.notifDiv = notifDiv;
        this.msgDiv = msgDiv;
        this.btnDiv = btnDiv;
    }

    Notif.prototype = {
        expireAfter: function(seconds) {
            window.setTimeout((function() {
                if (!this.notifDiv) return;
                this.close();
            }).bind(this), seconds * 1000);
            return this;
        },
        addButton: function(text, callback, classes) {
            callback = typeof callback == 'function' ? callback : undefined;
            classes = classes || "";
            var button = document.createElement('button');
            button.className = (classes).trim();
            button.innerHTML = text;
            button.addEventListener('click', (function() {
                if (callback) callback(this);
                else this.close();
            }).bind(this));
            this.btnDiv.appendChild(button);
            return this;
        },
        close: function() {
            if (!this.notifDiv) return;
            notifArea.removeChild(this.notifDiv);
            return this;
        },
        show: function() {
            if (!this.notifDiv) return;
            if (!notifAreaAppended) {
                document.body.appendChild(notifArea);
                notifAreaAppended = true;
            }
            notifArea.appendChild(this.notifDiv);
            return this;
        }
    };

    return Notif;
})();

/*
 * AJAX utils
 */

function ajax(url) {
    return new AjaxRequest(url);
}

function AjaxRequest(url) {
    this.url = url;
    this.req = new XMLHttpRequest();
    this.doAsync = true;
}

AjaxRequest.prototype = {
    load: function(callback) {
        this.req.addEventListener('load', callback);
        return this;
    },
    error: function(callback) {
        this.req.addEventListener('error', callback);
        return this;
    },
    httpError: function(callback) {
        this.req.addEventListener('load', function(e) {
            if (!(this.status >= 200 && this.status < 300 || this.status < 304)) {
                callback.bind(this)(e);
            }
        });
        return this;
    },
    success: function(callback) {
        this.req.addEventListener('load', function(e) {
            if (this.status >= 200 && this.status < 300 || this.status < 304) {
                callback.bind(this)(e);
            }
        });
        return this;
    },
    fail: function(callback) {
        this.error(callback);
        this.httpError(callback);
        return this;
    },
    abort: function(callback) {
        this.req.addEventListener('abort', callback);
        return this;
    },
    complete: function(callback) {
        this.req.addEventListener('loadend', callback);
        return this;
    },
    progress: function(callback) {
        this.req.addEventListener('progress', callback);
        return this;
    },
    type: function(responseType) {
        this.req.responseType = responseType;
        return this;
    },
    async: function(async) {
        this.doAsync = async;
        return this;
    },
    open: function(method, url) {
        method = method || 'GET';
        this.url = url || this.url;
        if (this.req.readyState === 0) {
            this.req.open(method, this.url, this.doAsync);
        }
        return this;
    },
    requestHeader: function(header, value) {
        if (this.req.readyState === 0) {
            this.open();
        }
        this.req.setRequestHeader(header, value);
        return this;
    },
    send: function() {
        if (this.req.readyState === 0) {
            this.open();
        }
        if (this.req.readyState === 1) {
            this.req.send();
        }
        return this;
    }
};
