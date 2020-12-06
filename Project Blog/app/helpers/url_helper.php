<?php ini_set('display_errors', 1);

// Page redirect funciton
function redirect($page){
    header('location: '.URLROOT. '/'.$page);
}
?>
