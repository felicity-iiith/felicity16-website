<?php
    $this->load_fragment('auth/form_header', ['heading' => __('Register') ]);
?>
<?php if ($sent): ?>
    <div class="text-center">
        <span class="success" style="display:inline-block"><?= __('Verification mail sent!') ?></span>
        <p style="margin-top: 1em;"><?= __('To proceed, you must verify your email address by clicking on the link sent to your inbox.') ?></p>
    </div>
<?php else: ?>
<div>
    <p><?= __('We will send an email to this address to verify it.') ?></p>
    <form action="register_email/" method="post" class="pure-form pure-form-stacked">
        <fieldset>
            <label for="email"><?= __('Email:') ?></label>
            <input type="email" id="email" name="email"  class="pure-input-1" required>
            <input type="submit" name="register" value="<?= __('Next') ?>" class="pure-button pure-button-primary some-top-margin">
        </fieldset>
    </form>
</div>
<?php endif; ?>
<?php $this->load_fragment('auth/form_footer'); ?>
