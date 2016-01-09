<?php
    if (empty($user_data['mail'])) {
        $heading = 'enter your email';
        $button_action = 'Submit';
        $message = "We will send an email to this address to verify it.";
    } else {
        $heading = 'confirm your email';
        $button_action = 'Confirm';
        $message = "If you change your email, we will send an email to the new address to verify it.";
    }
    $this->load_fragment('auth/form_header', ['heading' => 'Welcome!' ]);
?>
<div>
    <p>In order to continue, please <?= $heading ?>.</p>
    <form action="update_mail/" method="post" class="pure-form pure-form-stacked">
        <fieldset>
            <label for="mail">Email</label>
            <input type="email" name="mail" id="mail" value="<?= $user_data['mail'] ?>" required><br/>
            <!-- TODO : change confirm to more human friendly -->
            <input type="submit" name="update_mail" value="<?= $button_action ?>" class="pure-button pure-button-primary">
        </fieldet>
    </form>
</div>
<?php $this->load_fragment('auth/form_footer'); ?>
