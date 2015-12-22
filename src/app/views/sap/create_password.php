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
        <h1>Create new password</h1>
        <form method="post" class="block" action="<?= base_url() ?>sap/confirm/">
            <label>Password: <input name="password" type="password"></label>
            <label>Confirm Password: <input name="repassword" type="password"></label>
            <p class="text-error password-error"></p>
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="hash" value="<?= $hash ?>">
            <input type="submit" value="Submit">
        </form>
        <script type="text/javascript">
            window.onload = function () {
                document.querySelector('form').onsubmit = function (e) {
                    if (this.password.value !== this.repassword.value) {
                        e.preventDefault();
                        document.querySelector('.password-error').textContent = "Password and confirm password do not match";
                    }
                }
            }
        </script>
    </div>
</body>
</html>
