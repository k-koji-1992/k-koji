<?php
include("funcs.php");
$pdo = db_conn();

$sql = "SELECT * FROM gs_bm_table";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

$pins = [];
if ($status == false) {
  sql_error($stmt);
} else {
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pins[] = $row;
  }
}

header('Content-Type: application/json');
echo json_encode($pins);
?>