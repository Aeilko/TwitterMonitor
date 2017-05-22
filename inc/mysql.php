<?php
$SQLiConnection = mysqli_connect("localhost", $SQLName, $SQLPass) or die(mysqli_error($SQLiConnection));
$SQLiDB = mysqli_select_db($SQLiConnection, $SQLDB) or die(mysqli_error($SQLiConnection));
?>
