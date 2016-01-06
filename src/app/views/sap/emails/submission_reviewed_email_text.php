Hello <?= $name ?>,

You recently submitted the following task:

<?= $task_description ?>

<?php if ($has_text_answer): ?>

With the following answer:

<?= $answer ?>

<?php endif; ?>
<?php if ($approved): ?>

An admin has approved your submission! :D

<?php else: ?>

Unfortunately, an admin has rejected your submission. :/

<?php if (isset($reason)): ?>
The reason given was:

<?= $reason ?>

<?php endif; ?>
<?php endif; ?>

Link to mission: <?= $mission_url?>


With love,
Team Felicity

______________________________________________________________________________
Visit https://felicity.iiit.ac.in/ for more fun stuff!
