<?php
// DB接続
$dsn = 'mysql:dbname=49_CostCut;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->query('SET NAMES utf8');

// 回答の取得
//$answersという連想配列をつくる
$answers = [];

//もしもPOST送信されたら
if(!empty($_POST)){
	for ($i = 1; $i < 10; $i++) {
//$key変数に問題番号を代入
  	$key = 'Q' . $i;
  	$answers[$key] = $_POST[$key];
echo '<pre>';
var_dump($answers);
echo '</pre>';

}
// Bool boolean
// true/false
// false = 0
// true = 1

// Q9の合計値計算
// $answer_9 = array_sum($answer_9);
// 結果表示させる機能
// print_r ($answer_9);

//一旦コメントアウト
//$answer_9 = array_sum($answers['Q9']);
//echo '<pre>';
//var_dump($answer_9);
//echo '</pre>';


// 識別コード
$code = time() . '_' . rand();
// 年齢と性別(Q1)を$typeに代入
$type = $answers['Q1'];

// カテゴリー分け
//例えば、$category = 1は食費、2は交通費、3は交際費みたいな
$category = 0;
foreach ($answers as $key => $value) {
  if($key == 'Q1'){
  	continue;
  }elseif ($key == 'Q2' || $key == 'Q3' || $key == 'Q4'){
    $category = 1;
  }elseif($key == 'Q5') {
    $category = 2;
  }elseif($key == 'Q6' || $key == 'Q9'){
    $category = 3;
  }elseif($key == 'Q7' || $key = 'Q8'){
  	$category = 4;
  }else{
  	$category = 6;
  }
  $sql = 'INSERT INTO`answers`(`category`,`price`,`code`,`type`) VALUES (?, ?, ?, ?)';
  $data = [$category,$value,$code,$type];
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
}

//


// print "{$value}"
// echo '<pre>';
// var_dump($category);
// echo '</pre>';

echo '<pre>';
var_dump(compact('answers', 'key', 'code', 'type', 'value', 'category'));
echo '</pre>';
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <title>
    セブ生活費シュミレーター
  </title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body class="container ">
  <div class="">
    <a class="" href="">TOP </a>
    <a class="" href="">About</a>
    <a class="" href="">管理者ログイン</a>
  </div>
  <div class=" mx-auto d-block">
    <h1><a href="index.php"><img src="image/IMG_4821.JPG" alt=""></a></h1>
  </div>
  <div class="">
    <h2>サイト説明文</h2>
    <p>aaaaaaaaaaaaaaaaa</p>
  </div>

</body>
<div class="row justify-content-center">
  <form method="POST" action="index.php" >
    <div class="col-6 border ">
      <fieldset>
        <legend>Q1.年齢と性別を選んでください</legend>
          <label><input type="radio" class="" id="question_1" checked="checked" name="Q1" value="1">29歳以下の男性</label>
          <label><input type="radio" class="" id="question_1" name="Q1" value="2">29歳以下の女性</label>
          <label><input type="radio" class="" id="question_1" name="Q1" value="3">30歳以上の男性</label>
          <label><input type="radio" class="" id="question_1" name="Q1" value="4">30歳以上の女性</label>
      </fieldset>
    </div>
    <div class="col-6 border ">
      <fieldset>
        <legend>Q2.朝食はどれぐらい食べますか</legend>
          <label><input type="radio" class="" id="question_2" checked="checked" name="Q2" value="0">食べないor学校で用意されている</label>
          <label><input type="radio" class="" id="question_2" name="Q2" value="4500">かる〜く食べる</label>
          <label><input type="radio" class="" id="question_2" name="Q2" value="9000">しっかり食べる</label>
      </fieldset>
    </div>
    <div class="col-6 border ">
      <fieldset>
        <legend>Q3.昼食はどれぐらい食べますか</legend>
          <label><input type="radio" class="" id="question_3" checked="checked" name="Q3" value="0">食べないor学校で用意されている</label>
          <label><input type="radio" class="" id="question_3" name="Q3" value="4500">かる〜く食べる</label>
          <label><input type="radio" class="" id="question_3" name="Q3" value="9000">しっかり食べる</label>
      </fieldset>
    </div>
    <div class="col-6 border ">
      <fieldset>
        <legend>Q4.夕食はどれぐらい食べますか</legend>
          <label><input type="radio" class="" id="question_4" checked="checked" name="Q4" value="0">食べないor学校で用意されている</label>
          <label><input type="radio" class="" id="question_4" name="Q4" value="4500">かる〜く食べる</label>
          <label><input type="radio" class="" id="question_4" name="Q4" value="9000">しっかり食べる</label>
      </fieldset>
    </div>
    <div class="col-6 border ">
      <fieldset>
        <legend>Q5.主な交通手段はなんですか</legend>
          <label><input type="radio" class="" id="question_5" checked="checked" name="Q5" value="0">徒歩</label>
          <label><input type="radio" class="" id="question_5" name="Q5" value="420">ジプニー</label>
          <label><input type="radio" class="" id="question_5" name="Q5" value="4800">バイクタクシー</label>
          <label><input type="radio" class="" id="question_5" name="Q5" value="9000">タクシー</label>
      </fieldset>
    </div>
    <div class="col-6 border ">
      <fieldset>
        <legend>Q6.洗濯の頻度はどれくらいですか</legend>
          <label><input type="radio" class="" id="question_6" checked="checked" name="Q6" value="0">自分で手洗い</label>
          <label><input type="radio" class="" id="question_6" name="Q6" value="1000">週1〜2回</label>
          <label><input type="radio" class="" id="question_6" name="Q6" value="2500">週3回以上</label>
      </fieldset>
    </div>
    <div class="col-6 border ">
      <fieldset>
        <legend>Q7.あなたの土日の過ごし方</legend>
          <label><input type="radio" class="" id="question_7" checked="checked" name="Q7" value="0">どこにも行かない</label>
          <label><input type="radio" class="" id="question_7" name="Q7" value="10000">日帰り旅行に行きたい</label>
          <label><input type="radio" class="" id="question_7" name="Q7" value="16000">泊まりで旅行に行きたい</label>
      </fieldset>
    </div>
    <div class="col-6 border ">
      <fieldset>
        <legend>Q8.飲み会は</legend>
          <label><input type="radio" class="" id="question_8" checked="checked" name="Q8" value="0">行かない</label>
          <label><input type="radio" class="" id="question_8" name="Q8" value="1200"></label>ときどき行く
          <label><input type="radio" class="" id="question_8" name="Q8" value="4000">よく行く</label>
      </fieldset>
    </div>
    <div class="col-7 border ">
      <fieldset>
        <legend>Q9.当てはまるものを選択してください(複数選択可)</legend>
          <label class=""><input class="" type="checkbox" class="" id="question_9" name="Q9" value="5000" checked="checked"> おみやげを買いたい</label>
          <label class=""><input class="" type="checkbox" class="" id="question_9" name="Q9" value="5000"> 現地で服が買いたい</label>
          <label class=""><input class="" type="checkbox" class="" id="question_9" name="Q9" value="3000"> 習い事を始めたい</label>
          <label class=""><input class="" type="checkbox" class="" id="question_9" name="Q9" value="5000"> マッサージに行きたい</label>
          <label class=""><input class="" type="checkbox" class="" id="question_9" name="Q9" value="3000"> タバコを吸いたい</label>
          <label class=""><input class="" type="checkbox" class="" id="question_9" name="Q9" value="4000"> カジノに行きたい</label>
          <label class=""><input class="" type="checkbox" class="" id="question_9" name="Q9" value="8000"> 夜のスポットに遊びに行きたい</label>
      </fieldset>
    </div>
    <div class="">

      <input type="submit" value="結果表示">
<!--       <a href="" class="" value="">
        結果表示
      </a> -->
    </div>
  </form>
</div>

    <footer>
    	<p>(c)sunmigel drunkers</p>
    </footer>
</body>
</html>