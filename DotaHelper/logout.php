<?php
    session_start();
    session_unset(); 
    session_destroy(); 
    $newURL = "http://aherrin.create.stedwards.edu/research/DotaHelper/index.php";
    header('Location: '.$newURL);
?>