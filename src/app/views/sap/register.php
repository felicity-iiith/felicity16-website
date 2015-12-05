<!DOCTYPE html>
<?php
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Ambassador Program · Felicity ʼ16</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/sap_register.css">
</head>
<body>
    <?php
    if (isset($errors) && count($errors) !== 0) {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    } else if (isset($success)) {
        if ($success) {
            echo "<p>You have registered successfully.</p>";
        } else {
            echo '<p>Sorry! Something went wrong. Please email ',
            '<a href=\"mailto:help@felicity.iiit.ac.in\">help@felicity.iiit.ac.in</a> ',
            'with details about this error.</p>';
        }
    }
?>
    <div>
	<h3 class="text-center">Become a campus ambassador!</h3>
    </div>
    <div class="well" align="center">	
	<form method="post">
	    <table class="smalltable" style="border-collapse: collapse;">
	    <tr><td><label>Name:</label></td><td><input type="text" name="name" required></td></tr>
            <tr><td><label>Email ID: </label></td><td><input type="text" name="email" required></td></tr>
            <tr><td><label>Phone number: </label></td><td><input type="text" name="name" required></td></tr>
            <tr><td><label>College/University name: </label></td><td><input type="text" name="college" required></td></tr>
            <tr><td><label>Program of study: </label></td><td><input type="text" name="program-of-study" required></td></tr>
            <tr><td><label>Year of study: </label></td><td><input type="text" name="year-of-study" required></td></tr>
            <tr><td><label>Facebook profile link: </label></td><td><input type="text" name="facebook-profile-link" required></td></tr>

            <tr><td><label for="why-apply">Why do you want to apply for the student ambassador program?</label></td><td>
            <textarea id="why-apply" name="why-apply" required></textarea></td></tr>

            <tr><td><label for="about-you">Tell us a little about yourself.</label></td><td>
            <textarea id="about-you" name="about-you" required></textarea></td></tr>

            <tr><td><label for="organised-event">Have you organised any event/program in your school or college? If yes, tell us about it.</label></td><td>
            <textarea id="organised-event" name="organised-event"></textarea></td></tr>
            <tr><td></td><td><input type="submit" value="Register"></td></tr>
	    </table>
        </form>
    </div>
</body>
</html>
