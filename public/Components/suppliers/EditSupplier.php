<div class="card shadow-lg border-0 rounded-4">
  <div class="card-body p-4 p-lg-5">

    <!-- Response messages -->
    <div id="edit_success_message"></div>
    <div id="edit_error_message"></div>

    <form method="POST" enctype="multipart/form-data" id="supplierEditForm">
    <input type="hidden" name="id" id="supl_id">
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
          <input type="text" name="name" id="supl_name" class="form-control" placeholder="Supplier name" required value="<?= htmlspecialchars($name ?? '') ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" id="supl_phone" class="form-control" placeholder="Supplier phone" value="<?= htmlspecialchars($phone ?? '') ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" id="supl_emailaddress" class="form-control" placeholder="Supplier email" value="<?= htmlspecialchars($email ?? '') ?>">
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-12">
          <label class="form-label">Address</label>
          <textarea name="address" id="supl_address" class="form-control" placeholder="Supplier address" rows="3"><?= htmlspecialchars($address ?? '') ?></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success px-4">Submit</button>
        <button type="reset" class="btn btn-secondary px-4">Reset</button>
      </div>

    </form>
  </div>
</div>
