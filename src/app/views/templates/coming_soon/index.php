<?php $this->load_fragment('skeleton_template/header'); ?>
<?php if (!$is_ajax): ?>
<article class="page open coming_soon">
<?php endif; ?>
    <h1 class="text-center"><?= __('This page is coming soon') ?></h1>
<?php if (!$is_ajax): ?>
</article>
<?php endif; ?>
<?php $this->load_fragment('skeleton_template/footer'); ?>
