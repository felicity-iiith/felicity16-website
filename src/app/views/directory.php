<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $slug ?> - Felicity'16 Jugaad</title>
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/common.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/directory.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <article class="dir">
                <h1 class="dir-title"><?= $slug ?></h1>
                <div class="admin-panel padded text-right">
                    <?php if ($user_can['write_file']): ?>
                        <a href="?edit" id="edit-btn" class="btn btn-blue"><i class="fa fa-pencil"></i> Edit / Add file</a>
                    <?php endif; ?>
                    <?php if ($id == 0 && $user_can['see_global_trash']): ?>
                        <a href="<?= base_url() ?>trash/" class="btn btn-green"><i class="fa fa-trash"></i> Go to trash</a>
                    <?php endif; ?>
                </div>
                <?php 
                    if (empty($data)) {
                        echo "<p>No files/directories are currently present.</p>";
                    }
                ?>
                <div class="list-interface">
                    <?php
                        if ($parent != -1) {
                            echo '<a class="btn btn-blue" href="..">Go to parent <i class="fa fa-level-up"></i></a>';
                        }
                    ?>
                    <ul class="item-list">
                        <?php foreach ($data as $file): ?>
                                <li>
                                    <a href="<?= $file['slug'] ?>">
                                        <?php if ($file['type'] == 'file'): ?>
                                            <i class="fa fa-file-text-o"></i>
                                        <?php else: ?>
                                            <i class="fa fa-folder-o"></i>
                                        <?php endif; ?>
                                        <?= $file['slug'] ?>
                                    </a>
                                </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </article>
        </div>
        <script>
        var links = document.querySelectorAll('.item-list a');
        for (i = 0; i < links.length; ++i) {
            links[i].addEventListener('mouseenter', function() {
                var icon = this.querySelector('i');
                if (icon.classList.contains('fa-folder-o')) {
                    icon.classList.remove('fa-folder-o');
                    icon.classList.add('fa-folder');
                } else if (icon.classList.contains('fa-file-text-o')) {
                    icon.classList.remove('fa-file-text-o');
                    icon.classList.add('fa-file-text');
                }
            });
            links[i].addEventListener('mouseleave', function() {
                var icon = this.querySelector('i');
                if (icon.classList.contains('fa-folder')) {
                    icon.classList.remove('fa-folder');
                    icon.classList.add('fa-folder-o');
                } else if (icon.classList.contains('fa-file-text')) {
                    icon.classList.remove('fa-file-text');
                    icon.classList.add('fa-file-text-o');
                }
            });
        }
        </script>
    </body>
</html>
