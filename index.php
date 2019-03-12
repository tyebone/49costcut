<?php
// dbconnect.phpを利用
require('dbconnect.php');

// date_default_timezone_set関数で時間を指定
date_default_timezone_set('Asia/Manila');
// $today = date("Y/m/d");

// ユーザーによる回答はform内のmethod="POST"によってPOST送信される
// そしてPOST送信だったら$answersテーブルが定義される
// answersテーブルは配列である
$answers = [];
if (!empty($_POST)){
  // $iは問題文の数だけ増えていく
  for ($i = 1; $i < 17; $i++) {
    //$keyにQ1,Q2という引数が代入される
    $key = 'Q' . $i;
    //$answers[Q1]、$answer[Q2]など数字が代入される
    // intval関数は変数を整数型に変換する
    $answers[$key] = intval($_POST[$key]);
}

  // 年齢と性別(Q1)
  // $typeには$answers['Q1']が入る
  $type = $answers['Q1'];

  // 識別コード
  //time関数は投稿した時間、rand関数はランダムの数列が代入
  $code = time() . '_' . rand();

  // カテゴリー分け
  $category = 0;
  $category1 = 0;
  $category2 = 0;
  $category3 = 0;
  $category4 = 0;
  $category5 = 0;
  $category6 = 0;

//$keyが['Q1']のときは無視、それぞれ費目ごとにvalue（値段）が代入
foreach ($answers as $key => $value) {
    if ($key == 'Q1') {
      continue;
    }elseif($key == 'Q2' || $key == 'Q3' || $key == 'Q4') {
      $category = 1;  // 食費
    }elseif ($key == 'Q5') {
      $category = 2;  // 交通費
    }elseif ($key == 'Q6' || $key == 'Q13') {
      $category = 3;  // 生活費
    }elseif ($key == 'Q7' || $key == 'Q12' || $key == 'Q14') {
      $category = 4;  // 交際費
    }elseif ($key == 'Q8' || $key == 'Q10' || $key == 'Q6') {
      $category = 5;  // 衣服・美容
    }else {
      $category = 6;  // 趣味・娯楽
    }

// answersテーブルのレコードを一件ずつ、SQLのanswersテーブルに代入
    $sql = 'INSERT INTO`answers`(`category`, `price`, `code`,`type`) VALUES (?, ?, ?, ?)';
    $data = [$category,$value,$code,$type];
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

//カテゴリーごとに値が代入
    if ($category == 1){
      $category1 += $value;
    }elseif ($category == 2) {
      $category2 += $value;
    }elseif ($category == 3) {
      $category3 += $value;
    }elseif ($category == 4) {
      $category4 += $value;
    }elseif ($category == 5) {
      $category5 += $value;
    }elseif ($category == 6) {
      $category6 += $value;
    }
  }

  // 円グラフ2にデータを送るためにで使う
  $category = [$category1, $category2,  $category3,  $category4, $category5,  $category6];

  // チャートグラフ表示
  $sql = 'SELECT `code`, SUM(`price`) AS `price_sum` FROM `answers` GROUP BY `code`';
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $prices = [];
  while (true) {
    $price = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($price == false) {
        break;
    }
    $prices[] = $price;
  }


  // バーチャートへデータを送るためにpriceのみ抽出
  $price_sum = array_column($prices, 'price_sum');

  // 説明文表示のための合計値
  $your_price = array_sum($category);

  // 文字代入
  if ($your_price < 15000) {
    $your_type = '超人';
    $bun = 'すべてのカテゴリーにおいて理想的な家計状況であると言えるでしょう。これからもセブライフを楽しんでください。';
    }else if ($your_price < 22500) {
      $your_type = '達人';
      $bun = 'たまにハメを外してしまうことがありますが、胸を張れる家計状況であると言えるでしょう。'.'<br>'.'節約の仕方を周りの人にアドバイスしてあげましょう。';
    }else if ($your_price < 30000) {
      $your_type = '普通';
      $bun = 'まさに平均点。さまざまなカテゴリーで改善の余地があります。'.'<br>'.'さらなる高みを目指してください。';
    }else if ($your_price < 45000) {
      $your_type = '素人';
      $bun = '楽しんでいますが、このままだと、財政的に破綻します。'.'<br>'.'これからは気を引き締めて節約活動に励みましょう。';
    }else{
      $your_type = '初心者';
      $bun = 'ヘタしたら日本にいるときよりも、浪費しているのではないでしょうか。'.'<br>'.'財布の中身と相談してセブライフを楽しみましょう。';
    }
}

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

// $questionsテーブルからデータを取得する
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
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  </head>


  <body class=" mx-auto center-block">
    <header>

      <nav class="navbar">
        <a class="navbar-brand"></a>
        <div class="dropdown">
          <button type="button" id="dropdownMenuButton" class="btn btn-secondary rounded-0 menu-btn dropdown-toggle m-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            メニュー
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="z-index:9999">
            <a class="dropdown-item" href="index.php">TOP </a>
            <a class="dropdown-item" href="プロフィール/profile.html">About</a>
            <a class="dropdown-item" href="admin/login.php">管理者ログイン</a>
          </div>
        </div>
      </nav>
    </header>


<div class="all_area box-sizing">
  <section class=" mx-auto">
    <div class = "title-area">
    <img class="col-md-9 card card-body mx-auto rounded-0 border border-0 top-img" src="image/top_2145.jpg">
    </div>
  </section>
    <br>

      <!-- <div class="row">
        <div class ='col-md-8 card card-body bg-light mb-3 border-dark mx-auto'>
          <p>リーズナブルに楽しめるセブ島留学ですが、気づいたらお金を使いすぎた……ということになりがち。<br>セブ島節約シュミレーターで、あなたの節約度を診断してみましょう。</p>
        </div>
      </div>
      <br> -->

      <form method="POST" action="index.php#answer">
<!-- mx-autoは中央 -->
        <section class=" mx-auto">

          <div class="col-md-9 card card-body mx-auto rounded-0 border border-0 backcontainer">
            <br><br>

            <div class="row">
                <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                  <fieldset>
                    <p class =><span class="under">問題1</span></p>
                    <p class = "title"><?php echo $questions[0]['content']; ?></p>
                    <label><p><input type="radio" class="" id="question_1" checked="checked" name="Q1" value="1">&nbsp;<?php echo $options[0]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_1" name="Q1" value="2">&nbsp;<?php echo $options[1]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_1" name="Q1" value="3">&nbsp;<?php echo $options[2]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_1" name="Q1" value="4">&nbsp;<?php echo $options[3]['content']; ?></p></label>
                  </fieldset>
                </div>
            </div>
            <br>

            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題2</span></p>
                  <p class = "title"><?php echo $questions[1]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_2" checked="checked" name="Q2" value="<?php echo $options[4]['price']; ?>">&nbsp;<?php echo $options[4]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_2" name="Q2" value="<?php echo $options[5]['price']; ?>">&nbsp;<?php echo $options[5]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_2" name="Q2" value="<?php echo $options[6]['price']; ?>">&nbsp;<?php echo $options[6]['content']; ?></label>
                </fieldset>
              </div>
            </div>
            <br>

            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題3</span></p>
                  <p class = "title"><?php echo $questions[2]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_3" checked="checked" name="Q3" value="<?php echo $options[7]['price']; ?>">&nbsp;<?php echo $options[7]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_3" name="Q3" value="<?php echo $options[8]['price']; ?>">&nbsp;<?php echo $options[8]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_3" name="Q3" value="<?php echo $options[9]['price']; ?>">&nbsp;<?php echo $options[9]['content']; ?></label>
                </fieldset>
              </div>
            </div>
            <br>

            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題4</span></p>
                  <p class = "title"><?php echo $questions[3]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_4" checked="checked" name="Q4" value="<?php echo $options[10]['price']; ?>">&nbsp;<?php echo $options[10]['content']; ?>&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_4" name="Q4" value="<?php echo $options[11]['price']; ?>">&nbsp;<?php echo $options[11]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_4" name="Q4" value="<?php echo $options[12]['price']; ?>">&nbsp;<?php echo $options[12]['content']; ?></label>
                </fieldset>
              </div>
            </div>
            <br>


            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題5</span></p>
                  <p class = "title"><?php echo $questions[4]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_5" checked="checked" name="Q5" value="<?php echo $options[13]['price']; ?>">&nbsp;<?php echo $options[13]['content']; ?>&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_5" name="Q5" value="<?php echo $options[14]['price']; ?>">&nbsp;<?php echo $options[14]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_5" name="Q5" value="<?php echo $options[15]['price']; ?>">&nbsp;<?php echo $options[15]['content']; ?></p></label>
                </fieldset>
              </div>
            </div>
            <br>

            <div class="row">
                <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                  <fieldset>
                    <p class ="font"><span class="under">問題6</span></p>
                    <p class = "title"><?php echo $questions[5]['content']; ?></p>
                    <label><p><input type="radio" class="" id="question_6" checked="checked" name="Q6" value="<?php echo $options[16]['price']; ?>">&nbsp;<?php echo $options[16]['content']; ?>&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_6" name="Q6" value="<?php echo $options[17]['price']; ?>">&nbsp;<?php echo $options[17]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_6" name="Q6" value="<?php echo $options[18]['price']; ?>">&nbsp;<?php echo $options[18]['content']; ?></label>
                  </fieldset>
                </div>
            </div>
            <br>

            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題7</span></p>
                  <p class = "title"><?php echo $questions[6]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_7" checked="checked" name="Q7" value="<?php echo $options[19]['price']; ?>">&nbsp;<?php echo $options[19]['content']; ?>&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_7" name="Q7" value="<?php echo $options[20]['price']; ?>">&nbsp;<?php echo $options[20]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_7" name="Q7" value="<?php echo $options[21]['price']; ?>">&nbsp;<?php echo $options[21]['content']; ?></label>
                </fieldset>
              </div>
            </div>
            <br>

            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題8</span></p>
                  <p class = "title"><?php echo $questions[7]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_8" checked="checked" name="Q8" value="<?php echo $options[22]['price']; ?>">&nbsp;<?php echo $options[22]['content']; ?>&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_8" name="Q8" value="<?php echo $options[23]['price']; ?>">&nbsp;<?php echo $options[23]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_8" name="Q8" value="<?php echo $options[24]['price']; ?>">&nbsp;<?php echo $options[24]['content']; ?></label>
                </fieldset>
              </div>
              </div>
            <br>

            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題9</span></p>
                  <p class = "title"><?php echo $questions[8]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_9" checked="checked" name="Q9" value="<?php echo $options[25]['price']; ?>">&nbsp;<?php echo $options[25]['content']; ?>&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_9" name="Q9" value="<?php echo $options[26]['price']; ?>">&nbsp;<?php echo $options[26]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_9" name="Q9" value="<?php echo $options[27]['price']; ?>">&nbsp;<?php echo $options[27]['content']; ?></label>
                </fieldset>
              </div>
            </div>
            <br>

            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題10</span></p>
                  <p class = "title"><?php echo $questions[9]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_10" checked="checked" name="Q10" value="<?php echo $options[28]['price']; ?>">&nbsp;<?php echo $options[28]['content']; ?>&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_10" name="Q10" value="<?php echo $options[29]['price']; ?>">&nbsp;<?php echo $options[29]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_10" name="Q10" value="<?php echo $options[30]['price']; ?>">&nbsp;<?php echo $options[30]['content']; ?></label>
                </fieldset>
              </div>
            </div>
            <br>

            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題11</span></p>
                  <p class = "title"><?php echo $questions[10]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_11" checked="checked" name="Q11" value="<?php echo $options[31]['price']; ?>">&nbsp;<?php echo $options[31]['content']; ?>&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_11" name="Q11" value="<?php echo $options[32]['price']; ?>">&nbsp;<?php echo $options[32]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_11" name="Q11" value="<?php echo $options[33]['price']; ?>">&nbsp;<?php echo $options[33]['content']; ?></label>
                </fieldset>
              </div>
            </div>
            <br>

            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題12</span></p>
                  <p class = "title"><?php echo $questions[11]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_12" checked="checked" name="Q12" value="<?php echo $options[34]['price']; ?>">&nbsp;<?php echo $options[34]['content']; ?>&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_12" name="Q12" value="<?php echo $options[35]['price']; ?>">&nbsp;<?php echo $options[35]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_12" name="Q12" value="<?php echo $options[36]['price']; ?>">&nbsp;<?php echo $options[36]['content']; ?></label>
                </fieldset>
              </div>
            </div>
            <br>

            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題13</span></p>
                  <p class = "title"><?php echo $questions[12]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_13" checked="checked" name="Q13" value="<?php echo $options[37]['price']; ?>">&nbsp;<?php echo $options[37]['content']; ?>&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_13" name="Q13" value="<?php echo $options[38]['price']; ?>">&nbsp;<?php echo $options[38]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_13" name="Q13" value="<?php echo $options[39]['price']; ?>">&nbsp;<?php echo $options[39]['content']; ?></label>
                </fieldset>
              </div>
            </div>
            <br>

            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題14</span></p>
                  <p class = "title"><?php echo $questions[13]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_14" checked="checked" name="Q14" value="<?php echo $options[40]['price']; ?>">&nbsp;<?php echo $options[40]['content']; ?>&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_14" name="Q14" value="<?php echo $options[41]['price']; ?>">&nbsp;<?php echo $options[41]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_14" name="Q14" value="<?php echo $options[42]['price']; ?>">&nbsp;<?php echo $options[42]['content']; ?></label>
                </fieldset>
              </div>
            </div>
            <br>

            <div class="row">
              <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                <fieldset>
                  <p class ="font"><span class="under">問題15</span></p>
                  <p class = "title"><?php echo $questions[14]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_15" checked="checked" name="Q15" value="<?php echo $options[43]['price']; ?>">&nbsp;<?php echo $options[43]['content']; ?>&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_15" name="Q15" value="<?php echo $options[44]['price']; ?>">&nbsp;<?php echo $options[44]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_15" name="Q15" value="<?php echo $options[45]['price']; ?>">&nbsp;<?php echo $options[45]['content']; ?></label>
                </fieldset>
              </div>
            </div>
            <br>

            <div class="row">
                <div class ='col-md-10 card card-body mb-3 border-dark mx-auto rounded-0 border border-0 bg-color'>
                  <fieldset>
                    <p class ="font"><span class="under">問題16</span></p>
                    <p class = "title"><?php echo $questions[15]['content']; ?></p>
                    <label><p><input type="radio" class="" id="question_16" checked="checked" name="Q16" value="<?php echo $options[46]['price']; ?>">&nbsp;<?php echo $options[46]['content']; ?>&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_16" name="Q16" value="<?php echo $options[47]['price']; ?>">&nbsp;<?php echo $options[47]['content']; ?>&nbsp;&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_16" name="Q16" value="<?php echo $options[48]['price']; ?>">&nbsp;<?php echo $options[48]['content']; ?></label>
                  </fieldset>
                </div>
            </div>
            <br><br>
            </div>
          </div>
          <br><br>
          <div class="row">
              <div class ='col-md-6 text-center mx-auto'>
                <input type = "submit" class="btn rounded-0 submit-btn" value="&nbsp;診断する&nbsp;">
              </div>
            </div>
        </section>
        <br><br>
      </form>


<!-- ここから節約診断結果のページ -->
<?php if(!empty($_POST)): ?>
<div class="col-md-9 card card-body mx-auto rounded-0 border border-0 backcontaineranswer">

<div id="answer" class="all_area box-sizing">
  <div class="row">
    <div class ='col-md-6  mb-3 border-dark text-center mx-auto'>
    <!-- 節約度別の説明文 -->
    <br><br>
    <h3>あなたの節約度は</h3>
    <h1><strong class="text-danger"><?php echo $your_type ;?></strong>レベルです</h1>
    </div>
  </div>
  <div class="row">
                <div class ='col-md-9 mx-auto'>
                  <!-- 節約度別の説明文 -->
                  <p class = 'mondaibun'><?php echo $bun?></p>
                </div>
              </div>

<br><br>
  <div class="chart_area1x m-2">
  <div class="chart1and2 row">


<div id="result_chart1" class="col-md-5 text-center mx-auto">
<!-- 比較グラフ１ -->
  <canvas id = "pieChart1" width="400" height="400"></canvas>
</div>

<div id="result_chart2" class="col-md-5 text-center mx-auto">
                <!-- 比較グラフ２ -->
                <canvas id = "pieChart2" width="400" height="400"></canvas>
              </div>
            </div>
          <div class="result_sentence1"></div>
          </div>

          <div class="chart_area2 row text-center mx-auto">


            <div id='barChartarea' class="col-md-8 mx-auto">
            <!-- ラインチャート -->
              
              <canvas id = "barChart" width="400" height="200"></canvas>
            </div>
          </div>
              <div class="text-center row border-box">
                <div class ="col-md-3"></div>

                </div>
              </div>
            <div class ="col-md-3"></div>
            <br><br>
        </div>
        <br><br>
    <div class="row">
    <div class ='col-md-6 text-center mx-auto'>
    <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="btn rounded-0 share" data-size="large" data-text="セブ生活費シュミレーター私の診断結果は節約の<?php echo $your_type ;?>です。" data-url="http://localhost/49_CostCut/index.php" data-show-count="false">&nbsp;<i class="fab fa-twitter"></i>&nbsp;シェアする&nbsp;</a>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
  </div>
      <?php endif; ?>

    </div>
    </div>


<!--     <div class="row">
              <div class ='col-md-6 text-center mx-auto'>
                <input type = "submit" class="btn rounded-0 submit-btn" value="診断します">
              </div> -->


</div>
<br><br>
  </body>
    <script src="js/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <script src="js/pieChart1.js"></script>
      <script>
        showPie1(<?php echo $type; ?>);
      </script>
    <script src="js/pieChart2.js"></script>
      <script>
        showPie2(<?php echo json_encode($category); ?>);
      </script>
    <script src="js/barChart.js"></script>
      <script>
        showChart(<?php echo json_encode($price_sum); ?>);
      </script>
  <footer>
  </footer>
</html>