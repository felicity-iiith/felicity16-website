<?php
    $this->load_fragment('auth/form_header', ['heading' => 'Email Verification' ]);
?>
Email varification needed.
<br><br>
<a href="<?= base_url() . "auth/resend_mail/" ?>" class="pure-button pure-button-primary">Resend email</a>
<?php $this->load_fragment('auth/form_footer'); ?>
