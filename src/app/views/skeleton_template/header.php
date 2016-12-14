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
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/styles/vendor/pure-forms-tables-buttons.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/styles/core.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/styles/new.css">
    <script src="<?= base_url() ?>static/scripts/vendor/jquery.min.js"></script>
    <script type="text/javascript">
        var baseUrl = '<?= base_url() ?>';
        var localeBaseUrl = '<?= locale_base_url() ?>';
    </script>
</head>
<?php
    if (!isset($page_slug)) {
        $page_slug = 'static';
    }
?>
<body  style="overflow: hidden;">
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
    <div id="container" class="wrapper">
      <div id="loaderswing">
        <img class="loaderhoverslow" src="<?= base_url() ?>static/images/feli-board.png" />
      </div>
      <ul style="list-style: none;">
          <li class="layer" data-depth="0.00">
              <div class="background"></div>
          </li>
      <!--<li class="layer" data-depth="0.08"><div class="mountain"></div></li>
      <li class="layer" data-depth="0.15">
        <ul class="rope depth-10">
        <li><img src="assets/images/rope.png" alt="Rope"></li>
        <li class="hanger position-2">
          <div class="board cloud-2 swing-1"></div>
        </li>
        <li class="hanger position-4">
          <div class="board cloud-1 swing-3"></div>
        </li>
        <li class="hanger position-8">
          <div class="board birds swing-5"></div>
        </li>
        </ul>
      </li>
      <li class="layer" data-depth="0.20"><h1 class="title"><b>felicity<em>17</em></b></h1></li>
      <li class="layer" data-depth="0.30">
        <ul class="rope depth-30">
        <li><img src="assets/images/rope.png" alt="Rope"></li>
        <li class="hanger position-1">
          <div class="board cloud-1 swing-3"></div>
        </li>
        <li class="hanger position-5">
          <div class="board cloud-4 swing-1"></div>
        </li>
        </ul>
      </li-->
      <!--<li class="layer" data-depth="0.20">
                <div class="land2"></div>
            </li>
            <li class="layer" data-depth="0.21"><div class="land3"></div></li>
            <li class="layer" data-depth="0.23">
                <div class="house"></div>
            </li>
            <li class="layer" data-depth="0.27">
                <div class="land"></div>
            </li> -->
   <!--   <li class="layer" data-depth="0.95">
            <h1 class="title"><b>felicity<em>17</em></b></h1>
            <img class="title feli-board" src="static/images/feli-board.png" alt="felicity17" />
      </li>
-->
            <li class="layer" data-depth="0.40">
                <div class="wave plain depth-60"></div>
            </li>
            <li class="layer" data-depth="0.50">
                <div class="wave paint depth-50"></div>
            </li>
            <li class="layer" data-depth="0.50">
                <div class="boat depth-50"></div>
            </li>
            <li class="layer" data-depth="0.60">
                <div class="wave plain depth-40"></div>
            </li>
            <li class="layer" data-depth="0.70">
                <div class="wave paint depth-30"></div>
            </li>
            <li class="layer" data-depth="0.09">
                <div onclick="toggleDetails('about')" class="cloud cloud-1"><span>About</span></div>
                <div onclick="toggleDetails('gallery')" class="cloud cloud-2"><span>Gallery</span></div>
                <!--<div onclick="toggleDetails('sponsors')" class="cloud cloud-3"><span>Events</span></div>
                <div onclick="toggleDetails('sponsors')" class="cloud cloud-4"><span>Sponsors</span></div> -->
                <div onclick="toggleDetails('sponsors')" class="cloud cloud-4"><span>Sponsors</span></div>
                <!--<div onclick="toggleDetails('contact')" class="cloud cloud-5"><span>Team</span></div> -->
                <div onclick="toggleDetails('contact')" class="cloud cloud-6"><span>Contact</span></div>
            </li>
      </ul>
      <section id="about" class="about">
            <div class="cell">
                <div class="cables center accelerate">
                    <div class="linkholder">
                        <ul class="links">
                            <li><a onclick="showPage('about')">About US</a></li>
                            <li><a onclick="showPage('gallery')">Gallery</a></li>
                            <li><a onclick="showPage('sponsors')">Sponsors</a></li>
                            <li><a onclick="showPage('contact')">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="panel accelerate content-holder">

<?php endif;
