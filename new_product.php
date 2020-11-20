<?php

session_start();
session_regenerate_id(true);

//DB の接続情報（せつぞくじょうほう）
$db_name = 'zaiko2020_yse';
$host = 'localhost';
$user_name = 'zaiko2020_yse';
$password = '2020zaiko';
$dsn = "mysql:dbname={$db_name};host={$host};charset=utf8";
try {
	$pdo = new PDO($dsn, $user_name, $password);
} catch (PDOException $e) {
	exit;
}

function validate() {
    validateEmpty('title', '書籍名');
    validateEmpty('author', '著者名');
    validateEmpty('salesDate', '発売日');
    validateEmpty('price', '金額');
    validateEmpty('stock', '在庫数');
}

function validateEmpty($key, $label) {
    if (empty($_POST['book'][$key])) {
        $_SESSION['error'] = "{$label}を入力してください。";
        header('Location: new_product.php');
        exit;
    }
}

function addBook($data, $con) {
    $sql = "INSERT INTO books (isbn, title, author, salesDate, price, stock)
            VALUES ('', '{$data['title']}', '{$data['author']}',
                    '{$data['salesDate']}', '{$data['price']}', '{$data['stock']}');";
    $result = $con->query($sql);
    if (!$result) {
        $_SESSION['error'] = '追加に失敗しました。';
    } else {
        $_SESSION['error'] = '';
    }
}

function nextId($con) {
    $sql = "SELECT max(id) + 1 AS next_id FROM books";
    $row = $con->query($sql)->fetch(PDO::FETCH_ASSOC);
    return $row['next_id'];
}

if (isset($_POST['add']) && $_POST['add'] == 'ok') {
    if (isset($_POST['book']) && $_POST['book']) {
        validate();
        addBook($_POST['book'], $pdo);
    }
}

$next_id = nextId($pdo);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>入荷</title>
    <link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>

<body>
    <!-- ヘッダ -->
    <div id="header">
        <h1>新商品追加</h1>
    </div>

    <!-- メニュー -->
    <div id="menu">
        <nav>
            <ul>
                <li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
            </ul>
        </nav>
    </div>

    <form action="new_product.php" method="post">
        <div id="pagebody">
            <!-- エラーメッセージ -->
            <div id="error">
                <?php
                if (isset($_SESSION['error'])) {
                    //⑭SESSIONの「error」の中身を表示する。
                    echo $_SESSION['error'];
                }
                ?>
            </div>
            <div id="center">
                <table>
                    <thead>
                        <tr>
                            <th id="id">ID</th>
                            <th id="book_name">書籍名</th>
                            <th id="author">著者名</th>
                            <th id="salesDate">発売日</th>
                            <th id="itemPrice">金額(円)</th>
                            <th id="stock">在庫数</th>
                        </tr>
                    </thead>

                    <tr>
                        <td><?= $next_id ?></td>
                        <td><input type="text" name="book[title]"></td>
                        <td><input type="text" name="book[author]"></td>
                        <td><input type="text" name="book[salesDate]"></td>
                        <td><input type="text" name="book[price]"></td>
                        <td><input type="text" name="book[stock]"></td>
                    </tr>
                </table>
                <button type="submit" id="kakutei" formmethod="POST" name="add" value="ok">追加</button>
            </div>
        </div>
    </form>
    <!-- フッター -->
    <div id="footer">
        <footer>株式会社アクロイト</footer>
    </div>
</body>

</html>