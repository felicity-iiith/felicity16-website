<?php
    $this->load_fragment('auth/form_header', ['heading' => __('Email successfully verified!') ]);
?>
<div class="pure-g text-center">
    <div class="pure-u-1-5">&nbsp;</div>
    <div class="pure-u-3-5">
        <p><?= __('Success!') ?></p>
        <p><?= __('You have successfully verified your email address.') ?></p>
        <div>
            <a href="<?= locale_base_url() . "auth/login/" ?>" class="pure-button pure-button-primary pure-button-large"><?= __('Continue magical journey') ?></a>
        </div>
    </div>
    <div class="pure-u-1-5">&nbsp;</div>
</div>
<?php $this->load_fragment('auth/form_footer'); ?>
