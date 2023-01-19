<?php   

date_default_timezone_set("Asia/Tokyo");

$comment_array = array();
$pod = null;
$stmt = null;
$error_messages = array();

//DB接続
try{
    $pdo = new PDO('mysql:host=localhost;dbname=bbs-yt','root','');
} catch(PDOException $e){
    echo $e->getMessage();
}



//フォームを打ち込んだとき
if (!empty($_POST["submitButton"])){
   
    //名前のチェック
    if(empty($_POST["username"])){
        echo"名前を入力してください。";
        $error_messages["username"] = "名前を入力してください";
    }
     //コメントのチェック
     if(empty($_POST["comment"])){
        echo"コメントを入力してください。";
        $error_messages["comment"] = "コメントを入力してください";
    }

    if(empty($error_messages)){
    

    $postDate = date("Y-m-d H:i:s");
    $sql = 'DELETE FROM bbs-yt WHERE id=:id';

    try{
    $stmt = $pdo->prepare("INSERT INTO `bbs-table` (`username`, `comment`, `postDate`) VALUES (:username, :comment, :postDate);");
    $stmt->bindParam(':username', $_POST['username'],PDO::PARAM_STR);
    $stmt->bindParam(':comment',$_POST['comment'],PDO::PARAM_STR);
    $stmt->bindParam(':postDate',$postDate,PDO::PARAM_STR);

    $stmt->execute(); //実行する関数
    }catch(PDOException $e){
    echo $e->getMessage();
        }
    } 


    try {
        $status = $stmt->execute();
      } catch (PDOException $e) {
        echo json_encode(["sql error" => "{$e->getMessage()}"]);
        exit();
      }
}
//↑!つけることで”空じゃない”という意味
//↑$_POST これは取り出し方のグローバル変数、usernameのところに打ち込んだものが取り出せる
//PHPは変数の前に＄をつける



//DBからコメントデータを取得する
$sql = "SELECT `id`,`username`,`comment`,`postDate` FROM `bbs-table`;";
$comment_array = $pdo->query($sql);

//DBの接続を閉じる
$pdo = null;



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP掲示板</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
</head>
<body>
    <h1 class="title">掲示板アプリ</h1>
    <hr>
    <div class="boardWrapper">
        <section>
            <?php foreach($comment_array as $comment): ?>
            <article>
                <div class="wrapper">
                    <div class="nameArea">
                        <span>名前：</span>
                        <p class="username"><?php echo $comment["username"]; ?></p>
                        <time><?php echo $comment["postDate"]; ?></time>
                    </div>
                    <p class="comment"><?php echo $comment["comment"]; ?></p><input type="submit" value="編集" name="editButton"> <input type="submit" value="削除" name="deleteButton">

                </div>
            </article>
            <?php endforeach; ?>
        </section>

        <form class="formWrapper" method="POST">
            <div>
                <input type="submit" value="書き込む" name="submitButton">
                
               
                <label for="">名前：</labe;>
                <input tyle="text" name="username">

            </div>
            <div>
                <textarea class="commentTextArea" name="comment"></textarea>
        </form>
    </div>

    
</body>
</html>