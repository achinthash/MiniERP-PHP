

<?php 

ob_start();
session_start();


?>



<div style="background-color: #9a6dbd;" class="text-start p-2   m-1 rounded text-light d-flex justify-content-between" >
    <h4> dashboard </h4>
</div>



<?php $content = ob_get_clean(); include './layout.php' ?>