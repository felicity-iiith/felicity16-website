<?php $this->load_fragment('skeleton_template/header', ['title' => $heading]); ?>
<div class="container text-center">
    <h1><?= $heading ?></h1>
    <div class="text-left container-inline-block">
        <?php
            if (!empty($error)) {
                echo "<div class=\"error\">Error: " . nl2br($error) . "</div><br/>";
            }
        ?>
