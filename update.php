<?php
//1. POSTデータ取得
$uname = $_POST['uname']; // 変更: 'title' から 'uname' に変更
$title = $_POST['title']; // 変更: 'url' から 'text' に変更
$text = $_POST['text']; // 変更: 'url' から 'text' に変更
$category = $_POST['category'];
$address1 = $_POST['address1']; // 追加: 'address1' を追加
$address2 = $_POST['address2']; // 追加: 'address2' を追加
$image_path = null;
if (!empty($_FILES['image']['name'])) {
    $upload_dir = 'uploads/';
    $image_path = $upload_dir . uniqid() . '_' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
}
$id    = $_POST["id"];   //idを取得

//2. DB接続します
include("funcs.php");  //funcs.phpを読み込む（関数群）
$pdo = db_conn();      //DB接続関数


//３．データ登録SQL作成
$sql = "UPDATE gs_bm_table SET uname=:uname, title=:title, text=:text, category=:category, address1=:address1, address2=:address2, image_path=:image_path WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':uname',  $uname,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':title',  $title,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':text', $text,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':category', $category,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':address1', $address1,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':address2',   $address2,    PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':image_path',   $image_path,    PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':id', $id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行


//４．データ登録処理後
if ($status == false) {
    sql_error($stmt);
} else {
    redirect("select.php");
}
