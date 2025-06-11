$(document).ready(function () {
    let currentPage = 1;
    let globalProductOptions = '';

    // Fetch sales orders
    function fetchSalesOrders(search = '', page = 1) {
        $.ajax({
            url: './../controllers/salesOrderController.php',
            method: 'POST',
            data: {
                action: 'fetchSalesOrders',
                search: search,
                page: page
            },
            success: function (response) {

                
                const tbody = $("#salesOrderTableBody");
                tbody.empty();
                let parsed = typeof response === 'string' ? JSON.parse(response) : response;

                parsed.data.forEach(order => {
                    const row = `
                        <tr onclick="viewSalesOrder(${order.id})">
                            <td>${order.id}</td>
                            <td>${order.customer_name}</td>
                            <td>${order.sale_date}</td>
                            <td>${order.payment_status}</td>
                            <td>LKR: ${order.grand_total}</td>
                            <td>
                                <span onclick="event.stopPropagation(); editSalesOrder(${order.id})" class="btn btn-sm btn-primary">Edit</span>
                                <span onclick="event.stopPropagation(); deleteSalesOrder(${order.id})" class="btn btn-sm btn-danger">Delete</span>
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
                $('#pagination_sales').html(pagHtml);

                $('.page-link').click(function () {
                    currentPage = $(this).data('page');
                    fetchSalesOrders(currentPage);
                });


             
            }
        });
    }
    fetchSalesOrders();


    
// Add Purchase Order
$('#salesForm').on('submit', function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    formData.append('action', 'newSalesOrder');
    

    $.ajax({
        url: './../controllers/salesOrderController.php', 
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {

            console.log(response)


            if (response.success) {
                $('#success_message').html(`<div class="alert alert-success">${response.message}</div>`).fadeIn();
                $('#error_message').html('');
                form.reset();

                fetchSalesOrders(currentPage);
               

            } else if (response.errors) {
                let errorList = Object.values(response.errors).map(e => `<li>${e}</li>`).join('');
                $('#error_message').html(`<div class="alert alert-danger"><ul>${errorList}</ul></div>`).fadeIn();
                $('#success_message').html('');
            }
        },
        error: function () {
            $('#error_message').html('<div class="alert alert-danger">Failed to create Purchase Order.</div>').fadeIn();
            $('#success_message').html('');
        }
    });
});


    // View Sales Order
window.viewSalesOrder = function (id) {

    $.post("./../controllers/salesOrderController.php", {
        action: 'getSalesOrder',
        id: id
    }, function (response) {

        console.log(response)

        if (response.success) {
            const order = response.order;

                $('#sale_id').text(`#SALE-${order.id}`);
                $('#customer_name').text(order.customer_name);
                $('#customer_email').text(order.customer_email);
                $('#customer_phone').text(order.customer_phone);
                $('#customer_address').text(order.customer_address);
              
                $('#sale_date').text(order.sale_date);
                $('#created_by').text(order.created_by);
                $('#payment_status').text(order.payment_status);
                $('#payment_method').text(order.payment_method);
              
                $('#total_amount').text(`LKR ${order.total_amount}`);
                $('#discount').text(`LKR ${order.discount}`);
                $('#grand_total').text(`LKR ${order.grand_total}`);
                $('#remarks').text(order.remarks);
              
                const tbody = $('#sales_items');
                tbody.empty();
                order.items.forEach((item, i) => {
                  tbody.append(`
                    <tr>
                      <td>${i + 1}</td>
                      <td>${item.product_name}</td>
                      <td class="text-end">${item.quantity}</td>
                      <td class="text-end">LKR ${item.unit_price}</td>
                      <td class="text-end">LKR ${item.total}</td>
                    </tr>
                  `);
                });
              
              
            const modal = new bootstrap.Modal(document.getElementById('salesOrderProfile'));
            modal.show();
      
        } else {
            alert('Sales Order not found');
        }
    }, 'json');
};




 // salesEditModel


 window.editSalesOrder = function (id) {

    $.post("./../controllers/salesOrderController.php", {
        action: 'getSalesOrder',
        id: id
    }, function (response) {

      

        if (response.success) {
            const order = response.order;

                $('#sale_sale_id').val(order.id);
                $('#sale_customer_name').val(order.customer_name);
                $('#sale_customer_id').val(order.customer_id);
              
                $('#sale_sale_date').val(order.sale_date);
                $('#sale_payment_status').val(order.payment_status);
                $('#sale_payment_method').val(order.payment_method);
                $('#sale_total_amount').val(order.total_amount);
                $('#sale_discount').val(order.discount);
                $('#sale_grand_total').val(order.grand_total);
                $('#sale_remarks').val(order.remarks);

                const tbody = $("#edit_sales_items_container");
                tbody.empty();
                let parsed = typeof response === 'string' ? JSON.parse(response.order.items) : response.order.items;

                parsed.forEach((item, index) => {
                    const row = `
                        <tr>
                            <input type="hidden" name="items[${index}][id]" value="${item.id}" />
                            <td><input type="text" class="form-control" value="${item.product_name}" readonly></td>
                            <td><input type="number" name="items[${index}][quantity]" class="form-control" value="${item.quantity}" required></td>
                            <td><input type="number" name="items[${index}][unit_price]" class="form-control" value="${item.unit_price}" step="0.01" required></td>
                            <td><input type="number" name="items[${index}][total]" class="form-control" value="${item.total}" readonly></td>
                            <td><button type="button" class="btn btn-danger" onclick="deleteSalesItems(${item.id})">Remove</button></td>
                        </tr>
                    `;
                    tbody.append(row);
                });
              
              
            const modal = new bootstrap.Modal(document.getElementById('salesEditModel'));
            modal.show();
      
        } else {
            alert('Sales Order not found');
        }
    }, 'json');
};




//  updateSalesOrder


$('#saleEditForm').on('submit', function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    formData.append('action', 'updateSalesOrder');

    $.ajax({
        url: './../controllers/salesOrderController.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {


            if (response.success) {
                $('#edit_sale_success_message').html(`<div class="alert alert-success">${response.message}</div>`).fadeIn();
                $('#edit_sale_error_message').html('');

                fetchSalesOrders(currentPage);

            } else if (response.errors) {
                let errorList = Object.values(response.errors).map(e => `<li>${e}</li>`).join('');
                $('#edit_sale_error_message').html(`<div class="alert alert-danger"><ul>${errorList}</ul></div>`).fadeIn();
                $('#edit_sale_success_message').html('');
            }
        },
        error: function () {
            $('#edit_sale_error_message').html('<div class="alert alert-danger">Failed to update sale.</div>').fadeIn();
            $('#edit_sale_success_message').html('');
        }
    });
});







    // Delete sales order

    window.deleteSalesOrder = function (id) {
        if (!confirm("Are you sure you want to delete this sales?")) return;
        $.ajax({
            url: "./../controllers/salesOrderController.php",
            method: "POST",
            data: {
                action: 'deleteSalesOrder',
                id: id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#delete_sale_success_message').html(`<div class="alert alert-success">${response.message}</div>`).fadeIn();
                    $('#delete_sale_error_message').html('');

                    fetchSalesOrders(currentPage);
                } else {
                    $('#delete_sale_error_message').html(`<div class="alert alert-danger">${response.message}</div>`).fadeIn();
                    $('#delete_sale_success_message').html('');
                }
            },
            error: function () {
                $('#delete_sale_error_message').html('<div class="alert alert-danger">Failed to delete sale.</div>').fadeIn();
                $('#delete_sale_success_message').html('');
            }
        });
    };


    // deleteSalesItems 
    window.deleteSalesItems = function (id) {
        if (!confirm("Are you sure you want to delete this sales item?")) return;
        $.ajax({
            url: "./../controllers/salesOrderController.php",
            method: "POST",
            data: {
                action: 'deleteSalesItem',
                id: id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#delete_sale_item_success_message').html(`<div class="alert alert-success">${response.message}</div>`).fadeIn();
                    $('#delete_sale_item_error_message').html('');
                } else {
                    $('#delete_sale_item_error_message').html(`<div class="alert alert-danger">${response.message}</div>`).fadeIn();
                    $('#delete_sale_item_success_message').html('');
                }
            },
            error: function () {
                $('#delete_sale_item_error_message').html('<div class="alert alert-danger">Failed to delete sales item.</div>').fadeIn();
                $('#delete_sale_item_success_message').html('');
            }
        });
    };



 
});








// new sales 

window.newSale = function (){
    
    const Modal = new bootstrap.Modal(document.getElementById("newSalesModel"));
    Modal.show();


     //productsList 
     function productsList() {
        $.ajax({
            url: "./../controllers/salesOrderController.php",
            method: "POST",
            data: {
                action: 'productsList',
            
            },
            success: function (response) {

                console.log(response)

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




    //customerList 
    function customerList() {
        $.ajax({
            url: "./../controllers/salesOrderController.php",
            method: "POST",
            data: {
                action: 'customersList',
            
            },
            success: function (response) {

                console.log(response)

                try {
                    let parsed = typeof response === 'string' ? JSON.parse(response) : response;
                    let customers = Array.isArray(parsed[0]) ? parsed[0] : parsed;
    
                    globalCustomerOptions = '<option value="">Select Customer</option>';
                    customers.forEach(function (customer) {
                        globalCustomerOptions += `<option value="${customer.id}">${customer.name}</option>`;
                    });
    

                    $('#customersDropdown').html(globalCustomerOptions);

                } catch (err) {
                    console.error("Failed to parse JSON response", err);
                }
            },
            error: function () {
                console.log('Error fetching customers');
            }
        });

    }


    customerList();

  

}

// new sale


