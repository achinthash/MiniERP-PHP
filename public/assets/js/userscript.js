$(document).ready(function () {
    let currentPage = 1;

    function fetchUsers(search = '', page = 1) {
        $.post("./../controllers/userController.php", {
            action: 'paginate',
            search: search,
            page: page
        }, function (res) {
            const tbody = $("#userTableBody");
            tbody.empty();

            res.data.forEach(user => {
                const row = `
                   <tr onclick="userProfilebtn(${user.id})">
                        <td>${user.id}</td>
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td>${user.userrole}</td>
                        <td>${user.created_at}</td>
                    </tr>
                `;
                tbody.append(row);
            });

            // Pagination
            const totalPages = Math.ceil(res.total / res.perPage);
            let pagHtml = '';
            for (let i = 1; i <= totalPages; i++) {
                
                pagHtml += `<li class="page-item " > 
                    <button class=" page-link ${currentPage === i ? 'active': ''}"  data-page="${i}">  ${i} </button>
                </li>`; 
            }
            $('#pagination').html(pagHtml);

            $('.page-link').click(function () {
                currentPage = $(this).data('page');
                fetchUsers($('#search').val(), currentPage);
            });
        }, 'json');
    }


    // new user
    $('#userForm').on('submit', function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('action', 'create');
    
        $.ajax({
            url: "./../controllers/userController.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {

                if (response.success) {
                    $('#success_message')
                        .html('<div class="alert alert-success">' + response.success + '</div>')
                        .fadeIn();
                    $('#error_message').html('');
                    $('#userForm')[0].reset();
                   
                    fetchUsers($('#search').val(), currentPage); 

                } else if (response.errors) {
                    let errorList = Object.values(response.errors).map(e => `<li>${e}</li>`).join('');
                    $('#error_message')
                        .html(`<div class="alert alert-danger"><ul>${errorList}</ul></div>`)
                        .fadeIn();
                    $('#success_message').html('');
                }
            },
            error: function () {
                $('#error_message').html('<div class="alert alert-danger">Failed to create user. </div>').fadeIn();
                $('#success_message').html('');
            }
        });
    });
    
  
    // Initial fetch
    fetchUsers();

    // Search
    $('#search').on('input', function () {
        currentPage = 1;
        fetchUsers($(this).val(), currentPage);
    });



    $('#update-information').on('submit', function (e) {
        e.preventDefault();
      
        const form = this;
        const formData = new FormData(form);  
        formData.append('action', 'update');
    
        $.ajax({
          url: './../controllers/userController.php',
          method: 'POST',
          data: formData,
          dataType: 'json',
          processData: false, 
          contentType: false, 
    
          success: function(response) {
    
            if (response.success) {
       
              $('#success_message_update').html('<div class="alert alert-success">' + response.success + '</div>').fadeIn();
              $('#error_message_update').html('');
            fetchUsers($('#search').val(), currentPage);
           
    
            } else if (response.errors) {
                let errorList = Object.values(response.errors).map(e => `<li>${e}</li>`).join('');
                $('#error_message_update')
                    .html(`<div class="alert alert-danger"><ul>${errorList}</ul></div>`)
                    .fadeIn();
                $('#success_message_update').html('');
            }
          },
          error: function() {
            $('#error_message_update').html('<div class="alert alert-danger">Failed to update user. </div>').fadeIn();
            $('#success_message_update').html('');
          }
        });
    });
    
    
    $("#update-password").on('submit',function(e){
        e.preventDefault();
    
        const form = this;
        const formData = new FormData(form);
        formData.append('action','updatePassword');
    
        $.ajax({
            url: './../controllers/userController.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,  
            contentType: false,  
    
            success: function (response) {
            if (response.success) {
       
              $('#success_message_update').html('<div class="alert alert-success">' + response.message + '</div>').fadeIn();
              $('#error_message_update').html('');
              form.reset();
    
            } else  {
                $('#error_message_update').html('<div class="alert alert-danger">' + response.message + '</div>').fadeIn();
                $('#success_message_update').html('');
            }
    
            }, error:function(){
                $('#error_message_update').html('<div class="alert alert-danger"> Failed to update password. </div>').fadeIn();
                $('#success_message_update').html('');
            }
        });
    });
    
    
    
    
    $('#delete-account').on('submit',function (e) {
    
        e.preventDefault();
    
        const form = this;
        const formData = new FormData(form);
        formData.append('action', 'delete');
    
        $.ajax({
            url:'./../controllers/userController.php',
            method : 'POST',
            data: formData,
            dataType: 'json',
            processData: false,  
            contentType: false,
    
            success:function(response){
    
                if (response.success) {
                    $('#success_message_update').html('<div class="alert alert-success">' + response.message + '</div>').fadeIn();
                    $('#error_message_update').html('');

                    fetchUsers($('#search').val(), currentPage);
                    // Optionally redirect or reload
                    // location.href = 'login.php'; 
            
                    $('#delete-account')[0].reset();
                } else {
                    $('#error_message_update').html('<div class="alert alert-danger">' + response.message + '</div>').fadeIn();
                    $('#success_message_update').html('');
                }
    
            }, error:function(){
    
                $('#error_message_update').html('<div class="alert alert-danger"> Failed to  delete user. </div>').fadeIn();
                $('#success_message_update').html('');
            }
    
        });
    
    });
    
});






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

