<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $name ?> - Felicity'16 Organise</title>
        <script src="<?= base_url() ?>static/scripts/vendor/marked.min.js"></script>
        <script src="<?= base_url() ?>static/scripts/common.js"></script>
        <script src="<?= base_url() ?>static/scripts/common_edit.js"></script>
        <script src="<?= base_url() ?>static/scripts/file_edit.js"></script>
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/common.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/common_edit.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/file.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/diff_match_patch/20121119/diff_match_patch.js"></script>
        <script>
            var base_url = "<?= base_url() ?>";
        </script>
    </head>
    <body onload="setupEdit()">
        <nav>
            <a class="btn btn-blue" href="."><i class="fa fa-arrow-left"></i> Go back to file <span id="ignore_change"></span></a>
            <?php if ($user_can['manage_user']): ?>
            <a class="btn btn-blue" href="#useredit"><i class="fa fa-user"></i> Edit user permissions (scroll down) <span id="ignore_change"></span></a>
            <?php endif; ?>
        </nav>
        <?php if ($error): ?>
        <div class="error" id="error_msg"><?= $error ?></div>
        <?php endif; ?>
        <article class="file">
            <form action="" method="post" class="file_edit">
                <input type="hidden" name="file_id" value="<?= $id ?>" id="file_id"/>
                <input type="hidden" name="version_id" value="<?= $version_id ?>" id="version_id"/>
                <div class="file_title_edit">
                    <label for="filename">Name: </label><input type="text" name="name" id="editname" value="<?= isset($unsaved) ? $unsaved["name"] : $name ?>" required />
                    <label for="slug">Slug: </label><input type="text" name="slug" id="editslug" value="<?= isset($unsaved) ? $unsaved["slug"] : $slug ?>" required />
                    <input type="submit" class="btn btn-green" name="save" value="Save page"/>
                </div>
                <div id="orig_file">
                    <div id="orig_file_name" hidden><?= $name ?></div>
                    <div id="orig_file_slug" hidden><?= $slug ?></div>
                    <div id="orig_file_data" hidden><?= $data ?></div>
                </div>
                <div class="editor">
                    <div id="file_edit_contain">
                        <textarea id="file_md_edit" class="file_content" name="data"
                            placeholder="Write your markdown text here."
                            ><?= isset($unsaved) ? $unsaved["data"] : $data ?></textarea>
                        <textarea id="dummyTextarea"></textarea>
                    </div>
                    <section id="file_md" class="file_content"></section>
                </div>
            </form>
            <?php
                $this->load_fragment('user_edit');
            ?>
            <?php
                $this->load_fragment('delete_file');
            ?>
        </article>
    </body>
</html>
