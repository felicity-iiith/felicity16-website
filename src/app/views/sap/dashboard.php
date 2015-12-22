<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Student Ambassador Program · Felicity ʼ16</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url() ?>static/styles/vendor/thoda.min.css">
</head>
<body>
    <?php $this->load_fragment('sap/navbar_fragment', ['logged_in' => true]); ?>
    <div class="container">
        <h1>Dashboard</h1>
        <?php if ($is_admin): ?>
            <a class="btn btn-green" href="<?= base_url() ?>sap/portal/mission/create">
                Create new mission
            </a>
            <a class="btn btn-blue" href="<?= base_url() ?>sap/portal/users/">
                Review users
            </a>
        <?php endif; ?>
        <?php
            $level = 0;
            foreach ($missions as $mission) {
                if ($mission['level'] > $level) {
                    $level++;
                    if ($level > 1) {
                        echo '</ul>';
                    }
                    echo "<h2>Level $level</h2>";
                    echo '<ul>';
                }
        ?>
            <li>
                <a href="<?= base_url() ?>sap/portal/mission/<?= $mission['id'] ?>">
                    <?= htmlspecialchars($mission['title']) ?>
                </a>
            </li>
        <?php
            }
        ?>
    </div>
</body>
</html>
