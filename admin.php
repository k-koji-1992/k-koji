<?php
session_start();
//1. DB接続します
include("funcs.php");  //funcs.phpを読み込む（関数群）
sschk();
if ($_SESSION["kanri_flg"] != 1) {
  echo "アクセス権限がありません。";
  exit;
};

$pdo = db_conn();      //DB接続関数

//２．データ登録SQL作成
//ユーザー一覧取得
$stmt_user   = $pdo->prepare("SELECT * FROM gs_user_table WHERE kanri_flg != 1"); //SQLをセット
$status_user = $stmt_user->execute(); //SQLを実行→エラーの場合falseを$statusに代入

//投稿一覧取得
$stmt_post   = $pdo->prepare("SELECT * FROM gs_bm_table"); //SQLをセット
$status_post = $stmt_post->execute(); //SQLを実行→エラーの場合falseを$statusに代入

//３．データ表示
$view_user = "";
$view_post = "";

if ($status_user == false || $status_post == false) {
  //execute（SQL実行時にエラーがある場合）
  sql_error($stmt_user);
  sql_error($stmt_post);
} else {
  // ユーザー一覧表示
  $view_user .= "<h2>ユーザー一覧</h2>";
  $view_user .= "<table class='table'>";
  $view_user .= "<tr><th>管理番号</th><th>ユーザー名</th><th>ユーザーID</th><th>パスワード</th><th>更新</th><th>削除</th></tr>";
  while ($res = $stmt_user->fetch(PDO::FETCH_ASSOC)) {
    $view_user .= "<tr>";
    $view_user .= "<td>" . $res["id"] . "</td>";
    $view_user .= "<td>" . $res['sei'] . " " . $res['mei'] . "</td>";
    $view_user .= "<td>" . $res['lid'] . "</td>";
    $view_user .= "<td>" . $res['lpw'] . "</td>";
    $view_user .= "<td>";
    $view_user .= '<a href="detail2.php?id=' . h($res["id"]) . '" class="btn btn-primary">更新</a>';
    $view_user .= "</td>";
    $view_user .= "<td>";
    $view_user .= '<a href="delete2.php?id=' . h($res["id"]) . '" class="btn btn-danger" onclick="return confirm(\'本当に削除しますか？\');">削除</a>';
    $view_user .= "</td>";
    $view_user .= "</tr>";
  }
  $view_user .= "</table>";

  // 投稿一覧表示
  $view_post .= "<h2>投稿一覧</h2>";
  $view_post .= "<table class='table'>";
  $view_post .= "<tr><th>投稿ID</th><th>投稿者名</th><th>ユーザーID</th><th>件名</th><th>相談事項</th><th>カテゴリ</th><th>現住所</th><th>依頼先住所</th><th>投稿画像</th><th>更新</th><th>削除</th></tr>";
  while ($res = $stmt_post->fetch(PDO::FETCH_ASSOC)) {
    $view_post .= "<tr>";
    $view_post .= "<td>" . $res["id"] . "</td>";
    $view_post .= "<td>" . $res["uname"] . "</td>";
    $view_post .= "<td>" . $res["uid"] . "</td>";
    $view_post .= "<td>" . $res["title"] . "</td>";
    $view_post .= "<td>" . $res["text"] . "</td>";
    $view_post .= "<td>" . $res["category"] . "</td>";
    $view_post .= "<td>" . $res["address1"] . "</td>";
    $view_post .= "<td>" . $res["address2"] . "</td>";
    $view_post .= "<td>";
    if (!empty($res['image_path'])) {
      $view_post .= '<img src="' . $res['image_path'] . '" style="max-width: 100px;">';
    }
    $view_post .= "</td>";
    $view_post .= "<td>";
    $view_post .= '<a href="detail.php?id=' . h($res["id"]) . '" class="btn btn-primary">更新</a>';
    $view_post .= "</td>";
    $view_post .= "<td>";
    $view_post .= '<a href="delete.php?id=' . h($res["id"]) . '" class="btn btn-danger" onclick="return confirm(\'本当に削除しますか？\');">削除</a>';
    $view_post .= "</td>";
    $view_post .= "</tr>";
  }
  $view_post .= "</table>";
}

?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>管理画面</title>
  <link rel="stylesheet" href="css/range.css">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/sample.css" rel="stylesheet">
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
</head>

<body id="main">
  <!-- Head[Start] -->
  <header>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="index.php">ブックマーク登録</a>
          <a class="navbar-brand" href="select.php">ブックマーク一覧</a>
          <a class="navbar-brand" href="logout.php">ログアウト</a>
        </div>
      </div>
    </nav>
  </header>
  <!-- Head[End] -->


  <!-- Main[Start] -->
  <div>
    <div class="container jumbotron">
      <?= $view_user ?>
      <?= $view_post ?>
    </div>
  </div>
  <!-- Main[End] -->

</body>

</html>