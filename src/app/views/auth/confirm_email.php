<?php
    if (empty($user_data['mail'])) {
        $heading = __('In order to continue, please enter your email.');
        $button_action = __('Submit');
        $message = __("We will send an email to this address to verify it.");
    } else {
        $heading = __('In order to continue, please confirm your email.');
        $button_action = __('Confirm');
        $message = __("If you change your email, we will send an email to the new address to verify it.");
    }
    $this->load_fragment('auth/form_header', ['heading' => __('Welcome!') ]);
?>
<div>
    <p><?= $heading ?></p>
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
