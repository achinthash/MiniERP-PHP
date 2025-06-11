
<?php


  $user_id =  $_SESSION['user_id'];
  
?>


<form id="saleEditForm" class="p-3">

      
  <div id="edit_sale_success_message" class="me-auto"></div>
  <div id="edit_sale_error_message" class="me-auto"></div>

  <div id="delete_sale_item_success_message" class="me-auto"></div>
  <div id="delete_sale_item_error_message" class="me-auto"></div>



<div class="">

    <input type="hidden" id="sale_sale_id" name="id">
    <input type="hidden" name="action" value="updateSalesOrder">
 

    <div class="row mb-3">
    <div class="col-md-6">
        <label for="customer_name" class="form-label">Customer Name</label>
        <input type="text" id="sale_customer_name" class="form-control" readonly>
        <input type="hidden" id="sale_customer_id" name="customer_id">
    </div>

    <div class="col-md-3">
        <label for="sale_date" class="form-label">Order Date</label>
        <input type="date" id="sale_sale_date" name="sale_date" class="form-control" required>
    </div>

    <div class="col-md-3">
        <label for="payment_status" class="form-label">Status</label>
        <select id="sale_payment_status" name="payment_status" class="form-select" required>
        <option value="pending">Pending</option>
        <option value="paid">Paid</option>
        <option value="partial">Partial</option>
        </select>
    </div>
    </div>

    <div class="row mb-3">
    <div class="col-md-3">
        <label for="payment_method" class="form-label">Payment Method</label>
        <input type="text" id="sale_payment_method" name="payment_method" class="form-control">
    </div>

    <div class="col-md-3">
        <label for="discount" class="form-label">Discount</label>
        <input type="number" id="sale_discount" name="discount" step="0.01" class="form-control">
    </div>

    <div class="col-md-3">
        <label for="total_amount" class="form-label">Total Amount</label>
        <input type="number" id="sale_total_amount" class="form-control" readonly>
    </div>

    <div class="col-md-3">
        <label for="grand_total" class="form-label">Grand Total</label>
        <input type="number" id="sale_grand_total" class="form-control" readonly>
    </div>
    </div>

    <div class="mb-3">
    <label class="form-label">Remarks</label>
    <textarea id="sale_remarks" name="remarks" class="form-control" rows="2"></textarea>
    </div>

    <hr>

    <h5>Sales Items</h5>
    <table class="table table-bordered">
    <thead>
        <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total</th>
        <th>Action</th>
        </tr>
    </thead>
    <tbody id="edit_sales_items_container"></tbody>
    </table>

</div>

<div class="modal-footer">
    <div id="edit_po_success_message" class="text-success me-auto"></div>
    <div id="edit_po_error_message" class="text-danger me-auto"></div>

    <button type="submit" class="btn btn-primary">Update Order</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
</div>

</form>
