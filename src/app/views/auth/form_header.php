<?php $this->load_fragment('skeleton_template/header'); ?>
<?php if (empty($is_ajax)): ?>
<article class="page about open full">
<?php endif; ?>
<div class="container text-center">
    <h2><?= $heading ?></h2>
    <div class="text-left container-inline-block">
        <?php
            if (!empty($error)) {
                echo "<div class=\"error\">Error: " . nl2br($error) . "</div><br/>";
            }
        ?>
