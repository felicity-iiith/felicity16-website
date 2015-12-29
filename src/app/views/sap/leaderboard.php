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
        <h1>Leaderboard</h1>
        <table class="text-center striped">
        <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>Score</th>
        </tr>
        <?php
            $rank = 1;
            foreach ($user_scores as $user_score) {
                $name = $user_score['name'];
                $score = $user_score['score'];
                echo "<tr>";
                echo "<td>$rank</td>";
                echo "<td>$name</td>";
                echo "<td>$score</td>";
                echo "</tr>";
                $rank += 1;
            }
        ?>
        </table>
    </div>
</body>
</html>
