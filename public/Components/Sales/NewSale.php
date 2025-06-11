<?php
$create_by = $_SESSION['user_id'];
?>
 
 
 <div class="card-body p-4">
      <!-- Response Messages -->
      <div id="success_message"></div>
    <div id="error_message"></div>


    <form id="salesForm">
      <!-- Customer & Date -->

      <input type="hidden" name="created_by" value="<?= htmlspecialchars($create_by) ?>">

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Customer <span class="text-danger">*</span></label>
          <select name="customer_id" id="customersDropdown" class="form-control" required>
            <option value="">Select Customer</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Sale Date <span class="text-danger">*</span></label>
          <input type="date" name="order_date" class="form-control" required>
        </div>
      </div>

      <!-- Items Table -->
      <div class="mb-4">
        <label class="form-label">Sale Items <span class="text-danger">*</span></label>
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Product</th>
              <th>Qty</th>
              <th>Unit Price</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="salesItemsBody">
            <tr>
              <td>
                <select name="items[0][product_id]"  id="productsDropdown" class="form-control" required>
                  <option value="">Select Product</option>
                </select>
              </td>
              <td><input type="number" name="items[0][quantity]" class="form-control qty" min="1" required></td>
              <td><input type="number" step="0.01" name="items[0][unit_price]" class="form-control price" required></td>
              <td><input type="text" name="items[0][total]" class="form-control total" ></td>
              <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
            </tr>
          </tbody>
        </table>
        <button type="button" class="btn btn-outline-primary btn-sm" id="addRow">+ Add Item</button>
      </div>

      <!-- Totals -->
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Discount</label>
          <input type="number" name="discount" step="0.01" class="form-control" id="discountInput" value="0.00">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold">Grand Total</label>
          <input type="text" name="grand_total" class="form-control" id="grandTotal" >
        </div>
      </div>

      <!-- Payment Details -->
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Payment Status</label>
          <select name="payment_status" class="form-control">
            <option value="pending">Pending</option>
            <option value="partial">Partial</option>
            <option value="paid">Paid</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Payment Method</label>
          <select name="payment_method" class="form-control">
            <option value="Cash">Cash</option>
            <option value="Card">Card</option>
          </select>

        </div>
      </div>

      <!-- Remarks -->
      <div class="mb-3">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="3"></textarea>
      </div>

      <!-- Buttons -->
      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success px-4">Submit</button>
        <button type="reset" class="btn btn-secondary px-4">Reset</button>
      </div>
    </form>
  </div>



  <script>
    (() => {
      let itemIndex = 1;
      const SaleItemsBody = document.getElementById('salesItemsBody');
      const grandTotalInput = document.getElementById('grandTotal');
    
      document.getElementById('addRow').addEventListener('click', () => {
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
          <td>
            <select name="items[${itemIndex}][product_id]" class="form-control" required>
              ${document.getElementById('productsDropdown').innerHTML}
            </select>
          </td>
          <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control qty" min="1" required></td>
          <td><input type="number" step="0.01" name="items[${itemIndex}][unit_price]" class="form-control price" required></td>
          <td><input type="text" name="items[${itemIndex}][total]" class="form-control total" readonly></td>
          <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
        `;

        SaleItemsBody.appendChild(newRow);
        itemIndex++;
    });
    
    
      SaleItemsBody.addEventListener('click', e => {
        if (e.target.classList.contains('remove-row')) {
          e.target.closest('tr').remove();
          calculateGrandTotal();
        }
      });
    
      SaleItemsBody.addEventListener('input', e => {
        if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
          const row = e.target.closest('tr');
          const qty = parseFloat(row.querySelector('.qty').value) || 0;
          const price = parseFloat(row.querySelector('.price').value) || 0;
          const total = qty * price;
          row.querySelector('.total').value = total.toFixed(2);
          calculateGrandTotal();
        }
      });

      document.getElementById('discountInput').addEventListener('input', calculateGrandTotal);
    
      function calculateGrandTotal() {
        let grandTotal = 0;

        document.querySelectorAll('.total').forEach(input => {
          grandTotal += parseFloat(input.value) || 0;
        });

        const discount = parseFloat(document.getElementById('discountInput').value) || 0;
        const finalTotal = grandTotal - discount;

        grandTotalInput.value = (finalTotal >= 0 ? finalTotal : 0).toFixed(2);
      }



    

      
    })();
    </script>