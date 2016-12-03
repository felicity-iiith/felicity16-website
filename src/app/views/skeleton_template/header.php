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
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>static/styles/new.css">
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
  <div id="fb-root"></div>

    <div id="container" class="wrapper">
      <ul id="scene" class="scene unselectable"
        data-friction-x="0.1"
        data-friction-y="0.1"
        data-scalar-x="25"
        data-scalar-y="15">
      <li class="layer" data-depth="0.00"><div class="background"></div></li>
      <li class="layer" data-depth="0.08"><div class="mountain"></div></li>
      <!--li class="layer" data-depth="0.15">
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
      <li class="layer" data-depth="0.20">
                <div class="land2"></div>
            </li>
            <!-- <li class="layer" data-depth="0.21"><div class="land3"></div></li> -->
            <li class="layer" data-depth="0.23">
                <div class="house"></div>
            </li>
            <li class="layer" data-depth="0.27">
                <div class="land"></div>
            </li>
            <li class="layer" data-depth="0.28">
                <h1 class="title"><b>felicity<em>17</em></b></h1>
            </li>
            <li class="layer" data-depth="0.40">
                <div class="wave plain depth-60"></div>
            </li>
            <li class="layer" data-depth="0.50">
                <div class="wave paint depth-50"></div>
            </li>
            <!-- <<<<<<< HEAD -->
            <li class="layer" data-depth="0.50">
                <div class="boat depth-50"></div>
            </li>
            <li class="layer" data-depth="0.60">
                <div class="wave plain depth-40"></div>
            </li>
            <li class="layer" data-depth="0.70">
                <div class="wave paint depth-30"></div>
            </li>
      <li class="layer" data-depth="0.28"><h1 class="title"><b>felicity<em>17</em></b></h1></li>
      <!--li class="layer" data-depth="0.60"><div class="lighthouse depth-60"></div></li -->
      <!--li class="layer" data-depth="0.60">
        <ul class="rope depth-60">
        <li><img src="assets/images/rope.png" alt="Rope"></li>
        <li class="hanger position-3">
          <div class="board birds swing-5"></div>
        </li>
        <li class="hanger position-6">
          <div class="board cloud-2 swing-2"></div>
        </li>
        <li class="hanger position-8">
          <div class="board cloud-3 swing-4"></div>
        </li>
        </ul>
      </li-->
      <li class="layer" data-depth="0.09">
                <div onclick="toggleDetails('Team')" class="cloud cloud-5"><span>Team</span></div>
                <div onclick="toggleDetails('Gallery')" class="cloud cloud-2"><span>Gallery</span></div>
                <div onclick="toggleDetails('Schedule')" class="cloud cloud-3"><span>Schedule</span></div>
                <div onclick="toggleDetails('Sponsors')" class="cloud cloud-4"><span>Sponsors</span></div>
                <div onclick="toggleDetails('About')" class="cloud cloud-1"><span>About</span></div>
            </li>
      </ul>
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
      <!-- Scripts -->
      <script src="<?= base_url() ?>static/scripts/libraries.min.js"></script>
      <script src="<?= base_url() ?>static/scripts/jquery.parallax.js"></script>
      <script>

      // jQuery Selections
      var $html = $('html'),
      $container = $('#container'),
      $prompt = $('#prompt'),
      $toggle = $('#toggle'),
      $about = $('#about'),
      $scene = $('#scene');

      // Hide browser menu.
      (function() {
        setTimeout(function(){window.scrollTo(0,0);},0);
      })();

      // Setup FastClick.
      FastClick.attach(document.body);

      // Add touch functionality.
      if (Hammer.HAS_TOUCHEVENTS) {
        $container.hammer({drag_lock_to_axis: true});
        _.tap($html, 'a,button,[data-tap]');
      }

      // Add touch or mouse class to html element.
      $html.addClass(Hammer.HAS_TOUCHEVENTS ? 'touch' : 'mouse');

      // Resize handler.
      (resize = function() {
        $scene[0].style.width = window.innerWidth + 'px';
        $scene[0].style.height = window.innerHeight + 'px';
        if (!$prompt.hasClass('hide')) {
          if (window.innerWidth < 600) {
            $toggle.addClass('hide');
          } else {
            $toggle.removeClass('hide');
          }
        }
      })();

      // Attach window listeners.
      window.onresize = _.debounce(resize, 200);
      window.onscroll = _.debounce(resize, 200);

      function showDetails(type) {
        console.log(type);
        $about.removeClass('hide');
        $about.slideDown();
        $toggle.removeClass('i');
      }

      function hideDetails() {
        $about.addClass('hide');
        $about.slideUp();
        $toggle.addClass('i');
      }

      function toggleDetails(event) {
        $toggle.hasClass('i') ? showDetails() : hideDetails();
      }
        hideDetails();
      // Listen for toggle click event.
      $toggle.on('click', function(event) {
        $toggle.hasClass('i') ? showDetails() : hideDetails();
      });

      // Pretty simple huh?
      $scene.parallax();

      // Check for orientation support.
      setTimeout(function() {
        if ($scene.data('mode') === 'cursor') {
          $prompt.removeClass('hide');
          if (window.innerWidth < 600) $toggle.addClass('hide');
          $prompt.on('click', function(event) {
            $prompt.addClass('hide');
            if (window.innerWidth < 600) {
              setTimeout(function() {
                $toggle.removeClass('hide');
              },1200);
            }
          });
        }
      },1000);
      </script>

  </body>
</html>
<?php endif;
