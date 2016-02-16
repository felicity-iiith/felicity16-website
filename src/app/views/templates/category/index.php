<?php $this->load_fragment('skeleton_template/header', [
    'title' => __($name)
]); ?>
<?php if (!$is_ajax): ?>
<article class="page open full category <?= $page_slug ?>">
<?php endif; ?>
<?php
$path = str_replace('__', '/', $page_slug);
$len = strlen($path);
$events_to_display = [];
foreach ($events_data as $event) {
    if ( substr($event['path'], 1, $len) == $page_slug && $event['template'] == 'event' ) {
        $events_to_display[] = $event;
    }
}
usort($events_to_display, function ($e1, $e2) {
    if (!$e2['data']['start_time']) {
        return -1;
    }
    else if (!$e1['data']['start_time']) {
        return 1;
    }
    return strcmp($e1['data']['start_time'], $e2['data']['start_time']);
});
?>
<div class="container text-center">
    <h1><?= __($name) ?></h1>
    <?php if (!empty($tagline)): ?>
        <p class="lead"><?= __($tagline) ?></p>
    <?php endif; ?>
    <div class="row">
        <div class="col6">
            <p class="lead text-justify some-top-margin"><?= nl2br(__($long_description)) ?></p>
        </div>
        <div class="col6">
            <h2><?= __('Events') ?></h2>
            <div class="event-list">
                <?php if (count($events_to_display)): ?>
                    <?php foreach ($events_to_display as $event): ?>
                        <a class="event btn" href="<?= locale_base_url() . substr($event['path'], 1) ?>">
                            <p class="lead"><?= __($event['data']['name']) ?></p>
                            <?php if (!empty($event['data']['tagline'])): ?>
                                <p><small><?= __($event['data']['tagline']) ?></small></p>
                            <?php endif; ?>
                            <p><small><?= ($d = $event['data']['start_time']) ? strftime('%B %e', date_timestamp_get(date_create($d))) : __('To be announced') ?></small></p>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    Coming soon
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php if (!$is_ajax): ?>
</article>
<?php endif; ?>
<?php $this->load_fragment('skeleton_template/footer'); ?>
