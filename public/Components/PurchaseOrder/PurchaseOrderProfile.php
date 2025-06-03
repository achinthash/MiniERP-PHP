
    

<div class=" p-4 shadow border rounded bg-white" > 
<div class=" p-2" id="purchaseOrderPrintArea">

<h4 class="text-primary fw-bold mb-3">Purchase Order</h4>
  <div class="d-flex justify-content-between align-items-start mb-4" >

    <div>
   
      <span id="po_supplier_address"><strong>ID:</strong> </span> <span id="po_id"></span> <br>
      <span class="col-md-4"><strong>Date:</strong> <span id="po_date">2025-05-30</span></span>
    </div>

    <div>
      <p class="mb-0"> <strong>Billed To:</strong> <br><span id="po_supplier_name"></span><br>
      <i class="fa-solid fa-location-dot"></i>: <span id="po_supplier_address11"></span><br>
        <i class="fa-solid fa-envelope"></i>: <span id="po_supplier_email"></span><br>
        <i class="fa-solid fa-phone"></i>: <span id="po_supplier_phone"></span>
      </p>
    </div>
    
  </div>

  <div class="mb-4">
    <h6 class="fw-bold text-primary">Product Details</h6>
    <p id="po_supplier" class="mb-0"></p>
  </div>



<div class="my-custom-table-wrapper mb-4">
  <table class="my-custom-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Item Name</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody id="po_items">
      <!-- JS Populates here -->
    </tbody>
  </table>
</div>


  <div class="row mb-5">
    <div class="col-md-6">
     
    </div>
    <div class="col-md-6 text-end">
      <table class="my-custom-table">
        <tbody>
         
          <tr class="">
            <td><strong>Total:</strong></td>
            <td id="total_amount"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <hr>
  <div class="row bottom-0">
    <div class="col-md-6">
      <p><strong>Terms and Conditions</strong></p>
      <small>Goods once sold will not be taken back. Payment due within 30 days.</small>
    </div>
    <div class="col-md-6 text-end">
      <p><strong>Authorized Signature</strong></p>
      <div style="height: 50px;"></div>
    </div>
  </div>
</div>


  <div class="">
    <button type="button" onclick="downloadPDF()" class="btn btn-outline-secondary">
      <i class="bi bi-printer"></i> Print
    </button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
  </div>


</div>


<script>

window.jsPDF = window.jspdf.jsPDF;
var docPDF = new jsPDF();

function downloadPDF(){
    var elementHTML = document.querySelector('#purchaseOrderPrintArea');
    

    docPDF.html(elementHTML,{
        callback: function(){
            docPDF.save('invoice.pdf')
        },
        x: 15,
        y:15,
        width: 170,
        windowWidth: 650

    });

}

  </script>