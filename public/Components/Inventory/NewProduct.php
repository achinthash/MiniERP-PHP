<div class="card shadow-lg border-0 rounded-4">
  <div class="card-body p-4 p-lg-5">

    <!-- Response messages -->
    <div id="success_message"></div>
    <div id="error_message"></div>

    <form method="POST" id="productForm" enctype="multipart/form-data">

      <div class="row mb-3">
        <!-- Category ID -->
        <div class="col-md-6">
          <label class="form-label">Category ID <span class="text-danger">*</span></label>
          <input type="number" name="category_id" class="form-control" placeholder="Category ID" value="<?= htmlspecialchars($category_id ?? '') ?>" required>
        </div>

        <!-- SKU -->
        <div class="col-md-6">
          <label class="form-label">SKU <span class="text-danger">*</span></label>
          <input type="text" name="sku" class="form-control" placeholder="SKU" value="<?= htmlspecialchars($sku ?? '') ?>" required>
        </div>
      </div>

      <div class="row mb-3">
        <!-- Name -->
        <div class="col-md-6">
          <label class="form-label">Product Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" placeholder="Product Name" value="<?= htmlspecialchars($name ?? '') ?>" required>
        </div>

        <!-- Unit Price -->
        <div class="col-md-6">
          <label class="form-label">Unit Price <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="unit_price" class="form-control" placeholder="Unit Price" value="<?= htmlspecialchars($unit_price ?? '') ?>" required>
        </div>
      </div>

      <div class="row mb-3">
        <!-- Quantity in Stock -->
        <div class="col-md-6">
          <label class="form-label">Quantity in Stock <span class="text-danger">*</span></label>
          <input type="number" name="quantity_in_stock" class="form-control" placeholder="Quantity" value="<?= htmlspecialchars($quantity_in_stock ?? '') ?>" required>
        </div>

        <!-- Image Upload -->
        <div class="col-md-6">
          <label class="form-label">Product Image</label>
          <input type="file" name="image" class="form-control">
        </div>
      </div>

      <!-- Description -->
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" placeholder="Description"><?= htmlspecialchars($description ?? '') ?></textarea>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success px-4">Submit</button>
        <button type="reset" class="btn btn-secondary px-4">Reset</button>
      </div>

    </form>
  </div>
</div>
