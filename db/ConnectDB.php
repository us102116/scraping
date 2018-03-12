<?php
class ConnectDB {

	private $host = "ホスト名";
	private $user = "ユーザ名";
	private $pass = "パスワード";
	private $db_name = "DB名";

	private $my_con;


	// DB接続
	function connect() {
		$this->my_con = mysqli_connect($this->host, $this->user, $this->pass);

		if ($this->my_con == null) {
			print "MYSQLの接続に失敗しました。</br>";
		} else {
			//echo "MYSQLへ接続完了しました。</br>";
		}

		$db = mysqli_select_db($this->my_con, $this->db_name);
		//mysqli_query($this->my_con, "set names utf8");
	}

	// insert文の実行
	function insert($insertSql) {
		mysqli_query($this->my_con, $insertSql);
	}

	// select文の実行（記事一覧）
	function selectArticle($selectSql) {
		$res =  mysqli_query($this->my_con, $selectSql);
		$result = new ArrayObject();

		// 配列へデータを格納
		while ($row = mysqli_fetch_assoc($res)) {
			$result->append(new ArticleVo($row["id"],$row["title"],$row["sentense"],$row["extradate"],$row["url"]));
		}

		return $result;
	}

	// select文の実行（単語一覧）
	function selectWord($selectSql) {
		$res =  mysqli_query($this->my_con, $selectSql);
		$result = new ArrayObject();

		// 配列へデータを格納
		while ($row = mysqli_fetch_assoc($res)) {
			$result->append(new ArticleWordVo($row["id"],$row["word"],$row["parse1"],$row["parse2"],$row["parse3"],$row["articleId"],$row["extradate"]));
		}

		return $result;
	}

		// select文の実行（単語一覧）
	function selectMaxId($selectSql) {
		$res =  mysqli_query($this->my_con, $selectSql);
		$result = "";

		// 配列へデータを格納
		while ($row = mysqli_fetch_assoc($res)) {
			$result = $row["MAX(id)"];
		}

		return $result;
	}

	// DB切断
	function end() {
		//echo "MYSQLへ接続を終了しました。</br>";
		mysqli_close($this->my_con);
	}

	// タイトル一覧を取得
	function selectTitleArray() {
		$selectTitleSql = 'select title from article;';
		$titleArray = new ArrayObject();
		$res = mysqli_query($this->my_con, $selectTitleSql);

		// 配列へデータを格納
		while ($row = mysqli_fetch_assoc($res)) {
			$titleArray->append($row["title"]);
		}
		return $titleArray;
	}

}
?>
