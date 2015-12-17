<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Ambassador Program · Felicity ʼ16</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
</head>
<body>
    <?php $this->load_fragment('sap/navbar_fragment'); ?>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($error) && $error): ?>
            <p style="color:red">Invalid username or password.</p>
        <?php endif; ?>
        <form method="post" class="block">
            <label>Username: <input name="username" type="text"></label>
            <label>Password: <input name="password" type="password"></label>
            <input type="submit">
        </form>
    </div>
</body>
</html>
