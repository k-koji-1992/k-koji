//Initialization processing
let map;
let clickedLat;
let clickedLon;

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

                let pin = map.pin(data.location.latitude, data.location.longitude, "#0000ff");
                // ピンを置く
                map.onPin(pin, "click", function () {
                    map.reverseGeocode(data.location, function (address) {
                        // 逆ジオコーディングの結果（住所情報）を取得
                        let title = document.getElementById('uname').value;
                        let descript = '<div style="width:300px;">住所：' + address + '</div>'; // 住所情報を使用

                        const options = [];
                        options[0] = map.onInfobox(data.location.latitude, data.location.longitude, title, descript);

                        map.infoboxLayers(options, true);
                    });
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
            gs_bm_table.forEach(function (pin) {
                var lat = pin.latitude;
                var lon = pin.longitude;
                var address = pin.address2;
                var text = pin.text;
                var uname = pin.uname;

                var pinEntity = map.pin(parseFloat(lat), parseFloat(lon), "#0000ff");

                map.onPin(pinEntity, "click", function () {
                    map.reverseGeocode({ latitude: parseFloat(lat), longitude: parseFloat(lon) }, function (address) {
                        var title = uname;
                        var descript = '<div style="width:300px;">住所：' + address + '</div><br>' + text;
                        var options = [map.onInfobox(parseFloat(lat), parseFloat(lon), title, descript)];
                        map.infoboxLayers(options, true);
                    });
                });
            });
        });
}

$("#send").on("click", function (event) {
    event.preventDefault(); // フォームのデフォルトの送信動作を防ぐ
    const uname = $("#uname").val() || "匿名希望";
    const msg = {
        uname: uname,
        text: $("#text").val(),
        address1: $("#address1").val(),
        address2: $("#address2").val(),
        latitude: clickedLat,
        longitude: clickedLon
    };

    $.post("insert.php", msg, function (response) {
        console.log(response);
        // 新しいピンを地図上に追加
        var pin = map.pin(clickedLat, clickedLon, "#0000ff");
        map.onPin(pin, "click", function () {
            map.reverseGeocode({ latitude: clickedLat, longitude: clickedLon }, function (address) {
                var title = uname;
                var descript = '<div style="width:300px;">住所：' + address + '</div><br>' + msg.text;
                var options = [map.onInfobox(clickedLat, clickedLon, title, descript)];
                map.infoboxLayers(options, true);
            });
        });
        $("#uname").val("");
        $("#text").val("");
        $("#address1").val("");
        $("#address2").val("");

    });
});