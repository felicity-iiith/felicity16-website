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
        <h1>Review submissions</h1>
        <h2>Mission "<?= $mission['title'] ?>"</h2>
        <?php
        if (isset($success)) {
            if ($success) {
                echo "<p class=\"text-success\">$result</p>";
            } else {
                echo "<p class=\"text-error\">$result</p>";
            }
        }
        ?>
        <p>
            <a href="<?= base_url() ?>sap/portal/mission/<?= $mission['id'] ?>" class="btn">&lt; Back to mission</a>
            <a href="./approved/" class="btn btn-blue">View approved submissions</a>
        </p>
        <hr>
        <?php if (count($submissions) === 0): ?>
            <p>Nothing to review here. :)</p>
        <?php endif?>
        <?php foreach ($submissions as $submission): ?>
            <p>
                <?= htmlspecialchars($submission['users_name']) ?> submitted a task.
            </p>
            <p>
                <strong>Task description:</strong><br>
                <?= linkify(nl2br(htmlspecialchars($submission['task_description']))) ?>
            </p>
            <?php if (isset($submission['answer'])): ?>
                <p>
                    <strong>Their answer: </strong><br>
                    <?= linkify(nl2br(htmlspecialchars($submission['answer']))) ?>
                </p>
            <?php endif; ?>
            <p>
            <form method="post" action="../../submission/<?= $submission['id'] ?>/" class="block">
                <input type="hidden" name="mission-id" value="<?= $submission['mission_id'] ?>">
                <button type="submit" name="action" value="approve" class="btn btn-green">Approve</button>
                <button type="submit" name="action" value="reject" class="btn btn-red">Reject</button>
                <label for="reason">If rejecting, reason?</label>
                <textarea id="reason" name="reason"></textarea>
            </form>
        <hr>
        <?php endforeach; ?>
    </div>
</body>
</html>
