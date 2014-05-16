<?php
/*
效率对比（12688个字符，替换1次）： 

    str_replace： 0.109937906265秒
    strtr： 0.0306839942932秒


替换结果对比 

    比如：「张三」、「张三丰」、「张三丰田」 均为违禁词 （为何会有这样的区分？请参见 「法X」、「法Xgong」） 
    原文：「我今天开着张三丰田去上班 」 
    strtr：「我今天开着****去上班 」（四个词均替换为了*） 
    str_replace：「我今天开着**丰田去上班 」（仅仅替换了第一个匹配项） 

所以使用str_replace替换，是不能本质上解决问题。


时间对比：
关键词数量:6712 (无重复)
self init:0.00789093971252(加载xcache)
self:0.0354378223419
strtr:0.0311169624329
strtr_array:0.432713985443
str_replace:0.109627008438

*/
require('badword.src.php');
$badword1 = array_combine($badword,array_fill(0,count($badword),'*'));
$bb = '我今天开着张三丰田上班';
$str = strtr($bb, $badword1);

//PHP源代码中对于strtr的编程
function strtr_array(&$str,&$replace_arr) {
	$maxlen = 0;$minlen = 1024*128;
	if (empty($replace_arr)) return $str;
	foreach($replace_arr as $k => $v) {
		$len = strlen($k);
		if ($len < 1) continue;
		if ($len > $maxlen) $maxlen = $len;
		if ($len < $minlen) $minlen = $len;
	}
	$len = strlen($str);
	$pos = 0;$result = '';
	while ($pos < $len) {
		if ($pos + $maxlen > $len) $maxlen = $len - $pos; 
		$found = false;$key = '';
		for($i = 0;$i<$maxlen;++$i) $key .= $str[$i+$pos]; //原文：memcpy(key,str+$pos,$maxlen)
		for($i = $maxlen;$i >= $minlen;--$i) {
			$key1 = substr($key, 0, $i); //原文：key[$i] = '\0'
			if (isset($replace_arr[$key1])) {
				$result .= $replace_arr[$key1];
				$pos += $i;
				$found = true;
				break;
			}
		}
		if(!$found) $result .= $str[$pos++];
	}
	return $result;
}