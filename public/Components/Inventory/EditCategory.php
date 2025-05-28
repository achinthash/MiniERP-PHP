
<div class="card shadow-lg border-0 rounded-4">
  <div class="card-body p-4 p-lg-5">

    <!-- response alerts messages   -->
    <div id="edit_success_message" ></div>
    <div id="edit_error_message" ></div>
  

    <form method="POST"   id="categoryEditForm"> 

    <input type="hidden" name="id" id="id">

      <div class="row mb-3">
        <div class="col-md-12">
          <label class="form-label">Name <span class="text-danger">*</span></label>
          <input type="text" name="name" id="name" class="form-control " placeholder="Name" value="<?= htmlspecialchars($name ?? '') ?>" required>
        </div>

        <div class="col-md-12 mt-1">
          <label class="form-label">Description</label>
          <textarea type="text" name="description" id="description" class="form-control " placeholder="description"  value="<?= htmlspecialchars($description ?? '') ?>"> </textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit"  class="btn btn-success px-4">Submit</button>
        <button type="reset" class="btn btn-secondary px-4">Reset</button>
      </div>
      
    </form>
  </div>
</div>

