<?php
session_start();
//1. POSTデータ取得
$id   = $_GET["id"];

//2. DB接続します
include("funcs.php");  //funcs.phpを読み込む（関数群）
sschk();

$pdo = db_conn();      //DB接続関数

//３．データ登録SQL作成
$stmt   = $pdo->prepare("SELECT * FROM gs_user_table WHERE id=:id"); //SQLをセット
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$status = $stmt->execute(); //SQLを実行→エラーの場合falseを$statusに代入

if ($status == false) {
    //SQLエラーの場合
    sql_error($stmt);
} else {
    $row = $stmt->fetch();
    // ユーザー自身または管理者の場合のみ、削除処理を実行
    if ($_SESSION["id"] == $row["id"] || $_SESSION["kanri_flg"] == 1) {
        $sql = "DELETE FROM gs_user_table WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $status = $stmt->execute(); //実行

        //４．データ登録処理後
        if ($status == false) {
            sql_error($stmt);
        } else {
            // redirect("select.php");
            // 退会処理が成功した場合、ログアウト処理を実行
            $_SESSION = array(); // セッション変数を空にする
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 42000, '/');
            }
            session_destroy(); // セッションを破棄
            redirect("login.php"); // ログインページへリダイレクト
        }
    } else {
        // アクセス権限がない場合はエラーメッセージを表示
        echo "アクセス権限がありません。";
        exit;
    }
}
