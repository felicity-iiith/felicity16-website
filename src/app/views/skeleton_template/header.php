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
    <script type="text/javascript" src="<?= base_url() ?>static/scripts/vendor/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>static/scripts/vendor/plax.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/styles/new.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/styles/panel.css">
    <script type="text/javascript">
        var baseUrl = '<?= base_url() ?>';
        var localeBaseUrl = '<?= locale_base_url() ?>';
    </script>
    <script type="text/javascript">
      $(document).ready(function () {
        $('.mountain').plaxify({"xRange":5,"yRange":5})
        $('.land2').plaxify({"xRange":20,"yRange":20})
        $('.land3').plaxify({"xRange":25,"yRange":25})
        $('.land').plaxify({"xRange":40,"yRange":40})
        $('.house').plaxify({"xRange":30,"yRange":30})
        $('.title').plaxify({"xRange":100,"yRange":100})
        // $('.wave').plaxify({"xRange":40,"yRange":40,"invert":false})
        $('.boat').plaxify({"xRange":40,"yRange":40,"invert":false})

        $.plax.enable()
      })
    </script>
</head>
<?php
    if (!isset($page_slug)) {
        $page_slug = 'static';
    }
?>
<body>
    <img src="<?= base_url() ?>static/images/home/background.png" class="mountain"></div>
    <div class="land2"></div>
    <div class="land3"></div>
    <div class="house"></div>
    <div class="land"></div>
    <div class="wave plain depth-60"></div>
    <div class="wave paint depth-50"></div>
    <div class="wave paint depth-40"></div>
    <div class="wave plain depth-30"></div>
    <div class="wave paint depth-20"></div>
    <div class="wave plain depth-10"></div>
    <!--div class="wave plain depth-40"></div>
    <div class="wave paint depth-50"></div>
    <h1 class="title"><b>felicity<em>17</em></b></h1>
    <div class="wave plain depth-0"></div>
    <div class="boat"></div>
    <div class="wave paint depth-60"></div>
    <div class="wave plain depth-80"></div>
    <div class="wave paint depth-100"></div>
    <div onclick="showDetails('About')" class="cloud cloud-1">About</div>
    <div onclick="showDetails('Gallery')" class="cloud cloud-2">Gallery</div>
    <div onclick="showDetails('Schedule')" class="cloud cloud-3">Schedule</div>
    <div onclick="showDetails('Sponsors')" class="cloud cloud-4">Sponsors</div>
    <div onclick="showDetails('Team')" class="cloud cloud-5">Team</div> -->
    <section id="about" class="about hide">
        <div class="cell">
            <div class="cables center accelerate">
                <div class="linkholder">
                    <ul class="links">
                        <li><a>Button 1</a></li>
                        <li><a>Button 2</a></li>
                        <li><a>Button 3</a></li>
                        <li><a>Button 4</a></li>
                    </ul>
                </div>
                <div class="panel accelerate">
                    <header>
                        <h1 onclick="alert(1)">felicity<em>17</em></h1>
                    </header>
                    <p>Insert random content here</p>
                    <p>Insert random content here</p>
                    <p>Insert random content here</p>
                </div>
            </div>
        </div>
    </section>
    <button id="toggle" class="toggle i">
        <div class="cross">
            <div class="x"></div>
            <div class="y"></div>
        </div>
    </button>
    <?php if (isset($is_authenticated)): ?>
    <div class="auth-quick-links">
        <?php if ($is_authenticated): ?>
            <?php if (!empty($user_nick)): ?>
                <div class="nick"><?= sprintf(__('Hello, %s'), $user_nick) ?> <a href="<?= locale_base_url() . "auth/logout/" ?>"><?= __('Logout') ?></a></div>
            <?php else: ?>
                <div><a href="<?= locale_base_url() . "auth/logout/" ?>" class="pure-button btn"><?= __('Logout') ?></a></div>
            <?php endif; ?>
        <?php else: ?>
            <div><a onclick="window.location.href = '<?= locale_base_url() . "auth/login/" ?>';" class="pure-button btn"><?= __('Login / Register') ?></a></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <script>
        var $html = $('html'),
            $toggle = $('#toggle'),
            $about = $('#about'),
            $scene = $('#scene');

        function showDetails() {
            $about.removeClass('hide');
            $toggle.removeClass('i');
        }

        function hideDetails() {
            $about.addClass('hide');
            $toggle.addClass('i');
        }

        $toggle.on('click', function(event) {
            $toggle.hasClass('i') ? showDetails() : hideDetails();
        });
    </script>
  </body>
</html>
<?php endif;
