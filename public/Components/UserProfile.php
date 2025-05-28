<?php

  $user_role = $_SESSION['user_role'];

?>


<section style="background-color: #eee;">
  <div class=" p-4">

    <div id="success_message_update" ></div>
    <div id="error_message_update" ></div>


    <div class="row">
      <!-- Left Profile Image & Basic Info -->

      <div class="col-lg-4 ">
        <div class="card">
          <div class="card-body d-flex align-items-center justify-content-center" style="height: 350px;">
            <div class="text-center">
              <img id="profile_picture_preview" src="" alt="avatar" class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px;">
                <h5 class="p-2" id="username_display"></h5>
                <p  id="userrole_display"> userrole </p>
            </div>
          </div>
        </div>
      </div>


      
      <!-- Right Details --> 
      <div class="col-lg-8 right-panel-scroll"   >
   
        <div class="card mb-4">
          <h5 class=" p-4   "> Profile Information </h5>

          <form class="card-body" method="POST" enctype="multipart/form-data" id="update-information">

            <input type="hidden" name="userId" id="userId">

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">User Name</label>
              <div class="col-sm-9">
                <input type="text" id="username" name="username" class="form-control" required>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">Email</label>
              <div class="col-sm-9">
                <input type="email" id="email" name="email" class="form-control" required>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">Profile picture</label>
              <div class="col-sm-9">
                <!-- <input type="file" name="profile_picture" id="profile_picture_input" accept="image/*" class="form-control"> -->
                <input type="file" name="profile_picture"  id="profile_picture">
                <input type="hidden" name="existing_profile_picture" id="existing_profile_picture">


              </div>
            </div>

            <!-- <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">Phone</label>
              <div class="col-sm-9">
                <input type="text" id="phone" name="phone" class="form-control">
              </div>
            </div> -->


            <?php if ($user_role === 'admin'): ?>
              <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Role</label>
                <div class="col-sm-9">
                  <input type="text" id="userrole" name="userrole" class="form-control" value="<?= htmlspecialchars($user_role) ?>" readonly>
                </div>
              </div>
            <?php endif; ?>


            <!-- <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">Address</label>
              <div class="col-sm-9">
                <input type="text" id="addressDetails" name="address_details" class="form-control">
              </div>
            </div> -->

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">Joined At</label>
              <div class="col-sm-9">
                <input type="text" id="created_at" name="joined_at" class="form-control" readonly>
              </div>
            </div>

            <button type="submit"  class="btn btn-primary">Update</button>
  
          </form>


        </div>

        <div class="card mb-4">
          <h5 class=" p-4   "> Update Password </h5>

          <form class="card-body" id="update-password">

            <input type="hidden" name="password_user_id" id="password_user_id">

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">Old password</label>
              <div class="col-sm-9">
                <input type="password" id="old_password" name="old_password" class="form-control" required>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">New password</label>
              <div class="col-sm-9">
                <input type="password" id="new_password" name="new_password" class="form-control" required>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">Confirm password</label>
              <div class="col-sm-9">
                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
              </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Update</button>
            
          </form>
        </div>


        <div class="card mb-4">
          <h5 class=" p-4 "> Delete Account </h5>

          <div class="card-body">

            <button type="button"  id="delete-account-btn" class="btn btn-danger">Delete Account</button>

            

            <div class="mb-3 row "  style="display:none" id="delete-account-section">
              <h5> Are you sure you want to delete your account? </h5>
              <p>Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
              
              <form class=" card-body form-group" id="delete-account">
                <input type="hidden" name="delete_user_id" id="delete_user_id">  
                <input type="password" id="password" name="password" class="form-control mb-4" placeholder="Password">
                
                <button type="submit"  id="" class="btn btn-danger">Delete Account</button>
              </form>
            </div>


            
          </div>
        </div>


      </div>
    </div>
  </div>
</section>

<script>
$(document).ready(function() {
  $('#delete-account-btn').on('click', function() {

    $(this).hide();
    $('#delete-account-section').show();
  });
});


</script>


<!-- <script src="./assests/js/userscript.js"></script>
 -->


 