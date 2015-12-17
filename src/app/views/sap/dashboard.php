<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Ambassador Program · Felicity ʼ16</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>
        <p>Welcome to the SAP portal, <?= $username ?>. :)</p>
        <?php if ($is_admin): ?>
            <p>You are an admin!</p>
        <?php endif; ?>
        <a class="btn" href="<?= base_url() ?>sap/logout/">Logout</a>
    </div>
</body>
</html>
