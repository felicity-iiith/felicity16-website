var mdText = '',
    origFile,
    file_md_edit,
    file_md, file_name_edit,
    file_slug_edit,
    dummyText,
    ignore_change;
function setHeight(fieldId){
    dummyText.value = file_md_edit.value;

    file_md_edit.style.height = (dummyText.scrollHeight + 20) + 'px';
}
function updateMD() {
    // Update ignore changes button
    if (origFile.name != escapeHtml(file_name_edit.value) ||
        origFile.slug != escapeHtml(file_slug_edit.value) ||
        origFile.data != escapeHtml(file_md_edit.value)
    ) {
        ignore_change.innerHTML = "(Discard changes)";
    } else {
        ignore_change.innerHTML = "";
    }
    // Set height of textarea
    setHeight();
    // Convert markdown
    if (mdText == file_md_edit.value) return;
    mdText = file_md_edit.value;
    file_md.innerHTML = marked(mdText);
}
function setupEdit() {
    file_md_edit = document.getElementById('file_md_edit');
    file_md = document.getElementById('file_md');
    file_name_edit = document.getElementById('editname');
    file_slug_edit = document.getElementById('editslug');
    dummyText = document.getElementById('dummyTextarea');
    ignore_change = document.getElementById('ignore_change');
    origFile = {
        file_id: document.getElementById('file_id').value,
        version_id: document.getElementById('version_id').value,
        name: document.getElementById('orig_file_name').innerHTML,
        slug: document.getElementById('orig_file_slug').innerHTML,
        data: document.getElementById('orig_file_data').innerHTML,
    };
    file_md_edit.addEventListener('keypress', setHeight);
    file_md_edit.addEventListener('keyup', setHeight);

    updateMD();
    window.setInterval(updateMD, 1000);

    checkRemote();

    if (!window.location.hash) {
        file_md_edit.focus();
    }
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

    function automergeError() {
        remoteWIP = true;
        var notif = notify("Could not merge all the edits.")
            .addButton("Okay", closeError, 'btn btn-small btn-green')
            // .addButton("Discard your changes", null, 'btn btn-small btn-red')
            // .addButton("Discard remote changes", null, 'btn btn-small btn-red')
            .show();
    }

    function pathChangedError() {
        remoteWIP = true;
        var notif = notify("Seems like the file's path is changed. Be careful when saving your data.")
            .addButton("Okay", null, 'btn btn-small btn-green')
            .show();
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

    var cleanUpDummyDiv = document.createElement('div');
    function cleanUpServerData(data) {
        cleanUpDummyDiv.innerHTML = data;
        return cleanUpDummyDiv.innerHTML;
    }

    function attemptAutomerge(n) {
        n.close();

        var error_msg = document.getElementById('error_msg');
        if (error_msg) error_msg.innerHTML = '';

        var url = base_url + 'ajax/latest_version/?file_id=' + origFile.file_id;
        var patches;

        ajax(url)
            .type("json")
            .success(function() {
                remoteWIP = false;
                if (this.response) {
                    var newFile = {
                        slug: cleanUpServerData(this.response.slug),
                        name: cleanUpServerData(this.response.name),
                        data: cleanUpServerData(this.response.data)
                    };
                    if (newFile.slug != origFile.slug) {
                        pathChangedError();
                        return false;
                    }
                    if (newFile.name != origFile.name) {
                        file_name_edit.value = newFile.name;
                        origFile.name = newFile.name;
                    }
                    if (newFile.data != origFile.data) {
                        patches = patches || getDiff(origFile.data, file_md_edit.value);
                        var mergeResult = dmp.patch_apply(patches, newFile.data);
                        var newData = mergeResult[0];
                        if (mergeResult[1].filter(function(r) {return !r;}).length) {
                            automergeError();
                        }
                        file_md_edit.value = newData;
                        origFile.data = newFile.data;

                        document.getElementById('version_id').value = newVersionId;
                    }
                    origFile.version_id = newVersionId;
                    file_md_edit.focus();
                } else {
                    failCount += 1;
                }
            })
            .fail(function() {
                remoteWIP = false;
                failCount += 1;
                failingError();
            })
            .send();

        patches = patches || getDiff(origFile.data, file_md_edit.value);
    }

    function alertRemoteChanged() {
        var msgText = (origFile.data != escapeHtml(file_md_edit.value)) ? "Attempt Automerge" : "Load changes";
        var notif = notify("Remote version of the file is changed.")
            .addButton(msgText, attemptAutomerge, 'btn btn-small btn-green')
            .addButton("Don't bother me", null, 'btn btn-small btn-red')
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
