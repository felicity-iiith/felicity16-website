<?php
$text_input = function ($name, $type="text") use ($errors) {
?>
    <input
        name="<?= $name ?>"
        value="<?= isset($_POST[$name]) ? $_POST[$name] : '' ?>"
        type="<?=$type?>"
        class="pure-input-1" required="">

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
        <h1 class="text-center">
            <small><a class="underlined" href="<?= locale_base_url() ?>talks-and-workshops/paper-presentation/"><?= __('Paper presentation') ?></a></small><br/>
            <?= __('Register') ?>
        </h1>
        <?php if ($user_details): ?>
            <div class="row">
                <div class="offset3 col6 success text-center">
                    <p class="lead">
                        You are registered for the event :)
                    </p>
                    <p>
                        This is <a href="<?= $user_details['paper_link'] ?>" target="_blank" class="underlined">link</a> to your paper
                </div>
            </div>
        <?php else: ?>
            <form class="pure-form pure-form-stacked row" method="post" action="">
                <fieldset class="offset3 col6">
                    <?php if (isset($errors['common'])): ?>
                        <div class="error pure-input-1-1"><?= $errors['common'] ?></div>
                    <?php endif; ?>

                    <label><?= __('Contact number') ?></label>
                    <?php $text_input('contact_number'); ?>

                    <label><?= __('Link of your paper') ?></label>
                    <?php $text_input('paper_link', 'url'); ?>

                    <button type="submit" class="pure-button pure-button-primary some-top-margin"><?= __('Confirm Registration') ?></button>
                </fieldset>
            </form>
        <?php endif; ?>
    </div>
</article>
