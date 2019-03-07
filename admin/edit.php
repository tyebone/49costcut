<?php
require('../dbconnect.php');


// $q = '';
if(!empty($_POST)){

$q_content = $_POST['input_question'];
//オーナーIDを代入して

// INSERT文にて、IDはオートで入る
	$sql = 'INSERT INTO `questions`(`content`,`created`,`updated`)VALUES(?,NOW(),NOW())';
    $data = [$q_content,owner0d];
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
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

<h1>問題1</h1>
<p>問題文</p>
<!-- textarea内にユーザーが記述したものはそのままvalueになる -->
<textarea name="input_question" placeholder="hoge"></textarea><br>
<p>問題1の回答1</p>
<textarea></textarea>
<textarea></textarea>
<p>回答1の値段</p>
<textarea input type="number"></textarea>
<p>問題1の回答2</p>
<textarea></textarea>
<p>回答2の値段</p>
<textarea input type="number"></textarea>
<p>問題1の回答3</p>
<textarea></textarea>
<p>回答3の値段</p>
<textarea input type="number"></textarea>

<input type = "submit" class="btn btn-primary" value="結果表示">


</form>
</body>
</html>