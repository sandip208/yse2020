<?php


/* 
【機能】
書籍テーブルより書籍情報を取得し、画面に表示する。
商品をチェックし、ボタンを押すことで入荷、出荷が行える。
ログアウトボタン押下時に、セッション情報を削除しログイン画面に遷移する。
【エラー一覧（エラー表示：発生条件）】
入荷する商品が選択されていません：商品が一つも選択されていない状態で入荷ボタンを押す
出荷する商品が選択されていません：商品が一つも選択されていない状態で出荷ボタンを押す
*/

//①セッションを開始する
session_start();
 session_regenerate_id(True);
//②SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
// if (/* ②の処理を書く */){
// 	//③SESSIONの「error2」に「ログインしてください」と設定する。
// 	//④ログイン画面へ遷移する。
// }
function getByid($id,$con){
	/* 
	 * ②書籍を取得するSQLを作成する実行する。
	 * その際にWHERE句でメソッドの引数の$idに一致する書籍のみ取得する。
	 * SQLの実行結果を変数に保存する。
	 */
	$sql = "SELECT * FROM books WHERE id = {$id}";
	$query = $con->query($sql);
	$row = $query->fetch(PDO::FETCH_ASSOC);

//⑤データベースへ接続し、接続情報を変数に保存する
$db_name = 'zaiko2020_yse';
$host = 'localhost';
$user_name = 'zaiko2020_yse';
$password = '2020zaiko';

//⑥データベースで使用する文字コードを「UTF8」にする
$db_name="zaiko2020_yse";
$host='localhost';
$user_name='zaiko2020_yse';
$password='2020zaiko';
$dsn = "mysql:dbname={$db_name};host={$host};charset=utf8";
try {
	$pdo = new PDO($dsn, $user_name, $password);    
} catch (PDOException $e) {  
	echo 'db connect error';
    exit;
}


//⑦書籍テーブルから書籍情報を取得するSQLを実行する。また実行結果を変数に保存する
$sql='SELECT * FROM books;';
$query=$pdo->query($sql);

/*
 * ㉓POSTでこの画面のボタンの「add」に値が入ってるか確認する。
 * 値が入っている場合は中身に「ok」が設定されていることを確認する。
//  */
if(/* ㉓の処理を書く */isset($_POST['add'])&& $_POST['add']=='ok'){
 	//㉔書籍数をカウントするための変数を宣言し、値を0で初期化する。
	 $index=0;
// 	//㉕POSTの「books」から値を取得し、変数に設定する。
// 	foreach(/* ㉕の処理を書く */){
// 		//㉖「getByid」関数を呼び出し、変数に戻り値を入れる。その際引数に㉕の処理で取得した値と⑧のDBの接続情報を渡す。
// 		//㉗ ㉖で取得した書籍の情報の「stock」と、㉔の変数を元にPOSTの「stock」から値を取り出し、足した値を変数に保存する。
// 		//㉘「updateByid」関数を呼び出す。その際に引数に㉕の処理で取得した値と⑧のDBの接続情報と㉗で計算した値を渡す。
// 		//㉙ ㉔で宣言した変数をインクリメントで値を1増やす。
	$index++;
	}
	
// 	//㉚SESSIONの「success」に「入荷が完了しました」と設定する。
// 	//㉛「header」関数を使用して在庫一覧画面へ遷移する。
 }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>書籍一覧</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<div id="header">
		<h1>書籍一覧</h1>
	</div>
	<form action="zaiko_ichiran.php" method="post" id="myform" name="myform">
		<div id="pagebody">
			<!-- エラーメッセージ表示 -->
			<div id="error">
				<?php
				/*
				 * ⑧SESSIONの「success」にメッセージが設定されているかを判定する。
				 * 設定されていた場合はif文の中に入る。
				 */ 
				if(isset($_SESSION['success'])) {
					
					//⑨SESSIONの「success」の中身を表示する。
					echo $_SESSION['success'];
				}
				?>
			</div>
			
			<!-- 左メニュー -->
			<div id="left">
				<p id="ninsyou_ippan">
					<?php
						echo @$_SESSION["account_name"];
					?><br>
					<button type="button" id="logout" onclick="location.href='logout.php'">ログアウト</button>
				</p>
				<button type="submit" id="btn1" formmethod="POST" name="decision" value="3" formaction="nyuka.php">入荷</button>

				<button type="submit" id="btn1" formmethod="POST" name="decision" value="4" formaction="syukka.php">出荷</button>
			</div>
			<!-- 中央表示 -->
			<div id="center">

				<!-- 書籍一覧の表示 -->
				<table>
					<thead>
						<tr>
							<th id="check"></th>
							<th id="id">ID</th>
							<th id="book_name">書籍名</th>
							<th id="author">著者名</th>
							<th id="salesDate">発売日</th>
							<th id="itemPrice">金額</th>
							<th id="stock">在庫数</th>
							<th id="stock">入荷数</th>
						</tr>
					</thead>
					<tbody>
						<?php
						//⑩SQLの実行結果の変数から1レコードのデータを取り出す。レコードがない場合はループを終了する。
						while(/* ⑩の処理を書く */$extract=$query->fetch(PDO::FETCH_ASSOC)){
							//⑪extract変数を使用し、1レコードのデータを渡す。

							echo "<tr>";
							echo "<td><input type='checkbox' name='books[]' value='{$extract['id']}'></td>";
							echo "<td>{$extract['id']}</td>";							
							echo "<td>{$extract['title']}</td>";
							echo "<td>{$extract['author']}</td>";
							echo "<td>{$extract['salesDate']}</td>";
							echo "<td>{$extract['price']}</td>";
							echo "<td>{$extract['stock']}</td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
				<div id="kakunin">
					<p>
						上記の書籍を入荷します。<br>
						よろしいですか？
					</p>
					<button type="submit" id="message" formmethod="POST" name="add" value="ok">はい</button>
					<button type="submit" id="message" formaction="nyuka.php">いいえ</button>
				</div>
			</div>
		</div>
	</form>
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>
</html>
