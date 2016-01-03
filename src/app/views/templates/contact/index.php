<?php $this->load_fragment('skeleton_template/header'); ?>
<?php if (!$is_ajax): ?>
<article class="page contact open">
<?php endif; ?>
    <?= $contact_us ?>
<?php if (!$is_ajax): ?>
</article>
<?php endif; ?>
<?php $this->load_fragment('skeleton_template/footer'); ?>
