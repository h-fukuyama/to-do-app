<?php

session_start();
/* --- データベースに接続する処理 --- */

$dsn = 'mysql:host=localhost; dbname=php_todoapp; charset=utf8';
$user = 'testuser';
$pass = 'testpass';

try {
  $dbh = new PDO($dsn, $user, $pass);
}catch (PDOException $e){
  echo "接続失敗…<br>";
  echo "エラー内容：". $e->getMessage();
  die();
}


if( ! empty($_POST)){
    //追加ボタンを押されたときの挙動
    if($_POST["mode"]=="add"){
        $title = $_POST["title"];
        $detail = $_POST["detail"];

        //無効化宣言
        $title = htmlentities($title, ENT_QUOTES, "UTF-8");
        $detail = htmlentities($detail, ENT_QUOTES, "UTF-8");

        $insert_sql = "INSERT INTO tasks (title, detail, done, user_id) VALUES (?,?,?,?)";
        $data = Array($title, $detail, 0, $_SESSION["user_id"]);
        $insert_stmt = $dbh->prepare($insert_sql);
        $insert_result = $insert_stmt->execute($data);
    }
    //削除ボタンが押されたときの動作
    else if($_POST["mode"]=="delete"){
        $delete_sql = "DELETE FROM tasks WHERE id = ?";
        $data = Array($_POST["delete_id"]);
        $delete_stmt = $dbh->prepare($delete_sql);
        $delete_result = $delete_stmt->execute($data);
    }
    //完了ボタンが押されたときの動作
    else if($_POST["mode"]=="done"){
        $done_sql = "UPDATE tasks SET done=1 WHERE id = ?";
        $data = Array($_POST["done_id"]);
        $done_stmt = $dbh->prepare($done_sql);
        $done_result = $done_stmt->execute($data);
    }
}

//ログインしているかどうかの判定（セッションの中身を確認）
if(isset($_SESSION["logged_in"])){
    $user_id = $_SESSION["user_id"];
    $search_sql = "SELECT title, detail, id FROM tasks WHERE user_id = '$user_id' && done = 0";
    $search_stmt = $dbh->prepare($search_sql);
    $search_stmt->execute();

    $conf = fopen("data.html", "r");
    $size = filesize("data.html");
    $tmpl = fread($conf, $size);
    fclose($conf);

    $to_do_list = "";
    while($row = $search_stmt->fetch()){
        $tmple = $tmpl;
        $tmple = str_replace("!title!", $row["title"], $tmple);
        $tmple = str_replace("!detail!", $row["detail"], $tmple);
        $tmple = str_replace("!id!", $row["id"], $tmple);
        $to_do_list .= $tmple;
    }

    $conf = fopen("index.html", "r");
    $size = filesize("index.html");
    $tmpl = fread($conf, $size);
    fclose($conf);

    $tmpl = str_replace("!todolist!", $to_do_list, $tmpl);
    $tmpl = str_replace("!user_name!", $_SESSION["user_name"], $tmpl);
    echo $tmpl;
    exit;
}else {
    header('Location: login.html');
}


?>


