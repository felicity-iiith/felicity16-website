<?php $this->load_fragment('skeleton_template/header', ['title' => __($title)]); ?>
<?php if (!$is_ajax): ?>
<article class="page open full">
<?php endif; ?>
    <h1 class="text-center"><?= __($title) ?></h1>
    <div class="container"><?= __($body) ?></div>
<?php if (!$is_ajax): ?>
</article>
<?php endif; ?>
<?php $this->load_fragment('skeleton_template/footer'); ?>
