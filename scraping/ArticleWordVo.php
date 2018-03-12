<?php
class ArticleWordVo {

	private $id;
	private $word;
	private $parse1;
	private $parse2;
	private $parse3;
	private $articleId;
	private $extradate;

	function __construct($id, $word, $parse1, $parse2, $parse3, $articleId, $extradate) {
		$this->id = $id;
		$this->word = $word;
		$this->parse1 = $parse1;
		$this->parse2 = $parse2;
		$this->parse3 = $parse3;
		$this->articleId = $articleId;
		$this->extradate = $extradate;
	}

	function getId() {
		return $this->id;
	}

	function getWord() {
		return $this->word;
	}

	function getParse1() {
		return $this->parse1;
	}

	function getParse2() {
		return $this->parse2;
	}

	function getparse3() {
		return $this->parse3;
	}

	function getArticleId() {
		return $this->articleId;
	}

	function getExtradate() {
		return $this->extradate;
	}

}


?>