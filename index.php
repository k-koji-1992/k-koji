<?php
session_start();
include("funcs.php");
sschk();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>すぐやる課支援業務アプリ</title>
  <link href="css/style.css" rel="stylesheet">
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/BmapQuery1.js"></script>
  <script src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=ApzkQEdYymyvakYqLcMkXK0DnXvvIW2Y66KKY-_I67uUAILst4XPqfQllOteMSCn' async defer></script>
</head>

<body>

  <?php //追加: ログイン成功時のアラート
  if (isset($_GET['login_success']) && $_GET['login_success'] == 1) {
    echo "<script>alert('ログインに成功しました。');</script>";
  }
  ?>
  <header>
    <nav class="navbar">
      <div class="container navbar-container">
        <a class="navbar-brand" href="select.php">投稿一覧</a>
        <?php
        // 修正箇所: ログイン中は「ログアウト」を表示し、それ以外は「ログイン」を表示
        if (isset($_SESSION['chk_ssid'])) {
          echo '<a class="navbar-brand" href="logout.php">ログアウト</a>';
        } else {
          echo '<a class="navbar-brand" href="login.php">ログイン</a>';
        }
        ?>
        <a class="navbar-brand" href="register.php">会員登録</a>
      </div>
    </nav>
  </header>
  <main>
    <div class="container">
      <div id="myMap" style="width: 100%; height: 700px;"></div>

      <form method="post" action="insert.php" enctype="multipart/form-data">
        <legend>依頼登録欄</legend>
        <div class="form-group">
          <label for="uname">名前</label>
          <input type="text" id="uname" name="uname" required>
        </div>
        <div class="form-group">
          <label for="title">題名</label>
          <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
          <label for="text">相談事項</label>
          <textarea id="text" name="text" rows="4" required></textarea>
        </div>
        <div class="form-group">
          <label for="address1">住所（現在地）</label>
          <input type="text" id="address1" name="address1">
        </div>
        <div class="form-group">
          <label for="address2">住所（依頼地点）</label>
          <input type="text" id="address2" name="address2">
        </div>
        <div class="form-group">
          <label for="image">画像</label>
          <input type="file" id="image" name="image">
        </div>
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">
        <input type="submit" value="送信">
      </form>
    </div>
  </main>
  <script src="js/script.js"></script>
</body>

</html>