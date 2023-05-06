<?php 
chdir ($_SERVER['DOCUMENT_ROOT']);
require_once("php/functions.php");
$stmt = $pdo->prepare("SELECT refresh FROM polls WHERE poll_unique = ?");
$stmt->bindValue(1, $_GET["uni"]);
$stmt->execute();
if ($stmt->rowCount() < 1) {
    error_log("errorrrrr");
    // header("location: /index.php");
    exit;
}
$poll = $stmt->fetch();
if ($poll['refresh'] == 1) {
    print('refresh_true');
    exit;
} else {
    print('refresh_false');
    exit;
}
