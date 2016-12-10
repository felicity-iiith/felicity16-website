<?php
if (empty($is_ajax)):
?>
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
<script src="<?= base_url() ?>static/scripts/vendor/jquery.min.js"></script>
<script>

    var $html = $('html');
    var $toggle = $('#toggle');
    var $about = $('#about');

    (function() {
        $toggle.on('click', function(event) {
            $toggle.hasClass('i') ? showDetails() : hideDetails();
        });
    })();


    function showDetails() {
        $about.removeClass('hide');
        $toggle.removeClass('i');
        $about.css('display', 'table');
    }

    function hideDetails() {
        $about.addClass('hide');
        setTimeout(function() {
            $about.css('display', 'none');
        }, 500);
        $toggle.addClass('i');
        history.pushState(localeBaseUrl, null, localeBaseUrl);
    }

    $about.css('display', 'none');

    function toggleDetails(type) {
        showPage(type);
        $toggle.hasClass('i') ? showDetails() : hideDetails();
    }
</script>
<script src="<?= base_url() ?>static/scripts/common.js" charset="utf-8"></script>
<script src="<?= base_url() ?>static/scripts/ajaxify.js" charset="utf-8"></script>

<script>
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

    function showPage(type) {
        var newUrl = urlHelper.getPageUrl(type);
        loadContent(newUrl, $(".content-holder"));
        history.pushState(newUrl, null, newUrl);
    }
</script>
<script>
    var x = document.getElementById('feli-board-img');
    function setIntervalAndExecute(fn, t) {
        fn();
        return(setInterval(fn, t));
    }
    if (x) {
        setIntervalAndExecute(function() {
            console.log('bad');
            x.className = "";
            setTimeout(function() {
                x.className = x.className + " show";
            }, 0);
            setTimeout(function() {
                x.className = x.className + " showA";
            }, 1650);
            setTimeout(function() {
                x.className = x.className + " showB";
            }, 4500);
        }, 5000);
    }
</script>
<?php $this->load_fragment('google_analytics'); ?>
    </body>
</html>
<?php endif;
