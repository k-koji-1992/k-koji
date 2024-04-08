<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>すぐやる課支援業務アプリ</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/sample.css" rel="stylesheet">
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
  <header>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header"><a class="navbar-brand" href="select.php">投稿一覧</a></div>
        <div class="navbar-header"><a class="navbar-brand" href="login.php">ログイン</a></div>
        <div class="navbar-header"><a class="navbar-brand" href="register.php">会員登録</a></div>
      </div>
    </nav>
  </header>

  <main>
    <div id="myMap" style="width: 100%; height: 700px;"></div>
  </main>

  <form method="post" action="insert.php">
    <div class="jumbotron">
      <fieldset>
        <legend> 依頼登録欄 </legend>
        <label> 名前： <input type="text" id="uname" name="uname">
        </label><br>
        <label> 件名： <input type="text" id="title" name="title">
        </label><br>
        <label> 住所： <input type="text" id="address" name="address">
        </label><br>
        <label> 相談事項： <textArea id="text" name="text" rows="4" cols="40">
                </textArea></label>
        <br>
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">
        <input type="submit" value="送信">
      </fieldset>
    </div>
  </form>

  <script src="js/script.js"></script>
</body>

</html>