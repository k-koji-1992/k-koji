<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();

$stmt = $pdo->prepare("SELECT * FROM gs_bm_table");
$status = $stmt->execute();

$view = "";
if ($status == false) {
    sql_error($stmt);
} else {
    $pins_data = array(); // ピンのデータを格納する配列を初期化

    $view .= "<table class='table'>";
    $view .= "<tr><th>名前</th><th>件名</th><th>依頼事項</th><th>カテゴリ</th><th>依頼住所</th><th>登録日時</th><th>更新</th><th>削除</th></tr>";

    while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $view .= "<tr>";
        // $view .= "<td>" . $res["id"] . "</td>";
        $view .= "<td>" . $res['uname'] . "</td>";
        $view .= "<td>" . $res['title'] . "</td>"; // titleカラムを削除
        $view .= "<td>" . $res['text'] . "</td>"; // titleカラムを削除
        $view .= "<td>" . $res['category'] . "</td>"; // 修正箇所: カテゴリーを表示
        $view .= "<td>" . $res['address2'] . "</td>";
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
                <?php
                // 修正箇所: ログイン中は「ログアウト」を表示し、それ以外は「ログイン」を表示
                if (isset($_SESSION['chk_ssid'])) {
                    echo '<a class="navbar-brand" href="logout.php">ログアウト</a>';
                } else {
                    echo '<a class="navbar-brand" href="login.php">ログイン</a>';
                }
                ?>
            </div>
        </nav>
    </header>

    <div class="container">
        <div id="myMap" style="width: 100%; height: 500px;"></div>
        <div class="table-responsive">
            <?= $view ?>
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
        // switch (category) {
        //   case "ハチの巣駆除":
        //     pinIcon = "images/hachi.png";
        //     break;
        //   case "道路補修・整備":
        //     pinIcon = "images/douro.png";
        //     break;
        //   case "野生動物の死体撤去":
        //     pinIcon = "images/shibou.png";
        //     break;
        //   case "住民トラブル":
        //     pinIcon = "images/trouble.png";
        //     break;
        //   case "その他":
        //     pinIcon = "images/other.png";
        //     break;
        // }

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