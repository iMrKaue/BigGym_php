<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login_admin.php');
    exit;
}
require 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admins_admin.php');
    exit;
}

$id = (int)$_GET['id'];
$db = getDB();
$stmt = $db->prepare('DELETE FROM admins WHERE id = ?');
$stmt->execute([$id]);

header('Location: admins_admin.php');
exit; 