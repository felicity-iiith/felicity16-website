<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Ambassador Program · Felicity ʼ16</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/sap_register.css">
    <link href="https://fonts.googleapis.com/css?family=Crete+Round" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="navbar collapsed" style="position:static;">
    <a class="navbar-title" href="javascript:void(0)">Felicity ʼ16</a>
    <ul class="pull-right">
        <li><a href="<?= base_url() ?>">Home</a></li>
    </ul>
    <a href="javascript:void(0)" class="menu-toggle-button">&equiv;</a>
    </a>
</div>
    <div class="container">
        <h1 class="text-center">Student Ambassador Program</h1>
        <form method="post" class="block full-width middle-form">
            <h2 class="underlined">Register</h2>
            <?php
            if (isset($errors) && count($errors) !== 0) {
                foreach ($errors as $error) {
                    echo "<p class=\"error\">$error</p>";
                }
            } else if (isset($success)) {
                if ($success) {
                    echo "<p class=\"success\">You have registered successfully.</p>";
                } else {
                    echo '<p class=\"error\">Sorry! Something went wrong. Please email ',
                    '<a href=\"mailto:help@felicity.iiit.ac.in\">help@felicity.iiit.ac.in</a> ',
                    'with details about this error.</p>';
                }
            }
            ?>
            <label>Name: <input type="text" name="name" required></label>
            <label>Email ID: <input type="email" name="email" required></label>
            <label>Phone number: <input type="text" name="phone-number" required></label>
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

            <div class="text-center">
                <input type="submit" value="Register!" class="text-right btn-green btn-large">
            </div>
        </form>
    </div>
</body>
</html>
