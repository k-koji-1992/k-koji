<?php


//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

//DB接続関数：db_conn()
function db_conn()
{
    try {
        //localhostの場合
        $db_name = "gs_db";    //データベース名
        $db_id   = "root";      //アカウント名
        $db_pw   = "";          //パスワード：XAMPPはパスワード無しに修正してください。
        $db_host = "localhost"; //DBホスト

        //localhost以外＊＊自分で書き直してください！！＊＊
        if ($_SERVER["HTTP_HOST"] != 'localhost') {
            $db_name = "k-koji_unit2";  //データベース名
            $db_id   = "k-koji";  //アカウント名（さくらコントロールパネルに表示されています）
            $db_pw   = "";  //パスワード(さくらサーバー最初にDB作成する際に設定したパスワード)
            $db_host = "mysql57.k-koji.sakura.ne.jp"; //例）mysql**db.ne.jp...
        }
        return new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
    } catch (PDOException $e) {
        exit('DB Connection Error:' . $e->getMessage());
    }
}

//SQLエラー関数：sql_error($stmt)
function sql_error($stmt)
{
    $error = $stmt->errorInfo();
    exit("SQLError:" . $error[2]);
}


//リダイレクト関数: redirect($file_name)
function redirect($file_name)
{
    header("Location: " . $file_name);
    exit();
}

function sschk() {
    if ($_SESSION["chk_ssid"] != session_id()) {
        echo '<div class="login-error">';
        echo '<p>LOGIN ERROR</p>';
        echo '<p> <a href="login.php">こちら</a> からログインしてください。</p>';
        echo '<p> 会員登録をお済みでない場合は<a href="register.php">こちら</a> から登録してください。</p>';
        echo '</div>';
        exit();
    } else {
        session_regenerate_id(true);
        $_SESSION["chk_ssid"] = session_id();
    }
}
