<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>View old submissions · Student Ambassador Program · Felicity ʼ16</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
</head>
<body>
    <?php $this->load_fragment('sap/navbar_fragment', ['logged_in' => true]); ?>
    <div class="container">
        <h1>View old submissions</h1>
        <h2>Mission "<?= $mission['title'] ?>"</h2>
        <p>
            <a href="<?= base_url() ?>sap/portal/mission/<?= $mission['id'] ?>" class="btn">&lt; Back to mission</a>
            <a href="../" class="btn btn-blue">View unapproved submissions</a>
        </p>
        <hr>
        <?php if (count($submissions) === 0): ?>
            <p>No approved submissions yet.</p>
        <?php endif?>
        <?php foreach ($submissions as $submission): ?>
            <p>
                <?= htmlspecialchars($submission['users_name']) ?> submitted a task.
            </p>
            <p><strong>Task description: </strong><?= linkify(nl2br(htmlspecialchars($submission['task_description']))) ?></p>
            <?php if (isset($submission['answer'])): ?>
                <p><strong>Their answer: </strong><?= linkify(nl2br(htmlspecialchars($submission['answer']))) ?></p>
            <?php endif; ?>
            <p>
        <hr>
        <?php endforeach; ?>
    </div>
</body>
</html>
