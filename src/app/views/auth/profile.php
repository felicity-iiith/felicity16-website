<?php
    $this->load_fragment('auth/form_header', ['heading' => 'Fill your profile' ]);
?>
<?php
    //"nick", "name", "mail", "gender", "country", "city", "dob", "organization"
    $gender = $user_data["gender"];
?>
<form action="update_profile/" method="post" class="pure-form pure-form-aligned">
    <fieldset>
        <div class="pure-control-group">
            <label for="nick">Nick:</label>
            <input type="text" name="nick" id="nick" value="<?= $user_data['nick'] ?>" required>
        </div>

        <div class="pure-control-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?= $user_data['name'] ?>" required>
        </div>

        <div class="pure-control-group">
            <label for="gender">Gender:</label>
            <select name="gender" id="gender">
                <option value="female" <?= ($gender == "female") ? "selected" : "" ?>>Female</option>
                <option value="male" <?= ($gender == "male") ? "selected" : "" ?>>Male</option>
                <option value="other" <?= ($gender == "other") ? "selected" : "" ?>>Other</option>
            </select>
        </div>

        <div class="pure-control-group">
            <label for="location">Location/City:</label>
            <input type="text" name="location" id="location"value="<?= $user_data['location'] ?>" required>
        </div>

        <div class="pure-control-group">
            <label for="country">Country:</label>
            <input type="text" name="country" value="<?= $user_data['country'] ?>" required>
        </div>

        <div class="pure-control-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" value="<?= $user_data['dob'] ?>" required>
        </div>

        <div class="pure-control-group">
            <label for="organization">Organization:</label>
            <input type="text" name="organization" value="<?= $user_data['organization'] ?>" required>
        </div>

        <div class="pure-controls">
            <input type="submit" name="update" value="Save" class="pure-button pure-button-primary">
        </div>
    </fieldset>
</form>
<?php $this->load_fragment('auth/form_footer'); ?>
