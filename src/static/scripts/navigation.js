/* global $, loadContent, baseUrl */
/* jshint -W057 */

var transitionEnd = 'webkitTransitionEnd transitionend msTransitionEnd oTransitionEnd',
    animationEnd  = 'webkitAnimationEnd animationend msAnimationEnd oAnimationEnd';

var urlHelper = {
    getPageUrl : function (pageName) {
        var pagePath = pageName.replace('-', '/');
        return baseUrl + pagePath + '/';
    },

    getPageName : function (pageUrl) {
        return pageUrl.split('?')[0].split('#')[0].replace(baseUrl, '').replace(/\/+$/, '');
    }
};

var pageHelper = {
    getPageTitle : function (pageName) {
        return pageName.split('-').map(function (name) {
            return name.charAt(0).toUpperCase() + name.slice(1).toLowerCase();
        }).join(' ');
    }
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
        $article.on('webkitTransitionEnd transitionend msTransitionEnd oTransitionEnd', function () {
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

var eventsData = {
    'threads'       : ["codecraft", "cachein", "debug-the-c-bug"],
    'nights'        : ["codecraft", "cachein", "debug-the-c-bug"],
    'futsal'        : ["codecraft", "cachein", "debug-the-c-bug"],
    'stomp-the-yard': ["codecraft", "cachein", "debug-the-c-bug"],
};

$(function () {
    var $dummyTarget        = $('<div>', {'class': 'nav-cum-tooltip-dummy-target'}).appendTo('body'),
        $navCumTooltip      = $('<div>', {'class': 'nav-cum-tooltip'}).appendTo($dummyTarget),
        $navCumTooltipTitle = $('<div>', {'class': 'nav-title'}).appendTo($navCumTooltip),
        $navbar             = $('<ul>',  {'class': 'events-sub-nav'}).appendTo($navCumTooltip),
        $triangleBack       = $('<div>', {'class': 'triangle-back'}).appendTo($navCumTooltip),
        $triangleFront      = $('<div>', {'class': 'triangle-front'}).appendTo($navCumTooltip);

    var $contentHolder = $('.content-holder');

    var linkClicked = function (e) {
        e.stopPropagation();
        e.preventDefault();
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
        var offset = $(this).offset();
        var x = e.pageX - offset.left;
        var y = e.pageY - offset.top;
        $('<div>', {'class': 'wave'})
        .css({'top':y, 'left': x})
        .appendTo(this).on(animationEnd, function () {
            $(this).remove();
        });
        var $ball = $('.crystal-ball');
        $('body').addClass('page-open');
        $navCumTooltip.animate({'opacity': 0, 'margin-bottom': '10px'}, 50, function () {
            $navbar.css({'height' : 0});
            $('.has-tooltip').removeClass('has-tooltip');
            $navCumTooltip.removeClass('open');
        });
        offset = $ball[0].getBoundingClientRect();
        $('<div>', {'class' : 'big-wave'})
        .appendTo('body')
        .css({
            'top' : offset.top + offset.height / 2,
            'left': offset.left + offset.width / 2
        }).on(animationEnd, function () {

            $('<article>', {
                'class' : 'page open full',
            })
            .css('background-color', $(this).css('background-color'))
            .text('Loading...')
            .appendTo($contentHolder);
            $(this).fadeOut(function () {
                $(this).remove();
            });
        });
    };

    var getLI = function (href, text) {
        return $('<li>').append( $('<a>', {'href': href }).text( text ).click(linkClicked) );
    };

    var updateNavbarLinks = function (pageName) {
        var events = eventsData[pageName];
        var baseLink = urlHelper.getPageUrl(pageName);
        var $li = getLI( pageName, "Home" );
        $navbar.empty().append( $li );
        for(var i = 0; i < events.length ; i++) {
            var event = events[i];
            $li = getLI( baseLink + event + '/', event );
            $navbar.append( $li );
        }
        return $li.height() * (events.length + 1);
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

        var offset  = $target.offset();
        $dummyTarget.css({
            top: offset.top,
            left: offset.left,
            width: $target.width(),
            height: $target.height(),
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
    };

    $navCumTooltipTitle.click(function (e) {
        e.stopPropagation();
    });

    $('body').click(function (e) {
        $navCumTooltip.animate({'opacity': 0, 'margin-bottom': '10px'}, 50, function () {
            $navbar.css({'height' : 0});
            $('.has-tooltip').removeClass('has-tooltip');
            $navCumTooltip.removeClass('open');
        });
    });

    $('.testing')
    .on('mouseenter', showTooltip)
    .on('mouseleave', hideTooltip)
    .on('click', showNavbar);
});

/* Below is code for Tooltip from http://osvaldas.info/elegant-css-and-jquery-tooltip-responsive-mobile-friendly */
$( function()
{
    var targets = $( '[rel~=tooltip]' ),
        target  = false,
        tooltip = false,
        title   = false;

    targets.bind( 'mouseenter', function()
    {
        target  = $( this );
        title   = target.attr( 'title' );
        tooltip = $( '<div id="tooltip"></div>' );

        if( !title || title === '' )
            return false;

        target.removeAttr( 'title' );
        tooltip.css( 'opacity', 0 )
               .html( title )
               .appendTo( 'body' );

        var init_tooltip = function()
        {
            if( $( window ).width() < tooltip.outerWidth() * 1.5 )
                tooltip.css( 'max-width', $( window ).width() / 2 );
            else
                tooltip.css( 'max-width', 340 );

            var pos_left = target.offset().left + ( target.outerWidth() / 2 ) - ( tooltip.outerWidth() / 2 ),
                pos_top  = target.offset().top - tooltip.outerHeight() - 20;

            if( pos_left < 0 )
            {
                pos_left = target.offset().left + target.outerWidth() / 2 - 20;
                tooltip.addClass( 'left' );
            }
            else
                tooltip.removeClass( 'left' );

            if( pos_left + tooltip.outerWidth() > $( window ).width() )
            {
                pos_left = target.offset().left - tooltip.outerWidth() + target.outerWidth() / 2 + 20;
                tooltip.addClass( 'right' );
            }
            else
                tooltip.removeClass( 'right' );

            if( pos_top < 0 )
            {
                pos_top  = target.offset().top + target.outerHeight();
                tooltip.addClass( 'top' );
            }
            else
                tooltip.removeClass( 'top' );

            tooltip.css( { left: pos_left, top: pos_top } )
                   .animate( { top: '+=10', opacity: 1 }, 50 );
        };

        init_tooltip();
        $( window ).resize( init_tooltip );

        var remove_tooltip = function()
        {
            tooltip.animate( { top: '-=10', opacity: 0 }, 50, function()
            {
                $( this ).remove();
            });

            target.attr( 'title', title );
        };

        target.on( 'mouseleave', remove_tooltip );
        tooltip.on( 'click', remove_tooltip );
        //$( document ).on( 'scroll', init_tooltip );
    });
});
