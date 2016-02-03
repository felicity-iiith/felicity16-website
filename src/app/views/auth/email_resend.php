<?php
    $this->load_fragment('auth/form_header', ['heading' => __('Email Verification Required') ]);
?>
<div class="row">
    <div class="col6 offset3 text-center">
        <p class="lead"><?= __('Your email has not yet been verified. Please click the verification link you received in your email to proceed.') ?></p>
        <div>
            <a href="<?= locale_base_url() . "auth/resend_mail/" ?>" class="pure-button pure-button-primary"><?= __('Resend email') ?></a>
        </div>
    </div>
</div>
<?php $this->load_fragment('auth/form_footer'); ?>
