<?php if ($user_can['manage_user']): ?>
<?php
    $role_sel = function($role) use ($default_role){
        if ($role == $default_role) {
            return "selected";
        }
        return "";
    }
?>
<div class="padded" id="useredit">
    <h2>Permission control</h2>
    <form action="" method="post" class="padded">
        <h4>Default role</h4>
        <input type="hidden" name="file_id" value="<?= $id ?>"/>
        <select class="text-input" name="default_role" required>
            <option value="" disabled>Default user role ...</option>
            <option value="none" <?= $role_sel("none") ?>>None</option>
            <option value="reader" <?= $role_sel("reader") ?>>Reader</option>
            <option value="author" <?= $role_sel("author") ?>>Author</option>
            <option value="admin" <?= $role_sel("admin") ?>>Admin</option>
        </select>
        <input type="submit" class="btn-green inline" name="update_default_role" value="Update"/>
    </form>
    <form action="" method="post" class="padded">
        <h4>User roles</h4>
        <input type="hidden" name="file_id" value="<?= $id ?>"/>
        <input type="text" name="username" placeholder="Username to be added" required />
        <select class="text-input" name="role" required>
            <option value="" disabled>User role ...</option>
            <option value="none">None</option>
            <option value="reader" selected>Reader</option>
            <option value="author">Author</option>
            <option value="admin">Admin</option>
        </select>
        <input type="submit" class="btn-green inline" name="add_user" value="Add user"/>
    </form>
    <ul class="admin_list">
        <?php
            foreach ($admins as $admin):
                $inherited = ($admin["file_id"] != $id);
        ?>
                <li>
                    <?= $admin["user"] ?>
                    <span class="grey">(<?= $admin["role"] ?>)</span>
                    <?php
                        if ($inherited):
                    ?>
                        <span class="grey">(Inherited)</span>
                    <?php
                        elseif ($user == $admin["user"]):
                    ?>
                        <span class="grey">(It's you)</span>
                    <?php
                        else:
                    ?>
                        <form action="" method="post" style="display: inline-block;">
                            <input type="hidden" name="file_id" value="<?= $id ?>"/>
                            <input type="hidden" name="username" value="<?= $admin["user"] ?>"/>
                            <button type="submit" name="revoke_user" value="Revoke permissions" class="btn btn-small btn-red">
                                <i class="fa fa-trash-o"></i>
                                Remove role
                            </button>
                        </form>
                    <?php
                        endif;
                    ?>
                </li>
        <?php
            endforeach;
        ?>
    </ul>
</div>
<?php endif; ?>
