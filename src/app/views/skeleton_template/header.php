<?php
if (empty($is_ajax)):
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="keywords" content="felicity, felicty16, college, fest, threads, pulsation, iiit, iiith, international, institute, information, technology, hyderabad">
    <meta name="description" content="Felicity is the annual technical and cultural fest of IIIT-H. Includes technical, cultural and literary events, Major nights, talks, workshops and performances. We, at IIIT-H, believe in giving back to the society and use Felicity as a medium to serve this motive and pickup various social initiatives.">
    <meta property="og:description" content="Felicity is the annual technical and cultural fest of IIIT-H. Includes technical, cultural and literary events, Major nights, talks, workshops and performances. We, at IIIT-H, believe in giving back to the society and use Felicity as a medium to serve this motive and pickup various social initiatives.">
    <meta property="og:title" content="<?= implode(' · ', array_reverse(array_map(function($str){ return ucfirst(str_replace('-', ' ', $str)); }, explode('__', $page_slug)))) ?> Felicity · IIIT Hyderabad">
    <meta property="og:image" content="<?= base_url() . (isset($og_image) ? $og_image : 'files/16/logos/felicity16-logo-large.png') ?>" />
    <title>Felicity · IIIT Hyderabad</title>
    <link rel="icon" href="<?= base_url() ?>favicon.ico">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/normalize.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pure/0.6.0/pure-min.css">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/felicons.css">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/core.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Flamenco|Noto+Sans">
    <script type="text/javascript">
        var baseUrl = '<?= base_url() ?>';
    </script>
    <script src="<?= base_url() ?>static/scripts/vendor/jquery.min.js" type="text/javascript"></script>
</head>
<?php
    if (!isset($page_slug)) {
        $page_slug = 'static';
    }
?>
<body<?= empty($page_slug) ? '' : ' class="page-open"' ?>>
    <article class="landing">
        <div class="landing-content">
            <p class="iiit-h">
                <img src="<?= base_url() ?>static/images/iiit-logo.png" alt="IIIT H Logo"> IIIT-H
            </p>
            <p class="presents">
                presents
            </p>
            <h1 class="title"><a href="<?= base_url() ?>">Felicity</a></h1>
            <p class="year">2016</p>
            <p class="tagline">Where magic happens</p>
            <p class="dates">February 19<sup>th</sup>, 20<sup>th</sup> and 21<sup>st</sup></p>
        </div>
    </article>
    <?php if (isset($is_authenticated)): ?>
    <div class="auth-quick-links">
        <?php if ($is_authenticated): ?>
            <div><a href="<?= base_url() . "auth/logout/" ?>" class="pure-button btn">Logout</a></div>
        <?php else: ?>
            <div><a href="<?= base_url() . "auth/login/" ?>" class="pure-button btn">Login</a></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="content-holder">
<?php endif;
