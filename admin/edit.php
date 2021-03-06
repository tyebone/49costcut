<?php
//session（セッション）を使うときは一番最初に書く
session_start();
require('../dbconnect.php');

// if(!empty($_GET)){
//     echo '変更が完了しました';
// }

 if (!isset($_SESSION['49_CostCut'])) {
        header('Location: login.php');
        exit();
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

$q_data = [];
$o_data = [];

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
// echo '<pre>';
// var_dump($_data);
// echo '</pre>';

    }

    for($o = 4; $o < 49; $o++){
    $o_sql = '
    UPDATE `options`
    SET `content` = ?,
        `price` = ?
    WHERE `question_id` = ?
    ';
    $o_data = [$_POST['o'.$o],$_POST['o_pr_'.$o],$o];
    $o_stmt = $dbh->prepare($o_sql);
    $o_stmt->execute($o_data);

    }
    header('Location: edit.php');
}



?>

<!DOCTYPE html>
<html>
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
<!-- ヘッダー部分を取り入れました -->
<?php include('../navbar.php'); ?>
<form method="POST" action="edit.php">
    <div class = "container">
    <br><br>
<!-- 問題2 -->
    <div class = "row">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題2</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q2"><?php echo $questions[1]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o4"><?php echo $options[4]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o5"><?php echo $options[5]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o6"><?php echo $options[6]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_4" value ="<?php echo $options[4]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_5" value = "<?php echo $options[5]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_6" value = "<?php echo $options[6]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題3 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題3</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q3"><?php echo $questions[2]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o7"><?php echo $options[7]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o8"><?php echo $options[8]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o9"><?php echo $options[9]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_7" value ="<?php echo $options[7]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_8" value = "<?php echo $options[8]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_9" value = "<?php echo $options[9]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題4 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題4</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q4"><?php echo $questions[3]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o10"><?php echo $options[10]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o11"><?php echo $options[11]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o12"><?php echo $options[12]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_10" value ="<?php echo $options[10]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_11" value = "<?php echo $options[11]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_12" value = "<?php echo $options[12]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題5 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題5</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q5"><?php echo $questions[4]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o13"><?php echo $options[13]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o14"><?php echo $options[14]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o15"><?php echo $options[15]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_13" value ="<?php echo $options[13]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_14" value = "<?php echo $options[14]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_15" value = "<?php echo $options[15]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題6 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題6</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q6"><?php echo $questions[5]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o16"><?php echo $options[16]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o17"><?php echo $options[17]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o18"><?php echo $options[18]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_16" value ="<?php echo $options[16]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_17" value = "<?php echo $options[17]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_18" value = "<?php echo $options[18]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題7 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題7</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q7"><?php echo $questions[6]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o19"><?php echo $options[19]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o20"><?php echo $options[20]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o21"><?php echo $options[21]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_19" value ="<?php echo $options[19]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_20" value = "<?php echo $options[20]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_21" value = "<?php echo $options[21]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題8 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題8</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q8"><?php echo $questions[7]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o22"><?php echo $options[22]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o23"><?php echo $options[23]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o24"><?php echo $options[24]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_22" value ="<?php echo $options[22]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_23" value = "<?php echo $options[23]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_24" value = "<?php echo $options[24]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題9 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題9</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q9"><?php echo $questions[8]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o25"><?php echo $options[25]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o26"><?php echo $options[26]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o27"><?php echo $options[27]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_25" value ="<?php echo $options[25]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_26" value = "<?php echo $options[26]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_27" value = "<?php echo $options[27]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題10 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題10</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q10"><?php echo $questions[9]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o28"><?php echo $options[28]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o29"><?php echo $options[29]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o30"><?php echo $options[30]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_28" value ="<?php echo $options[28]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_29" value = "<?php echo $options[29]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_30" value = "<?php echo $options[30]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題11 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題11</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q11"><?php echo $questions[10]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o31"><?php echo $options[31]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o32"><?php echo $options[32]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o33"><?php echo $options[33]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_31" value ="<?php echo $options[31]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_32" value = "<?php echo $options[32]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_33" value = "<?php echo $options[33]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題12 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題12</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q12"><?php echo $questions[11]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o34"><?php echo $options[34]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o35"><?php echo $options[35]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o36"><?php echo $options[36]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_34" value ="<?php echo $options[34]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_35" value = "<?php echo $options[35]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_36" value = "<?php echo $options[36]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題13 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題13</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q13"><?php echo $questions[12]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o37"><?php echo $options[37]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o38"><?php echo $options[38]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o39"><?php echo $options[39]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_37" value ="<?php echo $options[37]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_38" value = "<?php echo $options[38]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_39" value = "<?php echo $options[39]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題14 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題14</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q14"><?php echo $questions[13]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o40"><?php echo $options[40]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o41"><?php echo $options[41]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o42"><?php echo $options[42]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_43" value ="<?php echo $options[40]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_44" value = "<?php echo $options[41]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_44" value = "<?php echo $options[42]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題15 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題15</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q15"><?php echo $questions[14]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o43"><?php echo $options[43]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o44"><?php echo $options[44]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o45"><?php echo $options[45]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_43" value ="<?php echo $options[43]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_44" value = "<?php echo $options[44]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_44" value = "<?php echo $options[45]['price']; ?>">
        </div>
    </div>
    <br><br><br><br>

<!-- 問題16 -->
    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <h2>問題16</h2>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = "col-md-11">
            <p>問題</p>
            <textarea name = "q16"><?php echo $questions[15]['content']; ?></textarea><br>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答1</p>
            <textarea name = "o46"><?php echo $options[46]['content']; ?></textarea>
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答2</p>
            <textarea name = "o47"><?php echo $options[47]['content']; ?></textarea>
        </div>

        <div class = "col-md-1"></div>
        <div class = 'col-md-3 mx-auto'>
            <p>回答3</p>
            <textarea name = "o48"><?php echo $options[48]['content']; ?></textarea>
        </div>
    </div>
    <br>

    <div class = "row mx-auto">
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答1の値段</p>
            <input type = 'number' name = "o_pr_46" value ="<?php echo $options[46]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答2の値段</p>
            <input type = 'number' name = "o_pr_47" value = "<?php echo $options[47]['price']; ?>">
        </div>
        <div class = "col-md-1"></div>
        <div class = 'col-md-3 center-block'>
            <p>回答3の値段</p>
            <input type = 'number' name = "o_pr_48" value = "<?php echo $options[48]['price']; ?>">
        </div>
    </div>
    <br><br><br>
              <div class="row mx-auto">
              <div class ='col-md-6 text-center mx-auto'>
                <input type = "submit" class="btn rounded-0 edit-btn" value="&nbsp;変更する&nbsp;">
              </div>
            </div>
    <br><br>
    </div>
</form>
</body>
</html>