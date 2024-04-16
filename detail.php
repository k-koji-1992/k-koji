<?php
session_start();
//１．PHP
//select.phpの[PHPコードだけ！]をマルっとコピーしてきます。
//※SQLとデータ取得の箇所を修正します。
$id = $_GET["id"];

include("funcs.php");  //funcs.phpを読み込む（関数群）
sschk();
$pdo = db_conn();      //DB接続関数

//２．データ登録SQL作成
$stmt   = $pdo->prepare("SELECT * FROM gs_bm_table WHERE id=:id"); //SQLをセット
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$status = $stmt->execute(); //SQLを実行→エラーの場合falseを$statusに代入

//３．データ表示
$view = ""; //HTML文字列作り、入れる変数
if ($status == false) {
  //SQLエラーの場合
  sql_error($stmt);
} else {
  $row = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>登録内容更新</title>
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
        <div class="navbar-header"><a class="navbar-brand" href="select.php">投稿一覧</a></div>
        <div class="navbar-header"><a class="navbar-brand" href="index.php">依頼投稿</a></div>
      </div>
    </nav>
  </header>
  <!-- Head[End] -->

  <!-- Main[Start] -->
  <form method="POST" action="update.php">
    <div class="jumbotron">
      <fieldset>
        <legend>投稿更新</legend>
        <label>投稿者名：<input type="text" name="uname" value="<?= $row["uname"] ?>" readonly></label><br>
        <label>ユーザーID：<input type="text" name="uid" value="<?= $row["uid"] ?>" readonly></label><br>
        <label>件名：<input type="text" name="title" value="<?= $row["title"] ?>"></label><br>
        <label>相談カテゴリ：<br>
          <input type="radio" id="hachi" name="category" value="<?= $row["category"] ?>">
          <label for="hachi">ハチの巣駆除</label><br>
          <input type="radio" id="douro" name="category" value="<?= $row["category"] ?>">
          <label for="douro">道路補修・整備</label><br>
          <input type="radio" id="shibou" name="category" value="<?= $row["category"] ?>">
          <label for="shibou">野生動物の死体撤去</label><br>
          <input type="radio" id="trouble" name="category" value="<?= $row["category"] ?>">
          <label for="trouble">住民トラブル</label><br>
          <input type="radio" id="other" name="category" value="<?= $row["category"] ?>">
          <label for="other">その他
          </label><br>
        </label><br>
        <label>現住所：<input type="text" name="address1" value="<?= $row["address1"] ?>"></label><br>
        <label>依頼先住所：<input type="text" name="address2" value="<?= $row["address2"] ?>"></label><br>
        <label>相談事項：<textArea name="text" rows="4" cols="40"><?= $row["text"] ?></textArea></label><br>
        <label for="image">画像</label>
        <input type="file" id="image" name="image">

        <!-- idを隠して送信 -->
        <input type="hidden" name="id" value="<?= $id ?>">
        <!-- idを隠して送信 -->
        <input type="submit" value="依頼更新">
      </fieldset>
    </div>
  </form>
  <!-- Main[End] -->


</body>

</html>