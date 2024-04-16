<?php
//1. POSTデータ取得
$sei = $_POST['sei'];
$mei = $_POST['mei'];
$zip = $_POST['zip'];
$addr1 = $_POST['addr1'];
$addr2 = $_POST['addr2'];
// $name = $_POST['name'];
$lid = $_POST['lid'];
$lpw = $_POST['lpw'];
$lpw_confirm = $_POST['lpw_confirm'];

// パスワードと確認用パスワードが一致しているかチェック
if ($lpw !== $lpw_confirm) {
  echo "パスワードが一致しません。";
  exit;
}


include("funcs.php");
$pdo = db_conn();

// ユーザーIDが既に存在するかチェック
$stmt = $pdo->prepare("SELECT COUNT(*) FROM gs_user_table WHERE lid = :lid");
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->fetchColumn();

if ($count > 0) {
    // ユーザーIDが既に存在する場合の処理
    echo "そのユーザーIDは既に使用されています。別のユーザーIDを入力してください。";
    exit;
}

//３．データ登録SQL作成
// $sql = "INSERT INTO `gs_user_table`(name, lid, lpw) VALUES (:name,:lid,:lpw)";
$sql = "INSERT INTO `gs_user_table`(sei, mei, zip, addr1, addr2, lid, lpw) VALUES (:sei, :mei, :zip, :addr1, :addr2, :lid, :lpw)";
$stmt = $pdo->prepare($sql); // statement
// $stmt->bindValue(':name', $name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
// $stmt->bindValue(':lid', $lid, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
// $stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':sei', $sei, PDO::PARAM_STR);
$stmt->bindValue(':mei', $mei, PDO::PARAM_STR);
$stmt->bindValue(':zip', $zip, PDO::PARAM_STR);
$stmt->bindValue(':addr1', $addr1, PDO::PARAM_STR);
$stmt->bindValue(':addr2', $addr2, PDO::PARAM_STR);
// $stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR);
$status = $stmt->execute();

//４．データ登録処理後
// if ($status == false) {
//   //*** function化を使う！*****************
//   sql_error($stmt);
// } else {
//   //*** function化を使う！*****************
//   redirect("login.php");
// }
//４．データ登録処理後
if ($status == false) {
  sql_error($stmt);
} else {
  // 追加: セッションにユーザー情報を保存
  $_SESSION['id'] = $pdo->lastInsertId();
  $_SESSION['sei'] = $sei;
  $_SESSION['mei'] = $mei;
  // $_SESSION['name'] = $name;
  $_SESSION['lid'] = $lid;
  $_SESSION['addr1'] = $addr1;
  $_SESSION['addr2'] = $addr2;
  redirect("login.php");
}