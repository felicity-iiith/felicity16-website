<?php
if (!$is_ajax):
$primary_nav_link = function ($name, $image) use ($page_slug) {
?>
    <a href="<?= base_url() . $name ?>/" data-href="<?= $name ?>" class="primary-nav-link <?= $name ?><?= $name == $page_slug ? ' open' : '' ?>">
        <img src="<?= base_url() ?>static/images/<?= $image ?>">
        <div class="title"><?= ucfirst($name) ?></div>
    </a>
<?php
}
?>
    </div>
    <nav>
        <div class="crystal-ball">
            <div class="ball-title">Events</div>
            <ul class="events-nav">
                <li>
                    <div class="events-nav-button testing" href="#" data-href="threads">
                        <i class="icon-code"></i>
                    </div>
                </li>
                <li>
                    <div class="events-nav-button testing" href="#" data-href="nights">
                        <i class="icon-music"></i>
                    </div>
                </li>
                <li>
                    <div class="events-nav-button testing" href="#" data-href="futsal">
                        <i class="icon-soccer-ball"></i>
                    </div>
                </li>
                <li>
                    <div class="events-nav-button testing" href="#" data-href="stomp-the-yard">
                        <i class="icon-child"></i>
                    </div>
                </li>
                <li>
                    <div class="events-nav-button" href="#" title="Paper presentation" rel="tooltip">
                        <i class="icon-doc"></i>
                    </div>
                </li>
                <li>
                    <div class="events-nav-button" href="#" title="Lit Cafe" rel="tooltip">
                        <i class="icon-pencil"></i>
                    </div>
                </li>
                <li>
                    <div class="events-nav-button" href="#" title="Futsal" rel="tooltip">
                        <i class="icon-soccer-ball"></i>
                    </div>
                </li>
                <li>
                    <div class="events-nav-button" href="#" title="Stomp the yard" rel="tooltip">
                        <i class="icon-child"></i>
                    </div>
                </li>
            </ul>
        </div>
        <ul class="primary-nav left">
            <li>
                <?php $primary_nav_link('about', 'dragon8.png'); ?>
            </li>
            <li>
                <?php $primary_nav_link('gallery', 'dragon2.png'); ?>
            </li>
            <li>
                <?php $primary_nav_link('schedule', 'dragon7.png'); ?>
            </li>
        </ul>
        <ul class="primary-nav right">
            <li>
                <?php $primary_nav_link('sponsors', 'dragon5.png'); ?>
            </li>
            <li>
                <?php $primary_nav_link('team', 'dragon3.png'); ?>
            </li>
            <li>
                <?php $primary_nav_link('contact', 'dragon6.png'); ?>
            </li>
        </ul>
    </nav>
    <script src="<?= base_url() ?>static/scripts/common.js" charset="utf-8"></script>
    <script src="<?= base_url() ?>static/scripts/ajaxify.js" charset="utf-8"></script>
    <script src="<?= base_url() ?>static/scripts/navigation.js" charset="utf-8"></script>
</body>
</html>
<?php endif;
