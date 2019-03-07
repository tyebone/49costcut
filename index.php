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
    $bun = 'すべてのカテゴリーにおいて理想的な家計状況であると言えるでしょう。'.'<br>'.'これからもセブライフを楽しんでください。';
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

// $optionsテーブルからデータを取得する
// $o_sql = 'SELECT * FROM `options` ORDER BY `question_id`';
// $o_stmt = $dbh->prepare($o_sql);
// $o_stmt->execute();

// // $osという名前の配列を作る
// $os = [];
// while(true){
// $os = $o_stmt->fetch(PDO::FETCH_ASSOC);
// if($os == false){
//   break;
// }
// // $qsの中に$qという連想配列を作り、一件ずつレコードを追加していく
// $os[] = $o;
// }

// echo '<pre>';
// var_dump($questions_table)[0];
// echo '</pre>';

?>

<!DOCTYPE html>
<html lang="ja">

  <head>
    <title> セブ生活費シュミレーター</title>
    <!-- 必要なメタタグ -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/material-kit.css?v=2.0.5" rel="stylesheet" />
  </head>


  <header>
    <div class='text-center'>
      <a class="" href="index.php">TOP </a>
      <a class="" href="">About</a>
      <a class="" href="admin/login.php">管理者ログイン</a>
    </div>
  </header>


  <body class="bg-warning">
    <div class="container">
      <div class="text-center">
        <p>セブ島留学生のための生活費シュミレーター</p>
      </div>
      <div class="row">
        <div class='col-md-2'></div>
        <div class='col-md-8'>
          <img class="text-center img-fluid" src="image/top.jpg">
        </div>
        <div class='col-md-2'></div>
      </div>
      <br>

      <div class="row">
        <div class ="col-md-3"></div>
        <div class ='col-md-6 card card-body bg-light mb-3 border-dark'>
          <p>リーズナブルに楽しめるセブ島留学ですが、気づいたらお金を使いすぎた……ということになりがち。セブ島節約シュミレーターで、あなたの節約度を診断してみましょう。</p>
        </div>
        <div class ="col-md-3"></div>
      </div>
      <br>

      <form method="POST" action="index.php#回答">
        <section class="What inView -in rounded-lg row">
          <div class ="col-md-1"></div>
          <div class="col-md-10 card card-body bg-light mb-3 border-dark">
            <div class="row">
                <div class ="col-md-1"></div>
                <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                  <fieldset>
                    <p class =><span class="under">問題1</span></p>
                    <p class = "title"><?php echo $questions[0]['content']; ?></p>
                    <label><p><input type="radio" class="" id="question_1" checked="checked" name="Q1" value="1">&nbsp;29歳以下の男性&nbsp;&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_1" name="Q1" value="2">&nbsp;29歳以下の女性&nbsp;&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_1" name="Q1" value="3">&nbsp;30歳以上の男性&nbsp;&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_1" name="Q1" value="4">&nbsp;30歳以上の女性</p></label>
                  </fieldset>
                </div>
                <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題2</span></p>
                  <p class = "title"><?php echo $questions[1]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_2" checked="checked" name="Q2" value="0">&nbsp;食べない、もしくは学校で用意されている&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_2" name="Q2" value="1500">&nbsp;軽く食べる&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_2" name="Q2" value="4500">&nbsp;しっかり食べる</label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題3</span></p>
                  <p class = "title"><?php echo $questions[2]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_3" checked="checked" name="Q3" value="0">&nbsp;食べない、もしくは学校で用意されている&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_3" name="Q3" value="1500">&nbsp;軽く食べる&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_3" name="Q3" value="4500">&nbsp;しっかり食べる</label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題4</span></p>
                  <p class = "title"><?php echo $questions[3]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_4" checked="checked" name="Q4" value="0">&nbsp;食べない、もしくは学校で用意されている&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_4" name="Q4" value="1500">&nbsp;軽く食べる&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_4" name="Q4" value="9000">&nbsp;しっかり食べる</label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
            </div>
            <br>


            <div class="row">
              <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題5</span></p>
                  <p class = "title"><?php echo $$questions[4]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_5" checked="checked" name="Q5" value="0">&nbsp;徒歩&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_5" name="Q5" value="400">&nbsp;ジプニー&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_5" name="Q5" value="2000">&nbsp;バイクタクシー&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_5" name="Q5" value="3000">&nbsp;タクシー</p></label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
                <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                  <fieldset>
                    <p class ="font"><span class="under">問題6</span></p>
                    <p class = "title"><?php echo $questions[5]['content']; ?></p>
                    <label><p><input type="radio" class="" id="question_6" checked="checked" name="Q6" value="0">&nbsp;自分で手洗いする&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_6" name="Q6" value="1500">&nbsp;軽く食べる&nbsp;&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_6" name="Q6" value="9000">&nbsp;しっかり食べる</label>
                  </fieldset>
                </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題7</span></p>
                  <p class = "title"><?php echo $questions[6]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_7" checked="checked" name="Q7" value="0">&nbsp;Wi-Fiのみ利用する&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_7" name="Q7" value="500">&nbsp;少しチャージして利用する&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_7" name="Q7" value="1000">&nbsp;しっかりチャージして利用する</label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題8</span></p>
                  <p class = "title"><?php echo $questions[7]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_8" checked="checked" name="Q8" value="0">&nbsp;吸わない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_8" name="Q8" value="1500">&nbsp;一日一箱以下吸う&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_8" name="Q8" value="3000">&nbsp;一日一箱以上吸う</label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
              </div>
            <br>

            <div class="row">
            <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題9</span></p>
                  <p class = "title"><?php echo $questions[8]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_9" checked="checked" name="Q9" value="0">&nbsp;行かない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_9" name="Q9" value="2000">&nbsp;週1〜2回行く&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_9" name="Q9" value="6000">&nbsp;週3回以上行く</label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題10</span></p>
                  <p class = "title"><?php echo $questions[9]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_10" checked="checked" name="Q10" value="0">&nbsp;どこにも行かない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_10" name="Q10" value="2000">&nbsp;日帰りで遊びに行く&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_10" name="Q10" value="6000">&nbsp;泊まりで旅行に行く</label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題11</span></p>
                  <p class = "title"><?php echo $questions[10]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_11" checked="checked" name="Q11" value="0">&nbsp;行かない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_11" name="Q11" value="800">&nbsp;リーズナブルなところに行く&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_11" name="Q11" value="2000">&nbsp;高級スパに行く</label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題12</span></p>
                  <p class = "title"><?php echo $questions[11]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_12" checked="checked" name="Q12" value="0">&nbsp;買わない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_12" name="Q12" value="1500">&nbsp;1〜2着買う&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_12" name="Q12" value="4000">&nbsp;3着以上買いたい</label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題13</span></p>
                  <p class = "title"><?php echo $questions[12]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_13" checked="checked" name="Q13" value="0">&nbsp;通わない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_13" name="Q13" value="1000">&nbsp;1つだけ通う&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_13" name="Q13" value="3000">&nbsp;2つ以上通う</label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題14</span></p>
                  <p class = "title"><?php echo $questions[13]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_14" checked="checked" name="Q14" value="0">&nbsp;行かない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_14" name="Q14" value="2000">&nbsp;週1〜2回行きたい&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_14" name="Q14" value="6000">&nbsp;週3回以上行きたい</label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
              <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題15</span></p>
                  <p class = "title"><?php echo $questions[14]['content']; ?></p>
                  <label><p><input type="radio" class="" id="question_15" checked="checked" name="Q15" value="0">&nbsp;行かない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_15" name="Q15" value="2000">&nbsp;一度は行きたい&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_15" name="Q15" value="8000">&nbsp;それ以上行きたい</label>
                </fieldset>
              </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-1"></div>
                <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                  <fieldset>
                    <p class ="font"><span class="under">問題16</span></p>
                    <p class = "title"><?php echo $questions[15]['content']; ?></p>
                    <label><p><input type="radio" class="" id="question_16" checked="checked" name="Q16" value="0">&nbsp;買わない&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_16" name="Q16" value="2000">&nbsp;自分用に少し欲しい&nbsp;&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_16" name="Q16" value="4000">&nbsp;友達にもたくさん買いたい</label>
                  </fieldset>
                </div>
              <div class ="col-md-1"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-3"></div>
              <div class ='col-md-6 text-center'>
                <input type = "submit" class="btn btn-primary" value="結果表示">
              </div>
              <div class ="col-md-3"></div>
            </div>
          </div>
          <div class ="col-md-1"></div>
        </div>
        </section>
      </form>


      <?php if(!empty($_POST)): ?>
        <div id="回答" class="all_area box-sizing">
          <div class="row">
            <div class ="col-md-3"></div>
              <div class ='col-md-6 card card-body bg-light mb-3 border-dark text-center'>
              <!-- 節約度別の説明文 -->
                <h2>あなたの節約度は・・・</h2>
                <h3><strong class="text-danger"><?php echo $your_type ;?></strong>&nbsp;&nbsp;&nbsp;レベルです</h3>
              </div>
            <div class ="col-md-3"></div>
          </div>
          <div class="chart_area1x">
            <div class="chart1and2 row">
              <div class="col-md-1 text-center"></div>
              <div id="result_chart1" class="col-md-5 text-center">
                <!-- 比較グラフ１ -->
                <canvas id = "pieChart1" width="400" height="400"></canvas>
              </div>
              <div id="result_chart2" class="col-md-5 text-center">
                <!-- 比較グラフ２ -->
                <canvas id = "pieChart2" width="400" height="400"></canvas>
              </div>
              <div class="col-md-1 text-center"></div>
            </div>
          <div class="result_sentence1"></div>
          </div>

          <div class="chart_area2 row text-center">
            <div class="col-md-2"></div>
            <div id='barChartarea' class="col-md-8">
            <!-- ラインチャート -->
              <div class="row">
                <div class ="col-md-1"></div>
                <div class ='col-md-10 card card-body bg-light mb-3 border-dark'>
                  <!-- 節約度別の説明文 -->
                  <p><?php echo $bun?></p>
                </div>
                <div class ="col-md-1"></div>
              </div>
              <canvas id = "barChart" width="400" height="200"></canvas>
            </div>
          </div>
              <div class="text-center row border-box">
                <div class ="col-md-3"></div>
                <div class ='col-md-6 text-center'>
                  <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="large" data-text="セブ生活費シュミレーター
                    私の診断結果は節約の<?php echo $your_type ;?>です。" data-url="http://localhost/49_CostCut/index.php" data-show-count="false">シェアする</a>
                  <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                </div>
              </div>
            <div class ="col-md-3"></div>
        </div>

      <?php endif; ?>

    </div>
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