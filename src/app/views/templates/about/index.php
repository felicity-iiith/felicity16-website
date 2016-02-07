<?php $this->load_fragment('skeleton_template/header', ['title' => __('About Us')]); ?>
<?php if (!$is_ajax): ?>
<article class="page open about">
<?php endif; ?>
    <p>
        <?= __($about_us) ?>
    </p>
<?php if (!$is_ajax): ?>
</article>
<?php endif; ?>
<?php $this->load_fragment('skeleton_template/footer'); ?>
