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
    $view .= "<tr><th>ID</th><th>名前</th><th>件名</th><th>コメント</th><th>住所</th><th>登録日時</th><th>更新</th><th>削除</th></tr>";

    while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $view .= "<tr>";
        $view .= "<td>" . $res["user_id"] . "</td>";
        $view .= "<td>" . $res['uname'] . "</td>";
        $view .= "<td>" . $res['text'] . "</td>"; // titleカラムを削除
        $view .= "<td>" . $res['address2'] . "</td>";
        $view .= "<td>" . $res['indate'] . "</td>";
        $view .= "<td>";
        if ($res['image_path']) {
            $view .= "<img src='" . $res['image_path'] . "' style='max-width:100px;'>";
        }
        $view .= "</td>";
        $view .= "<td><a href='detail.php?id=" . h($res["user_id"]) . "' class='btn btn-primary'>更新</a></td>";
        $view .= "<td><a href='delete.php?id=" . h($res["user_id"]) . "' class='btn btn-danger' onclick=\"return confirm('本当に削除しますか？');\">削除</a></td>";
        $view .= "</tr>";

        // ピンのデータを配列に追加
        $pins_data[] = array(
            'latitude' => $res['latitude'],
            'longitude' => $res['longitude'],
            'address' => $res['address2'],
            'text' => $res['text'],
            'uname' => $res['uname']
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
    <title>投稿管理画面</title>
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
                <a class="navbar-brand" href="logout.php">ログアウト</a>
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
            map.startMap(35.6809591, 139.7673068, "load", 12); // 東京を中心とした地図を表示

            var pins_data = <?php echo json_encode($pins_data); ?>; // PHPの配列をJavaScriptの配列に変換

            pins_data.forEach(function(pin) {
                var lat = pin.latitude;
                var lon = pin.longitude;
                var address = pin.address;
                var text = pin.text;
                var uname = pin.uname;

                var pin = map.pin(lat, lon, "#0000ff");

                map.onPin(pin, "click", function() {
                    var title = uname;
                    var descript = '<div style="width:300px;">住所：' + address + '</div><br>' + text;
                    var options = [map.onInfobox(lat, lon, title, descript)];
                    map.infoboxLayers(options, true);
                });
            });
        }
    </script>
</body>

</html>