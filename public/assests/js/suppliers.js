


$(document).ready(function (){


    let currentPage = 1;
    
    function fetchSuppliers(search = '', page = 1){
        $.ajax({
            url: "./../controllers/supplierController.php",
        
            method: "POST", 
            data: {
                action: 'fetchSuppliers',
                search: search,
                page: page
            },
            success: function(response){

                const tbody = $("#supplierTableBody");
                tbody.empty();
                let parsedResponse = typeof response === 'string' ? JSON.parse(response) : response;

                parsedResponse.data.forEach(supplier => {
                    const row = `
                       <tr onclick="supplierProfile(${supplier.id})">
                            <td>${supplier.id}</td>
                            <td>${supplier.name}</td>
                            <td>${supplier.email}</td>
                            <td>${supplier.phone}</td>
                            <td> 
                                <span onclick=" event.stopPropagation(); editSupplier(${supplier.id})">Edit</span>  
                                <span onclick=" event.stopPropagation(); deleteSupplier(${supplier.id})">Delete</span> 
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
                $('#pagination_supplier').html(pagHtml);

                $('.page-link').click(function () {
                    currentPage = $(this).data('page');
                    fetchSuppliers($('#search').val(), currentPage);
                });
              
            },
            error: function(){
                console.log('error');
            }
        })
    }

    fetchSuppliers();

    // Search
    $('#search').on('input', function () {
        currentPage = 1;
        fetchSuppliers($(this).val(), currentPage);
    });



    // new supplier
    $('#supplierForm').on('submit', function (e){

        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('action','newSupplier')
        $.ajax({
            url : './../controllers/supplierController.php',
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
                    $('#supplierForm')[0].reset();
    
                    fetchSuppliers($('#search').val(), currentPage);

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


     // supplier profile

     window.supplierProfile = function(id){
      

        $.post("./../controllers/supplierController.php", {
            action: 'suppliersProfile',
            id: id
        }, function (response) {
    
            if (response.success) {


                console.log(response)
    
                $('#name').text(response.suppliers.name);
                $('#sup_email').text(response.suppliers.email);
                $('#phone').text(response.suppliers.phone);
                $('#address').text(response.suppliers.address);
      
                const Modal = new bootstrap.Modal(document.getElementById("supplierProfile"));
                Modal.show();
              
                
            } else {
                alert('Supplier not found');
            }
           
        }, 'json');
    }

    // supplier edit
    window.editSupplier = function(id){

        $.post("./../controllers/supplierController.php", {
            action: 'suppliersProfile',
            id: id
        }, function (response) {

            if (response.success) {

                
                $('#supl_id').val(response.suppliers.id);
                $('#supl_name').val(response.suppliers.name);
                $('#supl_emailaddress').val(response.suppliers.email);
                $('#supl_phone').val(response.suppliers.phone);
                $('#supl_address').val(response.suppliers.address);

                const modal = new bootstrap.Modal(document.getElementById('supplierEdit'));
                modal.show();
                
            } else {
                alert('Supplier not found');
            }
           
        }, 'json');
    }



    $('#supplierEditForm').on('submit',function (e){

        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('action','updateSupplier');

        $.ajax({
            url : './../controllers/supplierController.php',
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
            
                    fetchSuppliers($('#search').val(), currentPage);

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
     window.deleteSupplier = function(id){
        if (!confirm("Are you sure you want to delete this Supplier?")) return;

        $.ajax({
            url: "./../controllers/supplierController.php",
            method: "POST",
            data: {
                action: 'deleteSupplier',
                id: id
            },
            dataType: 'json',
            success: function(response){

                if (response.success) {
                    $('#delete_success_message').html('<div class="alert alert-success">' + response.message + '</div>').fadeIn();
                    $('#delete_error_message').html('');
                    
                    fetchSuppliers($('#search').val(), currentPage);

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