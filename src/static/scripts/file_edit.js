function setupEdit() {
    origFile = {
        file_id: document.getElementById('file_id').value,
        version_id: document.getElementById('version_id').value,
    };

    checkRemote();
}

/*
 * Manage remote updates
 */
checkRemote = (function() {
    var dmp;
    var failCount = 0;
    var remoteWIP = false;
    var newVersionId;

    function getDiff(olddata, newdata) {
        dmp = new diff_match_patch();
        var patches = dmp.patch_make(olddata, newdata);
        return patches;
    }

    function closeError(n) {
        remoteWIP = false;
        n.close();
    }

    function failingError() {
        if (failCount < 3) return;
        failCount = 0;
        remoteWIP = true;
        var notif = notify("The server is acting weirdly. Be careful when saving your data.")
            .addButton("Okay", closeError, 'btn btn-small btn-green')
            .addButton("Don't bother me", null, 'btn btn-small btn-red')
            .show();
    }

    function alertRemoteChanged() {
        var notif = notify("Remote version of the file is changed.")
            .addButton("Okay", null, 'btn btn-small btn-red')
            .show();
    }

    return function() {
        if (remoteWIP) {
            setTimeout(checkRemote, 1000);
            return;
        }
        remoteWIP = true;

        var url = base_url + 'ajax/latest_version_id/?file_id=' + origFile.file_id;

        ajax(url)
            .type("json")
            .success(function() {
                newVersionId = this.response;
                if (newVersionId > origFile.version_id) {
                    alertRemoteChanged();
                } else {
                    remoteWIP = false;
                }
            })
            .fail(function() {
                remoteWIP = false;
                failCount += 1;
                failingError();
            })
            .complete(function() {
                setTimeout(checkRemote, 1000);
            })
            .send();
    };
})();
