<div class="card shadow-lg border-0 rounded-4">
  <div class="card-body p-4 p-lg-5">

    <!-- response alerts messages   -->
    <div id="Edit_success_message"></div>
    <div id="Edit_error_message"></div>

    <form id="productEditForm" enctype="multipart/form-data">
      <input type="hidden" name="id" id="product_id">
      <input type="hidden" name="existing_image" id="existing_image">

      <!-- Row 1: Name & SKU -->
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="name" class="form-label">Product Name</label>
          <input type="text" class="form-control" id="product_name" name="name" required>
        </div>
        <div class="col-md-6">
          <label for="sku" class="form-label">SKU</label>
          <input type="text" class="form-control" id="product_sku" name="sku">
        </div>
      </div>

      <!-- Row 2: Unit Price & Quantity In Stock -->
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="unit_price" class="form-label">Unit Price</label>
          <input type="number" step="0.01" class="form-control" id="product_unit_price" name="unit_price">
        </div>
        <div class="col-md-6">
          <label for="quantity_in_stock" class="form-label">Quantity in Stock</label>
          <input type="number" class="form-control" id="product_quantity_in_stock" name="quantity_in_stock">
        </div>
      </div>

      <!-- Row 3: Category & Image Upload -->
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="category_id" class="form-label">Category</label>
          <input type="number" class="form-control" id="product_category_id" name="category_id">
        </div>
        <div class="col-md-6">
          <label for="image" class="form-label">Change Image</label>
          <input class="form-control" type="file" id="product_image" name="image" accept="image/*">
        </div>
      </div>

      <!-- Row 4: Description (Full Width) -->
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="product_description" name="description" rows="3"></textarea>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update Product</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>
