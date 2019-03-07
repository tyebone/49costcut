<?php
require('../dbconnect.php');

// if(!empty($_GET)){
//     echo '変更が完了しました';
// }

// $questionsテーブルからデータを取得する
$q_sql = 'SELECT * FROM `questions` ORDER BY `q_id`';
$q_stmt = $dbh->prepare($q_sql);
$q_stmt->execute();
// $questionsという名前の配列を作る
$questions = [];
while(true){
// $question_tableという名前の連想配列を作り、一件ずつレコードを追加していく
$question_table = $q_stmt->fetch(PDO::FETCH_ASSOC);
if($question_table == false){
  break;
}
// $questionsの中に$question_tableを配置する
$questions[] = $question_table;
}

// $optionsテーブルからデータを取得する
$o_sql = 'SELECT * FROM `options` ORDER BY `question_id`';
$o_stmt = $dbh->prepare($o_sql);
$o_stmt->execute();
// $optionsという名前の配列を作る
$options = [];
while(true){
// $option_tableという名前の連想配列を作り、一件ずつレコードを追加していく
$option_table = $o_stmt->fetch(PDO::FETCH_ASSOC);
if($option_table == false){
  break;
}
// $optionの中に$option_tableを配置する
$options[] = $option_table;
}

// $q_edits配列を用意する
// index.phpの12行目以降と同じ考え方
// $q_qs = [];
// post送信で更新ボタンが押されると、以下の処理がおこなわれる
// それぞれの問題文にはname = $q_edit['数字']が入っている
//もしも['']の中の数字と問題の数字が一緒
// いや、問題文にはすでにvalueが定義しているから
// 問答無用にDBに代入されればよし。



if(!empty($_POST)){

    for($i = 2; $i < 17; $i++){
    $q_sql = '
    UPDATE `questions`
    SET `content` = ?
    WHERE `q_id` = ?
    ';
    $q_data = [$_POST['q'.$i],$i];
    $q_stmt = $dbh->prepare($q_sql);
    $q_stmt->execute($q_data);

    // $q_qs[] = $q_q;
    }

header('Location: edit.php');
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>セブ生活費シュミレーター</title>
    <!-- 必要なメタタグ -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<form method="POST" action="edit.php">

<h1>問題2</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q2"><?php echo $questions[1]['content']; ?></textarea><br>
<!-- <p>問題2の回答1</p>
<!-- <textarea name = "q2"><?php echo $questions[1]['content']; ?></textarea><br> -->
 -->

<h1>問題3</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q3"><?php echo $questions[2]['content']; ?></textarea><br>

<h1>問題4</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q4"><?php echo $questions[3]['content']; ?></textarea><br>

<h1>問題5</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q5"><?php echo $questions[4]['content']; ?></textarea><br>

<h1>問題6</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q6"><?php echo $questions[5]['content']; ?></textarea><br>

<h1>問題7</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q7"><?php echo $questions[6]['content']; ?></textarea><br>

<h1>問題8</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q8"><?php echo $questions[7]['content']; ?></textarea><br>

<h1>問題9</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q9"><?php echo $questions[8]['content']; ?></textarea><br>

<h1>問題10</h1>
<p>問題文</p>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q10"><?php echo $questions[9]['content']; ?></textarea><br>

<h1>問題11</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q11"><?php echo $questions[10]['content']; ?></textarea><br>

<h1>問題12</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q12"><?php echo $questions[11]['content']; ?></textarea><br>

<h1>問題13</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q13"><?php echo $questions[12]['content']; ?></textarea><br>

<h1>問題14</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q14"><?php echo $questions[13]['content']; ?></textarea><br>

<h1>問題15</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q15"><?php echo $questions[14]['content']; ?></textarea><br>

<h1>問題16</h1>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name = "q16"><?php echo $questions[15]['content']; ?></textarea><br>

<input type = "submit" class="btn btn-primary" value="変更">

</form>
</body>
</html>