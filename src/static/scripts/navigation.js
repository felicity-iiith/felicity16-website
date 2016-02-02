/* global $, loadContent, baseUrl, localeBaseUrl, ga */

var transitionEnd = 'webkitTransitionEnd transitionend msTransitionEnd oTransitionEnd',
    animationEnd  = 'webkitAnimationEnd animationend msAnimationEnd oAnimationEnd';

var lastHash = location.hash;
var lastLocation = location.href.split('#')[0];

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
    var $body = $('body'),
        $document = $(document),
        $contentHolder = $('.content-holder');

    var closeLink = function ($link) {
        $link
            .removeClass('open')
            .addClass('disappear')
            .on(animationEnd, function () {
                $(this).removeClass('disappear');
            });
        $('.mobile-page-title').removeClass('open');
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
        $('.mobile-page-title').text($clickedLink.text());
        $('.mobile-page-title').addClass('open');
        $('.primary-nav-wrap').removeClass('open');
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
            lastLocation = localeBaseUrl;
            updateAltUrls();
        } else {
            var newUrl = urlHelper.getPageUrl(pageName);
            if (location.hash == "#nav") {
                history.replaceState(newUrl, null, newUrl);
            } else {
                history.pushState(newUrl, null, newUrl);
            }
            lastLocation = newUrl;
            lastHash = '';
            openLink(pageName);
            updateAltUrls(pageName);
        }
        ga('send', 'pageview');
    };
    $(window).on('popstate', function() {
        var newLocation = location.href.split('#')[0];
        if (newLocation == lastLocation) return;
        var pageName = urlHelper.getPageName(window.location.href);
        if (pageName === '') {
            showLanding();
        } else {
            openLink(pageName);
        }
        lastLocation = newLocation;
    });
    $('.primary-nav-link').on('click', onPrimaryNavLinkClick);
    $('.landing-content .title a').on('click', function (e) {
        if (e.ctrlKey || e.shiftKey || e.altKey) {
            return;
        }
        e.preventDefault();
        showLanding();
        history.pushState(localeBaseUrl, null, localeBaseUrl);
        lastLocation = localeBaseUrl;
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

    var showNavbarMobile = function (e) {
        var $target = $(this);

        var $dummyTarget        = $target.closest('.nav-cum-tooltip-dummy-target'),
            $navCumTooltip      = $dummyTarget.find('.nav-cum-tooltip'),
            $navbar             = $navCumTooltip.find('.events-sub-nav');

        var pageName = $dummyTarget
            .attr('class')
            .split(' ')
            .filter(function(a) {
                return a.indexOf('cat-') === 0;
            })[0]
            .substr(4);

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

        if (e && e.stopPropagation) {
            e.stopPropagation();
        }
        if (e && e.preventDefault) {
            e.preventDefault();
        }
    };

    $('body').click(function () {
        var $navCumTooltip      = $('.nav-cum-tooltip.open');

        $navCumTooltip.animate({'opacity': 0, 'margin-bottom': '10px'}, 50, function () {
            $('.has-tooltip').removeClass('has-tooltip');
            $navCumTooltip.removeClass('open');
        });
    });

    $('.events-nav-button')
    .on('mouseenter', showTooltip)
    .on('mouseleave', hideTooltip)
    .on('click', showNavbar);

    $('.nav-title').on('click', showNavbarMobile);
});


$(function() {
    function openPrimaryNavSidebar() {
        $('.primary-nav-wrap').addClass('open');
        history.pushState(undefined, undefined, '#nav');
        lastHash = '#nav';
    }

    function closePrimaryNavSidebar() {
        $('.primary-nav-wrap').removeClass('open');
        history.replaceState(undefined, undefined, '#_');
        lastHash = "#_";
    }

    $('.primary-nav-overlay').on('click', closePrimaryNavSidebar);
    $('.primary-nav-open').on('click', openPrimaryNavSidebar);

    function openQuickLinksSidebar() {
        if ($('.primary-nav-wrap').hasClass('open')) {
            closePrimaryNavSidebar();
            return;
        }
        $('.quick-links-wrap').addClass('open');
        history.pushState(undefined, undefined, '#nav');
        lastHash = '#nav';
    }

    function closeQuickLinksSidebar() {
        $('.quick-links-wrap').removeClass('open');
        history.replaceState(undefined, undefined, '#_');
        lastHash = "#_";
    }

    $('.quick-links-overlay').on('click', closeQuickLinksSidebar);
    $('.quick-links-open').on('click', openQuickLinksSidebar);

    $(window).on('hashchange', function() {
        if (lastHash == '#nav') {
            closePrimaryNavSidebar();
            closeQuickLinksSidebar();
            closeEventsNav();
        }
        lastHash = location.hash;
    });

    function openEventsNav(e) {
        $('.events-nav-cum-tooltip').addClass('open');
        history.pushState(undefined, undefined, '#nav');
        lastHash = '#nav';

        if (e && e.stopPropagation) {
            e.stopPropagation();
        }
        if (e && e.preventDefault) {
            e.preventDefault();
        }
    }

    function closeEventsNav(e) {
        if (e && $(e.target).is('a')) {
            return;
        }

        $('.events-nav-cum-tooltip').removeClass('open');
        history.replaceState(undefined, undefined, '#_');
        lastHash = "#_";

        if (e && e.stopPropagation) {
            e.stopPropagation();
        }
        if (e && e.preventDefault) {
            e.preventDefault();
        }
    }

    $('.crystal-ball .ball-title').on('click', openEventsNav);
    $('.events-nav-cum-tooltip').on('click', closeEventsNav);
});
