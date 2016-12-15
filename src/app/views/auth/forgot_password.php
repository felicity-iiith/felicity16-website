<?php
    $this->load_fragment('auth/form_header', ['heading' => __('Forgot your password?') ]);
?>
<?php if ($sent): ?>
    <div class="success"><?= __('Mail sent!') ?></div>
<?php else: ?>
<p><?= __('Please enter your email address.') ?></p>
<form action="" method="post" class="pure-form pure-form-stacked">
    <fieldset>
        <label for="email"><?= __('Email:') ?></label>
        <input type="email" name="email" id="email" required>
        <br/>
        <input type="submit" name="register" value="<?= __('Next') ?>" class="pure-button pure-button-primary">
    </fieldset>
</form>
<?php endif; ?>
<?php $this->load_fragment('auth/form_footer'); ?>
