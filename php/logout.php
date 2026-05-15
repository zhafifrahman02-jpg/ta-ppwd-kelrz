<?php
session_start();
session_destroy();
header("Location: indexpp.php");
exit;

?>
