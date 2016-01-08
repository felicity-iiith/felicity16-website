<div class="error">
    <?= nl2br($error) ?>
</div>
<?php
    if ($success):
?>
Password successfully set
<?php
    else:
?>
<form action="<?= base_url() . "auth/register/password_reset/" ?>" method="post">
    <input type="hidden" name="hash" value="<?= $hash ?>">
    <label>Password: <input type="password" name="password"></label>
    <label>Password reset: <input type="password" name="confirm_password"></label>
    <input type="submit" value="Submit">
</form>
<?php
    endif;
?>
