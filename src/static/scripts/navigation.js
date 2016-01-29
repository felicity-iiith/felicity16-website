/* global $, loadContent, baseUrl, localeBaseUrl, eventsData, ga */

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

var pageHelper = {
    getPageTitle : function (pageName) {
        return pageName.split('-').map(function (name) {
            return name.charAt(0).toUpperCase() + name.slice(1).toLowerCase();
        }).join(' ');
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
    var $dummyTarget        = $('<div>', {'class': 'nav-cum-tooltip-dummy-target'}).appendTo('body'),
        $navCumTooltip      = $('<div>', {'class': 'nav-cum-tooltip'}).appendTo($dummyTarget),
        $navCumTooltipTitle = $('<div>', {'class': 'nav-title'}).appendTo($navCumTooltip),
        $navbar             = $('<ul>',  {'class': 'events-sub-nav'}).appendTo($navCumTooltip),
        $triangleBack       = $('<div>', {'class': 'triangle-back'}).appendTo($navCumTooltip),
        $triangleFront      = $('<div>', {'class': 'triangle-front'}).appendTo($navCumTooltip);

    //var $contentHolder = $('.content-holder');
    (function () {})($triangleBack, $triangleFront);

    var linkClicked = function (e) {
        e.stopPropagation();
        // $('<div>').appendTo('body').css({
        //     'z-index': 1000,
        //     height: '100%',
        //     width: '100%',
        //     'position': 'fixed',
        //     top: 0,
        //     bottom: 0,
        //     left: 0,
        //     right: 0
        // }).click(function (e) {
        //     e.stopPropagation();
        // });
        // var offset = $navCumTooltip.offset();
        // var y = e.pageY - offset.top;
        // $('<div>', {class:'fire1'}).css({
        //     top: y,
        // }).appendTo($navCumTooltip).animate({
        //     height: $navCumTooltip.height() - y
        // },function () {
        //     $triangleBack.css({
        //         'border-top': '9px solid green',
        //         'bottom': '-9px',
        //     });
        //     $triangleFront.animate({
        //         'border-width': 0
        //     }, function () {
        //
        //     });
        // });

        // var offset = $(this).offset();
        // var x = e.pageX - offset.left;
        // var y = e.pageY - offset.top;
        // $('<div>', {'class': 'wave'})
        // .css({'top':y, 'left': x})
        // .appendTo(this).on(animationEnd, function () {
        //     $(this).remove();
        // });
        // var $ball = $('.crystal-ball');
        // $('body').addClass('page-open');
        // $navCumTooltip.animate({'opacity': 0, 'margin-bottom': '10px'}, 50, function () {
        //     $navbar.css({'height' : 0});
        //     $('.has-tooltip').removeClass('has-tooltip');
        //     $navCumTooltip.removeClass('open');
        // });
        // offset = $ball[0].getBoundingClientRect();
        // $('<div>', {'class' : 'big-wave'})
        // .appendTo('body')
        // .css({
        //     'top' : offset.top + offset.height / 2,
        //     'left': offset.left + offset.width / 2
        // }).on(animationEnd, function () {
        //
        //     $('<article>', {
        //         'class' : 'page open full',
        //     })
        //     .css('background-color', $(this).css('background-color'))
        //     .text('Loading...')
        //     .appendTo($contentHolder);
        //     $(this).fadeOut(function () {
        //         $(this).remove();
        //     });
        // });
    };

    var getLI = function (href, text) {
        return $('<li>').append( $('<a>', {'href': href }).text( text ).click(linkClicked) );
    };

    var updateNavbarLinks = function (pageName) {
        var events = eventsData[pageName], $li;
        $navbar.empty();
        try {
            events[0].path = events[0].path.replace('index/', '');
            events[0].data.name = "Home";
        } catch (e) {

        }
        for(var i = 0; i < events.length ; i++) {
            var event = events[i];
            $li = getLI( localeBaseUrl + event.path.substr(1), event.data.name );
            $navbar.append( $li );
        }
        return $li.height() * events.length;
    };

    var showTooltip = function (cb) {
        if ($navCumTooltip.hasClass('open')) {
            return;
        }
        var $target = $(this);
        if ($target.hasClass('has-tooltip')) {
            return;
        }
        $target.addClass('has-tooltip');

        var pageName = $target.data('href');
        $navCumTooltipTitle.text(pageHelper.getPageTitle(pageName));
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
        if ($navCumTooltip.hasClass('open')) {
            return;
        }
        $(this).removeClass('has-tooltip');
        $navCumTooltip.stop().animate({'opacity': 0, 'margin-bottom': '10px'}, 50);
    };

    var showNavbar = function (e) {
        var $target = $(this);
        if ( $target.hasClass('has-tooltip')) {
            if ($navCumTooltip.hasClass('open')) {
                $navCumTooltip.removeClass('open');
                $navbar.stop().animate({
                    'height': 0
                });
            } else {
                $navCumTooltip.addClass('open');
                var pageName = $target.data('href');
                var height = updateNavbarLinks(pageName);
                $navbar.stop().animate({
                    'height': height
                });
            }
        } else {
            if ($navCumTooltip.hasClass('open')) {
                $navbar.css({'height' : 0});
                $('.has-tooltip').removeClass('has-tooltip');
                $navCumTooltip.removeClass('open');
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

    $navCumTooltipTitle.click(function (e) {
        e.stopPropagation();
    });

    $('body').click(function () {
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
