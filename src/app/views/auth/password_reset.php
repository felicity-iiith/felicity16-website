<?php
    $this->load_fragment('auth/form_header', ['heading' => 'Reset password' ]);
?>
<?php
    if ($success):
?>
<div class="success">Password successfully set</div>
<?php
    else:
?>
<form action="<?= base_url() . "auth/register/password_reset/" ?>" method="post" class="pure-form pure-form-stacked">
    <fieldset>
        <input type="hidden" name="hash" value="<?= $hash ?>">

        <label for="">Password:</label>
        <input type="password" name="password">

        <label for="">Password reset:</label>
        <input type="password" name="confirm_password">
        <br/>

        <input type="submit" value="Submit" class="pure-button pure-button-primary">
    </fieldset
</form>
<?php
    endif;
?>
<?php $this->load_fragment('auth/form_footer'); ?>
