<?php

$user_id = null;
$user_name = null;
$password = null;
$dbh = null;

//データベースに接続
$dsn = 'mysql:host=localhost; dbname=php_todoapp; charset=utf8';
$user = 'testuser';
$pass = 'testpass';

//直接このURLを踏んだ時の処理
session_start();
if(isset($_SESSION["user_id"])){
    $user_id = $_SESSION["user_id"];
}else {
    //ログイン画面に遷移
}

try{
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($dbh == null){
        //ログイン画面に戻る（エラーメッセージぐらいだしたい）
    } else{
        if(isset($_POST)==true){
            if($_POST["mode"]=="login"){ login(); }
            else if($_POST["mode"] =="signin"){ signin(); }
            else { 
                header('Location: login.html');
                exit;
            }
        }
    }
}catch(PDOException $e){
    die("PDO ERROR: " . $$e->getMessage());
}

function login(){
//マッチしたらindex.htmlに遷移する（このときセッションの動き確認）
    global $dbh;
    $search_sql = "SELECT * FROM users WHERE name=? && password=?";
    $name = htmlentities($_POST["user_name"], ENT_QUOTES, "utf-8");
    $password = htmlentities($_POST["password"], ENT_QUOTES, "utf-8");
    $password = md5($password);
    $search_array = Array($name, $password);
    $search_stmt = $dbh->prepare($search_sql);
    $search_result = $search_stmt->execute($search_array);
    $count = $search_stmt->rowCount();
    if($count == 1){
        $row = $search_stmt->fetch();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["user_name"] = $row["name"];
        $_SESSION["logged_in"] = true; 
        header('Location: index.php');
    }else{
        header('Location: login.php');
        exit;
    }
}
//サインアップならデータベースと接続して追加
function signin(){
//完了したらindex.htmlに遷移する（セッションの動き確認）
    global $dbh;
    $insert_sql = "INSERT INTO users (name, email, password) VALUES (?,?,?)";
    $name = htmlentities($_POST["user_name"], ENT_QUOTES, "utf-8");
    $email = htmlentities($_POST["email"], ENT_QUOTES, "utf-8");
    $password = htmlentities($_POST["password"], ENT_QUOTES, "utf-8");
    $password = md5($password);
    $insert_array = Array($name, $email, $password);
    $insert_stmt = $dbh->prepare($insert_sql);
    $insert_result = $insert_stmt->execute($insert_array);

    $search_sql = "SELECT * FROM users WHERE name='$name' && password='$password'";
    $search_stmt = $dbh->prepare($search_sql);
    $search_result = $search_stmt->execute();
    $count = $search_stmt->rowCount();
    if($count == 1){
        $row = $search_stmt->fetch();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["user_name"] = $row["name"];
        $_SESSION["logged_in"] = true; 
        header('Location: index.php');
    }else{
        header('Location: login.php');
        exit;
    }
}
?>