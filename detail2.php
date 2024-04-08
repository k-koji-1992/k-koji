<?php
session_start();
//１．PHP
//select.phpの[PHPコードだけ！]をマルっとコピーしてきます。
//※SQLとデータ取得の箇所を修正します。
$id = $_GET["id"];

include("funcs.php");  //funcs.phpを読み込む（関数群）
sschk();
if ($_SESSION["kanri_flg"] != 1) {
  echo "アクセス権限がありません。";
  exit;
};
$pdo = db_conn();      //DB接続関数

//２．データ登録SQL作成
$stmt   = $pdo->prepare("SELECT * FROM gs_user_table WHERE id=:id"); //SQLをセット
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$status = $stmt->execute(); //SQLを実行→エラーの場合falseを$statusに代入

//３．データ表示
$view=""; //HTML文字列作り、入れる変数
if($status==false) {
  //SQLエラーの場合
  sql_error($stmt);
}else{
  $row = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ユーザーデータ更新</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <div class="navbar-header"><a class="navbar-brand" href="select.php">ブックマーク一覧</a></div>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<form method="POST" action="update2.php">
  <div class="jumbotron">
   <fieldset>
    <legend>ユーザー情報更新</legend>
     <label>ユーザー名：<input type="text" name="name" value="<?=$row["name"]?>"></label><br>
     <label>ユーザーID：<input type="text" name="lid" value="<?=$row["lid"]?>"></label><br>
     <label>パスワード：<textArea name="lpw" rows="4" cols="40"><?=$row["lpw"]?></textArea></label><br>
     <!-- idを隠して送信 -->
     <input type="hidden" name="id" value="<?=$id?>">
     <!-- idを隠して送信 -->
     <input type="submit" value="送信">
    </fieldset>
  </div>
</form>
<!-- Main[End] -->


</body>
</html>




