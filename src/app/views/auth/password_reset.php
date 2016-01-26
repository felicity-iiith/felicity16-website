<?php
    $this->load_fragment('auth/form_header', [
        'heading' => $action == "reset_password" ? "Reset password" : "Set password"
    ]);
?>
<?php
    if ($success):
?>
<div class="success">Password successfully set!</div>
<a href="<?= locale_base_url() . "auth/login/" ?>" class="some-top-margin pure-button pure-button-primary pure-button-large">Continue to a magical journey</a>
<?php
    else:
?>
<form action="<?= locale_base_url() . "auth/register/password_reset/" ?>" method="post" class="pure-form pure-form-stacked">
    <fieldset>
        <input type="hidden" name="hash" value="<?= $hash ?>">

        <label for="">Password:</label>
        <input type="password" name="password" required>

        <label for="">Password reset:</label>
        <input type="password" name="confirm_password" required>
        <br/>

        <input type="submit" value="Submit" class="pure-button pure-button-primary">
    </fieldset
</form>
<?php
    endif;
?>
<?php $this->load_fragment('auth/form_footer'); ?>
