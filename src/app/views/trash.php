<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Trash - Felicity'16 Organise</title>
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/common.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/common_edit.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/directory.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <article class="dir">
                <h1 class="dir_title">Trash</h1>
                    <div class="admin_panel padded text-right">
                        <a href="<?= base_url() ?>jugaad/" class="btn btn-green"><i class="fa fa-file"></i> Go to jugaad</a>
                    </div>
                    <?php if ($error): ?>
                        <div class="error"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if ($msg): ?>
                        <div class="msg"><?= $msg ?></div>
                    <?php endif; ?>
                    <div class="list-interface">
                        <ul class="admin_list">
                            <?php
                                if (!count($files)) {
                                    echo "Nothing in trash!";
                                }
                                foreach ($files as $file):
                            ?>
                                <li class="cf">
                                    <span class="pull-left">
                                        <?php if ($file['type'] == 'file'): ?>
                                            <i class="fa fa-file-text-o"></i>
                                        <?php else: ?>
                                            <i class="fa fa-folder-o"></i>
                                        <?php endif; ?>
                                        <?= $file['name'] ?>
                                        <span class="grey"><?= $file['path'] ?></span><br>
                                        <span class="grey">
                                            <?= explode('@', $file["created_by"])[0] ?>
                                            deleted on
                                            <?= date('d M, Y h:ia', strtotime($file["timestamp"])) ?>
                                        </span>
                                    </span>
                                    <span class="pull-right">
                                        <?php
                                            if ($file['recoverable']):
                                        ?>
                                            <form action="" method="post" style="display: inline-block;">
                                                <input type="hidden" name="file_id" value="<?= $file['file_id'] ?>"/>
                                                <button type="submit" name="restore_file" value="Restore file"
                                                    class="btn btn-small btn-green">
                                                    <i class="fa fa-recycle"></i>
                                                    Restore File
                                                </button>
                                            </form>
                                        <?php
                                            else:
                                        ?>
                                            <span class="grey"><?= $file['reason'] ?></span>
                                        <?php
                                            endif;
                                        ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
            </article>
        </div>
    </body>
</html>
