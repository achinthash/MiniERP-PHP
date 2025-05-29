$(document).ready(function (){


    let currentPage = 1;
    
    function fetchCustomers(search = '', page = 1){
        $.ajax({
            url: "./../controllers/customerController.php",
        
            method: "POST", 
            data: {
                action: 'fetchCustomers',
                search: search,
                page: page
            },
            success: function(response){

                const tbody = $("#customerTableBody");
                tbody.empty();
                let parsedResponse = typeof response === 'string' ? JSON.parse(response) : response;

                parsedResponse.data.forEach(category => {
                    const row = `
                       <tr onclick="customerProfile(${category.id})">
                            <td>${category.id}</td>
                            <td>${category.name}</td>
                            <td>${category.email}</td>
                            <td>${category.phone}</td>
                            <td> 
                                <span onclick=" event.stopPropagation(); editCustomer(${category.id})">Edit</span>  
                                <span onclick=" event.stopPropagation(); deleteCustomer(${category.id})">Delete</span> 
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });

             

                // Pagination
                const totalPages = Math.ceil(parsedResponse.total / parsedResponse.perPage);
                let pagHtml = '';
                for (let i = 1; i <= totalPages; i++) {
                    
                    pagHtml += `<li class="page-item " > 
                        <button class=" page-link ${currentPage === i ? 'active': ''}"  data-page="${i}">  ${i} </button>
                    </li>`; 
                }
                $('#pagination_customers').html(pagHtml);

                $('.page-link').click(function () {
                    currentPage = $(this).data('page');
                    fetchCustomers($('#search').val(), currentPage);
                });
              
            },
            error: function(){
                console.log('error');
            }
        })
    }

    fetchCustomers();

    // Search
    $('#search').on('input', function () {
        currentPage = 1;
        fetchCustomers($(this).val(), currentPage);
    });



    // new customer
    $('#customerForm').on('submit', function (e){

        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('action','newCustomer')
        $.ajax({
            url : './../controllers/customerController.php',
            method : 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

            success:function(response){

                if (response.success) {
                    $('#success_message')
                        .html('<div class="alert alert-success">' + response.success + '</div>')
                        .fadeIn();
                    $('#error_message').html('');
                    $('#customerForm')[0].reset();
    
                    fetchCustomers($('#search').val(), currentPage);

                } else if (response.errors) {
                    let errorList = Object.values(response.errors).map(e => `<li>${e}</li>`).join('');
                    $('#error_message')
                        .html(`<div class="alert alert-danger"><ul>${errorList}</ul></div>`)
                        .fadeIn();
                    $('#success_message').html('');
                }
            },
            error:function(){
                $('#error_message').html('<div class="alert alert-danger">Failed to update category. </div>').fadeIn();
                $('#success_message').html('');

             
            }
        });
      
    });

    // customer profile

    window.customerProfile = function(id){
      

        $.post("./../controllers/customerController.php", {
            action: 'customerProfile',
            id: id
        }, function (response) {
    
            if (response.success) {
    
                $('#name').text(response.customer.name);
                $('#cust_email').text(response.customer.email);
                $('#phone').text(response.customer.phone);
                $('#address').text(response.customer.address);
      
                const Modal = new bootstrap.Modal(document.getElementById("customerProfile"));
                Modal.show();
              
                
            } else {
                alert('Customer not found');
            }
           
        }, 'json');
    }


    // customer edit
    window.editCustomer = function(id){

        $.post("./../controllers/customerController.php", {
            action: 'customerProfile',
            id: id
        }, function (response) {

            if (response.success) {

                
                $('#cust_id').val(response.customer.id);
                $('#cust_name').val(response.customer.name);
                $('#cust_emailaddress').val(response.customer.email);
                $('#cust_phone').val(response.customer.phone);
                $('#cust_address').val(response.customer.address);

                const modal = new bootstrap.Modal(document.getElementById('customerEdit'));
                modal.show();
                
            } else {
                alert('Customer not found');
            }
           
        }, 'json');
    }


    $('#edit_customerForm').on('submit',function (e){

        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('action','updateCustomer');

        $.ajax({
            url : './../controllers/customerController.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

            success:function(response){

                if (response.success) {
                    $('#edit_success_message')
                        .html('<div class="alert alert-success">' + response.success + '</div>')
                        .fadeIn();
                    $('#edit_error_message').html('');
            
                    fetchCustomers($('#search').val(), currentPage);

                } else if (response.errors) {
                    let errorList = Object.values(response.errors).map(e => `<li>${e}</li>`).join('');
                    $('#edit_error_message')
                        .html(`<div class="alert alert-danger"><ul>${errorList}</ul></div>`)
                        .fadeIn();
                    $('#edit_success_message').html('');
                }
            },
            error:function(){
                $('#edit_error_message').html('<div class="alert alert-danger">Failed to create category. </div>').fadeIn();
                $('#edit_success_message').html('');
            }
        })
    });


    // Delete  
    window.deleteCustomer = function(id){
        if (!confirm("Are you sure you want to delete this customer?")) return;

        $.ajax({
            url: "./../controllers/customerController.php",
            method: "POST",
            data: {
                action: 'deleteCustomer',
                id: id
            },
            dataType: 'json',
            success: function(response){

                if (response.success) {
                    $('#delete_success_message').html('<div class="alert alert-success">' + response.message + '</div>').fadeIn();
                    $('#delete_error_message').html('');
                    
                    fetchCustomers($('#search').val(), currentPage);

                } else {
                    $('#delete_error_message').html('<div class="alert alert-danger">' + response.message + '</div>').fadeIn();
                    $('#delete_success_message').html('');
                    
                }
            },
            error: function(){
                $('#delete_error_message').html('<div class="alert alert-danger">Failed to delete customer. </div>').fadeIn();
                $('#delete_success_message').html('');
                
            }
        });
    }

});