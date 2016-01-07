<?php $this->load_fragment('skeleton_template/header'); ?>
<?php if (!$is_ajax): ?>
<article class="page open full event <?= $page_slug ?>">
<?php endif; ?>
    <a href="#">Threads</a>
    <div class="container">
    <h1><?= $name ?></h1>
        <p class="lead"><?= $tagline ?></p>
        <div class="row padded">
            <div class="col6">
                <h2><?= $status ?></h2>
                <?php if ($button_text): ?>
                    <a class="btn" href="<?= base_url() . $button_link ?>"><?= $button_text ?></a>
                <?php endif; ?>
                <p class="lead some-top-margin"><?= $description ?></p>
                <h2 class="text-left">Rules</h2>
                <ul class="text-justify">
                    <?php foreach ($rules as $rule): ?>
                        <li><?= $rule ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col6">
                <h2>Event Details</h2>
                <p>All times are in UTC+5:30.</p>
                <div class="row padded some-top-margin">
                    <div class="col5 text-right">
                        Start time
                    </div>
                    <div class="col7 text-left">
                        <?php $datetime = date_parse($start_time); ?>
                        <?php if ($datetime['error_count']): ?>
                            <?= $start_time ?>
                        <?php else: ?>
                            <a class="date-time" target="_blank" href="http://www.timeanddate.com/worldclock/fixedtime.html?day=<?= $datetime['day']?>&month=<?= $datetime['month']?>&year=<?= $datetime['year']?>&hour=<?= $datetime['hour']?>&min=<?= $datetime['minute']?>&sec=<?= $datetime['second']?>&p1=505&msg=<?= urlencode($name) ?>">
                                <?= $start_time ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row padded some-top-margin">
                    <div class="col5 text-right">
                        End time
                    </div>
                    <div class="col7 text-left">
                        <?php $datetime = date_parse($end_time); ?>
                        <?php if ($datetime['error_count']): ?>
                            <?= $end_time ?>
                        <?php else: ?>
                            <a class="date-time" target="_blank" href="http://www.timeanddate.com/worldclock/fixedtime.html?day=<?= $datetime['day']?>&month=<?= $datetime['month']?>&year=<?= $datetime['year']?>&hour=<?= $datetime['hour']?>&min=<?= $datetime['minute']?>&sec=<?= $datetime['second']?>&p1=505&msg=<?= urlencode($name) ?>">
                                <?= $end_time ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row padded some-top-margin">
                    <div class="col5 text-right">
                        Venue
                    </div>
                    <div class="col7 text-left">
                        <?= $venue ?>
                    </div>
                </div>
                <div class="row padded some-top-margin">
                    <div class="col5 text-right">
                        Contact Email
                    </div>
                    <div class="col7 text-left">
                        <a href="mailto:<?= $contact_email?>"><?= $contact_email ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php if (!$is_ajax): ?>
</article>
<?php endif; ?>
<?php $this->load_fragment('skeleton_template/footer'); ?>
