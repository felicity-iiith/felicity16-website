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
    <meta property="og:title" content="<?= implode(' · ', array_reverse(array_map(function($str){ return ucfirst(str_replace('-', ' ', $str)); }, explode('__', isset($page_slug) ? $page_slug : "")))) ?> Felicity · IIIT Hyderabad">
    <meta property="og:image" content="<?= base_url() . (isset($og_image) ? $og_image : 'files/16/logos/felicity16-logo-large.png') ?>">
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
    <style>
.kites-area {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    z-index: -1;
    perspective: 300px;
}
.kites-area img {
    display: block;
    position: absolute;
    transform-origin: 50% 0 0;
    top: 100%;
    opacity: .9;
}
    </style>
    <script>
var baseUrl = '<?= base_url() ?>';
$(function () {
    var $window     = $(window);
    var $kitesArea  = $('.kites-area');
    var getRotation = function () {
        return 30 + Math.round( Math.random()*40 );
    };

    var getSide     = function () {
        return 0.5 > Math.random();
    };

    var getTop      = function () {
        return Math.round( $kitesArea.innerHeight() * 0.75 * (1 + Math.random()) );
    };

    var getDiagonal = function ($elem) {
        var h = $elem.height();
        var w = $elem.width();
        return Math.ceil( Math.sqrt( h*h + w*w ) );
    };

    var getTime     = function () {
        return 2000 + Math.round( Math.random() * 4000 );
    };

    var getScale    = function () {
        return 0.4 * (1 + Math.random());
    };

    var queue = new (function () {
        var q = [];
        this.push = function (elem) {
            q.push(elem);
        };
        this.pop = function () {
            thisq = q;
            q = [];
            return thisq;
        };
    })();

    var fly = function () {
        var $this = $(this);
        var side = getSide();
        var top = getTop();
        var rotation = getRotation();
        var scale = getScale();
        var transform = null;
        if (side) {
            transform = function(Y) { return [ 'scale(', scale, ') rotateZ(-', rotation, 'deg) rotateY(-5deg) translateY(-', Y, 'px)' ].join(''); }
            $this.css({
                'left'      : '100%',
                'top'       : top,
                'transform' : transform(0)
            });
        }
        else {
            transform = function(Y) { return [ 'scale(', scale, ') rotateZ(', rotation, 'deg) rotateY(-5deg) translateY(-', Y, 'px)' ].join(''); }
            $this.css({
                right       : '100%',
                'top'       : top,
                'transform' : transform(0)
            });

        }
        var totalDistance   = 3 * getDiagonal($kitesArea);
        var startTime       = Date.now();
        var T               = getTime();
        var factor          = totalDistance / T;
        var move = function () {
            var currentDistance = factor * ( Date.now() - startTime ) ;
            $this.css('transform', transform(currentDistance));
            if ( currentDistance < totalDistance ) {
                queue.push(move);
            }
            else {
                $this.remove();
            }
        };
        move();
    };

    var images = [
        baseUrl + 'static/images/kites/kite1.png',
        baseUrl + 'static/images/kites/kite2.png',
        baseUrl + 'static/images/kites/kite3.png',
        baseUrl + 'static/images/kites/kite4.png',
        baseUrl + 'static/images/kites/kite5.png',
    ];

    var getRandomElement = function (images) {
        return images[ Math.floor( Math.random() * images.length ) ];
    };

    var addKite = function () {
        var src = getRandomElement(images);
        $('<img>', { 'src' : src })
        .appendTo($kitesArea)
        .load(fly);
        setTimeout(addKite, Math.floor( Math.random() * 1000 ));
    };

    addKite();

    var animate = function () {
        var q = queue.pop();
        for (var i = 0; i < q.length; i++) {
            q[i].call();
        }
        requestAnimationFrame(animate);
    };

    animate();
});
    </script>
</head>
<?php
    if (!isset($page_slug)) {
        $page_slug = 'static';
    }
?>
<body<?= empty($page_slug) ? '' : ' class="page-open"' ?>>
    <div class="kites-area">
    </div>
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
            <div><a href="<?= base_url() . "auth/login/" ?>" class="pure-button btn">Login / Register</a></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="content-holder">
<?php endif;
