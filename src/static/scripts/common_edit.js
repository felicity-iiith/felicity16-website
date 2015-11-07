function confirm_delete() {
    var delete_button = document.getElementById('delete_button');
    var delete_confirm = document.getElementById('delete_confirm');

    delete_button.style.display = 'none';
    delete_confirm.style.display = 'block';
}

function cancel_delete() {
    var delete_button = document.getElementById('delete_button');
    var delete_confirm = document.getElementById('delete_confirm');

    delete_button.style.display = 'block';
    delete_confirm.style.display = 'none';
}
