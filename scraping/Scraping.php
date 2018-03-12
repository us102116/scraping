<?php
		
// phpQueryの読込
require_once("lib/phpQuery-onefile.php");

class Scraping {

/**
  記事を取得するメソッド
  input :titleArray タイトル一覧
  output:List<ArticleVo>
*/
	function exeScraping($titleArray) {

		$url = "http://news.livedoor.com/topics/category/eco/";

		// HTMLファイルの取得
		$html = file_get_contents($url, false, $this->getProxy());

		// キャッシュしたHTMLファイル
		//$html = file_get_contents('news.txt');

		// エンコード変換
		$html = $this->changeEncode($html);

		// domの取得
		$dom = phpQuery::newDocument($html);

		// タイトルの出力
		echo $dom["title"]->text().'</br>';

		// ファイル出力
		//file_put_contents($filename, $dom);

		$title = "";
		$extraDate = "";
		$url = "";
		$sentense = "";
		$result = new ArrayObject();

		// 記事リストを取得
		$articleArry = $dom[".mainBody"]->find("ul")->find("li");

		foreach ($articleArry as $li) {

		 	$a = pq($li)->html();
			$url =  pq($a)->attr("href"); 
		 	$title = pq($a)->find("h3")->text() ; 
		 	$extraDate = pq($a)->find("time")->text() ;
		 	$extraDate =  $this->changeDate($extraDate);

		 	// タイトルが登録されていない場合
		 	if (! in_array($title, (array)$titleArray)) {
			 	// URLの変換 元URLでは記事内容が表示されていないため
			 	$url = str_replace("topics", "article", $url);

				$sentense = $this->getSentense($url);
				$result->append(new ArticleVo("",$title, $sentense, $extraDate, $url));
			}
		 }

		return $result;
	 }

	 // 日付を変換する(2018年3月5日 11時11分 -> 2018-3-5)
	 private function changeDate($date) {
	 	$date = str_replace("年", "-", $date);
	 	$date = str_replace("月", "-", $date);
	 	$date = str_replace("日", ",", $date);
	 	$result = explode(",", $date);
	 	return $result[0];
	 }

	 private function getSentense($childUrl) {
	 	$html = file_get_contents($childUrl, false, $this->getProxy());
	 	//$html = file_get_contents('childnews.txt');
	 	$html = $this->changeEncode($html);
	 	$dom = phpQuery::newDocument($html);

	 	$result = "";

	 	// 記事内容を取得
		$articleContents = $dom[".articleBody"]->find("span");

		foreach ($articleContents as $text) {
			$contents = pq($text)->html();
			$result = $result.pq($contents)->text();
		}
		return $result;
	 }

	 // ネットにのってた無理やりエンコードする方法
	 private function changeEncode($html) {
		$from_encoding ='UTF-8';//デフォルト
		$min_pos = 99999999999999;//十分に大きな数字
		foreach(array('UTF-8','SJIS','EUC-JP','ASCII','JIS','ISO-2022-JP') as $charcode){
		  if($min_pos > stripos($html,$charcode,0) && stripos($html,$charcode,0)>0){
		    $min_pos =  stripos($html,$charcode,0);
		    $from_encoding = $charcode;
		  }
		}
		$html = mb_convert_encoding($html, "utf8", $from_encoding);
	 	return $html;
	 }

	 // proxy設定
	 private function getProxy() {
	 	$proxy = array(
			"http" => array(
				"proxy" => "tcp://各自のプロキシーを入力",
				'request_fulluri' => true,
			),
		);
		return stream_context_create($proxy);
	 }
}

?>