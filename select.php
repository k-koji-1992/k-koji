<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();

$stmt = $pdo->prepare("SELECT * FROM gs_bm_table WHERE uid = :uid");
$stmt->bindValue(':uid', $_SESSION['lid'], PDO::PARAM_STR);
$status = $stmt->execute();

$view = "";
if ($status == false) {
  sql_error($stmt);
} else {
  $pins_data = array(); // ピンのデータを格納する配列を初期化

  $view .= "<table class='table'>";
  $view .= "<th>件名</th><th>依頼事項</th><th>カテゴリ</th><th>依頼住所</th><th>投稿画像</th><th>登録日時</th><th>更新</th><th>削除</th></tr>";

  while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $view .= "<tr>";
    // $view .= "<td>" . $res['uname'] . "</td>";
    // $view .= "<td>" . $res['uid'] . "</td>";
    $view .= "<td>" . $res['title'] . "</td>";
    $view .= "<td>" . $res['text'] . "</td>";
    $view .= "<td>" . $res['category'] . "</td>";
    // $view .= "<td>" . $res['address1'] . "</td>";
    $view .= "<td>" . $res['address2'] . "</td>";
    $view .= "<td>";
    if (!empty($res['image_path'])) {
      $view .= '<img src="' . $res['image_path'] . '" style="max-width: 100px;">';
    }
    $view .= "</td>";
    $view .= "<td>" . $res['indate'] . "</td>";
    $view .= "<td><a href='detail.php?id=" . h($res["id"]) . "' class='btn btn-primary'>更新</a></td>";
    $view .= "<td><a href='delete.php?id=" . h($res["id"]) . "' class='btn btn-danger' onclick=\"return confirm('本当に削除しますか？');\">削除</a></td>";
    $view .= "</tr>";

    // ピンのデータを配列に追加
    $pins_data[] = array(
      'latitude' => $res['latitude'],
      'longitude' => $res['longitude'],
      'address' => $res['address2'],
      'text' => $res['text'],
      'uname' => $res['uname'],
      'category' => $res['category']
    );
  }
  $view .= "</table>";
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>マイページ</title>
  <link href="css/style.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/BmapQuery1.js"></script>
  <script src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=ApzkQEdYymyvakYqLcMkXK0DnXvvIW2Y66KKY-_I67uUAILst4XPqfQllOteMSCn' async defer></script>
</head>

<body id="main">
  <header>
    <nav class="navbar">
      <div class="container navbar-container">
        <a class="navbar-brand" href="index.php">依頼登録</a>
        <a class="navbar-brand" href="logout.php">ログアウト</a>;
        <a class="navbar-brand" href="admin.php">管理者専用</a>
      </div>
    </nav>
  </header>

  <div class="container">
    <div id="myMap" style="width: 100%; height: 500px;"></div>
    <div class="table-responsive">

      <h2>投稿一覧</h2>
      <?= $view ?>
    </div>

    <!-- 追加: ユーザー情報の表示 -->
    <div class="user-info">
      <h2>ユーザー情報</h2>
      <p>名前: <?= isset($_SESSION['sei'], $_SESSION['mei']) ? $_SESSION['sei'] . ' ' . $_SESSION['mei'] : '' ?></p>
      <p>ユーザーID: <?= isset($_SESSION['lid']) ? $_SESSION['lid'] : '' ?></p>
      <p>住所: <?= isset($_SESSION['addr1'], $_SESSION['addr2']) ? $_SESSION['addr1'] . $_SESSION['addr2'] : '' ?></p>
      <?php if (isset($_SESSION['id'])) : ?>
        <a href="detail2.php?id=<?= $_SESSION['id'] ?>" class="btn btn-primary">ユーザー情報更新</a>
        <a href="delete2.php?id=<?= $_SESSION['id'] ?>" class="btn btn-danger" onclick="return confirm('本当に退会しますか？');">退会</a>
      <?php endif; ?>
    </div>
  </div>
  </div>

  <script>
    let map;
        function GetMap() {
            map = new Bmap("#myMap");
            map.startMap(35.6809591, 139.7673068, "load", 15); // 東京を中心とした地図を表示
            fetch("get_pins.php")
    .then((response) => response.json())
    .then((data) => {
      var gs_bm_table = data;

      // 地図読み込み時にデータベースからピンの情報を呼び出す。
      gs_bm_table.forEach(function (pin) {
        var lat = pin.latitude;
        var lon = pin.longitude;
        var address = pin.address2;
        var text = pin.text;
        var uname = pin.uname;
        var image_path = pin.image_path; // 追加: 画像パスを取得
        var category = pin.category; // 修正箇所: カテゴリーを取得
        var pinIcon = "#0000ff";
        var pinEntity = map.pin(parseFloat(lat), parseFloat(lon), pinIcon);

        map.onPin(pinEntity, "click", function () {
          map.reverseGeocode(
            { latitude: parseFloat(lat), longitude: parseFloat(lon) },
            function (address) {
              var title = "依頼者：" + uname;
              var descript = "住所：" + address + "<br>相談事項：" + text;

              // 追加: 画像が存在する場合、画像を表示
              if (image_path) {
                descript +=
                  '<br><img src="' + image_path + '" style="max-width: 100%;">';
              }

              var options = [
                map.onInfobox(
                  parseFloat(lat),
                  parseFloat(lon),
                  title,
                  descript
                ),
              ];
              map.infoboxLayers(options, true);
            }
          );
        });
      });
    });
        }
  </script>
</body>

</html>