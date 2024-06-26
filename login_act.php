<?php
//最初にSESSIONを開始！！ココ大事！！
session_start();

//POST値
$lid = $_POST["lid"];
$lpw = $_POST["lpw"];


//1.  DB接続します
include("funcs.php");
$pdo = db_conn();

//2. データ登録SQL作成
$stmt = $pdo->prepare("SELECT*FROM gs_user_table WHERE lid = :lid AND lpw=:lpw");
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR);
$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if ($status == false) {
  sql_error($stmt);
}

//4. 抽出データ数を取得
$val = $stmt->fetch();         //1レコードだけ取得する方法



//5.該当１レコードがあればSESSIONに値を代入
//入力したPasswordと暗号化されたPasswordを比較！[戻り値：true,false]
if ($val['id'] != "") {
  //Login成功時
  $_SESSION["chk_ssid"]  = session_id();
  $_SESSION["kanri_flg"] = $val['kanri_flg'];
  $_SESSION["id"]        = $val['id'];
  $_SESSION["lid"]       = $val['lid'];
  $_SESSION["sei"]       = $val['sei'];
  $_SESSION["mei"]       = $val['mei'];
  $_SESSION["addr1"]     = $val['addr1'];
  $_SESSION["addr2"]     = $val['addr2'];

  if ($_SESSION["kanri_flg"] == 1) {
    redirect("admin.php");
  } else {
    //Login成功時（リダイレクト）
    redirect("index.php?login_success=1");
  }
} else {
  //Login失敗時(Logoutを経由：リダイレクト)
  redirect("login.php?login_error=1");
};


exit();
