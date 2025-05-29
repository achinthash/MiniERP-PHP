<div class="card shadow-lg border-0 rounded-4">
  <div class="card-body p-4 p-lg-5">

    <!-- Response messages -->
    <div id="edit_success_message"></div>
    <div id="edit_error_message"></div>

    <form method="POST" enctype="multipart/form-data" id="edit_customerForm">
    <input type="hidden" name="id" id="cust_id">
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Customer Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" id="cust_name" placeholder="Customer name" required value="<?= htmlspecialchars($name ?? '') ?>">
        </div>


        <div class="col-md-4">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" id="cust_emailaddress"  placeholder="Customer email" value="<?= htmlspecialchars($email ?? '') ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" class="form-control" id="cust_phone"  placeholder="Customer phone" value="<?= htmlspecialchars($phone ?? '') ?>">
        </div>

      </div>

      <div class="row mb-4">
        <div class="col-md-12">
          <label class="form-label">Address</label>
          <textarea name="address" id="cust_address" class="form-control" placeholder="Customer address" rows="3"><?= htmlspecialchars($address ?? '') ?></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success px-4">Submit</button>
        <button type="reset" class="btn btn-secondary px-4">Reset</button>
      </div>

    </form>
  </div>
</div>
