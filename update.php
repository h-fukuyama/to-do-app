<?php
//exit("てすと");
session_start();
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

//元のデータを編集テキストに持ってくる為の操作
if(! empty($_GET) ){
    $select_sql = "SELECT title, detail FROM tasks WHERE id = ?";
    $data = Array($_GET["update_id"]);
    $select_stmt = $dbh->prepare($select_sql);
    $select_result = $select_stmt->execute($data);
    $row = $select_stmt->fetch();
}

//編集ボタンが押されたときの動作
if(! empty($_POST)){
    if($_POST["mode"]=="update"){
        $delete_sql = "UPDATE tasks SET title = ?, detail = ? WHERE id = ?";
        $data = Array($_POST["title"], $_POST["detail"], $_GET["update_id"]);
        $delete_stmt = $dbh->prepare($delete_sql);
        $delete_result = $delete_stmt->execute($data);
        header('Location: index.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>編集画面</title>
</head>
<body>
    <form action="", method="post">
    <table>
            <tr>
                <td>件名</td>
                <td>
                    <input type="text" name="title" value="<?php echo $row["title"] ?>">
                </td>
            </tr>
            <tr>
                <td>詳細</td>
                <td>
                    <textarea name="detail" rows="10" cols="25"><?php echo $row["detail"] ?></textarea>
                </td>
            </tr>
        
    </table>
    <input type="submit" name="update" value="編集完了">
    <input type="hidden" name="mode" value="update">
    </form>
</body>
</html>