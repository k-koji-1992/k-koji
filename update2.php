<?php
//1. POSTデータ取得
$sei   = $_POST["sei"];
$mei  = $_POST["mei"];
// $name = $_POST["name"];
$lid = $_POST["lid"];
$lpw = $_POST["lpw"];
$id    = $_POST["id"];   //idを取得
//2. DB接続します
include("funcs.php");  //funcs.phpを読み込む（関数群）
$pdo = db_conn();      //DB接続関数


//３．データ登録SQL作成
$sql = "UPDATE gs_user_table SET sei=:sei, mei=:mei,  lid=:lid, lpw=:lpw WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':sei', $sei, PDO::PARAM_STR);
$stmt->bindValue(':mei', $mei, PDO::PARAM_STR);
// $stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute(); //実行


//４．データ登録処理後
if($status==false){
    sql_error($stmt);
}else{
    redirect("select.php");
}
?>
