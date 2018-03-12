<pre>
<?php

class Mecab {

	// 形態素解析から除外する単語
	private $exclusionArray = array("EOS","\"","",".","・",":","(",")","（","）","----------","?","？","0","1","2","3","4","5","6","7","8","9");

	// DBへ追加を除外する品詞
	private $exclusionParseArray = array("非自立","数","接尾","代名詞","サ変接続","形容動詞語幹");

	function exeMecab($str, $articleId, $publicDate){

		// mecabの辞書が"SJIS"のため、一旦SJISに変更
		$str = mb_convert_encoding($str, 'sjis', 'UTF-8');

		//必ず[']で囲むこと
		$exe_path = 'C:\work\MeCab\bin\mecab.exe';
		$descriptorspec = array(
			0 => array("pipe", "r"),
			1 => array("pipe", "w")
		);

		// mecabで処理可能なバイト数以上の場合、バイト数8000byteまで短縮する
		if (strlen($str) > 8000) {
			$str = substr($str, 0, 8000);	
		}
		
		$process = proc_open($exe_path, $descriptorspec, $pipes);
		if (is_resource($process)) {
			fwrite($pipes[0], $str);
			fclose($pipes[0]);
			$result = stream_get_contents($pipes[1]);
			fclose($pipes[1]);
			proc_close($process);
		}

		// mecabの解析結果を"UTF-8"に戻す
		$result = mb_convert_encoding($result, 'UTF-8', 'sjis');
		//\tを[,]に変換
		$result = str_replace("	", ",", $result);
		$tmp = array();
		//行で区切って配列に格納
		$tmp = explode("\r\n", $result);
		$num = 0;
		$resultAry = array();

		for ($i = 0;$i < count($tmp); $i++) {
			if (! in_array($tmp[$i], $this->exclusionArray)){
				$resultAry[$num] = explode(",", $tmp[$i]);
				$num++;
			}
		}
		return $this->getWordArray($resultAry, $articleId, $publicDate);
	}

	// input : 単語配列、記事ID、出版日時
	private function getWordArray($wordArray, $articleId, $publicDate) {

		$resultArray = new ArrayObject();

		foreach ($wordArray as $key => $value) {
			if (in_array($value[2], $this->exclusionParseArray)) {
				// 何もしない
			} else if ( strcmp($value[1], "名詞") == 0){
				$resultArray->append(new ArticleWordVo("", $value[0], $value[1], $value[2], $value[3], $articleId, $publicDate));
			}
		}
		return $resultArray;
	}

	// 単語とその数を計算し、配列を返却する
	function getArrayToJson($wordArray) {

		$wordJson = array();
		$text = 'text';
		$weight = 'weight';
		$flg = true;

		foreach ($wordArray as $word) {

			// 除外対象品詞が含まれる場合はスルーする
			if (in_array($word->getParse2(), $this->exclusionParseArray)) {
				// 何もしない

			} else {

				$flg = true;

				// すでに同じワードが登録されているかどうか判定
				// &をつけるとかわからんわ。。。配列の値を変更すると新たな参照先が作れられるんやって
				foreach ($wordJson as $key => &$value) {
					if ($word->getWord() == $value[$text]) {
						$value[$weight]+= 1;
						$flg = false;
						break;
					}
					unset($value);
				}

				if ($flg != false) {
					// array_appendではだめやった。配列を追加する際に添え字を合わせて追加していたため、
					// 変換後のjsonデータが思った通りではなかった
					$wordJson = array_merge($wordJson,array([$text=>$word->getWord(), $weight =>1]));
				}
			}
		}
		return $this->sortJson($wordJson);
	}

	// 上位100単語を表示する
	private function sortJson($wordJson) {

		// やっぱりこの辺がわかりずらいなー。。。連想配列使うと頭痛くなる
		foreach ($wordJson as $key => $value) {
			$weight[$key] = $value['weight'];
		}
		array_multisort($weight, SORT_DESC, $wordJson);

		return array_slice($wordJson, 0, 100);
	}

}
?>