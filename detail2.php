<?php
session_start();
//１．PHP
//※SQLとデータ取得の箇所を修正します。
$id = $_GET["id"];

include("funcs.php");  //funcs.phpを読み込む（関数群）
sschk();
$pdo = db_conn();      //DB接続関数

//２．データ登録SQL作成
$stmt   = $pdo->prepare("SELECT * FROM gs_user_table WHERE id=:id"); //SQLをセット
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$status = $stmt->execute(); //SQLを実行→エラーの場合falseを$statusに代入

//３．データ表示
$view = ""; //HTML文字列作り、入れる変数
if ($status == false) {
  //SQLエラーの場合
  sql_error($stmt);
} else {
  $row = $stmt->fetch();
  if ($_SESSION["id"] == $row["id"] || $_SESSION["kanri_flg"] == 1) {
    $view ='
      <form method="POST" action="update2.php">
        <div class="jumbotron">
          <fieldset>
            <legend>ユーザー情報更新</legend>
            <label>姓：<input type="text" name="sei" value="' . $row["sei"] . '"></label><br>
            <label>名：<input type="text" name="mei" value="' . $row["mei"] . '"></label><br>
            <label>ユーザー名：<input type="text" name="name" value="' . $row["name"] . '"></label><br>
            <label>ユーザーID：<input type="text" name="lid" value="' . $row["lid"] . '"></label><br>
            <label>パスワード：<input type="password" name="lpw" value="' . $row["lpw"] . '"></label><br>
            <input type="hidden" name="id" value="' . $id . '">
            <input type="submit" value="更新">
          </fieldset>
        </div>
      </form>
    ';
  } else {
    // アクセス権限がない場合はエラーメッセージを表示
    $view = '<p>アクセス権限がありません。</p>';
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>ユーザーデータ更新</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">

  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
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
  <?= $view ?>
  <!-- Main[End] -->
</body>

</html>