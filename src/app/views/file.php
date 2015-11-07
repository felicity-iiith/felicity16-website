<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $name ?> - Felicity'16 Organise</title>
        <script src="<?= base_url() ?>static/scripts/vendor/marked.min.js"></script>
        <script src="<?= base_url() ?>static/scripts/common.js"></script>
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/common.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <nav>
                <a class="btn btn-blue" href=".."><i class="fa fa-arrow-left"></i> Go back to directory</a>
            </nav>
            <article class="file">
                <h1 class="file_title"><?= $name ?></h1>
                <aside class="admin_panel padded text-right">
                    <?php
                        if ($user_can['write_file']):
                    ?>
                    <a href="?edit" id="edit-btn" class="btn btn-blue"><i class="fa fa-pencil"></i> Edit</a>
                    <?php
                        endif;
                    ?>
                    <a href="?history" id="history-btn" class="btn btn-blue"><i class="fa fa-history"></i> History</a>
                </aside>
                <section id="file_md" class="file_content"><?= $data ?></section>
            </article>
        </div>
        <script>
            (function(){
                // Convert markdown
                file_md = document.getElementById('file_md');
                mdText = unescapeHtml(file_md.innerHTML);
                file_md.innerHTML = marked(mdText);
            })();
        </script>
    </body>
</html>
