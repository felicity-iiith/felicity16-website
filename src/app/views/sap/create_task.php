<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Create Task · Student Ambassador Program · Felicity ʼ16</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
</head>
<body>
    <?php $this->load_fragment('sap/navbar_fragment', ['logged_in' => true]); ?>
    <div class="container">
        <div class="row">
            <div class="col-6-12 col-offset-3-12">
                <h1>Create new task</h1>
                <p>for mission "<a href="../"><?= htmlspecialchars($mission_title) ?></a>"</p>
                <?php
                if (isset($errors) && count($errors) !== 0) {
                    foreach ($errors as $error) {
                        echo "<p class=\"text-error\">$error</p>";
                    }
                }
                ?>
                <form class="block full-width" method="post">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" required></textarea>
                    <label for="has-text-answer">
                        <input type="checkbox" id="has-text-answer" name="has-text-answer" value="true">
                        Text answer required from user?
                    </label>
                    <button type="submit" class="btn btn-green">Create new task</button>
                </form>
            </div>
        </div>
    </div>
    <?php $this->load_fragment('google_analytics'); ?>
</body>
</html>
