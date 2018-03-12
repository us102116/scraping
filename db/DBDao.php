<?php
class DBDao {

	private $connectDB;
	private $tableName_article = "article";
	private $tableName_word =  "articleword";

	// コンストラクタ（接続処理も込み）
	function __construct() {
		include('ConnectDB.php');
		$this->connectDB = new ConnectDB();
		$this->connectDB->connect();
	}
	
	private function connect() {
		$this->connectDB->connect();
	}

	// 記事をDBへ追加
	function insertArticle(ArticleVo $article) {

		$maxId = $this->getMaxId($this->tableName_article) + 1;
		$insertSql = 'insert into '.$this->tableName_article.' values(\''.$maxId.'\',\''.$article->getTitle().'\',\''.''.'\',\''.$article->getExtradate().'\',\''.$article->getUrl().'\');';

		$this->connectDB->insert($insertSql);
		return $maxId;
	}

	// 単語をDBへ追加
	function insertWord(ArticleWordVo $articleWord) {

		$maxId = $this->getMaxId($this->tableName_word) + 1;
		$insertSql = 'insert into '.$this->tableName_word.' values('.$maxId.',\''.$articleWord->getWord().'\',\''.$articleWord->getParse1().'\',\''.$articleWord->getParse2().'\',\''.$articleWord->getParse3().'\','.$articleWord->getArticleId().',\''.$articleWord->getExtradate().'\');';

		$this->connectDB->insert($insertSql);
	}

	// 記事の検索
	function selectArticle($start, $end) {
		$selectSql = 'select * from '.$this->tableName_article.' ;';
		$result = $this->connectDB->selectArticle($selectSql);

		return $result;
	}

	// 単語の検索
	function selectWord($start, $end) {
		$selectSql = 'select * from '.$this->tableName_word.' ;';
		$result =  $this->connectDB->selectWord($selectSql);

		return $result;
	}

	// 接続終了
	function end() {
		$this->connectDB->end();
	}

	// idの最大値を取得
	private function getMaxId($table) {
		$selectMaxId = 'select MAX(id) from '.$table.' ;';
		return $this->connectDB->selectMaxId($selectMaxId);
	}

	// タイトル一覧を取得
	function getTitleArray() {
		return $this->connectDB->selectTitleArray();
	}
}
?>