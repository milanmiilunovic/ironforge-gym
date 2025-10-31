<?php
require_once 'config.php';

$conn = Database::connect();

if ($conn) {
    echo "✅ Uspješno povezan na bazu!";
}
?>
