<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?=$subject?></title>
    </head>
    <body>
        <p>
            Hello <?= $name ?>,
        </p>
        <p>
            You recently submitted the following task:
        </p>
        <blockquote>
            <?= $task_description ?>
        </blockquote>
        <?php if ($has_text_answer): ?>
            <p>
                With the following answer:
            </p>
            <blockquote>
                <?= $answer ?>
            </blockquote>
        <?php endif; ?>
        <?php if ($approved): ?>
            <p>An admin has approved your submission! :D</p>
        <?php else: ?>
            <p>Unfortunately, an admin has rejected your submission. :/</p>
            <?php if (isset($reason)): ?>
                <p>The reason given was:</p>
                <blockquote>
                    <?= $reason ?>
                </blockquote>
            <?php endif; ?>
        <?php endif; ?>
        <p>Link to mission: <a href="<?= $mission_url ?>"><?= $mission_url?> </a></p>
        <p>
            With love,<br/>
            Team Felicity
        </p>
        <div style="padding:0.4em 0;font-size:0.8em;margin-top:1em;border-top:1px solid #ccc;box-sizing:border-box;color:#666;">
            <p style="margin:0;line-height:1.5em;">
                Visit
                <a  style="color:#006EFF;"  href="<?= base_url() ?>" target="_blank">felicity.iiit.ac.in</a>
                for more fun stuff!
            </p>
        </div>
    </body>
</html>
