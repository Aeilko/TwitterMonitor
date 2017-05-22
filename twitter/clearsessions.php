<?php
/* Load and clear sessions */
session_start();
session_destroy();
 
// Redirect to index of main page
header('Location: ../index.php');
?>