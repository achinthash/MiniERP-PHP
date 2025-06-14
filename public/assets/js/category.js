

$(document).ready(function (){


    let currentPage = 1;
    
    function fetchCategories(search = '', page = 1){
        $.ajax({
            url: "./../controllers/inventoryController.php",
        
            method: "POST", 
            data: {
                action: 'categoryTable',
                search: search,
                page: page
            },
            success: function(response){

                const tbody = $("#categoryTableBody");
                tbody.empty();
                let parsedResponse = typeof response === 'string' ? JSON.parse(response) : response;

                parsedResponse.data.forEach(category => {
                    const row = `
                       <tr >
                            <td>${category.id}</td>
                            <td>${category.name}</td>
                            <td>${category.description}</td>
                            <td> 
                                <span onclick="editCategory(${category.id})">Edit</span>  
                                <span onclick="deleteCategory(${category.id})">Delete</span> 
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
                $('#pagination').html(pagHtml);

                $('.page-link').click(function () {
                    currentPage = $(this).data('page');
                    fetchCategories($('#search').val(), currentPage);
                });
              
            },
            error: function(){
                console.log('error');
            }
        })
    }

    fetchCategories();

    // Search
    $('#search').on('input', function () {
        currentPage = 1;
        fetchCategories($(this).val(), currentPage);
    });


    // Edit button click
    window.editCategory = function(id) {
 

        $.post("./../controllers/inventoryController.php", {
            action: 'getCategory',
            id: id
        }, function (response) {
    
            if (response.success) {
        
                $('#id').val(response.category.id);
                $('#name').val(response.category.name);
                $('#description').val(response.category.description);
            
                const modal = new bootstrap.Modal(document.getElementById('editCategory'));
                modal.show();
                
            } else {
                alert('Category not found');
            }
           
        }, 'json');

    }
   
    // Delete  
    window.deleteCategory = function(id){
        if (!confirm("Are you sure you want to delete this category?")) return;

        $.ajax({
            url: "./../controllers/inventoryController.php",
            method: "POST",
            data: {
                action: 'deleteCategory',
                id: id
            },
            dataType: 'json',
            success: function(response){

                if (response.success) {
                    $('#delete_success_message').html('<div class="alert alert-success">' + response.message + '</div>').fadeIn();
                    $('#delete_error_message').html('');
                    
                    fetchCategories($('#search').val(), currentPage);

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


    //  update category section
    $('#categoryEditForm').on('submit', function(e){

        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('action','updateCategory')
        $.ajax({
            url : './../controllers/inventoryController.php',
            method : 'POST',
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
                   
    
                    fetchCategories($('#search').val(), currentPage);

                } else if (response.errors) {
                    let errorList = Object.values(response.errors).map(e => `<li>${e}</li>`).join('');
                    $('#edit_error_message')
                        .html(`<div class="alert alert-danger"><ul>${errorList}</ul></div>`)
                        .fadeIn();
                    $('#edit_success_message').html('');
                }
            },
            error:function(){
                $('#edit_error_message').html('<div class="alert alert-danger">Failed to update category. </div>').fadeIn();
                $('#edit_success_message').html('');
            }
        });
      
        
    });

     //  creae category section

    $('#categoryForm').on('submit', function(e){
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        formData.append('action','create');

        $.ajax({
            url : './../controllers/inventoryController.php',
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

});




  //////////////////  
