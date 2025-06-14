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

                const role = response.user_role?.trim().toLowerCase(); 

                if (role === 'admin' || role === 'manager') {
                    window.location.href = "dashboard";
                } else if (role === 'staff') {
                    window.location.href = "sales";
                } else {
                    window.location.href = "unauthorized";
                }
                
               
 
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

