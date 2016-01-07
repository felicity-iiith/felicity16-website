<?php $this->load_fragment('skeleton_template/header'); ?>
<?php if (!$is_ajax): ?>
<article class="page open full category">
<?php endif; ?>
<?php
$path = str_replace('__', '/', $page_slug);
$len = strlen($path);
$events_to_display = [];
foreach ($events_list as $event) {
    if ( substr($event['path'], 1, $len) == $page_slug && $event['template'] == 'event' ) {
        $events_to_display[] = $event;
    }
}
usort($events_to_display, function ($e1, $e2) {
    return strcmp($e1['data']['start_time'], $e2['data']['start_time']);
});
?>
<div class="container text-center">
    <h1><?= $name ?></h1>
    <p class="lead"><?= $tagline ?></p>
    <div class="row">
        <div class="col6">
            <h2>About <?= $name ?></h2>
            <p class="lead text-justify"><?= $long_description ?></p>
        </div>
        <div class="col6">
            <h2>Events</h2>
            <div class="event-list">
                <?php foreach ($events_to_display as $event): ?>
                    <a class="event btn" href="<?= base_url() . substr($event['path'], 1) ?>">
                        <p class="lead"><?= $event['data']['name'] ?></p>
                        <p><small><?= $event['data']['tagline'] ?></small></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php if (!$is_ajax): ?>
</article>
<?php endif; ?>
<?php $this->load_fragment('skeleton_template/footer'); ?>
