<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Review mission · Student Ambassador Program · Felicity ʼ16</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
</head>
<body>
    <?php $this->load_fragment('sap/navbar_fragment', ['logged_in' => true]); ?>
    <div class="container">
        <?php
        if (isset($success)) {
            if ($success) {
                echo "<p style=\"color:green\">$result</p>";
            } else {
                echo "<p style=\"color:red\">$result</p>";
            }
        }
        ?>
        <?php
        if (count($submissions) === 0) {
            echo "Nothing to review here. :)";
        }
        ?>
        <?php foreach ($submissions as $submission): ?>
            <p>
                <?= htmlspecialchars($submission['users_name']) ?> submitted a task from mission
                "<a href="<?= base_url() ?>sap/portal/mission/<?= $submission['mission_id'] ?>" target="_blank"><?= htmlspecialchars($submission['mission_title']) ?></a>"
            </p>
            <p><strong>Task description: </strong><?= htmlspecialchars($submission['task_description']) ?></p>
            <?php if (isset($submission['answer'])): ?>
                <p><strong>Their answer: </strong><?= htmlspecialchars($submission['answer']) ?></p>
            <?php endif; ?>
            <p>
            <form method="post" action="../../submission/<?= $submission['id'] ?>/">
                <input type="hidden" name="mission-id" value="<?= $submission['mission_id'] ?>">
                <button type="submit" name="action" value="approve" class="btn btn-green">Approve</button>
                <button type="submit" name="action" value="reject" class="btn btn-red">Reject</button>
            </form>
        <hr>
        <?php endforeach; ?>
    </div>
</body>
</html>
