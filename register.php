<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>会員登録</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/sample.css" rel="stylesheet">
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>


</head>

<body>


  <header>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
      <div class="navbar-header"><a class="navbar-brand" href="index.php">ブックマーク登録</a></div>
        <div class="navbar-header"><a class="navbar-brand" href="select.php">ブックマーク一覧</a></div>
        <div class="navbar-header"><a class="navbar-brand" href="login.php">ログイン</a></div>
      </div>
    </nav>
  </header>


  <form method="post" action="insert2.php">
    <div class="jumbotron">
      <fieldset>
        <legend>会員登録欄</legend>
        <label>ユーザー名：<input type="text" name="name"></label><br>
        <label>ユーザーID：<input type="text" name="lid"></label><br>
        <label>パスワード：<input type="text" name="lpw"></label></textArea></label><br>
        <input type="submit" value="会員登録">
      </fieldset>
    </div>
  </form>
</body>

</html>