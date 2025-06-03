<?php
$create_by = $_SESSION['user_id'];
?>

<div class="card shadow-lg border-0 rounded-4">
  <div class="card-body p-4 p-lg-5">

    <!-- Response Messages -->
    <div id="po_success_message"></div>
    <div id="po_error_message"></div>

    <form method="POST" enctype="multipart/form-data" id="purchaseOrderForm">
      <input type="hidden" name="created_by" value="<?= htmlspecialchars($create_by) ?>">

      <!-- PO Basic Details -->
      <div class="row mb-3">
        

      <div class="col-md-6">
          <label class="form-label">Supplier <span class="text-danger">*</span></label>
          <select name="supplier_id" id="supplierDropdown" class="form-control" required>
            <option value="">Select Supplier</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">PO Date <span class="text-danger">*</span></label>
          <input type="date" name="order_date" class="form-control" required value="<?= htmlspecialchars($po_date ?? '') ?>">
        </div>
      </div>

    
     


      <!-- PO Items Table -->
      <div class="mb-4">
        <label class="form-label">PO Items <span class="text-danger">*</span></label>
        <table class="table table-bordered" id="poItemsTable">
          <thead>
            <tr>
              <th colspan="2">Item Name</th>

              <th>Qty</th>
              <th>Unit Price</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="poItemsBody">
            <tr>
              <td > 
              
                <select name="items[0][product_id]"  id="productsDropdown" class="form-control" required>
                  <option value="">Select Product</option>
                </select>
              
              </td>
              <td></td>


              <td><input type="number" name="items[0][quantity]" class="form-control qty" required></td>
              <td><input type="number" step="0.01" name="items[0][unit_price]" class="form-control price" required></td>
              <td><input type="text" name="items[0][total]" class="form-control total" readonly></td>
              <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
            </tr>
          </tbody>
        </table>
        <button type="button" class="btn btn-outline-primary btn-sm" id="addRow">+ Add Item</button>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Grand Total</label>
        <input type="text" name="grand_total" class="form-control" id="grandTotal" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="3"><?= htmlspecialchars($remarks ?? '') ?></textarea>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success px-4">Submit</button>
        <button type="reset" class="btn btn-secondary px-4">Reset</button>
      </div>
    </form>
  </div>
</div>

<script>
(() => {
  let itemIndex = 1;
  const poItemsBody = document.getElementById('poItemsBody');
  const grandTotalInput = document.getElementById('grandTotal');

  document.getElementById('addRow').addEventListener('click', () => {
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>
            <select name="items[${itemIndex}][product_id]" class="form-control" required>
                ${globalProductOptions}
            </select>
        </td>
        <td></td>
        <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control qty" required></td>
        <td><input type="number" step="0.01" name="items[${itemIndex}][unit_price]" class="form-control price" required></td>
        <td><input type="text" name="items[${itemIndex}][total]" class="form-control total" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
    `;
    poItemsBody.appendChild(newRow);
    itemIndex++;
});


  poItemsBody.addEventListener('click', e => {
    if (e.target.classList.contains('remove-row')) {
      e.target.closest('tr').remove();
      calculateGrandTotal();
    }
  });

  poItemsBody.addEventListener('input', e => {
    if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
      const row = e.target.closest('tr');
      const qty = parseFloat(row.querySelector('.qty').value) || 0;
      const price = parseFloat(row.querySelector('.price').value) || 0;
      const total = qty * price;
      row.querySelector('.total').value = total.toFixed(2);
      calculateGrandTotal();
    }
  });

  function calculateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.total').forEach(input => {
      grandTotal += parseFloat(input.value) || 0;
    });
    grandTotalInput.value = grandTotal.toFixed(2);
  }
})();
</script>