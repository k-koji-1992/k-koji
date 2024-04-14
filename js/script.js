//Initialization processing
let map;
let clickedLat;
let clickedLon;

// function updatePins() {
//   // AJAXでデータベースからピンの情報を取得
//   fetch("get_pins.php")
//     .then((response) => response.json())
//     .then((data) => {
//       var gs_bm_table = data;

//       // 既存のピンを削除
//       map.removePins();

//       // 地図上にピンを再配置
//       gs_bm_table.forEach(function (pin) {
//         var lat = pin.latitude;
//         var lon = pin.longitude;
//         var address = pin.address2;
//         var text = pin.text;
//         var uname = pin.uname;
//         var image_path = pin.image_path;

//         var pinEntity = map.pin(parseFloat(lat), parseFloat(lon), "#0000ff");

//         map.onPin(pinEntity, "click", function () {
//           map.reverseGeocode(
//             { latitude: parseFloat(lat), longitude: parseFloat(lon) },
//             function (address) {
//               var title = "依頼者：" + uname;
//               var descript = "住所：" + address + "<br>相談事項：" + text;

//               if (image_path) {
//                 descript +=
//                   '<br><img src="' + image_path + '" style="max-width: 100%;">';
//               }

//               var options = [
//                 map.onInfobox(
//                   parseFloat(lat),
//                   parseFloat(lon),
//                   title,
//                   descript
//                 ),
//               ];
//               map.infoboxLayers(options, true);
//             }
//           );
//         });
//       });
//     });
// }

function GetMap() {
  //1. Init
  map = new Bmap("#myMap");

  //2. geolocation: Display Map
  //------------------------------------------------------------------------
  map.geolocation(function (data) {
    //現在地の取得
    const lat = data.coords.latitude;
    const lon = data.coords.longitude;
    console.log(data);
    //現在地を中心にしたマップ
    map.startMap(lat, lon, "load", 15);
    //現在地のピン
    const currentLocationPin = map.pin(lat, lon, "#ff0000");

    const location = map.setLocation(lat, lon);
    map.reverseGeocode(location, function (data) {
      console.log(data);
      document.querySelector("#address1").value = data;
    });

    map.onGeocode("click", function (clickPoint) {
      map.reverseGeocode(clickPoint.location, function (data) {
        console.log(data);
        document.querySelector("#address2").value = data;
      });
    });

    //B. Get ReverseGeocode of click location
    map.onGeocode("click", function (data) {
      console.log(data); //Get Geocode ObjectData
      document.querySelector("#latitude").value = data.location.latitude;
      document.querySelector("#longitude").value = data.location.longitude;
      const lat = data.location.latitude; //Get latitude
      const lon = data.location.longitude; //Get longitude
      clickedLat = lat;
      clickedLon = lon;
      map.reverseGeocode(data.location, function (address) {
        document.querySelector("#address2").value = address;
        console.log(data); //Get Geocode ObjectData

        let pin = map.pin(
          data.location.latitude,
          data.location.longitude,
          "#9900ff"
        );
        // ピンを置く
        map.onPin(pin, "click", function () {
          map.reverseGeocode(data.location, function (address) {
            // 逆ジオコーディングの結果（住所情報）を取得
            let title = document.getElementById("uname").value;
            let descript =
              '<div style="width:300px;">住所：' + address + "</div>"; // 住所情報を使用

            const options = [];
            options[0] = map.onInfobox(
              data.location.latitude,
              data.location.longitude,
              title,
              descript
            );

            map.infoboxLayers(options, true);
          });
        });
      });
    });
  });

//   // 初回のピン更新
//   updatePins();

//   // 60秒ごとにピンを更新
//   setInterval(updatePins, 60000);

  // AJAXでデータベースからピンの情報を取得
  fetch("get_pins.php")
    .then((response) => response.json())
    .then((data) => {
      var gs_bm_table = data;
      console.log(data)
     
      // 地図読み込み時にデータベースからピンの情報を呼び出す。
      gs_bm_table.forEach(function (pin) {
        var lat = pin.latitude;
        var lon = pin.longitude;
        var address = pin.address2;
        var text = pin.text;
        var uname = pin.uname;
        var image_path = pin.image_path; // 追加: 画像パスを取得
        var category = pin.category; // 修正箇所: カテゴリーを取得
      
        var icon = "#0000ff";
        // switch (category) {
        //   case "ハチの巣駆除":
        //     icon = "/kadai/html_kikuchi_15/img/hachi.png";
        //     break;
        //   case "道路補修・整備":
        //     icon = "/img/douro.png";
        //     break;
        //   case "野生動物の死体撤去":
        //     icon = "/img/shibou.png";
        //     break;
        //   case "住民トラブル":
        //     icon = "/img/trouble.png";
        //     break;
        //   case "その他":
        //     icon = "/img/other.png";
        //     break;
        // }
        var pinEntity = map.pin(parseFloat(lat), parseFloat(lon),icon);
        
        // var pinEntity = map.pinIcon(parseFloat(lat), parseFloat(lon),icon, 0.3, 0, 0);

        map.onPin(pinEntity, "click", function () {
          map.reverseGeocode(
            { latitude: parseFloat(lat), longitude: parseFloat(lon) },
            function (address) {
              var title = "依頼者：" + uname ;
              var descript = "住所：" + address + "<br style='font-size:18px'>相談事項：" + text;

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
              map.infoboxLayers(options,true);// 直前のインフォボックスを閉じる
            }
          );
        });
      });
    });
}

// $("#send").on("click", function () {
//     const uname = $("#uname").val() || "匿名希望";
//     const msg = {
//         uname: uname,
//         text: $("#text").val(),
//         address1: $("#address1").val(),
//         address2: $("#address2").val(),
//         latitude: clickedLat,
//         longitude: clickedLon
//     };

//     $.post("insert.php", msg, function (response) {
//         console.log(response);
//         // 新しいピンを地図上に追加
//         var pin = map.pin(clickedLat, clickedLon, "#0000ff");
//         map.onPin(pin, "click", function () {
//             map.reverseGeocode({ latitude: clickedLat, longitude: clickedLon }, function (address) {
//                 var title = uname;
//                 var descript = '<div style="width:300px;">住所：' + address + '</div><br>' + msg.text;
//                 var options = [map.onInfobox(clickedLat, clickedLon, title, descript)];
//                 map.infoboxLayers(options, true);
//             });
//         });
//     });
// });
