<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">

  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
  <title>ログイン</title>
</head>

<body>


  <header>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="index.php">依頼登録</a>
          <a class="navbar-brand" href="select.php">依頼一覧</a>
        </div>
      </div>
    </nav>
  </header>

  <!-- lLOGINogin_act.php は認証処理用のPHPです。 -->
  <form name="form" action="login_act.php" method="post">
    ID:<input type="text" name="lid" />
    <p>

    </p>
    PW:<input type="password" name="lpw" />
    <p>

    </p>
    <input type="submit" value="LOGIN" />
  </form>


</body>

</html>