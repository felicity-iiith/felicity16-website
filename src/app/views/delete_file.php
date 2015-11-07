<div class="padded" id="filedelete">
    <form action="" method="post">
        <h2>Delete file</h2>
        <input type="hidden" name="file_id" value="<?= $id ?>"/>
        <div id="delete_button">
            <a class="btn btn-red" onclick="confirm_delete()">Delete File</a>
        </div>
        <div id="delete_confirm">
            <label for="delete_file">Are you sure you want to delete this file?</label>
            <input type="submit" class="btn-red inline" name="delete_file" value="Yes, please delete this file"/>
            <a class="btn btn-green" onclick="cancel_delete()">Cancel</a>
        </div>
    </form>
</div>
