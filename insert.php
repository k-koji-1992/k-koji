<?php
// 1. POSTデータ取得
$uname = $_POST['uname']; 
$uid = $_POST['uid'];
$title = $_POST['title']; 
$text = $_POST['text']; 
$category = $_POST['category'];
$address1 = $_POST['address1'];
$address2 = $_POST['address2']; 
$latitude = $_POST['latitude']; 
$longitude = $_POST['longitude']; 
$image_path = null;
if (!empty($_FILES['image']['name'])) {
    $upload_dir = 'uploads/';
    $image_path = $upload_dir . uniqid() . '_' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
}
include("funcs.php");
$pdo = db_conn();

// 3. データ登録SQL作成
$sql = "INSERT INTO `gs_bm_table`(uname, uid, title, text, category, address1, address2, latitude, longitude, image_path, indate)
        VALUES (:uname, :uid, :title, :text, :category, :address1, :address2, :latitude, :longitude, :image_path, sysdate())";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':uname', $uname, PDO::PARAM_STR);
$stmt->bindValue(':uid', $uid, PDO::PARAM_STR);
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$stmt->bindValue(':text', $text, PDO::PARAM_STR);
$stmt->bindValue(':category', $category, PDO::PARAM_STR);
$stmt->bindValue(':address1', $address1, PDO::PARAM_STR); 
$stmt->bindValue(':address2', $address2, PDO::PARAM_STR); 
$stmt->bindValue(':latitude', $latitude, PDO::PARAM_STR); 
$stmt->bindValue(':longitude', $longitude, PDO::PARAM_STR); 
$stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR); 
$status = $stmt->execute();

// 4. データ登録処理後
if ($status == false) {
    sql_error($stmt);
} else {
    redirect("index.php");
}
