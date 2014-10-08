<?php
//关键词高亮
function highlight($sString, $aWords) {
	if (!is_array ($aWords) || empty ($aWords) || !is_string ($sString)) {
		return false;
	}

	$sWords = implode ('|', $aWords);
 	return preg_replace ('@\b('.$sWords.')\b@si', '<strong style="background-color:yellow">$1</strong>', $sString);
}

//获取你的Feedburner的用户
function get_average_readers($feed_id,$interval = 7){
	$today = date('Y-m-d', strtotime("now"));
	$ago = date('Y-m-d', strtotime("-".$interval." days"));
	$feed_url="https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=".$feed_id."&dates=".$ago.",".$today;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $feed_url);
	$data = curl_exec($ch);
	curl_close($ch);
	$xml = new SimpleXMLElement($data);
	$fb = $xml->feed->entry['circulation'];

	$nb = 0;
	foreach($xml->feed->children() as $circ){
		$nb += $circ['circulation'];
	}

	return round($nb/$interval);
}

//自动生成密码
function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength >= 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength >= 2) {
		$vowels .= "AEUY";
	}
	if ($strength >= 4) {
		$consonants .= '23456789';
	}
	if ($strength >= 8 ) {
		$vowels .= '@#$%';
	}

	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

//压缩多个CSS文件
header('Content-type: text/css');
ob_start("compress");
function compress($buffer) {
  /* remove comments */
  $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
  /* remove tabs, spaces, newlines, etc. */
  $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
  return $buffer;
}

/* your css files */
include('master.css');
include('typography.css');
include('grid.css');
include('print.css');
include('handheld.css');

ob_end_flush();

//获取短网址
function getTinyUrl($url) {
    return file_get_contents("http://tinyurl.com/api-create.php?url=".$url);
}

//根据生日计算年龄
function age($date){
	$year_diff = '';
	$time = strtotime($date);
	if(FALSE === $time){
		return '';
	}

	$date = date('Y-m-d', $time);
	list($year,$month,$day) = explode("-",$date);
	$year_diff = date("Y") – $year;
	$month_diff = date("m") – $month;
	$day_diff = date("d") – $day;
	if ($day_diff < 0 || $month_diff < 0) $year_diff–;

	return $year_diff;
}

//计算执行时间
//Create a variable for start time
$time_start = microtime(true);

// Place your PHP/HTML/JavaScript/CSS/Etc. Here

//Create a variable for end time
$time_end = microtime(true);
//Subtract the two times to get seconds
$time = $time_end - $time_start;

echo 'Script took '.$time.' seconds to execute';

//PHP的维护模式
function maintenance($mode = FALSE){
    if($mode){
        if(basename($_SERVER['SCRIPT_FILENAME']) != 'maintenance.php'){
            header("Location: http://example.com/maintenance.php");
            exit;
        }
    }else{
        if(basename($_SERVER['SCRIPT_FILENAME']) == 'maintenance.php'){
            header("Location: http://example.com/");
            exit;
        }
    }
}

//阻止CSS样式被缓存
/*
	<link href="/stylesheet.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" /&glt;
*/

//为数字增加 st\nd\rd
function make_ranked($rank) {
	$last = substr( $rank, -1 );
	$seclast = substr( $rank, -2, -1 );
	if( $last > 3 || $last == 0 ) $ext = 'th';
	else if( $last == 3 ) $ext = 'rd';
	else if( $last == 2 ) $ext = 'nd';
	else $ext = 'st'; 

	if( $last == 1 && $seclast == 1) $ext = 'th';
	if( $last == 2 && $seclast == 1) $ext = 'th';
	if( $last == 3 && $seclast == 1) $ext = 'th'; 

	return $rank.$ext;
}

//获取浏览器IP地址
function getRemoteIPAddress() {
    $ip = $_SERVER['REMOTE_ADDR'];
    return $ip;
}

//如果有代理服务器的情况下获取IP
function getRealIPAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { // check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { // to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

//获取 MySQL 时间戳
$query = "select UNIX_TIMESTAMP(date_field) as mydate from mytable where 1=1";
$records = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($records)) {
    echo $row;
}

//验证日期格式：YYYY-MM-DD
function checkDateFormat($date) {
	// match the format of the date
	if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
		// check whether the date is valid of not
		if (checkdate($parts[2], $parts[3], $parts[1])) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

//重定向
header('Location: http://www.oschina.net/project/zh');

//发送邮件
$to = "someone@oschina.net";
$subject = "Your Subject here";
$body = "Body of your message here you can use HTML too. e.g. <br><b> Bold </b>";
$headers = "From: You\r\n";
$headers .= "Reply-To: info@yoursite.com\r\n";
$headers .= "Return-Path: info@yoursite.com\r\n";
$headers .= "X-Mailer: PHP\n";
$headers .= 'MIME-Version: 1.0' . "\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail($to, $subject, $body, $headers);

//BASE64 编码和解码
function base64url_encode($plainText) {
    $base64 = base64_encode($plainText);
    $base64url = strtr($base64, '+/=', '-_,');
    return $base64url;
}
 
function base64url_decode($plainText) {
    $base64url = strtr($plainText, '-_,', '+/=');
    $base64 = base64_decode($base64url);
    return $base64;
}

//JSON 处理
$json_data = array ('id'=>1,'name'=>"John",'country'=>'Canada',"work"=>array("Google","Oracle"));
echo json_encode($json_data);

$json_string='{"id":1,"name":"John","country":"Canada","work":["Google","Oracle"]} ';
$obj=json_decode($json_string);

// print the parsed data
echo $obj->name; //displays John
echo $obj->work[0]; //displays Google

//检测用户浏览器类型
$useragent = $_SERVER ['HTTP_USER_AGENT'];
echo "<b>Your User Agent is</b>: " . $useragent;

//显示网页源码
$lines = file('http://www.oschina.net/home/about');
foreach ($lines as $line_num => $line) {
	// loop thru each line and prepend line numbers
	echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br>\n";
}

//调整服务器时间
$now = date('Y-m-d-G');
$now = strftime("%Y-%m-%d-%H", strtotime("$now -8 hours"));

//当前时间
$timeStamp = date('Y-m-d H:i:s', time());

//移除 HTML 标签
$text = strip_tags($input, "");

//返回 $start 和 $end 之间的文本
function GetBetween($content,$start,$end){
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}

//将url转换成链接
$url = "Jean-Baptiste Jung (http://www.webdevcat.com)";
$url = preg_replace("#http://([A-z0-9./-]+)#", '<a href="http://www.catswhocode.com/blog/$1" style="font-size: 12px; vertical-align: baseline; background-color: transparent; margin: 0px; padding: 0px; color: #3777af; text-decoration: none; font-weight: bold">$0</a>', $url);

//切分字符串为140个字符
function split_to_chunks($to,$text){
	$total_length = (140 - strlen($to));
	$text_arr = explode(" ",$text);
	$i=0;
	$message[0]="";
	foreach ($text_arr as $word){
		if ( strlen($message[$i] . $word . ' ') <= $total_length ){
			if ($text_arr[count($text_arr)-1] == $word){
				$message[$i] .= $word;
			} else {
				$message[$i] .= $word . ' ';
			}
		} else {
			$i++;
			if ($text_arr[count($text_arr)-1] == $word){
				$message[$i] = $word;
			} else {
				$message[$i] = $word . ' ';
			}
		}
	}
	return $message;
}

//删除字符串中的URL
$string = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $string);

//将字符串转成SEO友好的字符串
function slug($str){
	$str = strtolower(trim($str));
	$str = preg_replace('/[^a-z0-9-]/', '-', $str);
	$str = preg_replace('/-+/', "-", $str);
	return $str;
}

//解析 CSV 文件
$fh = fopen("contacts.csv", "r");
while($line = fgetcsv($fh, 1000, ",")) {
    echo "Contact: {$line[1]}";
}

//字符串搜索
function contains($str, $content, $ignorecase=true){
    if ($ignorecase){
        $str = strtolower($str);
        $content = strtolower($content);
    }
    return strpos($content,$str) ? true : false;
}

//检查字符串是否以某个串开始
function String_Begins_With($needle, $haystack {
    return (substr($haystack, 0, strlen($needle))==$needle);
}

//从字符串中提取email地址
function extract_emails($str){
    // This regular expression extracts all emails from a string:
    $regexp = '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i';
    preg_match_all($regexp, $str, $m);

    return isset($m[0]) ? $m[0] : array();
}

$test_string = 'This is a test string...

        test1@example.org

        Test different formats:
        test2@example.org;
        <a href="test3@example.org">foobar</a>
        <test4@example.org>

        strange formats:
        test5@example.org
        test6[at]example.org
        test7@example.net.org.com
        test8@ example.org
        test9@!foo!.org

        foobar
';

print_r(extract_emails($test_string));

//连接 MySQL 数据库
$host = "localhost";
$uname = "database username";
$pass = "database password";
$database = "database name";
$connection = mysql_connect($host,$uname,$pass) or die("Database Connection Failed");

$result = mysql_select_db($database) or die("database cannot be selected");

//PHP function to display limited words from a string. 
function words_limit( $str, $num, $append_str='' ){
	$words = preg_split( '/[\s]+/', $str, -1, PREG_SPLIT_OFFSET_CAPTURE );
	 if( isset($words[$num][1]) ){
	   $str = substr( $str, 0, $words[$num][1] ).$append_str;
	 }
	unset( $words, $num );
	return trim( $str );>
	}

	echo words_limit($yourString, 50,'...'); 

	or

	echo words_limit($yourString, 50); 
}

//显示 Youtube 或 Vimeo 视频缩略图
function video_image($url){
   $image_url = parse_url($url);
     if($image_url['host'] == 'www.youtube.com' || 
        $image_url['host'] == 'youtube.com'){
         $array = explode("&", $image_url['query']);
         return "http://img.youtube.com/vi/".substr($array[0], 2)."/0.jpg";
     }else if($image_url['host'] == 'www.youtu.be' || 
              $image_url['host'] == 'youtu.be'){
         $array = explode("/", $image_url['path']);
         return "http://img.youtube.com/vi/".$array[1]."/0.jpg";
     }else if($image_url['host'] == 'www.vimeo.com' || 
         $image_url['host'] == 'vimeo.com'){
         $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".
         substr($image_url['path'], 1).".php"));
         return $hash[0]["thumbnail_medium"];
     }
}

/*<img src="<?php echo video_image('youtube URL'); ?>" />*/

//Cookie 操作
//设置 Cookie
setcookie("name", 'value', time()+3600*60*30);

//显示 Cookie
if ($_COOKIE["name"]!=""){
$_SESSION['name'] = $_COOKIE["name"];
}

//生成随机密码
//方法1
echo substr(md5(uniqid()), 0, 8); 

//方法2
function rand_password($length){
  $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
  $chars .= '0123456789' ;
  $chars .= '!@#%^&*()_,./<>?;:[]{}\|=+';

  $str = '';
  $max = strlen($chars) - 1;

  for ($i=0; $i < $length; $i++)
    $str .= $chars[rand(0, $max)];

  return $str;
}

echo rand_password(16);

//文件解压
$zip = zip_open("moooredale.zip");
if ($zip) {
   	while ($zip_entry = zip_read($zip)) {
	   $fp = fopen(zip_entry_name($zip_entry), "w");
   		if (zip_entry_open($zip, $zip_entry, "r")) {
   			$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
   			fwrite($fp,"$buf");
   			zip_entry_close($zip_entry);
   			fclose($fp);
 		}
	}
	zip_close($zip);
}

/**
 * 获取用户真实 IP
 * 淘宝IP库： http://ip.taobao.com 
 */
function getIP()
{
    static $realip;
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }


    return $realip;
}


/**
 * 获取 IP  地理位置
 * 淘宝IP接口
 * @Return: array
 */
function getCity($ip)
{
	$url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
	$ip=json_decode(file_get_contents($url));	
	if((string)$ip->code=='1'){
	   return false;
 	}
 	$data = (array)$ip->data;
	return $data;	
}

/*
PHP可阅读随机字符串
此代码将创建一个可阅读的字符串，使其更接近词典中的单词，实用且具有密码验证功能。
*/
function readable_random_string($length = 6){
	$conso=array("b","c","d","f","g","h","j","k","l","m","n","p","r","s","t","v","w","x","y","z");
	$vocal=array("a","e","i","o","u");
	$password="";
	srand ((double)microtime()*1000000);
	$max = $length/2;
	for($i=1; $i<=$max; $i++) {
		$password.=$conso[rand(0,19)];
		$password.=$vocal[rand(0,4)];
	}
	return $password;
}

/*
PHP生成一个随机字符串
如果不需要可阅读的字符串，使用此函数替代，即可创建一个随机字符串，作为用户的随机密码等。
*/
function generate_rand($l){
	$c= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	srand((double)microtime()*1000000);
	for($i=0; $i<$l; $i++) {
		$rand.= $c[rand()%strlen($c)];
	}
	return $rand;
}

/*
PHP编码电子邮件地址
使用此代码，可以将任何电子邮件地址编码为 html 字符实体，以防止被垃圾邮件程序收集。
*/
function encode_email($email='info@domain.com', $linkText='Contact Us', $attrs ='class="emailencoder"' )
{
	// remplazar aroba y puntos
	$email = str_replace('@', '&#64;', $email);
	$email = str_replace('.', '&#46;', $email);
	$email = str_split($email, 5);

	$linkText = str_replace('@', '&#64;', $linkText);
	$linkText = str_replace('.', '&#46;', $linkText);
	$linkText = str_split($linkText, 5);

	$part1 = '$part2 = 'ilto&#58;';
	$part3 = '" '. $attrs .' >';
	$part4 = '';

	$encoded = '';

	return $encoded;
}

/*
PHP验证邮件地址
电子邮件验证也许是中最常用的网页表单验证，此代码除了验证电子邮件地址，也可以选择检查邮件域所属 DNS 中的 MX 记录，使邮件验证功能更加强大。
*/
function is_valid_email($email, $test_mx = false)
{
	if(eregi("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
	if($test_mx) {
		list($username, $domain) = split("@", $email);
		return getmxrr($domain, $mxrecords);
	}
	else
	return true;
	else
	return false;
}

//PHP列出目录内容
function list_files($dir)
{
	if(is_dir($dir)){
		if($handle = opendir($dir)){
			while(($file = readdir($handle)) !== false)	{
				if($file != "." && $file != ".." && $file != "Thumbs.db")	{
					echo ''.$file.''."\n";
				}
			}
			closedir($handle);
		}
	}
}

/*
PHP销毁目录 删除一个目录，包括它的内容。
*/
function destroyDir($dir, $virtual = false)
{
	$ds = DIRECTORY_SEPARATOR;
	$dir = $virtual ? realpath($dir) : $dir;
	$dir = substr($dir, -1) == $ds ? substr($dir, 0, -1) : $dir;
	if (is_dir($dir) && $handle = opendir($dir)){
		while ($file = readdir($handle)){
			if ($file == '.' || $file == '..'){
				continue;
			}	elseif (is_dir($dir.$ds.$file))	{
				destroyDir($dir.$ds.$file);
			}	else	{
				unlink($dir.$ds.$file);
			}
		}
		closedir($handle);
		rmdir($dir);
		return true;
	}	else	{
		return false;
	}
}

/*
PHP解析 JSON 数据
与大多数流行的 Web 服务如 twitter 通过开放 API 来提供数据一样，它总是能够知道如何解析 API 数据的各种传送格式，包括 JSON，XML 等等。
*/
$json_string='{"id":1,"name":"foo","email":"foo@foobar.com","interest":["wordpress","php"]} ';
$obj=json_decode($json_string);
echo $obj->name; //prints foo
echo $obj->interest[1]; //prints php

//PHP解析 XML 数据
$xml_string="
Foo
foo@bar.com
Foobar
foobar@foo.com
";
//load the xml string using simplexml
$xml = simplexml_load_string($xml_string);
//loop through the each node of user
foreach ($xml->user as $user){
	//access attribute
	echo $user['id'], '  ';
	//subnodes are accessed by -> operator
	echo $user->name, '  ';
	echo $user->email, ' ';
}

/*
PHP创建日志缩略名
创建用户友好的日志缩略名。
*/
function create_slug($string){
	$slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
	return $slug;
}

/*
PHP获取客户端真实 IP 地址
该函数将获取用户的真实 IP 地址，即便他使用代理服务器。
*/
function getRealIpAddr()
{
	if (!emptyempty($_SERVER['HTTP_CLIENT_IP']))	{
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}elseif(!emptyempty($_SERVER['HTTP_X_FORWARDED_FOR'])){	//to check ip is pass from proxy	
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

11. PHP强制性文件下载

为用户提供强制性的文件下载功能。

/********************
*@file – path to file
*/
function force_download($file)
{
if ((isset($file))&&(file_exists($file))) {
header("Content-length: ".filesize($file));
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file . '"');
readfile("$file");
} else {
echo "No file selected";
}
}

12. PHP创建标签云

function getCloud( $data = array(), $minFontSize = 12, $maxFontSize = 30 )
{
$minimumCount = min( array_values( $data ) );
$maximumCount = max( array_values( $data ) );
$spread       = $maximumCount – $minimumCount;
$cloudHTML    = ";
$cloudTags    = array();

$spread == 0 && $spread = 1;

foreach( $data as $tag => $count )
{
$size = $minFontSize + ( $count – $minimumCount )
* ( $maxFontSize – $minFontSize ) / $spread;
$cloudTags[] = '. '" href="#" title="\" . $tag  .
'\' returned a count of ' . $count . '">'
. htmlspecialchars( stripslashes( $tag ) ) . '';
}

return join( "\n", $cloudTags ) . "\n";
}
/**************************
****   Sample usage    ***/
$arr = Array('Actionscript' => 35, 'Adobe' => 22, 'Array' => 44, 'Background' => 43,
'Blur' => 18, 'Canvas' => 33, 'Class' => 15, 'Color Palette' => 11, 'Crop' => 42,
'Delimiter' => 13, 'Depth' => 34, 'Design' => 8, 'Encode' => 12, 'Encryption' => 30,
'Extract' => 28, 'Filters' => 42);
echo getCloud($arr, 12, 36);

13. PHP寻找两个字符串的相似性

PHP 提供了一个极少使用的 similar_text 函数，但此函数非常有用，用于比较两个字符串并返回相似程度的百分比。

similar_text($string1, $string2, $percent);
//$percent will have the percentage of similarity

14. PHP在应用程序中使用 Gravatar 通用头像

随着 WordPress 越来越普及，Gravatar 也随之流行。由于 Gravatar 提供了易于使用的 API，将其纳入应用程序也变得十分方便。

/******************
*@email – Email address to show gravatar for
*@size – size of gravatar
*@default – URL of default gravatar to use
*@rating – rating of Gravatar(G, PG, R, X)
*/
function show_gravatar($email, $size, $default, $rating)
{
echo ''&default='.$default.'&size='.$size.'&rating='.$rating.'" width="'.$size.'px"
height="'.$size.'px" />';
}

15. PHP在字符断点处截断文字

所谓断字 (word break)，即一个单词可在转行时断开的地方。这一函数将在断字处截断字符串。

// Original PHP code by Chirp Internet: www.chirp.com.au
// Please acknowledge use of this code by including this header.
function myTruncate($string, $limit, $break=".", $pad="…") {
// return with no change if string is shorter than $limit
if(strlen($string) <= $limit)
return $string;

// is $break present between $limit and the end of the string?
if(false !== ($breakpoint = strpos($string, $break, $limit))) {
if($breakpoint < strlen($string) – 1) {
$string = substr($string, 0, $breakpoint) . $pad;
}
}
return $string;
}
/***** Example ****/
$short_string=myTruncate($long_string, 100, ' ');

16. PHP文件 Zip 压缩

/* creates a compressed zip file */
function create_zip($files = array(),$destination = ",$overwrite = false) {
//if the zip file already exists and overwrite is false, return false
if(file_exists($destination) && !$overwrite) { return false; }
//vars
$valid_files = array();
//if files were passed in…
if(is_array($files)) {
//cycle through each file
foreach($files as $file) {
//make sure the file exists
if(file_exists($file)) {
$valid_files[] = $file;
}
}
}
//if we have good files…
if(count($valid_files)) {
//create the archive
$zip = new ZipArchive();
if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
return false;
}
//add the files
foreach($valid_files as $file) {
$zip->addFile($file,$file);
}
//debug
//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

//close the zip — done!
$zip->close();

//check to make sure the file exists
return file_exists($destination);
}
else
{
return false;
}
}
/***** Example Usage ***/
$files=array('file1.jpg', 'file2.jpg', 'file3.gif');
create_zip($files, 'myzipfile.zip', true);

17. PHP解压缩 Zip 文件

/**********************
*@file – path to zip file
*@destination – destination directory for unzipped files
*/
function unzip_file($file, $destination){
// create object
$zip = new ZipArchive() ;
// open archive
if ($zip->open($file) !== TRUE) {
die ('Could not open archive');
}
// extract contents to destination directory
$zip->extractTo($destination);
// close archive
$zip->close();
echo 'Archive extracted to directory';
}

18. PHP为 URL 地址预设 http 字符串

有时需要接受一些表单中的网址输入，但用户很少添加 http:// 字段，此代码将为网址添加该字段。

if (!preg_match("/^(http|ftp):/", $_POST['url'])) {
$_POST['url'] = 'http://'.$_POST['url'];
}

19. PHP将网址字符串转换成超级链接

该函数将 URL 和 E-mail 地址字符串转换为可点击的超级链接。

function makeClickableLinks($text) {
$text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_+.~#?&//=]+)',
'\1', $text);
$text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_+.~#?&//=]+)',
'\1\2', $text);
$text = eregi_replace('([_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3})',
'\1', $text);

return $text;
}

20. PHP调整图像尺寸

创建图像缩略图需要许多时间，此代码将有助于了解缩略图的逻辑。

/**********************
*@filename – path to the image
*@tmpname – temporary path to thumbnail
*@xmax – max width
*@ymax – max height
*/
function resize_image($filename, $tmpname, $xmax, $ymax)
{
$ext = explode(".", $filename);
$ext = $ext[count($ext)-1];

if($ext == "jpg" || $ext == "jpeg")
$im = imagecreatefromjpeg($tmpname);
elseif($ext == "png")
$im = imagecreatefrompng($tmpname);
elseif($ext == "gif")
$im = imagecreatefromgif($tmpname);

$x = imagesx($im);
$y = imagesy($im);

if($x <= $xmax && $y <= $ymax)
return $im;

if($x >= $y) {
$newx = $xmax;
$newy = $newx * $y / $x;
}
else {
$newy = $ymax;
$newx = $x / $y * $newy;
}

$im2 = imagecreatetruecolor($newx, $newy);
imagecopyresized($im2, $im, 0, 0, 0, 0, floor($newx), floor($newy), $x, $y);
return $im2;
}

21. PHP检测 ajax 请求

大多数的 JavaScript 框架如 jquery，Mootools 等，在发出 Ajax 请求时，都会发送额外的 HTTP_X_REQUESTED_WITH 头部信息，头当他们一个ajax请求，因此你可以在服务器端侦测到 Ajax 请求。

if(!emptyempty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
//If AJAX Request Then
}else{
//something else
}

-------------------------   前端
/**
 *  前端公共函数方法整理 
 *  @author Weige 
 *  备注： 配合jqury.js 使用
 *  2012-04
 */

//获取字符串长度
function getWordSize(str)
{
	if(!str)
		return null;	
	var length = 0;
	var str = str;
	var regDoub = /[^x00-xff]/g;
	var regSingl = /[x00-xff]/g
	var douL = str.match(regDoub);
	var singL = str.match(regSingl)
	if(douL){
		length+=douL.length*2;
	}
	if(singL){
		length+=singL.length;
	}
	length/=2;
	length = Math.ceil(length);
	return length;
}
//祛除前后空格
function trim(str)
{
    return str.replace(/(^\s*)|(\s*$)/g, '');
}
//中文汉字编码判断
function isChinese(str)
{
  var str = str.replace(/(^\s*)|(\s*$)/g,'');
  if (!(/^[\u4E00-\uFA29]*$/.test(str)
          && (!/^[\uE7C7-\uE7F3]*$/.test(str))))
  {
      return false;
  }
  return true;
}
//手机判断
function isMobile(str)
{

	if(/^1[345689]\d{9}/.test(str))
      {
          return true;
      }
    return false;

}
// oschina
//特殊字符转换
function htmlEncode(str)   
{   
  var s = "";   
  if (str.length == 0) return "";  
  //s = s.replace(/ /g, "&nbsp;");   
  s = str.replace(/&/g, "&amp;");   
  s = s.replace(/</g, "&lt;");   
  s = s.replace(/>/g, "&gt;");   
  s = s.replace(/\'/g, "&#39;");   
  s = s.replace(/\"/g, "&quot;");   
  s = s.replace(/\n/g, "<br>");   
  return s;   
}   

function htmlDecode(str)   
{   
  var s = "";   
  if (str.length == 0) return "";   
  s = str.replace(/&gt;/g, "&");   
  s = s.replace(/&lt;/g, "<");   
  s = s.replace(/&gt;/g, ">");   
  s = s.replace(/&nbsp;/g, " ");   
  s = s.replace(/&#39;/g, "\'");   
  s = s.replace(/&quot;/g, "\"");   
  s = s.replace(/<br>/g, "\n");   
  return s;   
}   


//使用ajax提交数据
function ajaxPost(the_url,the_param,succ_callback)
{
	$.ajax({
		type	: 'POST',
		cache	: false,
		url		: the_url,
		data	: the_param,
		success	: succ_callback
	});
}


//使用ajax获取数据

function ajaxGet(the_url,succ_callback,error_callback)
{
	$.ajax({
		type	: 'GET',
		cache	: true,
		url		: the_url,
		success	: succ_callback,
		error   : error_callback
		
	});
}

//发送json格式数据

function ajaxPostJson(the_url,data_pro,succ_callback,error_callback)
{
	$.ajax({
    	async		: false,//同步更新	
    	type		: 'post',
    	dataType	: 'json',
    	data		: data_pro,
    	url			: the_url,
    	success		: succ_callback,
    	error		: error_callback
    	});
}
function real_len(name) 
{    
 	return (name.replace(/[^\x00-\xff]/g,"**").length);    
}

function is_email(email)
{
	var reg=/^\s*([A-Za-z0-9_-]+(\.\w+)*@(\w+\.)+\w{2,3})\s*$/;
    return reg.test(email);
}
function is_number(name)
{
	var reg = /^\d+$/g;    
   return reg.test(name);    
}
function is_pwd(name)
{
	 var reg = /^[A-Za-z@0-9_-]+$/g;    
  return reg.test(name);    
		
}

---------------------------------后端


<?php


	/**
	 * Weige
	 * 2012-05
	 * */
	
	/**
	 * 唯一名字
	 * */
    function get_unique_name($srand_id=0) 
	{
		$id		= $srand_id?$srand_id:mt_rand(0,99999999);
		$index	= 'z6OmlGsC9xqLPpN7iw8UDAb4HIBXfgEjJnrKZSeuV2Rt3yFcMWhakQT1oY5v0d';
		$base	= 62;
		$out	= "";
		 for ( $t = floor( log10( $id ) / log10( $base ) ); $t >= 0; $t-- ) 
		 {
			$a		= floor( $id / pow( $base, $t ) );
			$out	= $out . substr( $index, $a, 1 );
			$id		= $id - ( $a * pow( $base, $t ) );
		 }
	   return $out;
	}
	/** 
	 * 短链接密钥
	 * */
	function url_key($url,$key="wei爱微博") 
	{
    	$x		= sprintf("%u", crc32($key.$url)); 
    	$show	= ''; 
	     while($x> 0) 
	     { 
	         $s	= $x% 62; 
	         if($s> 35)
	         $s	= chr($s+61);             
	         elseif($s> 9 && $s<=35)
	         $s	= chr($s+ 55); 
	         $show.= $s; 
	        $x	= floor($x/62); 
	     } 
    	return $show;     
 	}
 	/**
 	 * 标签key
 	 * */ 
    function tag_key($tag,$key="wei爱话题")
 	{
 		$tag = str_replace(" ", "", $tag);
 		$tag.= $key;
 		$hash = md5($tag);
		$hash = $hash[0] | ($hash[1] <<8 ) | ($hash[2] <<16) | ($hash[3] <<24) | ($hash[4] <<32) | ($hash[5] <<40) | ($hash[6] <<48) | ($hash[7] <<56);
		return $hash % 99999999;
 	
 	}
 	
	/**
	 * 过滤非法标签
	 * */
    function strip_selected_tags($str,$disallowable="<script><iframe><style><link>")
	{
		$disallowable	= trim(str_replace(array(">","<"),array("","|"),$disallowable),'|');
		$str			= str_replace(array('&lt;', '&gt;'),array('<', '>'),$str);
		$str			= preg_replace("~<({$disallowable})[^>]*>(.*?<\s*\/(\\1)[^>]*>)?~is",'$2',$str);
		
		return $str;
	}
	/**
	 * 替换或转义标签
	 * */
	function convert_tags($str)
	{

		if($str)
	//	$str = str_replace(array('&','<', '>',"'",'"'),array('&amp;','&lt;', '&gt;','&#039;','&quot;'),$str);
    	$str = str_replace(array('<', '>',"'",'"'),array('&lt;', '&gt;','&#039;','&quot;'),$str);
	 	return $str;
	}
	

//    function jstrpos($haystack, $needle, $offset = null)
//    {
//    		$needle	 = trim($needle);
// 		$jstrpos = false;
// 		if(function_exists('mb_strpos'))
// 		{
// 			$jstrpos = mb_strpos($haystack, $needle, $offset, self::$charset);
// 		}
// 		elseif(function_exists('strpos'))
// 		{
// 			$jstrpos = strpos($haystack, $needle, $offset);
// 		}
// 		return $jstrpos;
//    }
   /**
	 * 检查是否为一个http开头并带有.com的url地址
	 **/
	function is_http($url)
	{
		if(filter_var($url, FILTER_VALIDATE_URL))
		return true;return false;
	}
	/**
	 * 发送一个http请求
	 * @param  $url    请求链接
	 * @param  $method 请求方式
	 * @param array $vars 请求参数
	 * @param  $time_out  请求过期时间
	 * @return JsonObj
	 */
	function get_curl($url, array $vars=array(), $method = 'post')
	{
		$method = strtolower($method);
		if($method == 'get' && !empty($vars))
		{
			if(strpos($url, '?') === false)
				$url = $url . '?' . http_build_query($vars);
			else
				$url = $url . '&' . http_build_query($vars);
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
		if ($method == 'post') 
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
		}	
		$result = curl_exec($ch);
		if(!curl_errno($ch))
		{
			$result = trim($result);
		}
		else 
		{
			$result = '[error：1]';
		}
		
		curl_close($ch);
		return $result;
        
	}
	
	/**
	 * 获取客户端ip
	 * */
    function getIp()
	{
		if (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			return $_SERVER['HTTP_CLIENT_IP'];
		}
		else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else if (isset($_SERVER['REMOTE_ADDR']))
		{
			return $_SERVER['REMOTE_ADDR'];
		}
		return '0.0.0';
	}
	/**
	 * 图片大小验证器
	 * $_FILES['file']数组
	 * 
	 * 只支持 jpg png gif 格式 默认5M
	 * */
	function check_img($file,$limitSize=5242880)
	{
		$type_maping = array(1=>'image/gif', 2=>'image/jpeg', 3=>'image/png',4=>'image/pjpeg',5=>'image/x-png',6=>'image/jpg');
		
		if($file['size']>$limitSize)
		{
			$rs['error'] = '1';
			$rs['msg']	 = '图片超过规定大小!';
		}
		elseif($file['error']==4)
		{
			$rs['error'] = '1';
			$rs['msg']	 = '图片文件损害!';
		}
		elseif($file['size']==0)
		{
			$rs['error'] = '1';
			$rs['msg']	 = '空图片或者超过规定大小';
		}
		elseif( !in_array($file['type'],$type_maping) )
		{
			$rs['error'] = '1';
			$rs['msg']	 = '图片类型错误!';
		}
		else
		{
			$rs['error'] = 'no';
			$rs['msg']	 = 'success';
		}
		return $rs;
	}
	
	/**
	 * 递归方式的对变量中的特殊字符进行转义以及过滤标签
	 */
	function addslashes_deep($value)
	{
		if (empty($value))return $value;
		//Huige 过滤html标签,防止sql注入
		return is_array($value) ? array_map('addslashes_deep', $value) : strip_tags(addslashes($value));
	}
	
	
	/**
	 * @param   mix     $value
	 * @return  mix
	 */
    function stripslashes_deep($value)
	{
		if (empty($value))return $value;
		return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
	}
	
	/**
	 * 以新浪微博的字数统计方式统计字数（简单版）
	 * 中文算1个，英文算0.5个，全角字符算1个，半角字符算0.5个。
	 * @param string $string
	 * @return integer
	 */
    function strlen_weibo($string)
	{
		if(is_string($string))
		{
			$string=trim(trim($string,'&nbsp;'));
			return (strlen($string) + mb_strlen($string,'UTF-8')) / 4;
		}
		return false;
	}
	/**
	 * 截取指定长度的字符串，超出部分用 ..替换
	 * @param string $text
	 * @param int $length
	 * @param string $replace
	 * @param string $encoding
	 */
	function substr_format($text, $length, $replace='..', $encoding='UTF-8')
	{
		if ($text && mb_strlen($text, $encoding)>$length)
		{
			return mb_substr($text, 0, $length, $encoding).$replace;
		}
		return $text;
	}
	/**
	 *
	 * 字符编码转换
	 *
	 * */
	function xwb_iconv($source, $in, $out)
	{
		$in		= strtoupper($in);
		$out	= strtoupper($out);
		if ($in == "UTF8"){
			$in = "UTF-8";
		}
		if ($out == "UTF8"){
			$out = "UTF-8";
		}
		if($in==$out){
			return $source;
		}
	
		if(function_exists('mb_convert_encoding')) {
			return mb_convert_encoding($source, $out, $in );
		}elseif (function_exists('iconv'))  {
			return iconv($in,$out."//IGNORE", $source);
		}
		return $source;
	}
	
	
	/**
	 *  Created:  2010-10-28
	 *
	 *  截取一定长度的字符串
	 *  @Author guoliang1
	 *
	 ***************************************************/
	
	function cut_string($str, $len)
	{
		// 检查长度
		if (mb_strwidth($str, 'UTF-8')<=$len)
		{
			return $str;
		}
		// 截取
		$i 		= 0;
		$tlen 	= 0;
		$tstr 	= '';
	
		while ($tlen < $len)
		{
			$chr 	= mb_substr($str, $i, 1, 'UTF-8');
			$chrLen = ord($chr) > 127 ? 2 : 1;
			if ($tlen + $chrLen > $len) break;
			$tstr .= $chr;
			$tlen += $chrLen;
			$i ++;
		}
	
		if ($tstr != $str)
		{
			$tstr .= '...';
		}
	
		return $tstr;
	}
	/**
	 *  Created:  2010-10-28
	 *
	 *  防止XSS攻击,htmlspecialchars的别名
	 *
	 ***************************************************/
	
	function escape($str,  $quote_style = ENT_COMPAT )
	{
		return htmlspecialchars($str, $quote_style);
	}
	/**
	 * 格式化时间
	 *
	 * */
	function wb_date_format($time,$format='m月d日 H:i')
	{
		$now = time();
		$t   = $now - $time;
		if($t >= 3600)
		{
			if(date('Y')==date('Y',$time))
				$time =date($format,$time);
			else
				$time =date('Y年m月d日 H:i',$time);
		}
		elseif ($t < 3600 && $t >= 60)
			$time = floor($t / 60) . "分钟前";
		else
			$time = "刚刚";
		return $time;
	}
	
	function isChinese($string)
	{
		if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$string))
			return true;
		return false;
	}
	function isMobile($mobile)
	{
		if(preg_match("/^1[345689]\d{9}$/", $mobile))
			return true;
		return false;
	}
	function dayToWeek($time)
	{
		$time = empty($time) ? TIME : $time;
		$date[0] = '周日';
		$date[1] = '周一';
		$date[2] = '周二';
		$date[3] = '周三';
		$date[4] = '周四';
		$date[5] = '周五';
		$date[6] = '周六';
		return $date[Date('w',$time)];
	}
	
	/**
	 * 检测是否为邮箱
	 * @param $val
	 * @param $domain
	 * @return boolean
	 */
	function is_email($val,$domain="")
	{
		if(!$domain)
		{
			if( preg_match("/^[a-z0-9-_.]+@[\da-z][\.\w-]+\.[a-z]{2,4}$/i", $val) )
			return TRUE;
			return FALSE;
		}

		if( preg_match("/^[a-z0-9-_.]+@".$domain."$/i", $val) )
		return TRUE;
		return FALSE;
	}
	// junz先生
	//http://www.oschina.net/code/snippet_162279_7186
	//可逆加密
	function extend_decrypt($encryptedText,$key)
	{
		$cryptText 		= base64_decode($encryptedText);
		$ivSize 		= mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv 			= mcrypt_create_iv($ivSize, MCRYPT_RAND);
		$decryptText 	= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $cryptText, MCRYPT_MODE_ECB, $iv);
		
		return trim($decryptText);
	}
	//http://www.oschina.net/code/snippet_162279_7186
	//可逆解密
	function extend_encrypt($plainText,$key)
	{
		$ivSize			= mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv 			= mcrypt_create_iv($ivSize, MCRYPT_RAND);
		$encryptText	= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $plainText, MCRYPT_MODE_ECB, $iv);
		
		return trim(base64_encode($encryptText));
	}
	
	
?>

	
//位运算
在实际应用中可以做用户权限的应用
我这里说到的权限管理办法是一个普遍采用的方法，主要是使用到"位运行符"操作，& 位与运算符、| 位或运行符。参与运算的如果是10进制数，则会被转换至2进制数参与运算，然后计算结果会再转换为10进制数输出。
它的权限值是这样的
2^0=1，相应2进数为"0001"(在这里^我表示成"次方"，即：2的0次方，下同)

2^1=2，相应2进数为"0010"
2^2=4，相应2进数为"0100"
2^3=8，相应2进数为"1000"
要判断一个数在某些数范围内就可以使用 & 运算符(数值从上面的表中得来)
如：7=4|2|1　(你也可以简单理解成7=4+2+1)
用 & 来操作，可以知道7&4、7&2、7&1都是真的，而如果7&8则是假的
&、|　不熟悉的就要去查查手册，看看是怎么用的了
下面来看例子吧：
// 赋予权限值-->删除：8、上传：4、写入：2、只读：1
define("mDELETE",8);
define("mUPLOAD",4);
define("mWRITE",2);
define("mREAD",1);
//vvvvvvvvvvvvv使用说明vvvvvvvvvvvvv
//部门经理的权限为(假设它拥有此部门的所有权限)，| 是位或运行符，不熟悉的就查查资料
echo mDELETE|mUPLOAD|mWRITE|mREAD ,"
";// 相当于是把上面的权限值加起来：8+4+2+1=15
// 设我只有 upload 和 read 权限，则
echo mUPLOAD|mREAD ,"
";//相当于是把上传、只读的权限值分别相加：4+1=5
/*
*赋予它多个权限就分别取得权限值相加，又比如某位员工拥有除了删除外的权限其余都拥有，那它的权限值是多少?
*应该是：4+2+1＝7
*明白了怎么赋值给权限吧?
*/
//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
//判断某人的权限可用，设权限值在$key中
/*
*判断权限用&位与符，
*/
$key = 13;//13＝8+4+1
if($key & mDELETE) echo "有删除权限
"; //8
if($key & mUPLOAD) echo "有上传权限
"; //4
$a=$key & mWRITE; echo "有写权限
".$a; //无此权限
if($key & mREAD) echo "有读权限
"; //1
?>
　　OK，权限分值的这其中一个算法就是这样的，可以说是简单高效。也不知大家明白没有，不明白也没关系，记住例子就行了。前提就是做好权限值的分布，即那个1、2、4、8、16….(这里还有个顺序问题，越高级的权限就要越高的权限值，比如上面的例子所演示的删除权限)。有了权限分布表就可以确定给某个人什么权限了，你简单的理解成要哪个权限就加上相应的权限值吧。
　　这个方法很好用的，缺点就是如果权限分布得细的话，那么权限值会越来越大，你自己想想，2的几次方、如果所有的权限都要则是全部相加。不过对于一般的权限来说这个已经足够了。

下面是些简单应用举例

(1) 判断int型变量a是奇数还是偶数

a&1 = 0 偶数

a&1 = 1 奇数

(2) 取int型变量a的第k位 (k=0,1,2……sizeof(int))，即a>>k&1

(3) 将int型变量a的第k位清0，即a=a&~(1<

<>

(4) 将int型变量a的第k位置1， 即a=a|(1<

<>

(5) int型变量循环左移k次，即a=a<>16-k (设sizeof(int)=16)

(6) int型变量a循环右移k次，即a=a>>k|a<<16-k (设sizeof(int)=16)

(7)整数的平均值

对于两个整数x,y，如果用 (x+y)/2 求平均值，会产生溢出，因为 x+y 可能会大于INT_MAX，但是我们知道它们的平均值是肯定不会溢出的，我们用如下算法：

int average(int x, int y) //返回X,Y 的平均值

{

return (x&y)+((x^y)>>1);

}

(8)判断一个整数是不是2的幂,对于一个数 x >= 0，判断他是不是2的幂

boolean power2(int x)

{

return ((x&(x-1))==0)&&(x!=0)；

}

(9)不用temp交换两个整数

void swap(int x , int y)

{

x ^= y;

y ^= x;

x ^= y;

}

(10)计算绝对值

int abs( int x )

{

int y ;

y = x >> 31 ;

return (x^y)-y ; //or: (x+y)^y

}

(11)取模运算转化成位运算 (在不产生溢出的情况下)

a % (2^n) 等价于 a & (2^n – 1)

(12)乘法运算转化成位运算 (在不产生溢出的情况下)

a * (2^n) 等价于 a<< n

(13)除法运算转化成位运算 (在不产生溢出的情况下)

a / (2^n) 等价于 a>> n

例: 12/8 == 12>>3

(14) a % 2 等价于 a & 1

(15) if (x == a) x= b;

　　 else x= a;

　　 等价于 x= a ^ b ^ x;

(16) x 的 相反数 表示为 (~x+1)

在32位系统上不要右移超过32位,不要在结果可能超过 32 位的情况下左移

	
//从一个PHP数据生成 CSV 文件
这的确是一个很简单的功能，从一个PHP数组生成一个.csv文件。此函数使用 fputcsv PHP 内置函数生成逗号分隔文件（.CSV）。该函数有3个参数：数据，分隔符和CSV enclosure 默认是双引号。
<?
function generateCsv($data, $delimiter = ',', $enclosure = '"') {
   $handle = fopen('php://temp', 'r+');
   foreach ($data as $line) {
		   fputcsv($handle, $line, $delimiter, $enclosure);
   }
   rewind($handle);
   while (!feof($handle)) {
		   $contents .= fread($handle, 8192);
   }
   fclose($handle);
   return $contents;
}
?>
	
//使用PHP对数据库输入进行恶意代码清除	
这是一个有用的PHP函数清理了所有的输入数据，并删除代码注入的几率。


function sanitize_input_data($input_data) {
	$input_data = trim(htmlentities(strip_tags($input_data,",")));
	if (get_magic_quotes_gpc())
	$input_data = stripslashes($input_data);
	$input_data = mysql_real_escape_string($input_data);
	return $input_data;
}
	
//使用PHP解压文件Unzip
这是一个非常方便的PHP函数从。zip文件解压缩文件。它有两个参数：第一个是压缩文件的路径和第二个是目标文件的路径。

function unzip_file($file, $destination) {
	// create object
	$zip = new ZipArchive() ;
	// open archive
	if ($zip->open($file) !== TRUE) {
		die ('Could not open archive');
	}
	// extract contents to destination directory
	$zip->extractTo($destination);
	// close archive
	$zip->close();
	echo 'Archive extracted to directory';
}	
	
//从网页提取的关键字
真的是一个非常有用的代码片段从任何网页中提取meta关键字。

$meta = get_meta_tags('http://www.emoticode.net/');
$keywords = $meta['keywords'];
// Split keywords
$keywords = explode(',', $keywords );
// Trim them
$keywords = array_map( 'trim', $keywords );
// Remove empty values
$keywords = array_filter( $keywords );

print_r( $keywords );	

//检查服务器是否是 HTTPS
这个PHP代码片段能够读取关于你服务器 SSL 启用(HTTPS)信息。
if ($_SERVER['HTTPS'] != "on") { 
	echo "This is not HTTPS";
}else{
	echo "This is HTTPS";
}

//创建数据的URI 
因为我们知道，数据URI可以将图像嵌入到HTML，CSS和JS以节省HTTP请求。这是一个非常实用的PHP代码片段来创建数据URI。
function data_uri($file, $mime) {
  $contents=file_get_contents($file);
  $base64=base64_encode($contents);
  echo "data:$mime;base64,$base64";
}

//取得一个页面中的所有链接
取得一个页面中的所有链接
$html = file_get_contents('http://blog.0907.org');

$dom = new DOMDocument();
@$dom->loadHTML($html);

// grab all the on the page
$xpath = new DOMXPath($dom);
$hrefs = $xpath->evaluate("/html/body//a");

for ($i = 0; $i < $hrefs->length; $i++) {
       $href = $hrefs->item($i);
       $url = $href->getAttribute('href');
       echo $url.'
';
}
//使用PHP下载和保存远程图片在你的服务器中。
$image = file_get_contents('http://blog.0907.org/wp-content/uploads/2014/03/xunlei.jpg');
file_put_contents('/images/image.jpg', $image); //save the image on your server

//彻底解决跨浏览器下PHP下载文件名中的中文乱码问题
<?php

$ua = $_SERVER["HTTP_USER_AGENT"];

$filename = "中文 文件名.txt";
$encoded_filename = urlencode($filename);
$encoded_filename = str_replace("+", "%20", $encoded_filename);

header('Content-Type: application/octet-stream');

if (preg_match("/MSIE/", $ua)) {
	header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
} else if (preg_match("/Firefox/", $ua)) {
	header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '"');
} else {
	header('Content-Disposition: attachment; filename="' . $filename . '"');
}

print 'ABC';
?>

//禁用 HTTP 缓存
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");