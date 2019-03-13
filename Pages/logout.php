<?php
    session_start();
    session_unset(); 
    session_destroy(); 
    $newURL = "http://aherrin.create.stedwards.edu/DotaHelper/Pages/index.php";
    header('Location: '.$newURL);
?>