<?php
class ArticleVo{
	private $id;
	private $title;
	private $sentense;
	private $extradate;
	private $url;

	function __construct($id, $title, $sentense, $extradate, $url) {
		$this->id = $id;
		$this->title = $title;
		$this->sentense = $sentense;
		$this->extradate = $extradate;
		$this->url = $url;
	}

	function getId() {
		return (String)$this->id;
	}

	function getTitle() {
		return (String)$this->title;
	}

	function getSentense() {
		return (String)$this->sentense;
	}

	function getExtradate() {
		return (String)$this->extradate;
	}

	function getUrl() {
		return (String)$this->url;
	}
}
?>