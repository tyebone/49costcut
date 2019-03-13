<?php
//session（セッション）を使うときは一番最初に書く
session_start();
require('../dbconnect.php');

if (!isset($_SESSION['49_CostCut'])) {
        header('Location: login.php');
        exit();
    }

// date_default_timezone_set関数で時間を取得する場所を指定
date_default_timezone_set('Asia/Manila');

// answersテーブルからcodeとtypeを取得
$sql = 'SELECT `code`, `type` FROM `answers` GROUP BY `code`';
$stmt = $dbh->prepare($sql);
$stmt->execute();

// 1.通算の人数を取得
// usersという配列を定義
$users = [];
while(true) {
    // レコード一件ずつ取得
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    // レコードは普通、一件ずつ取得するごとに下の行に移動
    // 最新のレコードにまで達したときに、もうこれ以上レコードを取得する必要ないよ
    // と伝えるために、$record == false を定義する
    if ($record == false) {
        break;
    }
    // trueの場合、users配列に一件ずつのレコードが入っていく
    $users[] = $record;
}

// 2.本日分の人数を取得
// $usersから今日のユーザーだけ抽出
// $today変数に今日の日付を定義
$today = date("Y/m/d");

// $m20 $f20 $m30 $f30にそれぞれ0を一旦代入しておく
$m20 = 0;
$f20 = 0;
$m30 = 0;
$f30 = 0;

// 今日の人数を入れる変数$currentに一旦、0を代入しておく
$current = 0;
// $usersの一件ずつに要件定義
foreach($users as $user) {
    // 変数$timeに$user['code']の冒頭の10桁を代入
    $time = substr($user['code'],0,10);
    // 変数$user_dateに$timeで取得した10桁からそれぞれの日付を取得
    $user_date = date("Y/m/d", $time);
    // ユーザーが登録した日（$user_date）と$todayが等しいか
    // 等しい場合$currentをプラス1する
    if($user_date == $today){
        $current ++;
    }

// 3.カテゴリーごとの人数を取得
    if($user['type'] == 1){
        $m20 ++ ;
    }elseif($user['type'] == 2){
        // echo 'hoge2';
        $f20 ++;
    }elseif($user['type'] == 3){
        // echo 'hoge3';
        $m30 ++;
    }elseif($user['type'] == 4){
        // echo 'hoge4'.'<br>';
        $f30 ++;
    }
}
    // 円グラフにデータ遷移
    $type = [$m20,$f20,$m30,$f30];


?>



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
<?php include('../navbar.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12 mx-auto">
        <br>
        <h2 class="text-center content_header mx-auto">ユーザー数確認ページ</h2>
        <br>
        </div>
    </div>

        <div class="row">
            <div class = "col-md-4"></div>
            <div class = "col-md-2 mx-auto">
                <p>本日の利用者数</p>
            </div>
            <div class = "col-md-2 mx-auto">
                <p>これまでの利用者数</p>
            </div>
            <div class = "col-md-4"></div>
        </div>

        <div class="row">
            <div class = "col-md-4"></div>
            <div class = "col-md-2 mx-auto">
                <?php echo $current; ?>人
            </div>
            <div class = "col-md-2 mx-auto">
                <?php echo count($users) ?>人

            </div>
            <div class = "col-md-4"></div>
        </div>
        <br><br>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 mx-auto">
                <div id="pie-div">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
            <div class="col-md-3 mx-auto"></div>
    </div>
</body>
    <script src="js/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <script src="js/pie.js"></script>
        <script>
            showPie(<?php echo json_encode($type);?>);
        </script>
</html>