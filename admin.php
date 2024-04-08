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
$stmt   = $pdo->prepare("SELECT * FROM gs_user_table WHERE kanri_flg != 1"); //SQLをセット
$status = $stmt->execute(); //SQLを実行→エラーの場合falseを$statusに代入

//３．データ表示
$view = "";
if ($status == false) {
  //execute（SQL実行時にエラーがある場合）
  sql_error($stmt);
} else {
  // 表のヘッダーを設定
  $view .= "<table class='table'>";
  $view .= "<tr><th>管理番号</th><th>ユーザー名</th><th>ユーザーID</th><th>パスワード</th><th>更新</th><th>削除</th></tr>";
  // データを表形式で表示
  while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $view .= "<tr>";
    $view .= "<td>" . $res["id"] . "</td>";
    $view .= "<td>" . $res['name'] . "</td>";
    $view .= "<td>" . $res['lid'] . "</td>";
    $view .= "<td>" . $res['lpw'] . "</td>";

    // 更新ボタンを追加
    $view .= "<td>";
    $view .= '<a href="detail2.php?id=' . h($res["id"]) . '" class="btn btn-primary">更新</a>';
    $view .= "</td>";

    // 削除ボタンを追加
    $view .= "<td>";
    $view .= '<a href="delete2.php?id=' . h($res["id"]) . '" class="btn btn-danger" onclick="return confirm(\'本当に削除しますか？\');">削除</a>';
    $view .= "</td>";
    $view .= "</tr>";
  }
  $view .= "</table>";
}
$view .= "<script>
function confirmDelete(id) {
  var choice = confirm('このレコードを削除しますか？');
  if(choice) {
    window.location.href = 'delete.php?id=' + id;
  }
}
</script>";



?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ユーザー一覧</title>
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
    <div class="container jumbotron"><?= $view ?></div>
  </div>
  <!-- Main[End] -->

</body>

</html>