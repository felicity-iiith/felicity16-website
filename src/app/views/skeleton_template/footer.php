<?php
if (empty($is_ajax)):
    if (isset($events_data)) {
        $categorised_event = [];
        foreach ($events_data as $event) {
            $category = trim( str_replace($event['slug'], '', $event['path']), '/' );
            if ($event['template'] == 'category') {
                if (!isset($categorised_event[$category])) {
                    $categorised_event[$category] = [];
                }
                array_unshift($categorised_event[$category], $event);
            } else {
                $categorised_event[$category][] = $event;
            }
        }
    }
    if (!isset($page_slug)) {
        $page_slug = "static";
    }
    $primary_nav_link = function ($name, $display_name, $image) use ($page_slug) {
?>
        <a href="<?= locale_base_url() . $name ?>/" data-href="<?= $name ?>" class="primary-nav-link <?= $name ?><?= $name == $page_slug ? ' open' : '' ?>">
            <img src="<?= base_url() ?>static/images/<?= $image ?>">
            <div class="title"><?= $display_name ?></div>
        </a>
<?php
    }
?>
    </div>
    <nav>
        <?php if (isset($categorised_event)): ?>
        <div class="crystal-ball">
            <div class="ball-title"><?= __('Events') ?></div>
            <ul class="events-nav">
                <?php foreach ($categorised_event as $category => $events): ?>
                    <li>
                        <a class="events-nav-button" href="#" data-href="<?= $category ?>">
                            <i class="<?= $events[0]['data']['icon'] ?>"></i>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="events-nav-cum-tooltip">
            <?php foreach ($categorised_event as $category => $events): ?>
                <div class="nav-cum-tooltip-dummy-target cat-<?= $category ?>">
                    <?php
                        $category_name = "";
                        $events_sub_nav_home = [];
                        $events_sub_nav = [];
                        foreach ($events as $event) {
                            if (substr($event['path'], -6) === 'index/') {
                                $category_name = $event['data']['name'];
                                $events_sub_nav_home = [
                                    'path'=> substr($event['path'], 1, -6),
                                    'name'=> __('Home')
                                ];
                            } else {
                                array_push($events_sub_nav, [
                                    'path'=> substr($event['path'], 1),
                                    'name'=> $event['data']['name']
                                ]);
                            }
                        }
                        usort($events_sub_nav, function($a, $b) {
                            return $a['name'] - $b['name'];
                        });

                        // Localize now
                        foreach ($events_sub_nav as $key => $event) {
                            $events_sub_nav[$key]['name'] = __($event['name']);
                        }

                        // Add `Home` at the begining
                        array_unshift($events_sub_nav, $events_sub_nav_home);
                    ?>
                    <div class="nav-cum-tooltip">
                        <div class="nav-title"><?= __($category_name) ?></div>
                        <ul class="events-sub-nav">
                            <?php foreach ($events_sub_nav as $event): ?>
                                <li><a href="<?= locale_base_url() . $event['path'] ?>"><?= $event['name'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="triangle-back"></div>
                        <div class="triangle-front"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <ul class="primary-nav left">
            <li>
                <?php $primary_nav_link('about', __('About'), 'dragon8.png'); ?>
            </li>
            <li>
                <?php $primary_nav_link('gallery', __('Gallery'), 'dragon2.png'); ?>
            </li>
            <li>
                <?php $primary_nav_link('schedule', __('Schedule'), 'dragon7.png'); ?>
            </li>
        </ul>
        <ul class="primary-nav right">
            <li>
                <?php $primary_nav_link('sponsors', __('Sponsors'), 'dragon5.png'); ?>
            </li>
            <li>
                <?php $primary_nav_link('team', __('Team'), 'dragon3.png'); ?>
            </li>
            <li>
                <?php $primary_nav_link('contact', __('Contact'), 'dragon6.png'); ?>
            </li>
        </ul>
    </nav>
    <script src="<?= base_url() ?>static/scripts/common.js" charset="utf-8"></script>
    <script src="<?= base_url() ?>static/scripts/ajaxify.js" charset="utf-8"></script>
    <?php if ($page_slug !== "static"): ?>
    <script src="<?= base_url() ?>static/scripts/navigation.js?v=2" charset="utf-8"></script>
    <?php endif; ?>
    <?php $this->load_fragment('google_analytics'); ?>
</body>
</html>
<?php endif;
