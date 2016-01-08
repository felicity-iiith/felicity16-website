<div class="error">
    <?= nl2br($error) ?>
</div>
<?php if ($sent): ?>
    Mail sent.
<?php else: ?>
<form action="" method="post">
    <label>Email: <input type="email" name="email"></label>
    <input type="submit" name="register" value="Next">
</form>
<?php endif; ?>
