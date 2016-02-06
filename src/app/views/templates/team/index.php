<?php $this->load_fragment('skeleton_template/header'); ?>
<?php if (!$is_ajax): ?>
<article class="page open team">
<?php endif; ?>
<?php
function img($img_name, $name=null) {
?>
    <div class="img-container<?=$name ? '' : ' group-pic' ?>" style="transform: rotate(<?= 2 - rand(1, 4) ?>deg)">
        <img src="<?=base_url()?>static/images/clip.png" class="clip">
        <img src="<?=base_url()?>static/images/team/<?=$img_name?>">
    </div>
    <?php if ($name): ?>
        <p class="name"><?= $name ?></p>
    <?php endif; ?>
<?php
}
?>
<div>
    <h2 class="text-center"><?= __('The Felicity Coordinators')?></h2>
    <div class="row">
        <div class="col3 offset1"><?php img('Jeevan-Chowdary.jpg', __('Jeevan Chowdary')); ?></div>
        <div class="col3 offset-half"><?php img('Sanatan-Mishra.jpg', __('Sanatan Mishra')); ?></div>
        <div class="col3 offset-half"><?php img('Vivek-Ghaisas.jpg', __('Vivek Ghaisas')); ?></div>
    </div>
</div>
<div>
    <h2 class="text-center"><?= __('Finance Council') ?></h2>
    <div class="row">
        <div class="col3 offset2"><?php img('Shyamili-Venkatapathy.jpg', __('Shyamili Venkatapathy')); ?></div>
        <div class="col3 offset2"><?php img('Gorang-Maniar.jpg', __('Gorang Maniar')); ?></div>
    </div>
</div>
<div>
    <h2 class="text-center"><?= __('The Threads Team') ?></h2>
    <div class="row">
        <div class="col11 offset-half">
            <?php img('Threads-Team.jpg'); ?>
        </div>
    </div>
    <div class="container">
        <p class="names">
            <strong><?= __('First row') ?></strong>:<br>
            <?= __('Aditya Baskar') ?>,
            <?= __('Rounak Mundra') ?>,
            <?= __('Rahul Nahata') ?>,
            <?= __('Amitha Reddy') ?>,
            <?= __('Meghana Manusanipalli') ?>
        </p>
        <p class="names">
            <strong><?= __('Second row') ?></strong>:<br>
            <?= __('Ayush Mishra') ?>,
            <?= __('Chanakya Malireddy') ?>,
            <?= __('Sriram Narayanan') ?>,
            <?= __('Shriram Rahatgaonkar') ?>,
            <?= __('Harshil Goel') ?>,
            <?= __('Saksham Aggarwal') ?>,
            <?= __('Tanmay Sahay') ?>,
            <?= __('Himanshu Dahiya') ?>,
            <?= __('Ashish Jain') ?>,
            <?= __('Kunal Singh') ?>
        </p>
        <p class="names">
            <strong><?= __('Third row') ?></strong>:<br>
            <?= __('Vivek Ghaisas') ?>,
            <?= __('Nikhil Daliya') ?>,
            <?= __('Anveshi Shukla') ?>,
            <?= __('Aniket Jain') ?>,
            <?= __('Harshit Harchani') ?>,
            <?= __('Pranjal Rai') ?>,
            <strong><?= __('Abhineet Jain')  ?> (<?= __('Threads Coordinator')?>)</strong>,
            <strong><?= __('Parth Kolekar')  ?> (<?= __('Primary Server Admin')?>)</strong>,
            <strong><?= __('Nisarg Jhaveri') ?> (<?= __('Secondary Server Admin')?>)</strong>,
            <?= __('Anurag Gupta') ?>,
            <?= __('Rohan Karnawat') ?>
        </p>
    </div>
</div>
<div>
    <h2 class="text-center"><?= __('The Pulsation Coordinators') ?></h2>
    <div class="row">
        <div class="col3"><?php img('Aditya-Sreekar.jpg', __('Aditya Sreekar')); ?></div>
        <div class="col3"><?php img('Danish-Sodhi.jpg', __('Danish Sodhi')); ?></div>
        <div class="col3"><?php img('Nayan-Joshi.jpg', __('Nayan Joshi')); ?></div>
        <div class="col3"><?php img('Rajat-Singla.jpg', __('Rajat Singla')); ?></div>
    </div>
    <div class="row">
        <div class="col3 offset2"><?php img('Roopal-Nahar.jpg', __('Roopal Nahar')); ?></div>
        <div class="col3 offset2"><?php img('Sai-Krishna.jpg', __('Sai Krishna')); ?></div>
    </div>
</div>
<div>
    <h2 class="text-center"><?= __('The Marketing and HR Team') ?></h2>
    <div class="row">
        <div class="col8 offset2">
            <?php img('Marketing-Team.jpg'); ?>
        </div>
    </div>
    <div class="container text-center">
        <p class="names">
            <strong><?= __('First row') ?></strong>:<br>
            <?= __('Jeevan Chowdary') ?>
        </p>
        <p class="names">
            <strong><?= __('Second row') ?></strong>:<br>
            <?= __('Pranjal Rai') ?>,
            <?= __('Inturi Suhas Reddy') ?>,
            <?= __('Revi Teja') ?>,
            <?= __('Rishabh Khawad') ?>
        </p>
        <p class="names">
            <strong><?= __('Third row') ?></strong>:<br>
            <?= __('Pooja Shamili Ganti') ?>,
            <?= __('Akruti Kushwaha') ?>,
            <?= __('Smriti Singh') ?>
        </p>
    </div>
</div>
<div>
    <h2 class="text-center"><?= __('The Kalakshetra Team') ?></h2>
    <div class="row">
        <div class="col10 offset1">
            <?php img('Kalakshetra-Team.jpg', false); ?>
        </div>
    </div>
    <div class="container">
        <p class="names">
            <strong><?= __('First row') ?></strong>:<br>
            <?= __('Sagar Gaur') ?>,
            <?= __('Aditya Baskar') ?>,
            <?= __('Apuroop Kumar Reddy') ?>,
            <?= __('Anvesh Rao') ?>,
            <?= __('Saksham Agrawal') ?>,
            <?= __('Nurendra Choudhary') ?>,
            <?= __('Abhinav Prasad') ?>,
            <?= __('Debayan Das') ?>
        </p>
        <p class="names">
            <strong><?= __('Second row') ?></strong>:<br>
            <?= __('Roopal Nahar') ?>,
            <?= __('Amitha Reddy') ?>,
            <?= __('Kaveri Anuranjana') ?>,
            <?= __('Shyamli Venkatapathy') ?>,
            <?= __('Srishti Aggarwal') ?>,
            <?= __('Smriti Singh') ?>,
            <?= __('Sanjana Sharma') ?>,
            <?= __('Nisarg Jhaveri') ?>
        </p>
        <p class="names">
            <strong><?= __('Third row') ?></strong>:<br>
            <?= __('Rishabh Banarasia') ?>
        </p>
    </div>
</div>
<div>
    <h2 class="text-center"><?= __('The LitCafe Coordinators') ?></h2>
    <div class="row">
        <div class="col3 offset1"><?php img('Anurag-Ghosh.jpg', __('Anurag Ghosh')); ?></div>
        <div class="col3 offset-half"><?php img('Gorang-Maniar.jpg', __('Gorang Maniar')); ?></div>
        <div class="col3 offset-half"><?php img('Vatika-Harlalka.jpg', __('Vatika Harlalka')); ?></div>
    </div>
</div>
<div>
    <h2 class="text-center"><?= __('The Pronites Coordinators') ?></h2>
    <div class="row">
        <div class="col3 offset1"><?php img('Anurag-Gupta.jpg', __('Anurag Gupta')); ?></div>
        <div class="col3 offset-half"><?php img('Rishabh-Khawad.jpg', __('Rishabh Khawad')); ?></div>
        <div class="col3 offset-half"><?php img('Shashank-Agrawal.jpg', __('Shashank Agrawal')); ?></div>
    </div>
</div>
<div>
    <h2 class="text-center"><?= __('Stage Controls') ?></h2>
    <div class="row">
        <div class="col4 offset4">
            <?php img('Stage-Team.jpg'); ?>
            <p class="name">
                <?= __('Aditya Bohra') ?>,
                <?= __('Aishwary Gupta') ?>
            </p>
        </div>
    </div>
</div>


<?php if (!$is_ajax): ?>
</article>
<?php endif; ?>
<?php $this->load_fragment('skeleton_template/footer'); ?>
