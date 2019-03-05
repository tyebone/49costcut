 <?php
require('dbconnect.php');

date_default_timezone_set('Asia/Manila');
$today = date("Y/m/d");

// POST送信だったら
//$answersテーブルが定義される
if (!empty($_POST)){

  $answers = [];
  for ($i = 1; $i < 17; $i++) {
    $key = 'Q' . $i;
    //この時点で、$answers[Q1]とか$answer[Q2]とかの数字が代入される
    $answers[$key] = intval($_POST[$key]);
}

  // 年齢と性別(Q1)
  // $typeには$answers['Q1']が入る
  $type = $answers['Q1'];

  // 識別コード
  //time関数は投稿した時間、rand関数はランダムの数列が代入
  $code = time() . '_' . rand();


  // カテゴリー分け
  //一旦、0入れとく
  $category = 0;
  $category1 = 0;
  $category2 = 0;
  $category3 = 0;
  $category4 = 0;
  $category5 = 0;
  $category6 = 0;

//$keyが['Q1']のときは無視、それぞれ費目ごとに代入
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

// 一件ずつ、SQLのanswersテーブルに代入
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
  $cyoujin = '超人';
  $cyoujin_bun = 'すべてのカテゴリーにおいて理想的な家計状況であると言えるでしょう。'.'<br>'.'これからもセブライフを楽しんでください。';

  $tatujin = '達人';
  $tatujin_bun = 'たまにハメを外してしまうことがありますが、胸を張れる家計状況であると言えるでしょう。'.'<br>'.'節約の仕方を周りの人にアドバイスしてあげましょう。';

  $futu = '普通';
  $futu_bun = 'まさに平均点。さまざまなカテゴリーで改善の余地があります。'.'<br>'.'さらなる高みを目指してください。';

  $shiroto = '素人';
  $shiroto_bun = '楽しんでいますが、このままだと、財政的に破綻します。'.'<br>'.'これからは気を引き締めて節約活動に励みましょう。';

  $syoshinsya = '初心者';
  $syoshinsya_bun = 'ヘタしたら日本にいるときよりも、浪費しているのではないでしょうか。'.'<br>'.'財布の中身と相談してセブライフを楽しみましょう。';

  if ($your_price < 15000) {
    $your_price = $cyoujin;
    $bun = $cyoujin_bun;
    }else if ($your_price < 22500) {
      $your_price = $tatujin;
      $bun = $tatujin_bun;
    }else if ($your_price < 30000) {
      $your_price = $futu;
      $bun = $futu_bun;
    }else if ($your_price < 45000) {
      $your_price = $shiroto;
      $bun = $shiroto_bun;
    }else{
      $your_price = $syoshinsya;
      $bun = $syoshinsya_bun;
    }


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
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
  </head>


  <header>
    <div class='text-center'>
      <a class="" href="index.php">TOP </a>
      <a class="" href="">About</a>
      <a class="" href="admin/register.php">管理者ログイン</a>
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
              <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class =><span class="under">問題1</span></p>
                  <p class = "title">年齢と性別を選んでください</p>
                  <label><p><input type="radio" class="" id="question_1" checked="checked" name="Q1" value="1">&nbsp;29歳以下の男性&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_1" name="Q1" value="2">&nbsp;29歳以下の女性&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_1" name="Q1" value="3">&nbsp;30歳以上の男性&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_1" name="Q1" value="4">&nbsp;30歳以上の女性</p></label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
              <div class ='col-md-8 alert alert-secondary'>
                <fieldset>
                  <p class ="font"><span class="under">問題2</span></p>
                  <p class = "title">朝食をどれぐらい食べますか</p>
                  <label><p><input type="radio" class="" id="question_2" checked="checked" name="Q2" value="0">&nbsp;食べない、もしくは学校で用意されている&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_2" name="Q2" value="1500">&nbsp;軽く食べる&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_2" name="Q2" value="4500">&nbsp;しっかり食べる</label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題3</span></p>
                  <p class = "title">昼食をどれぐらい食べますか</p>
                  <label><p><input type="radio" class="" id="question_3" checked="checked" name="Q3" value="0">&nbsp;食べない、もしくは学校で用意されている&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_3" name="Q3" value="1500">&nbsp;軽く食べる&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_3" name="Q3" value="4500">&nbsp;しっかり食べる</label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題4</span></p>
                  <p class = "title">夕食をどれぐらい食べますか</p>
                  <label><p><input type="radio" class="" id="question_4" checked="checked" name="Q4" value="0">&nbsp;食べない、もしくは学校で用意されている&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_4" name="Q4" value="1500">&nbsp;軽く食べる&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_4" name="Q4" value="9000">&nbsp;しっかり食べる</label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>


            <div class="row">
              <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題5</span></p>
                  <p class = "title">おもな交通手段はなんですか</p>
                  <label><p><input type="radio" class="" id="question_5" checked="checked" name="Q5" value="0">&nbsp;徒歩&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_5" name="Q5" value="400">&nbsp;ジプニー&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_5" name="Q5" value="2000">&nbsp;バイクタクシー&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_5" name="Q5" value="3000">&nbsp;タクシー</p></label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
                <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                  <fieldset>
                    <p class ="font"><span class="under">問題6</span></p>
                    <p class = "title">洗濯の頻度はどれぐらいですか</p>
                    <label><p><input type="radio" class="" id="question_6" checked="checked" name="Q6" value="0">&nbsp;自分で手洗いする&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_6" name="Q6" value="1500">&nbsp;軽く食べる&nbsp;&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_6" name="Q6" value="9000">&nbsp;しっかり食べる</label>
                  </fieldset>
                </div>
              <div class ="col-md-8"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題7</span></p>
                  <p class = "title">携帯電話をどれぐらい利用しますか</p>
                  <label><p><input type="radio" class="" id="question_7" checked="checked" name="Q7" value="0">&nbsp;Wi-Fiのみ利用する&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_7" name="Q7" value="500">&nbsp;少しチャージして利用する&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_7" name="Q7" value="1000">&nbsp;しっかりチャージして利用する</label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題8</span></p>
                  <p class = "title">タバコを吸いますか</p>
                  <label><p><input type="radio" class="" id="question_8" checked="checked" name="Q8" value="0">&nbsp;吸わない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_8" name="Q8" value="1500">&nbsp;一日一箱以下吸う&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_8" name="Q8" value="3000">&nbsp;一日一箱以上吸う</label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
              </div>
            <br>

            <div class="row">
            <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題9</span></p>
                  <p class = "title">飲み会に行きますか</p>
                  <label><p><input type="radio" class="" id="question_9" checked="checked" name="Q9" value="0">&nbsp;行かない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_9" name="Q9" value="2000">&nbsp;週1〜2回行く&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_9" name="Q9" value="6000">&nbsp;週3回以上行く</label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題10</span></p>
                  <p class = "title">週末はどのように過ごしますか</p>
                  <label><p><input type="radio" class="" id="question_10" checked="checked" name="Q10" value="0">&nbsp;どこにも行かない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_10" name="Q10" value="2000">&nbsp;日帰りで遊びに行く&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_10" name="Q10" value="6000">&nbsp;泊まりで旅行に行く</label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題11</span></p>
                  <p class = "title">マッサージに行きますか</p>
                  <label><p><input type="radio" class="" id="question_11" checked="checked" name="Q11" value="0">&nbsp;行かない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_11" name="Q11" value="800">&nbsp;リーズナブルなところに行く&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_11" name="Q11" value="2000">&nbsp;高級スパに行く</label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題12</span></p>
                  <p class = "title">現地の服を買いますか</p>
                  <label><p><input type="radio" class="" id="question_12" checked="checked" name="Q12" value="0">&nbsp;買わない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_12" name="Q12" value="1500">&nbsp;1〜2着買う&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_12" name="Q12" value="4000">&nbsp;3着以上買いたい</label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題13</span></p>
                  <p class = "title">習い事やジムに通いますか</p>
                  <label><p><input type="radio" class="" id="question_13" checked="checked" name="Q13" value="0">&nbsp;通わない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_13" name="Q13" value="1000">&nbsp;1つだけ通う&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_13" name="Q13" value="3000">&nbsp;2つ以上通う</label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題14</span></p>
                  <p class = "title">カジノに行きますか</p>
                  <label><p><input type="radio" class="" id="question_14" checked="checked" name="Q14" value="0">&nbsp;行かない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_14" name="Q14" value="2000">&nbsp;週1〜2回行きたい&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_14" name="Q14" value="6000">&nbsp;週3回以上行きたい</label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
              <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                <fieldset>
                  <p class ="font"><span class="under">問題15</span></p>
                  <p class = "title">夜のスポットに遊びに行きますか</p>
                  <label><p><input type="radio" class="" id="question_15" checked="checked" name="Q15" value="0">&nbsp;行かない&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_15" name="Q15" value="2000">&nbsp;一度は行きたい&nbsp;&nbsp;&nbsp;</p></label>
                  <label><p><input type="radio" class="" id="question_15" name="Q15" value="8000">&nbsp;それ以上行きたい</label>
                </fieldset>
              </div>
              <div class ="col-md-2"></div>
            </div>
            <br>

            <div class="row">
              <div class ="col-md-2"></div>
                <div class ='col-md-8 card card-body bg-light mb-3 border-dark'>
                  <fieldset>
                    <p class ="font"><span class="under">問題16</span></p>
                    <p class = "title">おみやげを買いますか</p>
                    <label><p><input type="radio" class="" id="question_16" checked="checked" name="Q16" value="0">&nbsp;買わない&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_16" name="Q16" value="2000">&nbsp;自分用に少し欲しい&nbsp;&nbsp;&nbsp;</p></label>
                    <label><p><input type="radio" class="" id="question_16" name="Q16" value="4000">&nbsp;友達にもたくさん買いたい</label>
                  </fieldset>
                </div>
              <div class ="col-md-2"></div>
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
        </section>
      </form>


      <?php if(!empty($_POST)): ?>
        <div class="row">
          <div class ="col-md-3"></div>
            <div class ='col-md-6 card card-body bg-light mb-3 border-dark text-center'>
            <!-- 節約度別の説明文 -->
              <p>あなたの節約度は・・・</p>
              <p><strong class="text-danger"><?php echo $your_price?></strong>&nbsp;&nbsp;&nbsp;レベルです</p>
            </div>
          <div class ="col-md-3"></div>
        </div>
        <div id="回答" class="all_area">
          <div class="chart_area1x">
            <div class="chart1and2 row">
              <div class="col-md-1 text-center"></div>
              <div id="result_chart1" class="col-md-5 text-center">
                <!-- 比較グラフ１ -->
                <p>円グラフ1</p>
                <canvas id = "pieChart1"></canvas>
              </div>
              <div id="result_chart2" class="col-md-5 text-center">
                <!-- 比較グラフ２ -->
                <p>円グラフ2</p>
                <canvas id = "pieChart2"></canvas>
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
                <div class ="col-md-3"></div>
                <div class ='col-md-6 card card-body bg-light mb-3 border-dark'>
                  <!-- 節約度別の説明文 -->
                  <p><?php echo $bun?></p>
                </div>
                <div class ="col-md-3"></div>
              </div>
              <p>チャートグラフ</p>
              <canvas id = "barChart"></canvas>
            </div>
          </div>
          <div class='text-center'>
            <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="large" data-text="セブ生活費シュミレーター  あなたの診断結果は〇〇です。" data-url="http://localhost/49_CostCut/index.php" data-show-count="false">シェアする</a>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
          </div>
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