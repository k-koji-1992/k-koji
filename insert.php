<?php
session_start();
include("funcs.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = db_conn();
    $uname = $_SESSION['user_name'];
    $user_id = $_SESSION['user_id'];
    $text = $_POST['text'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // 画像アップロード処理
    $image_path = null;
    if (!empty($_FILES['image']['name'])) {
        $image_path = 'uploads/' . uniqid() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    try {
        // データ登録SQL作成
        $sql = "INSERT INTO `gs_bm_table`(user_id, uname, text, address1, address2, latitude, longitude, image_path, indate)
                VALUES (:user_id, :uname, :text, :address1, :address2, :latitude, :longitude, :image_path, sysdate())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':uname', $uname, PDO::PARAM_STR);
        $stmt->bindValue(':text', $text, PDO::PARAM_STR);
        $stmt->bindValue(':address1', $address1, PDO::PARAM_STR);
        $stmt->bindValue(':address2', $address2, PDO::PARAM_STR);
        $stmt->bindValue(':latitude', $latitude, PDO::PARAM_STR);
        $stmt->bindValue(':longitude', $longitude, PDO::PARAM_STR);
        $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
        $stmt->execute();
        $response = array("status" => "success", "uname" => $uname);
    } catch (PDOException $e) {
        $response = array("status" => "error", "message" => $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} else {
    $response = array("status" => "error", "message" => "Invalid request method");
    header('Content-Type: application/json');
    echo json_encode($response);
    return;
}
?>