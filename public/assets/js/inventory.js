// switch category and produts tables
let productsSection = document.getElementById('products-section');
let categorySection = document.getElementById('category-section');

function handleCategory(){
    categorySection.classList.remove('d-none');  
    productsSection.classList.add('d-none'); 
}


function handleProducts(){
    productsSection.classList.remove('d-none');  
    categorySection.classList.add('d-none'); 
}