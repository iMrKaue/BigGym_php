<?php
session_start();
session_destroy();
header('Location: produtos.php');
exit;
?> 