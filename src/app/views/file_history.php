<?php
    function get_past($action) {
        switch ($action) {
            case 'create':
                $action = 'created';
                break;
            case 'edit':
                $action = 'edited';
                break;
            case 'delete':
                $action = 'deleted';
                break;
            case 'recover':
                $action = 'recovered';
                break;
            default:
                $action = false;
                break;
        };
        return $action;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $name ?> - Felicity'16 Organise</title>
        <script src="<?= base_url() ?>static/scripts/vendor/marked.min.js"></script>
        <script src="<?= base_url() ?>static/scripts/common_edit.js"></script>
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/common.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/common_edit.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/file.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/diff_match_patch/20121119/diff_match_patch.js"></script>
    </head>
    <body>
        <div class="container">
            <nav>
                <a class="btn btn-blue" href="."><i class="fa fa-arrow-left"></i> Go back to file</a>
            </nav>
            <article class="file">
                <h1 class="file_title"><?= $name ?></h1>
                <?php
                    $initial = true;
                    if (isset($history_diff)):
                        $username = explode('@', $history_diff[0]["created_by"])[0];
                        $time = date('d M, Y h:ia', strtotime($history_diff[0]["timestamp"]));
                        if (count($history_diff) == 2) {
                            $initial = false;
                            $old_username = explode('@', $history_diff[1]["created_by"])[0];
                            $old_time = date('d M, Y h:ia', strtotime($history_diff[1]["timestamp"]));
                        }
                ?>
                        <h2><?= $username ?>'s<span style="color: #777"> edit on </span><?= $time ?></h2>
                        <?php if (!$initial): ?>
                            <h4>Compared with <?= $old_username ?>'s<span style="color: #777"> edit on </span><?= $old_time ?></h4>
                        <?php endif; ?>
                        <div id="diff"></div>
                        <div id="newdata" style="display:none"><?= $history_diff[0]["data"] ?></div>
                        <div id="olddata" style="display:none"><?= $initial ? "" : $history_diff[1]["data"] ?></div>
                <?php
                    elseif (!empty($perm_error)):
                ?>
                        <div class="error">Sorry, you don't have permission to see edit details.</div>
                <?php
                    elseif (isset($history_item)):
                        $username = explode('@', $history_item["created_by"])[0];
                        $time = date('d M, Y h:ia', strtotime($history_item["timestamp"]));
                        $action = get_past($history_item['action']);
                ?>
                        <h2><?= $username ?><span style="color: #777"> <?= $action ?> file on </span><?= $time ?></h2>
                <?php
                    endif;
                ?>
                <ul class="item_list">
                <?php
                    foreach ($history as $edit):
                        $username = explode('@', $edit["created_by"])[0];
                        $time = date('d M, Y h:ia', strtotime($edit["timestamp"]));
                        $action = get_past($edit['action']);
                ?>
                        <li>
                            <a href="?history&id=<?= $edit["id"] ?>">
                                <i class="fa fa-history"></i>
                                <?= $username ?><span style="color: #777"> <?= $action ?> on </span><?= $time ?>
                            </a>
                        </li>
                <?php
                    endforeach;
                ?>
                </ul>
            </article>
        </div>
        <script>
            function diff_prettyHtml(diffs) {
                // Adapted from https://code.google.com/p/google-diff-match-patch
                var html = [];
                var pattern_amp = /&/g;
                var pattern_lt = /</g;
                var pattern_gt = />/g;
                var pattern_para = /\n/g;
                for (var x = 0; x < diffs.length; x++) {
                    var op = diffs[x][0];    // Operation (insert, delete, equal)
                    var data = diffs[x][1];  // Text of change.
                    var text = data.replace(pattern_amp, '&amp;').replace(pattern_lt, '&lt;')
                        .replace(pattern_gt, '&gt;').replace(pattern_para, '↵<br>');
                    switch (op) {
                        case DIFF_INSERT:
                            html[x] = '<ins>' + text + '</ins>';
                            break;
                        case DIFF_DELETE:
                            html[x] = '<del>' + text + '</del>';
                            break;
                        case DIFF_EQUAL:
                            html[x] = '<span>' + text + '</span>';
                            break;
                    }
                }
                return html.join('');
            }
            (function() {
                var newdata = document.getElementById('newdata').innerHTML;
                var olddata = document.getElementById('olddata').innerHTML;
                var dmp = new diff_match_patch();
                var d = dmp.diff_main(olddata, newdata);
                dmp.diff_cleanupSemantic(d);
                var diffout = diff_prettyHtml(d);

                var diff = document.getElementById('diff');
                diff.innerHTML = diffout;
            })();
        </script>
    </body>
</html>
