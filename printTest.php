<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>スクレイピング_記事一覧</title>
</head>
<body>

<h1>記事一覧表示</h1>

<p>
今日の日付は
<?php
echo date("Y/m/d H:i:s");
?>


です。
</p>

</br>

<?php
include('scraping/ArticleVo.php');
include('db/DBDao.php');

$dbManager = new DBDao();

// タイトル一覧を取得
$titleArray = array();
$titleArray = $dbManager->getTitleArray();

$articleArray = $dbManager->selectArticle("","");

echo '記事数 : '.count($articleArray).'</br>';

?>
<table border="1">
	<tr>
		<th>ID</th>
		<th>タイトル</th>
		<th>掲載日付</th>
	</tr>

<?php


// 記事一覧を表示
foreach ($articleArray as $value) {
	echo '<tr><td>'.$value->getId().'</td>';
	echo '    <td><a href='.$value->getUrl().'>'.$value->getTitle().'</a></td>';
	echo '    <td>'.$value->getExtradate().'</td>';
	echo '</tr>';
}

// DB接続終了
$dbManager->end();
?>

</table>

</br></br>
</br>
<input type="button" onclick="location.href='./index.html'" value="戻る" />
</br></br>
</body>
</html>