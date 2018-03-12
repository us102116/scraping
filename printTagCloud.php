<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>スクレイピング_タグクラウド</title>

<script src="js/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/jqcloud/jqcloud.css" />
<script type="text/javascript" src="js/jqcloud/jqcloud-1.0.4.js"></script>

</head>
<body>

<h1>タグクラウドの表示</h1>

<p>
今日の日付は
<?php
echo date("Y/m/d H:i:s");
?>


です。
</p>

</br>

<?php

include('scraping/ArticleWordVo.php');
include('db/DBDao.php');
include('mecab/mecab.php');

$dbManager = new DBDao();
$wordArray = $dbManager->selectWord("","");
$mecab = new Mecab();

$php_json = json_encode($mecab->getArrayToJson($wordArray));

?>

<script type="text/javascript">

	var phplist = JSON.parse('<?php echo $php_json; ?>');

	$(function() {
		// 配列をタグクラウドで表示
		$("#tagcloud").jQCloud( phplist, {width: 600, height: 300});
	});
</script>

<!-- ここにタグクラウドを表示する -->
<div id="tagcloud"></div>

</br>
<input type="button" onclick="location.href='./index.html'" value="戻る" />

</body>
</html>