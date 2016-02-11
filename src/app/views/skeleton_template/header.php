<?php
if (empty($is_ajax)):
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="keywords" content="felicity, felicty16, college, fest, threads, pulsation, iiit, iiith, international, institute, information, technology, hyderabad">
    <meta name="description" content="<?= __("Felicity is the annual technical and cultural fest of IIIT-H. Includes technical, cultural and literary events, Major nights, talks, workshops and performances. We, at IIIT-H, believe in giving back to the society and use Felicity as a medium to serve this motive and pickup various social initiatives.") ?>">
    <meta property="og:description" content="<?= __("Felicity is the annual technical and cultural fest of IIIT-H. Includes technical, cultural and literary events, Major nights, talks, workshops and performances. We, at IIIT-H, believe in giving back to the society and use Felicity as a medium to serve this motive and pickup various social initiatives.") ?>">
    <meta property="og:title" content="<?= isset($title) ? $title . ' · ' : '' ?><?= __('Felicity') ?> · <?= __('IIIT-H') ?>">
    <meta property="og:image" content="<?= base_url() . (isset($og_image) ? $og_image : 'files/16/poster1.jpg') ?>">

    <?php
    global $cfg;
    $path = empty($_SERVER['PATH_INFO']) ? '/' : $_SERVER['PATH_INFO'];
    $lang_prefix = explode('_', setlocale(LC_ALL, "0"))[0];

    if (strpos($path, $lang_prefix) === 1) {
        $path = substr($path, strlen($lang_prefix) + 1);
    }

    $lang_list = isset($cfg['i18n']['languages']) ? $cfg['i18n']['languages'] : [];
    ?>
    <?php if ($lang_list): ?>
        <link rel="alternate" href="<?= base_url() . substr($path, 1) ?>" hreflang="x-default" />
        <?php foreach ($lang_list as $lang => $locale): ?>
        <link rel="alternate" href="<?= base_url() . $lang . $path ?>" hreflang="<?= $lang ?>" />
        <?php endforeach; ?>
    <?php endif; ?>

    <title><?= isset($title) ? $title . ' · ' : '' ?><?= __('Felicity') ?> · <?= __('IIIT-Hyderabad') ?></title>

    <link rel="icon" href="<?= base_url() ?>favicon.ico">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/normalize.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/pure-forms-tables-buttons.css">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/felicons.css?v=2">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/core.css?v=4">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Flamenco|Noto+Sans">
    <script type="text/javascript">
        var baseUrl = '<?= base_url() ?>';
        var localeBaseUrl = '<?= locale_base_url() ?>';
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
                <img src="<?= base_url() ?>static/images/iiit-logo.png" alt="IIIT H Logo"> <?= __('IIIT-H') ?>
            </p>
            <p class="presents">
                <?= __('presents') ?>
            </p>
            <h1 class="title"><a href="<?= locale_base_url() ?>"><?= __('Felicity') ?></a></h1>
            <p class="year">2016</p>
            <p class="tagline"><?= __('Where magic happens') ?></p>
            <p class="dates"><?= __('February 19<sup>th</sup>, 20<sup>th</sup> and 21<sup>st</sup>') ?></p>
        </div>
    </article>
    <?php if ($lang_list): ?>
    <div class="lang-quick-links">
        <?php foreach ($lang_list as $lang => $locale): ?>
            <a href="<?= base_url() . $lang . $path ?>" lang="<?= $lang ?>" class="lang-link<?= ($lang == $lang_prefix) ? ' active-lang' : '' ?>"><?= locale_get_display_name($lang, $lang) ?></a> ·
        <?php endforeach; ?>
        <i class="icon-language"></i>
    </div>
    <?php endif; ?>
    <?php if (isset($is_authenticated)): ?>
    <div class="auth-quick-links">
        <?php if ($is_authenticated): ?>
            <?php if (!empty($user_nick)): ?>
                <div class="nick"><?= sprintf(__('Hello, %s'), $user_nick) ?> <a href="<?= locale_base_url() . "auth/logout/" ?>"><?= __('Logout') ?></a></div>
            <?php else: ?>
                <div><a href="<?= locale_base_url() . "auth/logout/" ?>" class="pure-button btn"><?= __('Logout') ?></a></div>
            <?php endif; ?>
        <?php else: ?>
            <div><a href="<?= locale_base_url() . "auth/login/" ?>" class="pure-button btn"><?= __('Login / Register') ?></a></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="content-holder">
<?php endif;
