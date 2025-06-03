$(document).ready(function () {

    let currentPage = 1;
    
    function fetchProducts(search = '', page = 1){
        $.ajax({
            url: "./../controllers/productsController.php",
        
            method: "POST", 
            data: {
                action: 'fetchProducts',
                search: search,
                page: page
            },
            success: function(response){
             
                const tbody = $("#productTableBody");
                tbody.empty();
                let parsedResponse = typeof response === 'string' ? JSON.parse(response) : response;


                parsedResponse.data.forEach(product => {
                    // conditionally set a CSS class or inline style
                    const rowClass  = product.quantity_in_stock < 5 ? 'table-danger' : '';
                    const cellClass = product.quantity_in_stock < 5 ? 'text-danger font-weight-bold' : '';
                
                    const row = `
                        <tr onClick="productProfile(${product.id})" class="${rowClass}">
                            <td >
                                <span>
                                    <img style="width:25px; height:auto;" src="./uploads/products/${product.image}">
                                </span>
                                <span> ${product.name} </span>
                            </td>
                            <td>${product.sku}</td>
                            <td>${product.category_name}</td>
                            <td>supplier</td>
                            <td class="${cellClass}">${product.quantity_in_stock}</td>
                            <td> LKR:${product.unit_price}</td>
                            <td> 
                                <span onclick="event.stopPropagation(); productEdit(${product.id})">Edit</span>  
                                <span onclick="event.stopPropagation(); productDelete(${product.id})">Delete</span> 
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
                 $('#pagination_products').html(pagHtml);
 
                 $('.page-link').click(function () {
                     currentPage = $(this).data('page');
                     fetchProducts($('#search').val(), currentPage);
                 });

            },
            error: function(){
                console.log('error');
            }
        })
    }

    fetchProducts();
    // Search
    $('#search').on('input', function () {
        currentPage = 1;
        fetchProducts($(this).val(), currentPage);
    });



     //  creae category section

     $('#productForm').on('submit', function(e){
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('action','create');

        $.ajax({
            url : './../controllers/productsController.php',
            method: 'POST',
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
                    $('#categoryForm')[0].reset();
                   
                    fetchCategories($('#search').val(), currentPage);

                } else if (response.errors) {
                    let errorList = Object.values(response.errors).map(e => `<li>${e}</li>`).join('');
                    $('#error_message')
                        .html(`<div class="alert alert-danger"><ul>${errorList}</ul></div>`)
                        .fadeIn();
                    $('#success_message').html('');
                }
            },
            error:function(){
                $('#error_message').html('<div class="alert alert-danger">Failed to create category. </div>').fadeIn();
                $('#success_message').html('');
            }
        })



    });


    // product profile
    window.productProfile = function(id){

        $.post("./../controllers/productsController.php", {
            action: 'productProfile',
            id: id
        }, function (response) {

            if (response.success) {

                $('#name').text(response.product.name);
                $('#sku').text(response.product.sku);
                $('#category_name').text(response.product.category_name);
                $('#unit_price').text(`LKR ${response.product.unit_price}`);
                $('#quantity_in_stock').text(response.product.quantity_in_stock).removeClass('text-danger text-success')
                    .addClass(response.product.quantity_in_stock < 5 ? 'text-danger' : 'text-success');
                $('#description').text(response.product.description || 'No description provided.');

                if (response.product.image) {
                    $('#imagePreview').attr('src', './uploads/products/' + response.product.image);
                } else{
                    $('#imagePreview').attr('src', './uploads/products/product default.png' );
                }


                const modal = new bootstrap.Modal(document.getElementById('productProfle'));
                modal.show();
                
            } else {
                alert('Product not found');
            }
           
        }, 'json');

    }


    // product eidt
    window.productEdit = function(id){

        $.post("./../controllers/productsController.php", {
            action: 'productProfile',
            id: id
        }, function (response) {

            if (response.success) {

                 $('#product_id').val(response.product.id);
                $('#product_name').val(response.product.name);
                $('#product_description').val(response.product.description);
                $('#product_sku').val(response.product.sku);
                $('#product_unit_price').val(response.product.unit_price);
                $('#product_quantity_in_stock').val(response.product.quantity_in_stock);
                $('#product_category_id').val(response.product.category_id);
                
                $('#existing_image').val(response.product.image);


                 const modal = new bootstrap.Modal(document.getElementById('productEdit'));
                modal.show();
                
            } else {
                alert('Product not found');
            }
           
        }, 'json');

    }

    $('#productEditForm').on('submit',function (e){

        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('action','updateProduct');

        $.ajax({
            url : './../controllers/productsController.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

            success:function(response){

                if (response.success) {
                    $('#Edit_success_message')
                        .html('<div class="alert alert-success">' + response.success + '</div>')
                        .fadeIn();
                    $('#Edit_error_message').html('');
            
                    fetchProducts($('#search').val(), currentPage);

                } else if (response.errors) {
                    let errorList = Object.values(response.errors).map(e => `<li>${e}</li>`).join('');
                    $('#Edit_error_message')
                        .html(`<div class="alert alert-danger"><ul>${errorList}</ul></div>`)
                        .fadeIn();
                    $('#Edit_success_message').html('');
                }
            },
            error:function(){
                $('#Edit_error_message').html('<div class="alert alert-danger">Failed to create category. </div>').fadeIn();
                $('#Edit_success_message').html('');
            }
        })
    });



     // Delete  
     window.productDelete = function(id){
        if (!confirm("Are you sure you want to delete this product?")) return;

        $.ajax({
            url: "./../controllers/productsController.php",
            method: "POST",
            data: {
                action: 'deleteProduct',
                id: id
            },
            dataType: 'json',
            success: function(response){

                if (response.success) {
                    $('#delete_success_message').html('<div class="alert alert-success">' + response.message + '</div>').fadeIn();
                    $('#delete_error_message').html('');
                    
                    fetchProducts($('#search').val(), currentPage);

                } else {
                    $('#delete_error_message').html('<div class="alert alert-danger">' + response.message + '</div>').fadeIn();
                    $('#delete_success_message').html('');
                    
                }
            },
            error: function(){
                $('#delete_error_message').html('<div class="alert alert-danger">Failed to update category. </div>').fadeIn();
                $('#delete_success_message').html('');
                
            }
        });
    }

});