<?php
    $this->load_fragment('auth/form_header', ['heading' => __('Update your profile') ]);
?>
<?php
    //"nick", "name", "mail", "gender", "country", "city", "dob", "organization"
    $gender = $user_data["gender"];
?>
<form action="update_profile/" method="post" class="pure-form pure-form-aligned">
    <fieldset>
        <div class="pure-control-group">
            <label for="nick"><?= __('Nick / Username / Handle:') ?></label>
            <input type="text" name="nick" id="nick" value="<?= $user_data['nick'] ?>" required>
        </div>

        <div class="pure-control-group">
            <label for="name"><?= __('Name:') ?></label>
            <input type="text" name="name" id="name" value="<?= htmlentities($user_data['name']) ?>" required>
        </div>

        <div class="pure-control-group">
            <label for="gender"><?= __('Gender:') ?></label>
            <label style="width:auto;"><input type="radio" name="gender" value="female" <?= ($gender == "female") ? "checked" : "" ?> required> <?= __('Female') ?></label>
            <label style="width:auto;"><input type="radio" name="gender" value="male" <?= ($gender == "male") ? "checked" : "" ?> required> <?= __('Male') ?></label>
            <label style="width:auto;"><input type="radio" name="gender" value="other" <?= ($gender == "other") ? "checked" : "" ?> required> <?= __('Other') ?></label>
        </div>

        <div class="pure-control-group">
            <label for="location"><?= __('Location/City:') ?></label>
            <input type="text" name="location" id="location"value="<?= htmlentities($user_data['location']) ?>" required>
        </div>

        <div class="pure-control-group">
            <label for="country"><?= __('Country:') ?></label>
            <select name="country" required style="padding: 2px;">
            <?php
                load_helper('country_list');
                $countries = get_country_list();
                foreach($countries as $code => $name):
                    if ($user_data['country'] == $code) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
            ?>
                <option value="<?= $code ?>" <?= $selected ?>><?= $name ?></option>
            <?php endforeach; ?>
            </select>
        </div>

        <div class="pure-control-group">
            <label for="dob"><?= __('Date of Birth:') ?></label>
            <input type="date" name="dob" value="<?= $user_data['dob'] ?>" required>
        </div>

        <div class="pure-control-group">
            <label for="organization"><?= __('College / Institution / Company:') ?></label>
            <input type="text" name="organization" value="<?= htmlentities($user_data['organization']) ?>" required>
        </div>

        <div class="pure-controls">
            <input type="submit" name="update" value="<?= __('Save') ?>" class="pure-button pure-button-primary">
        </div>
    </fieldset>
</form>
<?php $this->load_fragment('auth/form_footer'); ?>
