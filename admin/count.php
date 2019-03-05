<?php
require('../dbconnect.php');
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
?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>セブ生活費シュミレーター</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
</head>
<body style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">管理者画面</h2>
                <p>本日の利用者 <?php echo $current; ?>人</p>
                <p>通算の利用者 <?php echo count($users) ?>人</p>
                <p>29歳以下男性の通算利用者 <?php echo $m20; ?>人</p>
                <p>29歳以下女性の通算利用者 <?php echo $f20; ?>人</p>
                <p>30歳以上男性の通算利用者 <?php echo $m30; ?>人</p>
                <p>30歳以上女性の通算利用者 <?php echo $f30; ?>人</p>
                <br>
                <p>新規登録</p>
                <p>確認</p>
                </div>
            </div>
        </div>
</body>
</html>