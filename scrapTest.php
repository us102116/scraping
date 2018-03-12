<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>スクレイピング_記事取得</title>
</head>
<body>

<h1>PHPのスクレイピングテストです</h1>

<p>
今日の日付は
<?php
$old = 30;

if ($old >= 20) {
	print "あなたの年齢は${old} </br>";
}
echo date("Y/m/d H:i:s");
?>


です。
</p>

</br>

<?php
include('scraping/ArticleVo.php');
include('scraping/ArticleWordVo.php');
include('scraping/scraping.php');
include('mecab/mecab.php');
include('db/DBDao.php');

file_put_contents('log.txt', '処理開始 : '.date( "Y年m月d日 H時i分s秒" ), FILE_APPEND);

$dbManager = new DBDao();

$articleArray = new ArrayObject();
$scrap = new Scraping();

$wordArray = new ArrayObject();
$mecab = new Mecab();

// タイトル一覧を取得
$titleArray = array();
$titleArray = $dbManager->getTitleArray();


// マッピング後の記事一覧を取得
$articleArray = $scrap->exeScraping($titleArray);

foreach ($articleArray as  $value) {
	// 記事IDの取得
	$articleId =  $dbManager->insertArticle($value);

	echo '   '.$value->getTitle().'</br>';

	// 形態素解析の結果を取得
	$wordArray = $mecab->exeMecab($value->getSentense(), $articleId, $value->getExtradate());

	foreach ($wordArray as $word) {
		$dbManager->insertWord($word);
	}
}

// DB接続終了
$dbManager->end();
?>

</br>
<input type="button" onclick="location.href='./index.html'" value="戻る" />
</body>
</html>