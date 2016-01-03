<?php $this->load_fragment('skeleton_template/header'); ?>
<?php if (!$is_ajax): ?>
<article class="page about open">
<?php endif; ?>
    <p>
        <?= $about_us ?>
    </p>
<?php if (!$is_ajax): ?>
</article>
<?php endif; ?>
<?php $this->load_fragment('skeleton_template/footer'); ?>
