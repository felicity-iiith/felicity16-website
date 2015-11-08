<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $name ?> - Felicity'16 Organise</title>
        <script src="<?= base_url() ?>static/scripts/common.js"></script>
        <script src="<?= base_url() ?>static/scripts/common_edit.js"></script>
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/common.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/common_edit.css">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/directory.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <h1 class="file_title">Editing directory: <?= $name ?></h1>
            <nav>
                <a class="btn btn-blue" href="."><i class="fa fa-arrow-left"></i> Go back to directory</a>
            </nav>
            <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
            <?php endif; ?>
            <div class="row">
                <div class="col-6-12 padded" style="border-right: 1px solid #ccc;">
                    <form class="block" action="" method="post">
                        <h2>Add file/directory</h2>
                        <input type="hidden" name="parent_id" value="<?= $id ?>"/>
                        <label for="name">Name: <input type="text" name="name" id="newname" required /></label>
                        <label for="slug">Slug: <input type="text" name="slug" id="newslug" required /></label>
                        <label for="type">Type:
                            <select name="type" class="text-input">
                                <option value="file">File</option>
                                <option value="directory">Directory</option>
                            </select>
                        </label>
                        <label for="template">Template:
                            <select name="template" class="text-input">
                                <?php foreach ($templates as $t): ?>
                                    <option value="<?= $t ?>"><?= $t ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label for="type">Default role:
                            <select class="text-input" name="default_role" required>
                                <option value="none">None</option>
                                <option value="reader" selected>Reader</option>
                                <option value="author">Author</option>
                                <option value="admin">Admin</option>
                            </select>
                        </label>

                        <input type="submit" class="btn-green" name="add" value="Add"/>
                    </form>
                </div>
                <div class="col-6-12 padded">
                    <form class="block" action="" method="post">
                        <h2>Rename directory</h2>
                        <input type="hidden" name="file_id" value="<?= $id ?>"/>
                        <input type="hidden" name="version_id" value="<?= $version_id ?>"/>
                        <label for="name">Name: <input type="text" name="name" value="<?= $name ?>" required /></label>
                        <?php if ($id != 0): ?>
                            <label for="slug">Slug: <input type="text" name="slug" value="<?= $slug ?>" required /></label>
                        <?php endif; ?>
                        <input type="submit" class="btn-green" name="save" value="Save"/>
                    </form>
                </div>
            </div>
            <div class="row">
                <?php
                    $this->load_fragment('user_edit');
                ?>
                <?php
                    if ($id != 0) {
                        $this->load_fragment('delete_file');
                    }
                ?>
            </div>
        </div>
        <script>
            (function() {
                var newname = document.getElementById("newname");

                function updateSlug() {
                    var newslug = document.getElementById("newslug");
                    newslug.value = getSlug(newname.value);
                }

                newname.addEventListener('keyup', updateSlug);
                newname.addEventListener('blur', updateSlug);
            })();
        </script>
    </body>
</html>
