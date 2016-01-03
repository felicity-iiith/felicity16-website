<?php
if (!$is_ajax):
$path = implode('-', $path);
$primary_nav_link = function ($name, $image) use ($path) {
?>
    <a href="<?= base_url() . $name ?>/" data-href="<?= $name ?>" class="primary-nav-link <?= $name ?><?= $path == $name ? ' open' : '' ?>">
        <img src="<?= base_url() ?>static/images/<?= $image ?>">
        <div><?= ucfirst($name) ?></div>
    </a>
<?php
}
?>
    </div>
    <nav>
        <div>
            <ul class="primary-nav left">
                <li>
                    <?php $primary_nav_link('about', 'dragon8.png'); ?>
                </li>
                <li>
                    <?php $primary_nav_link('gallery', 'dragon2.png'); ?>
                </li>
            </ul>
            <div class="crystal-ball">
                <div class="bulb">
                    <span>Events</span>
                </div>
                <ul class="events-nav">
                    <li><a class="events-nav-link" href="#"><img class="events-nav-icon" src="<?= base_url() ?>static/images/icon.jpeg"></a></li>
                    <li><a class="events-nav-link" href="#"><img class="events-nav-icon" src="<?= base_url() ?>static/images/icon.jpeg"></a></li>
                    <li><a class="events-nav-link" href="#"><img class="events-nav-icon" src="<?= base_url() ?>static/images/icon.jpeg"></a></li>
                    <li><a class="events-nav-link" href="#"><img class="events-nav-icon" src="<?= base_url() ?>static/images/icon.jpeg"></a></li>
                </ul>
            </div>
            <ul class="primary-nav right">
                <li>
                    <?php $primary_nav_link('sponsors', 'dragon5.png'); ?>
                </li>
                <li>
                    <?php $primary_nav_link('contact', 'dragon6.png'); ?>
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
    <script type="text/javascript">
        var baseUrl = '<?= base_url() ?>';
    </script>
    <script src="<?= base_url() ?>static/scripts/vendor/jquery.min.js" charset="utf-8"></script>
    <script src="<?= base_url() ?>static/scripts/common.js" charset="utf-8"></script>
    <script src="<?= base_url() ?>static/scripts/ajaxify.js" charset="utf-8"></script>
    <script src="<?= base_url() ?>static/scripts/navigation.js" charset="utf-8"></script>
</body>
</html>
<?php endif;
