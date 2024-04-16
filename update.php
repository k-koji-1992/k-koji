<?php

include("funcs.php");  //funcs.phpを読み込む（関数群）
$pdo = db_conn();

//1. POSTデータ取得
$uname = $_POST['uname'];
$uid = $_POST['uid'];
$title = $_POST['title'];
$text = $_POST['text'];
$category = $_POST['category'];
$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$id = $_POST["id"];

// 画像更新の有無を判定
// ①最初の投稿で画像が投稿している場合で、新しい画像を登録して更新する場合
if (!empty($_FILES['image']['name'])) {
    $upload_dir = 'uploads/';
    $image_path = $upload_dir . uniqid() . '_' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
}
// ②最初の投稿で画像が投稿している場合で、新しい画像は登録しないで更新する場合
// ③最初の投稿で画像が投稿していない場合で、画像を登録して更新する場合
else {
    $stmt = $pdo->prepare("SELECT image_path FROM gs_bm_table WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($row['image_path'])) {
        // ②最初の投稿で画像が投稿している場合で、新しい画像は登録しないで更新する場合
        $image_path = $row['image_path'];
    } else {
        // ③最初の投稿で画像が投稿していない場合で、画像を登録して更新する場合
        if (!empty($_FILES['image']['name'])) {
            $upload_dir = 'uploads/';
            $image_path = $upload_dir . uniqid() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        }
    }
}



//2. DB接続します
// include("funcs.php");  //funcs.phpを読み込む（関数群）
// $pdo = db_conn();      //DB接続関数


//３．データ登録SQL作成
$sql = "UPDATE gs_bm_table SET uname=:uname, uid=:uid, title=:title, text=:text, category=:category, address1=:address1, address2=:address2, image_path=:image_path WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':uname',  $uname,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':uid',  $uid,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
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
    if ($_SESSION["kanri_flg"] == 1) {
        redirect("admin.php");
    } else {
        redirect("select.php");
    }
}

?>
