/* global $, loadContent, baseUrl */
/* jshint -W057 */

var urlHelper = {
    getPageUrl : function (pageName) {
        var pagePath = pageName.replace('-', '/');
        return baseUrl + pagePath + '/';
    },

    getPageName : function (pageUrl) {
        return pageUrl.split('?')[0].split('#')[0].replace(baseUrl, '').replace(/\/+$/, '');
    }
};

$(function () {
    var $body = $('body'),
        $contentHolder = $('.content-holder');

    var closeLink = function ($link) {
        $link
            .removeClass('open')
            .addClass('disappear')
            .on('webkitAnimationEnd animationend msAnimationEnd oAnimationEnd', function () {
                $(this).removeClass('disappear');
            });
    };

    var openLink = function(pageName) {
        var $clickedLink = $('.primary-nav-link.' + pageName);
        var $openedLink = $('.primary-nav-link.open');
        if ($clickedLink.is($openedLink)) {
            return;
        }
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
        $article.on('webkitTransitionEnd transitionend msTransitionEnd oTransitionEnd', function () {
            $contentHolder.children('.to-be-deleted').remove();
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
            history.pushState(baseUrl, null, baseUrl);
        } else {
            var newUrl = urlHelper.getPageUrl(pageName);
            history.pushState(newUrl, null, newUrl);
            openLink(pageName);
        }
    };
    $(window).on('popstate', function() {
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
        history.pushState(baseUrl, null, baseUrl);
    });
});

$(function () {
    var $links = $('.events-nav-link');
    var angle = 200 / $links.length;
    var curAngle = (200 - angle * ($links.length - 1)) / 2 - 10;
    $links.each(function() {
        var transform = 'rotate(-' + curAngle + 'deg) translate(4.5em) rotate(' + curAngle + 'deg)';
        $(this).css({
            'transform' : transform
        });
        curAngle += angle;
    });
});
