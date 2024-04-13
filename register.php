<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>会員登録</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/fetch-jsonp@1.1.3/build/fetch-jsonp.min.js"></script>
  <script>
    function searchAddress() {
      const zip = document.getElementsByName("zip")[0].value;
      const api = 'https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + zip;

      fetch(api)
        .then(response => response.json())
        .then(data => {
          if (data.results) {
            const result = data.results[0];
            document.getElementsByName("addr1")[0].value = result.address1 + result.address2 + result.address3;
          } else {
            alert('住所が見つかりませんでした。');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('住所検索でエラーが発生しました。');
        });
    }
  </script>

</head>

<body>


  <header>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header"><a class="navbar-brand" href="index.php">依頼登録</a></div>
        <div class="navbar-header"><a class="navbar-brand" href="select.php">依頼一覧</a></div>
        <div class="navbar-header"><a class="navbar-brand" href="login.php">ログイン</a></div>
      </div>
    </nav>
  </header>

  <form method="post" action="insert2.php" class="h-adr">
    <div class="jumbotron">
      <fieldset>
        <legend>会員登録欄</legend>
        <label>お名前：
          <input type="text" name="sei" placeholder="姓" required>
          <input type="text" name="mei" placeholder="名" required>
        </label><br>
        <label>郵便番号：
          <input type="text" name="zip" size="8" maxlength="8" required>
          <button type="button" onclick="searchAddress()">住所検索</button>
        </label><br>
        <label>住所：
          <input type="text" name="addr1" class="p-region p-locality p-street-address p-extended-address" required>
        </label><br>
        <label>住所（町名以降）：
          <input type="text" name="addr2"required>
        </label><br>
        <label>ユーザー名：
          <input type="text" name="name" required>
        </label><br>
        <label>ユーザーID：
          <input type="text" name="lid" required>
        </label><br>
        <label>パスワード：
          <input type="password" name="lpw" required>
        </label><br>
        <label>パスワード（確認用）：
          <input type="password" name="lpw_confirm" required>
        </label><br>
        <input type="submit" value="会員登録">
      </fieldset>
    </div>
  </form>
</body>

</html>