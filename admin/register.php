<?php

//1.$errorsの定義
$errors = [];

//check.phpから戻ってきた場合の処理
//POST送信の場合は$_POST、GET送信の場合は$_GETというスーパーグローバル変数が使える
if (isset($_GET['action']) && $_GET['action'] =='rewrite'){
    //$_POSTに擬似的に値を代入する
    //バリデーションを働かせるため
    $_POST['input_name'] = $_SESSION['49_CostCut']['name'];
    $_POST['input_email'] = $_SESSION['49_CostCut']['email'];
    $_POST['input_password'] = $_SESSION['49_CostCut']['password'];

    //check.phpが空の場合、check.phpへ再遷移してもらう
    $errors['rewrite'] = true;
}

//空チェック
//1.エラーだった場合になんのエラーかを保持する$errorsを定義
//2.送信されたデータと空文字を比較
//3.一致する場合は$errorsにnameをキーにblankという値を保持
//4.エラーがある場合、エラーメッセージを表示

//if(!empty)以降で、POST送信だった場合の状況を定義してしまっていて、エラーが出てしまうので、いったん中に空白を入れる。

$name = '';
$email = '';

// POSTかどうか（単純にアクセスするのはGET送信）
if (!empty($_POST)){
    //2.空文字かどうか
    $name = $_POST['input_name'];
    $email = $_POST['input_email'];
    $password = $_POST['input_password'];
    if($name == ''){
        //3.ユーザー名が空である、という情報を保持
        $errors['name'] = 'blank';
    }
    if($email == ''){
        $errors['email'] = 'blank';
    }
    //パスワードの文字数を数える
    //hogehogeと入力した場合には$countには8が入る
    $count = strlen($password);
    if($password == ''){
        $errors['password'] = 'blank';
    }elseif($count < 4 || 16 < $count){
        // ||パイプ演算子を使って4文字未満または16文字より多い場合エラー
        $errors['password'] = 'length';
    }

    //エラーがなかった場合、
    if(empty($errors)){

        //$_SESSION
        //セッションは各サーバの簡易的な保管庫
        //連想配列形式で値を保持する
        $_SESSION['49_CostCut']['name'] = $_POST['input_name'];
        $_SESSION['49_CostCut']['email'] = $_POST['input_email'];
        $_SESSION['49_CostCut']['password'] = $_POST['input_password'];

        // check.phpへの遷移（移動）
        // header('Location: 移動先')
        header('Location: check.php');
        exit();
    }
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Cebu Costs Simulator</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">アカウント作成</h2>
                <form method="POST" ac
                tion="register.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">ユーザー名</label>
                        <input type="text" name="input_name" class="form-control" id="name" placeholder="山田 太郎"
                            value="<?php echo htmlspecialchars($name); ?>">
                        <?php if(isset($errors['name']) && $errors['name'] == 'blank'): ?>
                            <p class="text-danger">ユーザー名を入力してください</p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">メールアドレス</label>
                        <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com"
                            value="<?php echo htmlspecialchars($email); ?>">
                        <?php if(isset($errors['email']) && $errors['email'] == 'blank'): ?>
                            <p class="text-danger">メールアドレスを入力してください</p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="password">パスワード</label>
                        <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
                        <?php if(isset($errors['password']) && $errors['password'] == 'blank'): ?>
                            <p class="text-danger">パスワードを入力してください</p>
                        <?php endif; ?>
                        <?php if(isset($errors['password']) && $errors['password'] == 'length'): ?>
                            <p class="text-danger">パスワードは4 ~ 16文字で入力してください</p>
                        <?php endif; ?>
                        <?php if(!empty($errors) && isset($errors['rewrite'])): ?>
                            <p class = "text-danger">パスワードを再度入力してください</p>
                        <?php endif; ?>
                    </div>
                    <input type="submit" class="btn btn-default" value="確認">
                    <span style="float: right; padding-top: 6px;">ログインは
                        <a href="login.php">こちら</a>
                    </span>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="../assets/js/jquery-3.1.1.js"></script>
<script src="../assets/js/jquery-migrate-1.4.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</html>