<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Felcity ʼ16</title>
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/normalize.min.css" media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" href="<?= base_url() ?>static/styles/core.css" media="screen" title="no title" charset="utf-8">
    </head>
    <body>
        <article class="landing">

        </article>
        <article class="page bg-red about">
            <h1>Felicity</h1>
        </article>
        <article class="page bg-green gallery">

        </article>
        <article class="page bg-blue sponsors">

        </article>
        <article class="page bg-orange contact">

        </article>
        <nav>
            <div class="">
                <ul class="primary-nav left">
                    <li>
                        <a href="<?= base_url() ?>about/" data-href="about" class="primary-nav-link">About Us</a>
                    </li>
                    <li>
                        <a href="<?= base_url() ?>gallery/" data-href="gallery" class="primary-nav-link">Gallery</a>
                    </li>
                </ul>
                <div class="ball">

                </div>
                <ul class="primary-nav right">
                    <li>
                        <a href="<?= base_url() ?>sponsors/" data-href="sponsors" class="primary-nav-link">Sponsors</a>
                    </li>
                    <li>
                        <a href="<?= base_url() ?>contact/" data-href="contact" class="primary-nav-link">Contact Us</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="stripes">
            <div class="stripe bg-red"></div>
            <div class="stripe bg-green"></div>
            <div class="stripe bg-blue"></div>
            <div class="stripe bg-orange"></div>
        </div>
        <script src="<?= base_url() ?>static/scripts/vendor/jquery.min.js" charset="utf-8"></script>
        <script type="text/javascript">
            var baseUrl = '<?= base_url() ?>';
            $(function () {
                var opening = false;
                $('.primary-nav-link').on('click', function (e) {
                    e.preventDefault();
                    if (opening) {
                        return;
                    }
                    if ($(this).hasClass('open')) {
                        return;
                    }
                    opening = true;
                    var pageName = $(this).data('href');
                    var $currentlyClickedLink = $(this);
                    var $previouslyOpenedLink = $('.primary-nav-link.open');
                    $currentlyClickedLink.addClass('open');
                    $previouslyOpenedLink.addClass('disappear')
                    .one('webkitAnimationEnd animationend msAnimationEnd oAnimationEnd', function () {
                        $(this).removeClass('open').removeClass('disappear');
                    });
                    $('.page.' + pageName)
                    .css('z-index', 1)
                    .addClass('open')
                    .one('webkitTransitionEnd transitionend msTransitionEnd oTransitionEnd', function (e) {
                        $previouslyOpenedPage = $('.page.open').not(this);
                        $previouslyOpenedPage
                        .css('transition', 'none')
                        .removeClass('open');
                        $('body').offset();
                        $previouslyOpenedPage.css('transition', 'top 1s');
                        opening = false;
                        $(this).css('z-index', 0);
                    });
                });
            });
        </script>
    </body>
</html>
