<?php
//session（セッション）を使うときは一番最初に書く
session_start();
require('../dbconnect.php');

$errors = [];

//課題：post送信されたときに、postに入っている値が空かどうかのバリデーションを書いていく
if(!empty($_POST)){
    $email = $_POST['input_email'];
    $password = $_POST['input_password'];
    if($email != '' && $password != ''){
        //正常系
        //両方入力されているとき
        //データベースとの照合処理
        //1.入力されたメールアドレスと一致する登録データを1件DBから取得する
        $sql = 'SELECT * FROM `owners` WHERE `email` = ?';
        $data = [$email];
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        //$recordはDBの1レコード（1行）に値する
        //形式は連想配列
        //キーはカラムに依存する
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        //SELECT文の実行結果がある場合は連想配列
        //結果がない場合はfalseが入る
        if($record == false){
            $errors['signin'] = 'failed';
        }

        //2.パスワード照合
        //password_verify(文字列,ハッシュ化=暗号化された文字列)
        //指定したふたつの文字列が合致する場合true
        //$password と $record['password']は == かどうかを確認している
        if(password_verify($password,$record['password'])){
            //認証成功
            //3.セッションにユーザーのIDを格納
            $_SESSION['49_CostCut']['id'] = $record['id'];

            //4.タイムライン画面に遷移
            header('Location: count.php');
            exit();
        }else{
            //認証失敗
            $errors['signin'] = 'failed';
        }

    }else{
        $errors['signin'] = 'blank';
    }
}


?>
<!-- <?php include('layouts/header.php'); ?>-->

<!--  <link href="js/material-kit.css?v=2.0.5" rel="stylesheet" />
 --><body style="margin-top: 60px">

    <div class="container">




<!DOCTYPE html>
<html lang="ja">

<head>
    <title> セブ生活費シュミレーター</title>
    <!-- 必要なメタタグ -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- timのスタイルシート -->
    <!-- <link href="css/material-kit.css?v=2.0.5" rel="stylesheet" /> -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <br><br><br>
                <h2 class="text-center content_header mx-auto">サインイン</h2>
                <br>
            </div>
        </div>


    <form method="POST" action="login.php" enctype="multipart/form-data">
        <div class="form-group row">
            <div class = "col-md-3"></div>
            <div class = "col-md-6">
                <label for="email">メールアドレス</label>
                <input type="email" name="input_email" class="form-control rounded-0" id="email" placeholder="email">
                        <?php if(isset($errors['signin']) && $errors['signin'] =='blank'): ?>
                            <p class= 'text-danger'>正しく入力してください</p>
                        <?php endif; ?>
                        <?php if(isset($errors['signin']) && $errors['signin'] =='failed'): ?>
                            <p class= 'text-danger'>サインインに失敗しました</p>
                        <?php endif; ?>
            </div>
            <div class = "col-md-3"></div>
        </div>

        <div class="form-group row">
            <div class = "col-md-3"></div>
            <div class = "col-md-6">
                <label for="password">パスワード</label>
                <input type="password" name="input_password" class="form-control rounded-0" id="password" placeholder="password">
            </div>
            <div class = "col-md-3"></div>
        </div>
        <br>

        <div class='row'>
            <div class = "col-md-3"></div>
            <div class = "col-md-2 float-left">
                    <input type="submit" class="btn rounded-0 signin-btn mx-auto margin-left" value="サインイン">
            </div>
            <div class = "col-md-7"></div>
        </div>
        <br>

         <div class='row'>
            <div class = "col-md-3"></div>
            <div class = "col-md-2 float-left">
                <a class = 'btn rounded-0 submit-btn mx-auto reverse-btn' href="../index.php">&nbsp;戻る&nbsp;</a>
            </div>
            <div class = "col-md-7"></div>
        </div>
    </form>
    </div>
</div>
</body>
<!-- <?php include('layouts/header.php'); ?> -->
</html>