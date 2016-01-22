<style>
.pure-form input[type="text"][disabled] {
    color: #555;
}
</style>
<article class="page open full">
    <div class="container">
        <h1 class="text-center">Register for <a class="underlined" href="<?= base_url() ?>threads/breakin/">Breakin</a></h1>
        <?php if ($team_info): ?>
            <p class="text-center lead success">
                You're registered for the event
                <?php if ($team_info['nick2']): ?>
                    as a team with <?= $team_info['nick2'] == $user_nick ? $team_info['nick1'] : $team_info['nick2'] ?>.
                <?php else: ?>
                    as individual.
                <?php endif; ?>
                <?php if ($team_info['team_name']): ?>
                    <br>Your team name is <?= $team_info['team_name'] ?>.
                <?php endif; ?>
            </p>
        <?php else: ?>
            <p class="text-center lead">
                You can register as an individual too.
            </p>
            <form class="pure-form pure-form-aligned row" method="post" action="">
                <fieldset class="offset2">
                    <div class="pure-control-group">
                        <label>Team name(*)</label>
                        <input name="team" value="<?= isset($_POST['team']) ? $_POST['team'] : '' ?>" type="text" placeholder="Your awesome team's name" class="pure-input-1-2" autofocus="" required="">
                        <?php if (isset($errors['team'])): ?>
                            <div class="error pure-input-1-2" style="margin-left: 11.2em;"><?= $errors['team'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="pure-control-group">
                        <label>Your nick(*)</label>
                        <input type="text" value="<?= $user_nick ?>" disabled="" class="pure-input-1-2">
                    </div>
                    <div class="pure-control-group">
                        <label>Your teammate's nick (Optional)</label>
                        <input name="teammate_nick" value="<?= isset($_POST['teammate_nick']) ? $_POST['teammate_nick'] : '' ?>" type="text" placeholder="Your awesome teammate's nick on our site" class="pure-input-1-2">
                        <?php if (isset($errors['teammate_nick'])): ?>
                            <div class="error pure-input-1-2" style="margin-left: 11.2em;"><?= $errors['teammate_nick'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="pure-controls">
                        <button type="submit" class="pure-button pure-button-primary">Register</button>
                    </div>
                </fieldset>
            </form>
            <p class="text-center lead">
                If you're registering as a team,<br>
                your teammate should also be registered with us.
            </p>
        <?php endif; ?>
    </div>
</article>
