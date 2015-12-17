<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Create Mission · Student Ambassador Program · Felicity ʼ16</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
</head>
<body>
    <?php $this->load_fragment('sap/navbar_fragment', ['logged_in' => true]); ?>
    <div class="container">
        <div class="row">
            <div class="col-6-12 col-offset-3-12">
                <h1>Create new mission</h1>
                <?php
                if (isset($errors) && count($errors) !== 0) {
                    foreach ($errors as $error) {
                        echo "<p style=\"color:red\">$error</p>";
                    }
                } else if (isset($result)) {
                    if ($result === false) {
                        echo "<p style=\"color:red\">Something went wrong. :/</p>";
                    }
                }
                ?>
                <?php if (isset($result) && is_int($result)): ?>
                    <p>Mission created!</p>
                    <a class="btn btn-blue" href="<?= base_url() ?>sap/portal/mission/<?= $result ?>">
                        Go to mission
                    </a>
                <?php endif; ?>
                <form method="post" class="block full-width">
                    <label>Title: <input name="title" type="text" required></label>
                    <label>Level: <input name="level" class="text-input" type="number" min="1" required></label>
                    <label>Points: <input name="points" class="text-input" type="number" min="0" step="5" required></label>
                    <label for="description">Description:</label>
                    <textarea name="description"></textarea>
                    <input class="btn btn-green" type="submit">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
