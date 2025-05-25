
<div class="card shadow-lg border-0 rounded-4">
  <div class="card-body p-4 p-lg-5">

    <!-- response alerts messages   -->
    <div id="success_message" ></div>
    <div id="error_message" ></div>
  

    <form method="POST"  enctype="multipart/form-data" id="userForm"> 

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">User Name <span class="text-danger">*</span></label>
          <input type="text" name="username" class="form-control " placeholder="User name" value="<?= htmlspecialchars($username ?? '') ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Email Address <span class="text-danger">*</span></label>
          <input type="email" name="email" class="form-control " placeholder="User email"  required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Password <span class="text-danger">*</span></label>
          <input type="password" name="password" class="form-control "  placeholder="Password" value="<?= htmlspecialchars($password ?? '') ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
          <input type="password" name="confirmPassword" class="form-control " placeholder="Confirm Password" value="<?= htmlspecialchars($confirmPassword ?? '') ?>">
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-6 form-group">
          <label class="form-label">User Role <span class="text-danger">*</span></label>
          <select class="form-select " name="userrole" value="<?= htmlspecialchars($userrole ?? '') ?>">
          <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
            <option value="cashier">Cashier</option>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label for="profilePicture" class="form-label">Profile Picture</label>
          <input type="file" class="form-control " id="profilePicture" name="profile_picture" value="<?= htmlspecialchars($profilePicture ?? '')?>" >
        </div>
        
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit"  class="btn btn-success px-4">Submit</button>
        <button type="reset" class="btn btn-secondary px-4">Reset</button>
      </div>
      

    </form>
  </div>
</div>

