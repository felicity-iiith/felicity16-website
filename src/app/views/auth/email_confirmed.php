<?php
    $this->load_fragment('auth/form_header', ['heading' => __('Email successfully verified!') ]);
?>
<div class="row">
    <div class="col6 offset3 text-center">
        <p class="lead"><?= __('Success!') ?></p>
        <p><?= __('You have successfully verified your email address.') ?></p>
        <div>
            <a href="<?= locale_base_url() . "auth/login/" ?>" class="pure-button pure-button-primary"><?= __('Continue magical journey') ?></a>
        </div>
    </div>
</div>
<?php $this->load_fragment('auth/form_footer'); ?>
