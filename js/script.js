// 1．位置情報の取得に成功した時の処理
function mapsInit(position) {
    //lat=緯度、lon=経度 を取得
    console.log(position);
    const lat = position.coords.latitude;
    const lon = position.coords.longitude;
    console.log(lat, lon);
  };
  //2． 位置情報の取得に失敗した場合の処理
  function mapsError(error) {
    let e = "";
    if (error.code == 1) { //1＝位置情報取得が許可されてない（ブラウザの設定）
      e = "位置情報が許可されてません";
    }
    if (error.code == 2) { //2＝現在地を特定できない
      e = "現在位置を特定できません";
    }
    if (error.code == 3) { //3＝位置情報を取得する前にタイムアウトになった場合
      e = "位置情報を取得する前にタイムアウトになりました";
    }
    alert("エラー：" + e);
  };
  
  //3.位置情報取得オプション
  var where = {
    enableHighAccuracy: true, //より高精度な位置を求める
    maximumAge: 20000, //最後の現在地情報取得が20秒以内であればその情報を再利用する設定
    timeout: 10000 //10秒以内に現在地情報を取得できなければ、処理を終了
  };
  
  //Main:位置情報を取得する処理 //getCurrentPosition :or: watchPosition
  navigator.geolocation.getCurrentPosition(mapsInit, mapsError, where);
  
  //Initialization processing
  let map;
  let clickedLat;
  let clickedLon;
  
  function GetMap() {
    //1. Init
    map = new Bmap("#myMap");
  
    //2. geolocation: Display Map
    //------------------------------------------------------------------------
    map.geolocation(function(data) {
      //現在地の取得
      const lat = data.coords.latitude;
      const lon = data.coords.longitude;
      console.log(data)
      //現在地を中心にしたマップ
      map.startMap(lat, lon, "load", 15);
      //現在地のピン
      const currentLocationPin = map.pin(lat, lon, "#ff0000");
  
      const location = map.setLocation(lat, lon);
      map.reverseGeocode(location, function(data) {
        console.log(data);
        document.querySelector("#address1").value = data;
      });
  
      map.onGeocode("click", function(clickPoint) {
        map.reverseGeocode(clickPoint.location, function(data) {
          console.log(data);
          document.querySelector("#address2").value = data;
        });
      });
  
      //B. Get ReverseGeocode of click location
      map.onGeocode("click", function(data) {
        console.log(data); //Get Geocode ObjectData
        document.querySelector("#latitude").value = lat;
        document.querySelector("#longitude").value = lon;
        const lat = data.location.latitude; //Get latitude
        const lon = data.location.longitude; //Get longitude
        clickedLat = lat;
        clickedLon = lon;
        map.reverseGeocode(data, function(address) {
          document.querySelector("#address").value = address;
        });
        console.log(data); //Get Geocode ObjectData
  
        let pin = map.pin(lat, lon, "#0000ff")
        // ピンを置く
        map.onPin(pin, "click", function() {
          map.reverseGeocode({
            latitude: lat,
            longitude: lon
          }, function(address) {
            // 逆ジオコーディングの結果（住所情報）を取得
            let title = document.getElementById('uname').value;
            let descript = '<div style="width:300px;">住所：</div>' + address; // 住所情報を使用
  
            const options = [];
            options[0] = map.onInfobox(lat, lon, title, descript)
  
            map.infoboxLayers(options, true);
          });
        });
      });
    });
  
    // AJAXでデータベースからピンの情報を取得
    fetch('get_pins.php')
      .then(response => response.json())
      .then(data => {
        var gs_bm_table = data;
  
        // 地図読み込み時にデータベースからピンの情報を呼び出す。
        gs_bm_table.forEach(function(pin) {
          var lat = pin.latitude;
          var lon = pin.longitude;
          var address = pin.address2;
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
      });
  }
  
  $("#send").on("click", function() {
    const uname = $("#uname").val() || "匿名希望";
    const msg = {
      uname: uname,
      text: $("#text").val(),
      address1: $("#address1").val(),
      address2: $("#address2").val(),
      latitude: clickedLat,
      longitude: clickedLon
    };
  
    $.post("insert.php", msg, function(response) {
      console.log(response);
      // 新しいピンを地図上に追加
      var pin = map.pin(clickedLat, clickedLon, "#0000ff");
      map.onPin(pin, "click", function() {
        var title = uname;
        var descript = '<div style="width:300px;">住所：' + msg.address2 + '</div><br>' + msg.text;
        var options = [map.onInfobox(clickedLat, clickedLon, title, descript)];
        map.infoboxLayers(options, true);
      });
    });
  });