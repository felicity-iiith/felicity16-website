<?php $this->load_fragment('skeleton_template/header'); ?>
<?php if (!$is_ajax): ?>
<article class="page open schedule">
<?php endif; ?>
<?php
    $events_list = array_filter($events_data, function ($event) {
        return $event['template'] == 'event';
    });
    usort($events_list, function($a, $b) {
        if (!isset($b['data']['start_time'])) {
            return -1;
        }
        else if (!isset($a['data']['start_time'])) {
            return 1;
        }
        $a_date = $a['data']['start_time'];
        $b_date = $b['data']['start_time'];
        $a_enddate = $a['data']['end_time'];
        $b_enddate = $b['data']['end_time'];
        $now=@date('Y-m-d H:i');
        if ($a_enddate >= $now xor $b_enddate >= $now) {
            if ($a_enddate >= $now) {
                return -1;
            }
            else {
                return 1;
            }
        }
        if ($a_date == $b_date) {
            return 0;
        }
        return ($a_date < $b_date) ? -1 : 1;
    });

    $dates = array();
    foreach ($events_list as &$evref) {
        $evref['path'] = substr($evref['path'], 1);
        $evref['type'] = substr($evref['path'], 0, - (strlen($evref['slug']) + 2));
        $cur = date_parse($evref['data']['start_time']);
        array_push($dates, $cur['month']."-".$cur['day']);
    }
    $is_imp = function($date) use ($dates) {
        if ( in_array($date, $dates) ) {
            echo "class='has-event'";
        }
    };
?>
<div class="container row">
    <div class="col6">
        <div class="cal-month">
            <table class="cal-table" data-month="Jan">
                <thead>
                    <tr>
                        <th class="cal-month-name" colspan="7">January 2016</th>
                    </tr>
                    <tr>
                        <th>Sun</th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="empty"></td>
                        <td class="empty"></td>
                        <td class="empty"></td>
                        <td class="empty"></td>
                        <td class="empty"></td>
                        <td <?php $is_imp('1-1'); ?>>1</td>
                        <td <?php $is_imp('1-2'); ?>>2</td>
                    </tr>
                    <tr>
                        <td <?php $is_imp('1-3'); ?>>3</td>
                        <td <?php $is_imp('1-4'); ?>>4</td>
                        <td <?php $is_imp('1-5'); ?>>5</td>
                        <td <?php $is_imp('1-6'); ?>>6</td>
                        <td <?php $is_imp('1-7'); ?>>7</td>
                        <td <?php $is_imp('1-8'); ?>>8</td>
                        <td <?php $is_imp('1-9'); ?>>9</td>
                    </tr>
                    <tr>
                        <td <?php $is_imp('1-10'); ?>>10</td>
                        <td <?php $is_imp('1-11'); ?>>11</td>
                        <td <?php $is_imp('1-12'); ?>>12</td>
                        <td <?php $is_imp('1-13'); ?>>13</td>
                        <td <?php $is_imp('1-14'); ?>>14</td>
                        <td <?php $is_imp('1-15'); ?>>15</td>
                        <td <?php $is_imp('1-16'); ?>>16</td>
                    </tr>
                    <tr>
                        <td <?php $is_imp('1-17'); ?>>17</td>
                        <td <?php $is_imp('1-18'); ?>>18</td>
                        <td <?php $is_imp('1-19'); ?>>19</td>
                        <td <?php $is_imp('1-20'); ?>>20</td>
                        <td <?php $is_imp('1-21'); ?>>21</td>
                        <td <?php $is_imp('1-22'); ?>>22</td>
                        <td <?php $is_imp('1-23'); ?>>23</td>
                    </tr>
                    <tr>
                        <td <?php $is_imp('1-24'); ?>>24</td>
                        <td <?php $is_imp('1-25'); ?>>25</td>
                        <td <?php $is_imp('1-26'); ?>>26</td>
                        <td <?php $is_imp('1-27'); ?>>27</td>
                        <td <?php $is_imp('1-28'); ?>>28</td>
                        <td <?php $is_imp('1-29'); ?>>29</td>
                        <td <?php $is_imp('1-30'); ?>>30</td>
                    </tr>
                    <tr>
                        <td <?php $is_imp('1-31'); ?>>31</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="cal-month">
            <table class="cal-table" data-month="Jan">
                <thead>
                    <tr>
                        <th class="cal-month-name" colspan="7">February 2016</th>
                    </tr>
                    <tr>
                        <th>Sun</th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="empty"></td>
                        <td <?php $is_imp('2-1'); ?>>1</td>
                        <td <?php $is_imp('2-2'); ?>>2</td>
                        <td <?php $is_imp('2-3'); ?>>3</td>
                        <td <?php $is_imp('2-4'); ?>>4</td>
                        <td <?php $is_imp('2-5'); ?>>5</td>
                        <td <?php $is_imp('2-6'); ?>>6</td>
                    </tr>
                    <tr>
                        <td <?php $is_imp('2-7'); ?>>7</td>
                        <td <?php $is_imp('2-8'); ?>>8</td>
                        <td <?php $is_imp('2-9'); ?>>9</td>
                        <td <?php $is_imp('2-10'); ?>>10</td>
                        <td <?php $is_imp('2-11'); ?>>11</td>
                        <td <?php $is_imp('2-12'); ?>>12</td>
                        <td <?php $is_imp('2-13'); ?>>13</td>
                    </tr>
                    <tr>
                        <td <?php $is_imp('2-14'); ?>>14</td>
                        <td <?php $is_imp('2-15'); ?>>15</td>
                        <td <?php $is_imp('2-16'); ?>>16</td>
                        <td <?php $is_imp('2-17'); ?>>17</td>
                        <td <?php $is_imp('2-18'); ?>>18</td>
                        <td <?php $is_imp('2-19'); ?>>19</td>
                        <td <?php $is_imp('2-20'); ?>>20</td>
                    </tr>
                    <tr>
                        <td <?php $is_imp('2-21'); ?>>21</td>
                        <td <?php $is_imp('2-22'); ?>>22</td>
                        <td <?php $is_imp('2-23'); ?>>23</td>
                        <td <?php $is_imp('2-24'); ?>>24</td>
                        <td <?php $is_imp('2-25'); ?>>25</td>
                        <td <?php $is_imp('2-26'); ?>>26</td>
                        <td <?php $is_imp('2-27'); ?>>27</td>
                    </tr>
                    <tr>
                        <td <?php $is_imp('2-28'); ?>>28</td>
                        <td <?php $is_imp('2-29'); ?>>29</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col6">
        <table class="eventslist">
            <tbody>
                <?php
                    $lastdate = '';
                    foreach ($events_list as $event):
                ?>
                    <tr class="timeline <?= $event['type'] ?>">
                        <td class="day">
                            <?php
                                $formatted = date_format(@date_create($event['data']['start_time']), 'l, jS F');
                                if (strcmp($lastdate, $formatted) != 0) {
                                    $lastdate = $formatted;
                                    echo $formatted;
                                }
                            ?>
                        </td>
                        <td class="event-container">
                            <a href="<?= base_url() . $event['path'] ?>" class="event">
                                <div class="circle"><div class="innercircle"></div></div>
                                <span><?= $event['data']['name'] ?></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php if (!$is_ajax): ?>
</article>
<?php endif; ?>
<?php $this->load_fragment('skeleton_template/footer'); ?>
