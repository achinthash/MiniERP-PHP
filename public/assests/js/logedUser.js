
window.userProfilebtn = function(id) {

    $.post("./../controllers/userController.php", {
        action: 'get',
        id: id
    }, function (response) {

        if (response.success) {
    
            $('#userId').val(response.user.id);
            $('#username').val(response.user.username);
            $('#email').val(response.user.email);
            $('#created_at').val(response.user.created_at);
            $('#userrole').val(response.user.userrole);
            $('#profile_picture_preview').attr('src', './uploads/profile_pictures/' + response.user.profile_picture);
            $('#existing_profile_picture').val(response.user.profile_picture);
            $('#username_display').text(response.user.username);
            $('#userrole_display').text(response.user.userrole);
       
            $('#password_user_id').val(response.user.id);
            $('#delete_user_id').val(response.user.id);

            const modal = new bootstrap.Modal(document.getElementById('userProfile'));
            modal.show();

        } else {
            alert('User not found');
        }
       
    }, 'json');
}