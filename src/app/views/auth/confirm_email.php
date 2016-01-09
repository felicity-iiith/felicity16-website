<?php
    $this->load_fragment('auth/form_header', ['heading' => 'Confirm your email' ]);
?>
<form action="update_mail/" method="post" class="pure-form pure-form-stacked">
    <fieldset>
        <label for="mail">Email</label>
        <input type="email" name="mail" id="mail" value="<?= $user_data['mail'] ?>"><br/>
        <!-- TODO : change confirm to more human friendly -->
        <input type="submit" name="update_mail" value="Confirm" class="pure-button pure-button-primary">
    </fieldet>
</form>
<?php $this->load_fragment('auth/form_footer'); ?>
