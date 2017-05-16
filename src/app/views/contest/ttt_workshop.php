<?php
$text_input = function ($name, $large_text=false) use ($errors) {
?>
    <?php if ($large_text): ?>
        <textarea name="<?= $name ?>" class="pure-input-1" required=""><?= isset($_POST[$name]) ? $_POST[$name] : '' ?></textarea>
    <?php else: ?>
        <input
            name="<?= $name ?>"
            value="<?= isset($_POST[$name]) ? $_POST[$name] : '' ?>"
            required=""
            type="text"
            class="pure-input-1">
    <?php endif; ?>

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
            <small><a class="underlined" href="<?= locale_base_url() ?>litcafe/ttt-workshop/"><?= __('Terribly Tiny Tales Workshop') ?></a></small><br/>
            <?= __('Register') ?>
        </h1>
        <?php if ($user_details): ?>
            <?php if ($user_details['payment_status'] == 'success'): ?>
                <p class="success"><?= __('You are successfully registered for the workshop') ?></p>
            <?php else: ?>
                <div class="text-center">
                    <?php if ($user_details['payment_status'] == 'failed'): ?>
                        <p class="error"><?= __('Payment was unsuccessful') ?></p>
                    <?php endif; ?>

                    <a href="<?= $payment_url ?>" class="pure-button pure-button-primary some-top-margin">
                        <?= __('Proceed to payment ₹100') ?>
                    </a>
                </div>

            <?php endif; ?>
        <?php endif; ?>
        <?php if (!$user_details): ?>
            <form class="pure-form pure-form-stacked row" method="post" action="">
                <fieldset class="offset3 col6">

                    <?php if (isset($errors['common'])): ?>
                        <div class="error pure-input-1-1"><?= $errors['common'] ?></div>
                    <?php endif; ?>

                    <label><?= __('Contact number') ?></label>
                    <?php $text_input('contact_number') ?>
                    <button type="submit" class="pure-button pure-button-primary some-top-margin"><?= __('Proceed to pay ₹100') ?></button>
                </fieldset>
            </form>
        <?php endif; ?>
    </div>
</article>
