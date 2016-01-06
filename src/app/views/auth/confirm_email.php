<div class="error">
    <?= nl2br($error) ?>
</div>
<form action="update_mail/" method="post">
    <input type="email" name="mail" value="<?= $user_data['mail'] ?>">
    <input type="submit" name="update_mail" value="Confirm">
</form>
