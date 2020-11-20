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

function getBook($id, $con)
{
    $sql = "SELECT * FROM books WHERE id = {$id};";
    $row = $con->query($sql)->fetch(PDO::FETCH_ASSOC);
    return $row;
}

function deleteBook($data, $con)
{
    $sql = "UPDATE books SET is_delete = true";
    $result = $con->query($sql);
    if (!$result) {
        $_SESSION['error'] = '更新に失敗しました。';
    } else {
        $_SESSION['error'] = '';
    }
}

if (isset($_POST['delete']) && $_POST['delete'] == 'ok') {
    // echo 'delete';
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>削除確認</title>
    <link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>

<body>
    <div id="header">
        <h1>削除確認</h1>
    </div>
    <form action="delete.php" method="post" id="test">
        <div id="pagebody">
            <div id="center">
                <table>
                    <thead>
                        <tr>
                            <th id="book_name">書籍名</th>
                            <th id="stock">在庫数</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($_POST['books'])): ?>
                        <?php foreach ($_POST['books'] as $index => $book_id) : ?>
                            <?php $book = getBook($book_id, $pdo); ?>
                            <tr>
                                <td><?= $book['title']; ?></td>
                                <td><?= $book['stock']; ?></td>
                            </tr>
                            <input type="hidden" name="books[]" value="<?= $book['id'] ?>">
                        <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
                <div id="kakunin">
                    <p>
                        上記の書籍を削除します。<br>
                        よろしいですか？
                    </p>
                    <button type="submit" id="message" formmethod="POST" name="delete" value="ok">はい</button>
                    <button type="submit" id="message" formaction="zaiko_ichiran.php">いいえ</button>
                </div>
            </div>
        </div>
    </form>
    <div id="footer">
        <footer>株式会社アクロイト</footer>
    </div>
</body>

</html>