$(document).ready(function () {

    let currentPage = 1;

    // Fetch Purchase Orders
    function fetchPurchaseOrders(search = '', page = 1) {
        $.ajax({
            url: "./../controllers/purchaseOrderController.php",
            method: "POST",
            data: {
                action: 'fetchPurchaseOrders',
                search: search,
                page: page
            },
            success: function (response) {

               
                const tbody = $("#purchaseOrderTableBody");
                tbody.empty();
                let parsed = typeof response === 'string' ? JSON.parse(response) : response;

                parsed.data.forEach(order => {
                    const row = `
                        <tr onclick="viewPurchaseOrder(${order.id})">
                            <td>${order.id}</td>
                            <td>${order.supplier_name}</td>
                            <td>${order.status}</td>
                            <td>LKR: ${order.total_amount}</td>
                            <td>
                                <span onclick="event.stopPropagation(); editPurchaseOrder(${order.id})">Edit</span>
                                <span onclick="event.stopPropagation(); deletePurchaseOrder(${order.id})">Delete</span>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });

                // Pagination
                const totalPages = Math.ceil(parsed.total / parsed.perPage);
                let pagHtml = '';
                for (let i = 1; i <= totalPages; i++) {
                    pagHtml += `<li class="page-item">
                        <button class="page-link ${currentPage === i ? 'active' : ''}" data-page="${i}">${i}</button>
                    </li>`;
                }
                $('#pagination_po').html(pagHtml);

                $('.page-link').click(function () {
                    currentPage = $(this).data('page');
                    fetchPurchaseOrders(currentPage);
                });
            },
            error: function () {
                console.log('Error fetching purchase orders');
            }
        });
    }

    fetchPurchaseOrders();

  

    // Add Purchase Order
    $('#purchaseOrderForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        formData.append('action', 'newPurchaseOrder');
        

        $.ajax({
            url: './../controllers/purchaseOrderController.php', 
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {

                if (response.success) {
                    $('#po_success_message').html(`<div class="alert alert-success">${response.message}</div>`).fadeIn();
                    $('#po_error_message').html('');
                    form.reset();

                    fetchPurchaseOrders(currentPage);


                } else if (response.errors) {
                    let errorList = Object.values(response.errors).map(e => `<li>${e}</li>`).join('');
                    $('#po_error_message').html(`<div class="alert alert-danger"><ul>${errorList}</ul></div>`).fadeIn();
                    $('#po_success_message').html('');
                }
            },
            error: function () {
                $('#po_error_message').html('<div class="alert alert-danger">Failed to create Purchase Order.</div>').fadeIn();
                $('#po_success_message').html('');
            }
        });
    });

    // View Purchase Order
    window.viewPurchaseOrder = function (id) {

        
        $.post("./../controllers/purchaseOrderController.php", {
            action: 'getPurchaseOrder',
            id: id
        }, function (response) {

            if (response.success) {
                
                const order = response.order;

                $('#po_id').text(`#PO-${order.id}`); 
                $('#po_date').text(order.order_date);

                $('#po_supplier_name').text(order.supplier_name);
                $('#po_supplier_email').text(order.supplier_email);
                $('#po_supplier_address11').text(order.supplier_address);
                $('#po_supplier_phone').text(order.supplier_phone);

                $('#po_total').text(`LKR{order.total_amount}`);
        
                $('#po_created_by').text(order.created_by); 
          
                const itemsTable = $('#po_items');
                itemsTable.empty();
        
                order.items.forEach((item, index) => {
                  
                  itemsTable.append(`
                    <tr>
                      <td>${index + 1}</td>
                      <td>${item.product_name}</td>
                      <td>LKR :${item.quantity}</td>
                      <td>LKR :${item.unit_price}</td>
                      <td>LKR :${item.line_total}</td>
                      
                    </tr>
                  `);
                });
          
                 $('#total_amount').text(`LKR :${order.total_amount}`);

                const modal = new bootstrap.Modal(document.getElementById('purchaseOrderprofile'));
                modal.show();
            } else {
                alert('Purchase Order not found');
            }
        }, 'json');
    };

    // Edit Purchase Order
    window.editPurchaseOrder = function (id) {
        $.post("./../controllers/purchaseOrderController.php", {
            action: 'getPurchaseOrder',
            id: id
        }, function (response) {
            if (response.success) {

                const order = response.order;

                $('#edit_po_id').val(response.order.id);
                $('#edit_po_supplier_id').val(response.order.supplier_id);
                $('#edit_po_order_date').val(response.order.order_date);

                $('#edit_po_order_status').val(response.order.status);
                $('#edit_po_order_approved_by').val(response.order.approved_by);
                $('#edit_po_order_approved_at').val(response.order.approved_at);

                $('#edit_po_order_approved_by').val(order.approved_by);
                $('#edit_po_order_approved_at').val(order.approved_at);
                
                if (order.approved_by && order.approved_at) {
                    $('#approval_fields').show();
                    $('#not_approved_text').hide();
                } else {
                    $('#approval_fields').hide();
                    $('#not_approved_text').show();
                }
                


                const tbody = $("#edit_po_items_container");
                tbody.empty();
                let parsed = typeof response === 'string' ? JSON.parse(response.order.items) : response.order.items;

                parsed.forEach((item, index) => {
                    const row = `
                        <tr>
                            <input type="hidden" name="items[${index}][id]" value="${item.id}" />
                            <td><input type="text" class="form-control" value="${item.product_name}" readonly></td>
                            <td><input type="number" name="items[${index}][quantity]" class="form-control" value="${item.quantity}" required></td>
                            <td><input type="number" name="items[${index}][unit_price]" class="form-control" value="${item.unit_price}" step="0.01" required></td>
                            <td><input type="number" name="items[${index}][line_total]" class="form-control" value="${item.line_total}" readonly></td>
                            <td><button type="button" class="btn btn-danger" onclick="deletePurchaseOrderItems(${item.id})">Remove</button></td>
                        </tr>
                    `;
                    tbody.append(row);
                });
                
            

                const modal = new bootstrap.Modal(document.getElementById('purchaseOrderEdit'));
                modal.show();
            } else {
                alert('Purchase Order not found');
            }
        }, 'json');
    };

    $('#purchaseOrderEditForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        formData.append('action', 'updatePurchaseOrder');

        $.ajax({
            url: './../controllers/purchaseOrderController.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {


                if (response.success) {
                    $('#edit_po_success_message').html(`<div class="alert alert-success">${response.success}</div>`).fadeIn();
                    $('#edit_po_error_message').html('');

                    fetchPurchaseOrders(currentPage);

                } else if (response.errors) {
                    let errorList = Object.values(response.errors).map(e => `<li>${e}</li>`).join('');
                    $('#edit_po_error_message').html(`<div class="alert alert-danger"><ul>${errorList}</ul></div>`).fadeIn();
                    $('#edit_po_success_message').html('');
                }
            },
            error: function () {
                $('#edit_po_error_message').html('<div class="alert alert-danger">Failed to update Purchase Order.</div>').fadeIn();
                $('#edit_po_success_message').html('');
            }
        });
    });

    
     // Delete Purchase Order items 
     window.deletePurchaseOrderItems = function (id) {

 
        if (!confirm("Are you sure you want to delete this Purchase Order Item?")) return;

        $.ajax({
            url: "./../controllers/purchaseOrderController.php",
            method: "POST",
            data: {
                action: 'deletePurchaseOrderItems',
                id: id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#delete_po_item_success_message').html(`<div class="alert alert-success">${response.message}</div>`).fadeIn();
                    $('#delete_po_item_error_message').html('');
                } else {
                    $('#delete_po_item_error_message').html(`<div class="alert alert-danger">${response.message}</div>`).fadeIn();
                    $('#delete_po_item_success_message').html('');
                }
            },
            error: function () {
                $('#delete_po_item_error_message').html('<div class="alert alert-danger">Failed to delete Purchase Order.</div>').fadeIn();
                $('#delete_po_item_success_message').html('');
            }
        });
    };


    // Delete Purchase Order
    window.deletePurchaseOrder = function (id) {
        if (!confirm("Are you sure you want to delete this Purchase Order?")) return;

        $.ajax({
            url: "./../controllers/purchaseOrderController.php",
            method: "POST",
            data: {
                action: 'deletePurchaseOrder',
                id: id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#delete_po_success_message').html(`<div class="alert alert-success">${response.message}</div>`).fadeIn();
                    $('#delete_po_error_message').html('');
                   
                    fetchPurchaseOrders(currentPage);
                } else {
                    $('#delete_po_error_message').html(`<div class="alert alert-danger">${response.message}</div>`).fadeIn();
                    $('#delete_po_success_message').html('');
                }
            },
            error: function () {
                $('#delete_po_error_message').html('<div class="alert alert-danger">Failed to delete Purchase Order.</div>').fadeIn();
                $('#delete_po_success_message').html('');
            }
        });
    };



// new po related
    window.newPurchOrderClick = function(){
        const Modal = new bootstrap.Modal(document.getElementById("newpurchaseOrder"));
        Modal.show();
    
        //productsList 
    
        function productsList() {
            $.ajax({
                url: "./../controllers/purchaseOrderController.php",
                method: "POST",
                data: {
                    action: 'productsList',
                
                },
                success: function (response) {
    
                    try {
                        let parsed = typeof response === 'string' ? JSON.parse(response) : response;
                        let products = Array.isArray(parsed[0]) ? parsed[0] : parsed;
        
                        globalProductOptions = '<option value="">Select Product</option>';
                        products.forEach(function (product) {
                            globalProductOptions += `<option value="${product.id}">${product.name}</option>`;
                        });
        

                        $('#productsDropdown').html(globalProductOptions);

                    } catch (err) {
                        console.error("Failed to parse JSON response", err);
                    }
                },
                error: function () {
                    console.log('Error fetching products');
                }
            });
    
        }
    
    
        productsList();
    
    
        //suppliersList 
    
    function suppliersList() {
        $.ajax({
            url: "./../controllers/purchaseOrderController.php",
            method: "POST",
            data: {
                action: 'suppliersList',
              
            },
            success: function (response) {

                try {
                    let parsed = typeof response === 'string' ? JSON.parse(response) : response;


                    let suppliers = Array.isArray(parsed[0]) ? parsed[0] : parsed;
    
                    const supplierDropdown = $('#supplierDropdown');
                    supplierDropdown.empty(); 
                    supplierDropdown.append(`<option value="">Select Supplier</option>`);
    
                    suppliers.forEach(function (supplier) {
                        supplierDropdown.append(`<option value="${supplier.id}">${supplier.name}</option>`);
                    });
                } catch (err) {
                    console.error("Failed to parse JSON response", err);
                }
            },
            error: function () {
                console.log('Error fetching suppliers');
            }
        });
    
    }
    
    
    suppliersList();
    
    
    }
    





});



function approvePo(user_id) {
    const po_id = $('#edit_po_id').val();

    $.ajax({
        url: './../controllers/purchaseOrderController.php',
        method: 'POST',
        data: {
            action: 'poApproval',
            id: po_id,
            approved_by: user_id
        },
        dataType: 'json',
        success: function (response) {

       
            if (response.success) {
                $('#edit_po_success_message').html(`<div class="alert alert-success">${response.success}</div>`).fadeIn();
                $('#edit_po_error_message').html('');

                // Show approved fields
                $('#approval_fields').show();
                $('#edit_po_order_approved_by').val(user_id);
                const today = new Date().toISOString().split('T')[0];
                $('#edit_po_order_approved_at').val(today);

                $('#not_approved_text').hide();

                fetchPurchaseOrders(currentPage);
            } else if (response.errors) {
                let errorList = Object.values(response.errors).map(e => `<li>${e}</li>`).join('');
                $('#edit_po_error_message').html(`<div class="alert alert-danger"><ul>${errorList}</ul></div>`).fadeIn();
                $('#edit_po_success_message').html('');
            }
        },
        error: function () {
            $('#edit_po_error_message').html('<div class="alert alert-danger">Failed to approve Purchase Order.</div>').fadeIn();
            $('#edit_po_success_message').html('');
        }
    });
}
