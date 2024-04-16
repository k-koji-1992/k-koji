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
        <a class="navbar-brand" href="select.php">マイページ</a>
        <a class="navbar-brand" href="logout.php">ログアウト</a>
        <a class="navbar-brand" href="admin.php">管理者専用</a>
    </nav>
  </header>
  <main>
    <div class="container">
      <div class="search-container">
        <select id="regionSelect">
          <option value="">地域を選択</option>
          <option value="北海道・東北">北海道・東北</option>
          <option value="関東">関東</option>
          <option value="甲信越・北陸">甲信越・北陸</option>
          <option value="東海">東海</option>
          <option value="関西">関西</option>
          <option value="中国">中国</option>
          <option value="四国">四国</option>
          <option value="九州・沖縄">九州・沖縄</option>
        </select>
        <select id="prefectureSelect">
          <option value="">都道府県を選択</option>
        </select>
        <select id="citySelect">
          <option value="">市区町村を選択</option>
        </select>
        <button id="search">検索</button>
        <script src="js/city.js"></script>
      </div>

      <!-- <div class="sort-container">
        <select id="categorySort">
          <option value="">カテゴリでソート</option>
          <option value="ハチの巣駆除">ハチの巣駆除</option>
          <option value="道路補修・整備">道路補修・整備</option>
          <option value="野生動物の死体撤去">野生動物の死体撤去</option>
          <option value="住民トラブル">住民トラブル</option>
          <option value="その他">その他</option>
        </select>
        <button id="sortButton">ソート</button>
      </div> -->
      <div id="myMap"></div>

      <div class="legend">
        <p><span class="red-pin" style="color:red;"><strong>◎</strong></span> 現在地</p>
        <p><span class="purple-pin" style="color:purple;"><strong>◎</strong></span> クリックした場所</p>
        <p><img src="img/hachi.png" alt="ハチ駆除" width="20"> ハチ駆除</p>
        <p><img src="img/douro.png" alt="道路整備" width="20"> 道路補修・整備</p>
        <p><img src="img/shibou.png" alt="動物" width="20">野生動物の死体撤去</p>
        <p><img src="img/trouble.png" alt="トラブル" width="20"> 住民トラブル</p>
        <p><span class="other" style="color:blue;"><strong>◎</strong></span>その他</p>
      </div>

      <form method="post" action="insert.php" enctype="multipart/form-data">
        <legend>依頼登録欄</legend>
        <div class="form-group">
          <label for="uname">名前</label>
          <input type="text" id="uname" name="uname" value="<?= isset($_SESSION['sei'], $_SESSION['mei']) ? $_SESSION['sei'] . ' ' . $_SESSION['mei'] : '' ?>" readonly required>
        </div>
        <div class="form-group">
          <label for="uid">ユーザーID</label>
          <input type="text" id="uid" name="uid" value="<?= isset($_SESSION['lid']) ? $_SESSION['lid'] : '' ?>" readonly required>
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
document.getElementById("search").onclick = function() {
      const region = document.getElementById("regionSelect").value;
      const prefecture = document.getElementById("prefectureSelect").value;
      const city = document.getElementById("citySelect").value;

      // 地域、都道府県、市区町村のいずれかが選択されている場合のみ検索を実行
      if (region || prefecture || city) {
        const searchQuery = `${region} ${prefecture} ${city}`;
        map.getSearchBoundary(searchQuery, "PopulatedPlace");
      }
    };

  </script>

</body>

</html>