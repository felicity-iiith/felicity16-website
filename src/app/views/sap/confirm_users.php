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
    <div>
        <table style="width: 100%;">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Contact Details</th>
                <th>Clg info</th>
                <th>&nbsp;</th>
                <th>Status</th>
            </tr>
            <?php foreach($users as $user): ?>
                <tr>
                    <td>
                        <?= $user['id']?>
                    </td>
                    <td>
                        <?= htmlspecialchars($user['name'])?>
                    </td>
                    <td>
                        <?= htmlspecialchars($user['email'])?>
                        <hr>
                        <?= htmlspecialchars($user['phone_number'])?>
                        <hr>
                        <?= htmlspecialchars($user['facebook_profile_link'])?>
                    </td>
                    <td>
                        <?= htmlspecialchars($user['college'])?>
                        <hr>
                        <?= htmlspecialchars($user['program_of_study'])?>
                        <hr>
                        <?= htmlspecialchars($user['year_of_study'])?>
                    </td>
                    <td>
                        <strong>About you: </strong>
                        <?= htmlspecialchars($user['about_you'])?>
                        <hr>
                        <strong>Why apply: </strong>
                        <?= htmlspecialchars($user['why_apply'])?>
                        <hr>
                        <strong>Events Organised: </strong>
                        <?= htmlspecialchars($user['organised_event'])?>
                    </td>
                    <td>
                        <?php if ($user['has_activated']): ?>
                            User has been approved and activated.
                        <?php elseif ($user['hash_for_ceating_password']): ?>
                            User has been approved but have not activated.
                        <?php else: ?>
                            <form method="post"
                                action="<?= base_url() ?>sap/portal/users/approve/<?= $user['id'] ?>"
                                onsubmit="return confirm('Are you sure you want to approve <?= htmlspecialchars($user['name']) ?>?')">
                                <button class="btn btn-green"> ✓ Approve</button>
                            </form>
                            <br>
                            <form method="post"
                                action="<?= base_url() ?>sap/portal/users/remove/<?= $user['id'] ?>"
                                onsubmit="return confirm('Are you sure you want to remove <?= htmlspecialchars($user['name']) ?>?')">
                                <button class="btn btn-red"> ✘ Remove</button>
                            </form>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</body>
</html>
