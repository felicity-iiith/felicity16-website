<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Mission <?= $mission['id'] ?> · Student Ambassador Program · Felicity ʼ16</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
</head>
<body>
    <?php $this->load_fragment('sap/navbar_fragment', ['logged_in' => true]); ?>
    <div class="container">
        <h1>Mission <?= $mission['id'] ?> · Level <?= $mission['level'] ?></h1>
        <h2><?= $mission['title'] ?></h2>
        <?php if (isset($mission['description'])): ?>
            <p><?= $mission['description'] ?></p>
        <?php endif; ?>
        <p>
            <strong>
                This mission has <?= count($tasks) ?> task<?php
                    if (count($tasks) !== 1) {
                        echo 's';
                    }
                ?>.
            </strong>
        </p>
        <?php if ($is_admin): ?>
            <a href="./createtask/" class="btn btn-green">Create new task</a>
        <?php endif; ?>
        <hr>
        <?php foreach ($tasks as $i=>$task): ?>
            <h3>Task <?= $i + 1 ?></h3>
            <p><?= $task['description'] ?></p>
            <hr>
        <?php endforeach; ?>
    </div>
</body>
</html>
