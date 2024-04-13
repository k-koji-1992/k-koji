<?php
// 1. POSTデータ取得
$uname = $_POST['uname']; // 変更: 'title' から 'uname' に変更
$title = $_POST['title']; // 変更: 'url' から 'text' に変更
$text = $_POST['text']; // 変更: 'url' から 'text' に変更
$address1 = $_POST['address1']; // 追加: 'address1' を追加
$address2 = $_POST['address2']; // 追加: 'address2' を追加
$latitude = $_POST['latitude']; // 追加: 'latitude' を追加
$longitude = $_POST['longitude']; // 追加: 'longitude' を追加
$image_path = null;
if (!empty($_FILES['image']['name'])) {
    $upload_dir = 'uploads/';
    $image_path = $upload_dir . uniqid() . '_' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
}
// $image_data = NULL;  // 画像データを保存するための変数
// if (!empty($_FILES['image']['name'])) {
//     $image_data = file_get_contents($_FILES['image']['tmp_name']);  // 画像データを読み込み
// }
include("funcs.php");
$pdo = db_conn();

// 3. データ登録SQL作成
$sql = "INSERT INTO `gs_bm_table`(uname, title, text, address1, address2, latitude, longitude, image_path, indate)
        VALUES (:uname, :title, :text, :address1, :address2, :latitude, :longitude, :image_path, sysdate())";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':uname', $uname, PDO::PARAM_STR);
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$stmt->bindValue(':text', $text, PDO::PARAM_STR);
$stmt->bindValue(':address1', $address1, PDO::PARAM_STR); // 追加: 'address1' のバインド
$stmt->bindValue(':address2', $address2, PDO::PARAM_STR); // 追加: 'address2' のバインド
$stmt->bindValue(':latitude', $latitude, PDO::PARAM_STR); // 追加: 'latitude' のバインド
$stmt->bindValue(':longitude', $longitude, PDO::PARAM_STR); // 追加: 'longitude' のバインド
$stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR); // 追加: 'image_path' のバインド
// $stmt->bindParam(':image_data', $image_data, PDO::PARAM_LOB);
$status = $stmt->execute();

// 4. データ登録処理後
if ($status == false) {
    sql_error($stmt);
} else {
    redirect("index.php");
}
?> 