<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Ambassador Program · Felicity ʼ16</title>
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
    <form method="post">
        <label>Name: <input type="text" name="name" required></label>
        <label>Email ID: <input type="text" name="email" required></label>
        <label>Phone number: <input type="text" name="name" required></label>
        <label>College/University name: <input type="text" name="college" required></label>
        <label>Program of study: <input type="text" name="program-of-study" required></label>
        <label>Year of study: <input type="text" name="year-of-study" required></label>
        <label>Facebook profile link: <input type="text" name="facebook-profile-link" required></label>

        <label for="why-apply">Why do you want to apply for the student ambassador program?</label>
        <textarea id="why-apply" name="why-apply" required></textarea>

        <label for="about-you">Tell us a little about yourself.</label>
        <textarea id="about-you" name="about-you" required></textarea>

        <label for="organised-event">Have you organised any event/program in your school or college? If yes, tell us about it.</label>
        <textarea id="organised-event" name="organised-event"></textarea>

        <input type="submit" value="Register">
    </form>
</body>
</html>
