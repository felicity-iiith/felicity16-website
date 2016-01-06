<?php
    //"nick", "name", "mail", "gender", "country", "city", "dob", "organization"
    $gender = $user_data["gender"];
?>
<div class="error">
    <?= nl2br($error) ?>
</div>
<form action="update_profile/" method="post">
    <label for="name">Nick: <input type="text" name="nick" value="<?= $user_data['nick'] ?>" required></label><br>
    <label for="name">Name: <input type="text" name="name" value="<?= $user_data['name'] ?>" required></label><br>
    <label for="gender">Gender:</label>
    <label><input type="radio" name="gender" value="female" required <?= ($gender == "female") ? "checked" : "" ?>>Female</label>
    <label><input type="radio" name="gender" value="male" required <?= ($gender == "male") ? "checked" : "" ?>>Male</label>
    <label><input type="radio" name="gender" value="other" required <?= ($gender == "other") ? "checked" : "" ?>>Other</label><br>
    <label for="location">Location/City: <input type="text" name="location" value="<?= $user_data['location'] ?>" required></label><br>
    <label for="country">Country: <input type="text" name="country" value="<?= $user_data['country'] ?>" required></label><br>
    <label for="dob">Date of Birth: <input type="date" name="dob" value="<?= $user_data['dob'] ?>" required></label><br>
    <label for="organization">Organization: <input type="text" name="organization" value="<?= $user_data['organization'] ?>" required></label><br>
    <input type="submit" name="update" value="Save">
</form>
