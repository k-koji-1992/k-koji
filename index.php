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
  <link href="css/infobox.css" rel="stylesheet">
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
      <div class="search-container">
        <input type="text" id="searchBox" placeholder="地名を入力">
        <button id="search">検索</button>
      </div>


      <div class="sort-container">
        <select id="categorySort">
          <option value="">カテゴリでソート</option>
          <option value="ハチの巣駆除">ハチの巣駆除</option>
          <option value="道路補修・整備">道路補修・整備</option>
          <option value="野生動物の死体撤去">野生動物の死体撤去</option>
          <option value="住民トラブル">住民トラブル</option>
          <option value="その他">その他</option>
        </select>
      </div>
      <div id="myMap"></div>

      <div class="legend">
        <p><span class="red-pin">●</span> 現在地</p>
        <p><span class="purple-pin">●</span> クリックした場所</p>
        <p><img src="img/hachi.png" alt="ハチ駆除" width="20"> ハチ駆除</p>
      </div>

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
          <label>カテゴリー:</label><br>
          <div>
            <input type="radio" id="hachi" name="category" value="ハチの巣駆除" required>
            <label for="hachi">ハチの巣駆除</label>
          </div>
          <div>
            <input type="radio" id="douro" name="category" value="道路補修・整備">
            <label for="douro">道路補修・整備</label>
          </div>
          <div>
            <input type="radio" id="shibou" name="category" value="野生動物の死体撤去">
            <label for="shibou">野生動物の死体撤去</label>
          </div>
          <div>
            <input type="radio" id="trouble" name="category" value="住民トラブル">
            <label for="trouble">住民トラブル</label>
          </div>
          <div>
            <input type="radio" id="other" name="category" value="その他">
            <label for="other">その他</label>
          </div>
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
  <script>
    // 地名検索機能
    document.getElementById("search").onclick = function() {
      const searchBox = document.getElementById("searchBox").value;
      if (searchBox) {
        map.getSearchBoundary(searchBox, "PopulatedPlace");
      }
    };
  </script>
</body>

</html>