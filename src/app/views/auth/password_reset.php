<?php
    $this->load_fragment('auth/form_header', [
        'heading' => $action == "reset_password" ? __("Reset password") : __("Set password")
    ]);
?>
<?php
    if ($success):
?>

<div class="success"><?= __('Password successfully set!') ?></div>
<a href="<?= locale_base_url() . "auth/login/" ?>" class="some-top-margin pure-button pure-button-primary"><?= __('Continue to a magical journey') ?></a>
<?php
    else:
?>
<form action="<?= locale_base_url() . "auth/register/password_reset/" ?>" method="post" class="pure-form pure-form-stacked">
    <fieldset>
        <input type="hidden" name="hash" value="<?= $hash ?>">

        <label for="password"><?= __('Password:') ?></label>
        <input type="password" name="password" id="password" required>


        <label for="confirm_password"><?= __('Confirm pasword:') ?></label>
        <input type="password" name="confirm_password" id="confirm_password" required>


        <input type="submit" value="<?= __('Submit') ?>" class="pure-button pure-button-primary some-top-margin">
    </fieldset>
</form>
<?php
    endif;
?>
<?php $this->load_fragment('auth/form_footer'); ?>
