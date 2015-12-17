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
        <div class="description">
            <p>The Student Ambassador Program by Felicity ’16 is a nationwide programme which explores, innovates, shapes and exposes the students nationwide from various colleges as efficient managers and creative leaders. Members not only acquire leadership/ entrepreneurial qualities but also gain a social streak with a good character. We call upon our ambassadors to educate their constituencies about the powerful role of socio-initiatives.</p>

            <h2>Why should you join us?</h2>

            <p>Being a representative of Felicity ’16 on campus will help you to understand the technical and marketing issues in depth.

            <p>You get work experience, and we help more students succeed. It's a win-win!</p>

            <p>As a Felicity ‘16 Student Ambassador, you play a vital role in providing student support to your campus community. The program is built to maximize your learning, and provides a powerful networking platform by bringing together some of the best student minds across the country to collaborate and share ideas. The program is designed to enable amazing hands-on experience in marketing, along with mentorship & learning through continuous interactions with the team of Felicity for getting hands-on experience on how to plan any strategy and execute it for a good market response  with cost effective structure.</p>

            <h2>Prerequisites:</h2>
            <ul>
                <li>All applicants must be college/university students irrespective of their fields.</li>
                <li>Must be from age group (17-25) but should not be from final year of their course.</li>
                <li>Must currently hold some position in their college or have organised some event in the college preferably (e.g.:Association/Student body member, club president, class representative, member of student welfare committee).</li>
                <li>Must be able to provide strong motivation.</li>
                <li>Must be able to arrange, conduct meetings and should be able to handle the crowd.</li>
                <li>Capable of appreciably representing the views and policies of an ambassador during campaigns.</li>
                <li>Must be consistent to work efficiently during his/her tenure.</li>
                <li>Good at English (preferably) or good at regional language (if local).</li>
                <li>A maximum of 2 ambassadors are allowed from each college.</li>
            </ul>

            <h2>Benefits and Opportunities :</h2>
            <ul>
                <li>Each Student Ambassador will be the proud owner of an official Felicity, IIIT Hyderabad T-shirt. Every month, one Student Ambassador will be chosen as the “Ambassador of the month” and will be featured on the Facebook page of Felicity.</li>
                <li>The Student Ambassadors who have been very active in the whole program,  by analysing their abilities with their scale <li>in understanding and execution of given missions. You are shown the internship opportunities at Startups in <a href="http://www.t-hub.co" target="_blank">T-Hub</a> and your resumes will be forwarded there</li>
                <li>You will be given badges at each level, which will give you a certificate of appreciation.</li>
                <li>Being a part of the Felicity ecosystem, you will have access to a network of all Campus Ambassadors across India.</li>
                <li>You will be taken over a tour of the program structure of IIIT (academics and projects) by students.</li>
                <li>You will be given exclusive coupons for many services, be it recharges, be it clothing.</li>
                <li>and many more</li>
            </ul>

            <h2>Program Structure</h2>
            <p>The program is designed for two months.</p>
            <p> the ones who applied and got selected will get access to Nationwide ambassador dashboard, which tells you the activities you need to go through.</p>
            <p>This is structured into different levels and each level containing certain missions.</p>
            <p>Each mission has its own definition and purpose, where you have to understand the need and outcome of doing it. And there is a <p>proper channel for  learning it on the portal.
            <p>As you go on crossing the missions, your level is incremented. At each level, you will get badges.</p>
            <p>Through this entire program, you will understand the different levels of depth in marketing and its technical and non-technical way of doing it.</p>

            <h2>About IIIT-Hyderabad</h2>
            <p>IIIT-H was set up as a research university focused on the core areas of Information Technology, such as Computer Science, Electronics and Communications, and their applications in other domains. The institute evolved strong research programmes in a host of areas, with computation or IT providing the connecting thread, and with an emphasis on the development of technology and applications, which can be transferred for use to industry and society. This required carrying out basic research that can be used to solve real life problems. As a result, a synergistic relationship has come to exist at the Institute between basic and applied research. Faculty carries out a number of academic industrial projects, and a few companies have been incubated based on the research done at the Institute.</p>
        </div>
        <form id="register-form" method="post" class="block full-width middle-form">
            <h2 class="underlined">Apply</h2>
            <?php
            if (isset($errors) && count($errors) !== 0) {
                foreach ($errors as $error) {
                    echo "<p class=\"error\">$error</p>";
                }
            } else if (isset($success)) {
                if ($success) {
                    echo "<p class=\"success\">You have applied successfully. We shall review your application and get back to you.</p>";
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
                <input type="hidden" value="<?= $csrf_token ?>" name="csrf_token">
                <input type="submit" value="Apply!" class="text-right btn-green btn-large">
            </div>
        </form>
    </div>
</body>
</html>
