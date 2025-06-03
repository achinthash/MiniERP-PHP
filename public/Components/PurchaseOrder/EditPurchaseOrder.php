
<?php


  $user_id =  $_SESSION['user_id'];
  
?>





<form id="purchaseOrderEditForm" class="p-3">

  <div id="edit_po_success_message" class="me-auto"></div>
  <div id="edit_po_error_message" class="me-auto"></div>

  <div id="delete_po_item_success_message" class="me-auto"></div>
  <div id="delete_po_item_error_message" class="me-auto"></div>
  

  <div class="row">
    <input type="hidden" name="id" id="edit_po_id">
    <div class="mb-3 col-md-6">
      <label for="edit_po_supplier_id" class="form-label">Supplier</label>
      <!-- <select class="form-control" name="supplier_id" id="edit_po_supplier_id" required>
      </select> -->
      <input type="text" class="form-control" name="supplier_id" id="edit_po_supplier_id" required>
    </div>

    <div class="mb-3 col-md-6">
      <label for="edit_po_order_date" class="form-label">Order Date</label>
      <input type="date" class="form-control" name="order_date" id="edit_po_order_date" required>
    </div>

    <div class="mb-3 col-md-6">
      <label for="edit_po_order_status" class="form-label">Status</label>
      <select class="form-control" name="status" id="edit_po_order_status" required>
        <option value="">-- Select Status --</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
        <option value="pending">Pending</option>
      </select>
    </div>


    <div class="mb-3 col-md-6">
      <br/>
      


    </div>


    <div id="approval_fields" style="display: none;" class="row">
      <div class="mb-3 col-md-6">
        <label for="edit_po_order_approved_by" class="form-label">Approved By</label>
        <input type="text" class="form-control" name="approved_by" id="edit_po_order_approved_by" required>
      </div>
      <div class="mb-3 col-md-6">
        <label for="edit_po_order_approved_at" class="form-label">Approved Date</label>
        <input type="date" class="form-control" name="approved_at" id="edit_po_order_approved_at" required>
      </div>
    </div>

    <div id="not_approved_text" >
    <p >This purchase order is not approved yet.</p>
    <button type="button" class="btn btn-secondary mt-2" onclick="approvePo(<?php echo $user_id ?>)">Approve Purchase Order</button>

    </div>

   
    

   
  </div>

  <hr>

  <h5>Items</h5>

  <div >
    <!-- JS will dynamically add items here -->

    <table class="table  table-hover">
      <thead>
        <tr>
          <th> Product Name </th>
          <th> Quantity  </th>
          <th>  Unit Price</th>
          <th> Line Total</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody id="edit_po_items_container">
       
        
      </tbody>
    </table>


  </div>

  <!-- <button type="button" class="btn btn-secondary mt-2" onclick="addEditPOItemRow()">Add Item</button> -->
       

  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Update Order</button>
  </div>

</form>


