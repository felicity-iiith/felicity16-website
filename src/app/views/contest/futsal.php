<?php
$text_input = function ($name, $required=true) use ($errors) {
?>
    <input
        name="<?= $name ?>"
        value="<?= isset($_POST[$name]) ? $_POST[$name] : '' ?>"
        type="text"
        <?php if ($required): ?>
            required=""
        <?php endif; ?>
        class="pure-input-1">

    <?php if (isset($errors[$name])): ?>
        <div class="error pure-input-1-1"><?= $errors[$name] ?></div>
    <?php endif; ?>
<?php
}
?>

<style>
.pure-form input[type="text"][disabled] {
    color: #555;
}
</style>
<article class="page open full">
    <div class="container">
        <h1 class="text-center">Register for
            <a class="underlined" href="<?= locale_base_url() ?>">Futsal</a>
        </h1>
        <?php if (!$errors && $team_info): ?>
            <p class="text-center lead success">
                You're registered for the event as a part of team <?= $team_info['team_name'] ?>
            </p>
            <p class="text-center">
                If you think this is a mistake, please email us at <a href="mailto:contact@felicity.iiit.ac.in" class="underlined">contact@felicity.iiit.ac.in</a>
            </p>
        <?php else: ?>
            <p class="text-center lead">
                Note: Make sure that all of the team members are registered with us.
            </p>
            <p class="text-center">
                Fields marked (*) are required.
            </p>
            <form class="pure-form pure-form-stacked row" method="post" action="">
                <fieldset class="offset3 col6">
                    <?php if (isset($errors['common'])): ?>
                        <div class="error pure-input-1-1"><?= $errors['common'] ?></div>
                    <?php endif; ?>

                    <label><?= __('Team name') ?> (*)</label>
                    <?php $text_input('team_name'); ?>

                    <label><?= __('Contact number of any one team member') ?> (*)</label>
                    <?php $text_input('contact_number'); ?>

                    <label><?= __('Team member 1 username') ?> (*)</label>
                    <input value="<?= $user_nick ?>" type="text" class="pure-input-1" disabled="">

                    <label><?= __('Team member 2 username') ?> (*)</label>
                    <?php $text_input('nick2'); ?>

                    <label><?= __('Team member 3 username') ?> (*)</label>
                    <?php $text_input('nick3'); ?>

                    <label><?= __('Team member 4 username') ?> (*)</label>
                    <?php $text_input('nick4'); ?>

                    <label><?= __('Team member 5 username') ?></label>
                    <?php $text_input('nick5', false); ?>

                    <label><?= __('Team member 6 username') ?></label>
                    <?php $text_input('nick6', false); ?>

                    <button type="submit" class="pure-button pure-button-primary some-top-margin"><?= __('Confirm Registration') ?></button>
                </fieldset>
            </form>
        <?php endif; ?>
    </div>
</article>
