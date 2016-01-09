<?php
    $this->load_fragment('auth/form_header', ['heading' => 'Email successfully verified!' ]);
?>
<div class="pure-g text-center">
    <div class="pure-u-1-5">&nbsp;</div>
    <div class="pure-u-3-5">
        <p>Success!</p>
        <p>You have successfully verified your email address.</p>
        <div>
            <a href="<?= base_url() . "auth/register/" ?>" class="pure-button pure-button-primary pure-button-large">Continue magical journey</a>
        </div>
    </div>
    <div class="pure-u-1-5">&nbsp;</div>
</div>
<?php $this->load_fragment('auth/form_footer'); ?>
