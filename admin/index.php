<?php
require('../dbconnect.php');

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

// 今日だけ
// $usersから今日のユーザーだけ抽出
// $today変数に今日の日付を定義
$today = date("Y/m/d");
echo $today.'hoge'.'<br>';

// $usersの一件ずつに要件定義
foreach($users as $user) {
    // $timeに10桁のtimeを
    $time = substr($user['code'],0,10);
    // $user_dateは$timeで取得した10桁からそれぞれの日付を取得
    $user_date = date("Y/m/d", $time);
}

// もしも$today(今日の日付)と$user_dataに代入されている日付が同じ場合
// $today_numberに$usersに入っているデータの数を代入
if($today == $user_date){
     $today_num = count($users);
     }else{
    }

    // echo '<pre>';
    // var_dump($users);
    // echo '</pre>';


// type別
// $usersから
// typeがそれぞれこの数字だったら、このカテゴリーに代入




// $today = strtotime('today 00:00:00').'<br>';
// echo $today;

//引っ張ってきたcodeを一回date化してそれが、date(today)に当てはまる
//かどうかを確認する

//$user_numberはユーザーの識別コード（time関数+rand関数）
//$user_number_10は$user_numberを前半の10桁だけ取り出したもの
//$user_number_10sは$user_number_10が連想配列になったもの
// $users_number_10s = [];
// while(true){
//     $users_number = $stmt->fetch(PDO::FETCH_ASSOC);
//     $users_number_10 = substr($users_number['code'],0,+10);
//     if($users_number_10 == false){
//         break;
//     $users_number_10s[] = $users_number_10;


//     // echo '<pre>';
//     // var_dump($users_number_10s);
//     // echo '</pre>';

// $users_number_dates= [];
// while(true){
// $users_number_date = date("Y/m/d", strtotime($users_number_10));
// if($users_number_date == false){
//     break;
//       }
// }

// $users_number_dates[] = $users_number_date;

//  }
// }

    // echo '<pre>';
    // var_dump($users_number_10s);
    // echo '</pre>';

    //$users_number_datesは日付が入る連想配列



    // $users_number_dates = [];
    // $users_number_date = date("Y-m-d", strtotime($users_number_10));
    // $users_number_dates[] = $users_number_date;

    // echo '<pre>';
    // var_dump($users_number_date);
    // echo '</pre>';


//$user_number_10を日付にする

// echo '<pre>';
// var_dump($users_number_date);
// echo '</pre>';

  // while (true) {
  //   $price = $stmt->fetch(PDO::FETCH_ASSOC);
  //   if ($price == false) {
  //       break;
  //   }
  //   $prices[] = $price;
  // }


// $users_numbers_day = date("Y-m-d", strtotime("$users_numbers_10"));

// if( date($users_numbers_10) == $users_numbers_day){
//     echo $users_numbers_day;
// }


// $today = '1551007217_101356288';

// if($today ==$users_number['code']){
//     echo 'あ';
// }


// codeの冒頭の10桁がstrotime('today')と同じ（？）だったら
// $number_users_todayに数字を代入
// $today = strtotime('today').'<br>';
// echo $today;

// echo date("Y-m-d", strtotime("today"));


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
                <p>本日の利用者 <?php echo $today_number; ?>人</p>
                <p>通算の利用者 <?php echo count($users) ?>人</p>
                <p>29歳以下男性の通算利用者 30人</p>
                <p>29歳以下女性の通算利用者 30人</p>
                <p>30歳以上男性の通算利用者 20人</p>
                <p>30歳以上女性の通算利用者 20人</p>
                <br>
                <p>新規登録</p>
                <p>確認</p>
                </div>
            </div>
        </div>
</body>
</html>