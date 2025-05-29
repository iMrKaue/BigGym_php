<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: servicos_admin.php');
    exit;
}

$id = (int)$_GET['id'];
$db = getDB();
$stmt = $db->prepare('DELETE FROM servicos WHERE id = ?');
$stmt->execute([$id]);

header('Location: servicos_admin.php');
exit;
 