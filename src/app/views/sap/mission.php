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
        <h2><?= htmlspecialchars($mission['title']) ?></h2>
        <?php if (isset($mission['description'])): ?>
            <p><?= htmlspecialchars($mission['description']) ?></p>
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
            <a href="../../review/mission/<?= $mission['id'] ?>/" class="btn btn-blue">
                Review submissions for this mission
            </a>
        <?php endif; ?>
        <?php if (isset($errors)) {
                foreach ($errors as $error) {
                    echo "<p class=\"text-error\">$error</p>";
                }
            }
        ?>
        <hr>
        <?php foreach ($tasks as $i=>$task): ?>
            <h3>Task <?= $i + 1 ?></h3>
            <?php if (isset($task['submission']) && $task['submission']['done'] !== 2): ?>
                <p><?= htmlspecialchars($task['description']) ?></p>
                <?php if ($task['has_text_answer']): ?>
                    <textarea disabled><?= htmlspecialchars($task['submission']['answer']) ?></textarea>
                <?php endif; ?>
                <?php if ($task['submission']['done'] == 0): ?>
                    <p><strong>Submitted for review</strong></p>
                <?php elseif ($task['submission']['done'] == 1): ?>
                    <p class="text-success"><strong>Done! :)</strong></p>
                <?php endif; ?>
            <?php else: ?>
                <form class="block" method="post" action="./submittask/">
                    <p><?= htmlspecialchars($task['description']) ?></p>
                    <?php if (isset($task['submission']) && $task['submission']['done'] == 2): ?>
                        <p class="text-error">Your submission for this task was rejected. :/</p>
                    <?php endif; ?>
                    <?php if ($task['has_text_answer']): ?>
                        <textarea name="text-answer" required></textarea>
                    <?php endif; ?>
                    <button class="btn btn-blue" type="submit" name="submit-task" value="<?= $task['id'] ?>">
                        Submit task for review
                    </button>
                </form>
            <?php endif; ?>
            <hr>
        <?php endforeach; ?>
    </div>
</body>
</html>
