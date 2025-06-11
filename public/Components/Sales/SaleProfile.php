<div class=" p-4 shadow border rounded bg-white" > 

    <div class=" " id="salesInvoice">

        <h3 class="mb-3 text-end text-uppercase fw-bold text-primary">Invoice</h3>


        <div class="row mb-4">
            <div class="col-md-6">
              <h6 class="mb-3">ABC Company Pvt</h6>
              <ul class="list-unstyled">
                <li> <span id="">achinthas2000@gmail.com</span></li>
                <li><span id="">c/86 mahawatta ussapitiya</span></li>
                <li> <span id="">0704202695</span></li>
              </ul>
            </div>
          
            <div class="col-md-6 text-md-end">
              <ul class="list-unstyled">
                <li><strong>Invoice No:</strong> <span id="sale_id">#SALE-1</span></li>
                <li><strong>Sale Date:</strong> <span id="sale_date">2025-06-20</span></li>
                <li><strong>Created By:</strong> <span id="created_by">78</span></li>
                <li><strong>Payment Status:</strong> <span id="payment_status">Pending</span></li>
                <li><strong>Payment Method:</strong> <span id="payment_method">ex cash</span></li>
              </ul>
            </div>
          </div>
          
        <div class="">

            <div class="row mb-4">
                <div class="col-md-6 text-md-start">
                    <ul class="list-unstyled">
                        <h6 class="mb-3"><strong>Bill To:</strong></h6>

                    <li> <span id="customer_name">#SALE-1</span></li>
                    <li> <span id="customer_email">2025-06-20</span></li>
                    <li> <span id="customer_phone">78</span></li>
                    <li> <span id="customer_address">Pending</span></li>
                    </ul>
                </div>
            </div>

            <h5 class="mb-3">Sale Items</h5>

            <div class="my-custom-table-wrapper mb-5">
                <table class="my-custom-table align-middle">
                <thead class="table-light">
                    <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Unit Price (LKR)</th>
                    <th class="text-end">Total (LKR)</th>
                    </tr>
                </thead>
                <tbody id="sales_items">
                    <!-- JS will insert rows here -->
                </tbody>
                </table>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-6">
                    <table class="my-custom-table ">
                        <tr>
                            <th>Total Amount:</th>
                            <td class="text-end" id="total_amount"></td>
                        </tr>
                        <tr>
                            <th>Discount:</th>
                            <td class="text-end" id="discount"></td>
                        </tr>
                        <tr style="background-color: rgb(246, 254, 254);">
                            <th>Grand Total:</th>
                            <td class="text-end fw-bold" id="grand_total"></td>
                        </tr>
                    
                    </table>
                </div>
            </div>
        </div>

       
        <div class="row bottom-0 mt-5">
            <div class="col-md-12">
            <p><strong>Remarks</strong></p>
            <small id="remarks"></small>
            </div>
           
        </div>

    </div>

    <hr>

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
  
    function downloadPDF() {
      var elementHTML = document.querySelector('#salesInvoice');
  
      docPDF.html(elementHTML, {
        callback: function (doc) {
          const pageHeight = doc.internal.pageSize.height;
          doc.setFontSize(10);
          doc.setTextColor(100); 

          doc.text("This is a system-generated invoice. For any queries, contact achinthas2000@gmail.com", 15, pageHeight - 10);
          doc.save('invoice.pdf');
        },
        x: 15,
        y: 15,
        width: 170,
        windowWidth: 650
      });
    }
  </script>
  