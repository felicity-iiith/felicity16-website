/* global $, loadContent, baseUrl, localeBaseUrl, ga */

var transitionEnd = 'webkitTransitionEnd transitionend msTransitionEnd oTransitionEnd',
    animationEnd  = 'webkitAnimationEnd animationend msAnimationEnd oAnimationEnd';

var urlHelper = {
    getPageUrl : function (pageName) {
        var pagePath = pageName.replace('-', '/');
        return localeBaseUrl + pagePath + '/';
    },

    getAltPageUrl: function (pageName, lang) {
        var pagePath = '';
        if (pageName) {
            pagePath = pageName.replace('-', '/') + '/';
        }
        return baseUrl + lang + '/' + pagePath;
    },

    getPageName : function (pageUrl) {
        return pageUrl.split('?')[0].split('#')[0].replace(localeBaseUrl, '').replace(/\/+$/, '');
    }
};

var updateAltUrls = function(pageName) {
    $('.lang-link').each(function() {
        this.href = urlHelper.getAltPageUrl(pageName, this.lang);
    });
};

$(function () {
    var localeBaseTitle = document.title.split(' · ').slice(-2).join(' · '),
        $body = $('body'),
        $document = $(document),
        $contentHolder = $('.content-holder');

    var closeLink = function ($link) {
        $link
            .removeClass('open')
            .addClass('disappear')
            .on(animationEnd, function () {
                $(this).removeClass('disappear');
            });
    };

    var openLink = function(pageName) {
        var $clickedLink = $('.primary-nav-link.' + pageName);
        var $openedLink = $('.primary-nav-link.open');
        if ($clickedLink.is($openedLink)) {
            return;
        }
        $document.scrollTop(0);
        closeLink($openedLink);
        var newUrl = urlHelper.getPageUrl(pageName);
        var $article = $('<article>', {
            'class' : 'page ' + pageName
        }).text('Loading...');
        $contentHolder.children().addClass('to-be-deleted');
        $contentHolder.append($article);
        loadContent(newUrl, $article);
        $body.addClass('page-open');
        $clickedLink.addClass('open');
        // Force redraw
        $body.offset();
        $article.addClass('open');
        $article.on(transitionEnd, function () {
            if (!$(this).hasClass('to-be-deleted')) {
                $contentHolder.children('.to-be-deleted').remove();
            }
        });
    };

    var showLanding = function () {
        closeLink($('.primary-nav-link.open'));
        $contentHolder.empty();
        $body.removeClass('page-open');
    };

    var onPrimaryNavLinkClick = function (e) {
        if (e.ctrlKey || e.shiftKey || e.altKey) {
            return;
        }
        e.preventDefault();
        var $clickedLink = $(this);
        var pageName = $clickedLink.data('href');
        if ($clickedLink.hasClass('open')) {
            showLanding();
            history.pushState(localeBaseUrl, null, localeBaseUrl);
            updateAltUrls();
        } else {
            var newUrl = urlHelper.getPageUrl(pageName);
            history.pushState(newUrl, null, newUrl);
            openLink(pageName);
            updateAltUrls(pageName);
        }
        document.title = localeBaseTitle;
        ga('send', 'pageview');
    };
    $(window).on('popstate', function() {
        if (!history.state) return;
        var pageName = urlHelper.getPageName(window.location.href);
        if (pageName === '') {
            showLanding();
        } else {
            openLink(pageName);
        }
    });
    $('.primary-nav-link').on('click', onPrimaryNavLinkClick);
    $('.landing-content .title a').on('click', function (e) {
        if (e.ctrlKey || e.shiftKey || e.altKey) {
            return;
        }
        e.preventDefault();
        showLanding();
        history.pushState(localeBaseUrl, null, localeBaseUrl);
        document.title = localeBaseTitle;
        updateAltUrls();
        ga('send', 'pageview');
    });
});

$(function () {
    var $links = $('.events-nav-button');
    var angle = 200 / $links.length;
    var curAngle = (200 - (200 - angle * ($links.length - 1)) / 2 - 10);
    $links.each(function() {
        var transform = 'rotate(-' + curAngle + 'deg) translate(6em) rotate(' + curAngle + 'deg)';
        $(this).css({
            'transform' : transform
        });
        curAngle -= angle;
    });
});

$(function () {
    var getNavbarHeight = function (pageName) {
        var $dummyTarget        = $('.nav-cum-tooltip-dummy-target.cat-' + pageName),
            $navCumTooltip      = $dummyTarget.find('.nav-cum-tooltip'),
            $navbar             = $navCumTooltip.find('.events-sub-nav');

        var events = $navbar.find('li');
        return events.height() * events.length;
    };

    var showTooltip = function (cb) {
        var $target = $(this);
        var pageName = $target.data('href');

        var $dummyTarget        = $('.nav-cum-tooltip-dummy-target.cat-' + pageName),
            $navCumTooltip      = $dummyTarget.find('.nav-cum-tooltip'),
            $navbar             = $navCumTooltip.find('.events-sub-nav');

        if ($('.nav-cum-tooltip').filter('.open').length) {
            return;
        }

        if ($target.hasClass('has-tooltip')) {
            return;
        }
        $target.addClass('has-tooltip');

        $navbar.height(0);

        var offset  = $target[0].getBoundingClientRect();
        $dummyTarget.css({
            top: offset.top,
            left: offset.left,
            width: offset.width,
            height: offset.height,
        });

        $navCumTooltip.animate({'opacity': 1, 'margin-bottom': 0}, 50, (typeof cb) == "function" ? cb.bind(this) : undefined);
    };

    var hideTooltip = function () {
        var $target = $(this);
        var pageName = $target.data('href');

        var $dummyTarget        = $('.nav-cum-tooltip-dummy-target.cat-' + pageName),
            $navCumTooltip      = $dummyTarget.find('.nav-cum-tooltip');

        if ($navCumTooltip.hasClass('open')) {
            return;
        }
        $(this).removeClass('has-tooltip');
        $navCumTooltip.stop().animate({'opacity': 0, 'margin-bottom': '10px'}, 50);
    };

    var showNavbar = function (e) {
        var $target = $(this);
        var pageName = $target.data('href');

        var $dummyTarget        = $('.nav-cum-tooltip-dummy-target.cat-' + pageName),
            $navCumTooltip      = $dummyTarget.find('.nav-cum-tooltip'),
            $navbar             = $navCumTooltip.find('.events-sub-nav');

        if ( $target.hasClass('has-tooltip')) {
            if ($navCumTooltip.hasClass('open')) {
                $navCumTooltip.removeClass('open');
                $navbar.stop().animate({
                    'height': 0
                });
            } else {
                $navCumTooltip.addClass('open');
                var height = getNavbarHeight(pageName);
                $navbar.stop().animate({
                    'height': height
                });
            }
        } else {
            if ($('.nav-cum-tooltip').filter('.open').length) {
                $navCumTooltip = $('.nav-cum-tooltip.open');
                $navbar = $navCumTooltip.find('.events-sub-nav');
                $navbar.css({'height' : 0});
                $('.has-tooltip').removeClass('has-tooltip');
                $navCumTooltip.removeClass('open');
                $navCumTooltip.css({'opacity': 0, 'margin-bottom': '10px'}, 50);
                showTooltip.call(this, showNavbar.bind(this));
            }
        }
        if (e && e.stopPropagation) {
            e.stopPropagation();
        }
        if (e && e.preventDefault) {
            e.preventDefault();
        }
    };

    $('body').click(function () {
        var $navCumTooltip      = $('.nav-cum-tooltip.open'),
            $navbar             = $navCumTooltip.find('.events-sub-nav');

        $navCumTooltip.animate({'opacity': 0, 'margin-bottom': '10px'}, 50, function () {
            $navbar.css({'height' : 0});
            $('.has-tooltip').removeClass('has-tooltip');
            $navCumTooltip.removeClass('open');
        });
    });

    $('.events-nav-button')
    .on('mouseenter', showTooltip)
    .on('mouseleave', hideTooltip)
    .on('click', showNavbar);
});
