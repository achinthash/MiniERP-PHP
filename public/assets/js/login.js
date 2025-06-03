$('#loginForm').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('action', 'login');

    $.ajax({
        url: "./../controllers/userController.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {

            if (response.success) {
                window.location.href="users";
 
            } else if (response.error) {
             
                $('#error_message').html('<div class="alert alert-danger">' + response.error + '</div>').fadeIn();
                $('#success_message').html('');
            }
        },
        error: function () {
            $('#error_message').html('<div class="alert alert-danger"> Failed to login </div>').fadeIn();
            $('#success_message').html('');
        }
    });
});

