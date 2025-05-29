<?php
function getDB() {
    $db = new PDO('sqlite:' . __DIR__ . '/biggym.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}
?> 