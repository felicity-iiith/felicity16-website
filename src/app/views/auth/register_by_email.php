<?php
    $this->load_fragment('auth/form_header', ['heading' => 'Register' ]);
?>
<?php if ($sent): ?>
    <div class="text-center">
        <span class="success" style="display:inline-block">Verification mail sent!</span>
        <p style="margin-top: 1em;">To proceed, you must verify your email address by clicking on the link sent to your inbox.</p>
    </div>
<?php else: ?>
<p>We will send an email to this address to verify it.</p>
<form action="register_email/" method="post" class="pure-form pure-form-stacked">
    <fieldset>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br/>
        <input type="submit" name="register" value="Next" class="pure-button pure-button-primary">
    </fieldset>
</form>
<?php endif; ?>
<?php $this->load_fragment('auth/form_footer'); ?>
