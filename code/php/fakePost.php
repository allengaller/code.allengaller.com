<?php
// PHP POST数据的三种方法
// php有三种方法可以post数据,分别为Curl、socket、file_get_contents:

/**
 * Socket版本
 * 使用方法：
 * $post_string = "app=socket&version=beta";
 * request_by_socket('facebook.cn','/restServer.php',$post_string);
 */
function request_by_socket($remote_server, $remote_path, $post_string, $port = 80, $timeout = 30)
{
	$socket = fsockopen($remote_server, $port, $errno, $errstr, $timeout);
	if (!$socket) die("$errstr($errno)");

	fwrite($socket, "POST $remote_path HTTP/1.0\r\n");
	fwrite($socket, "User-Agent: Socket Example\r\n");
	fwrite($socket, "HOST: $remote_server\r\n");
	fwrite($socket, "Content-type: application/x-www-form-urlencoded\r\n");
	fwrite($socket, "Content-length: " . (strlen($post_string) + 8) . '\r\n');
	fwrite($socket, "Accept:*/*\r\n");
	fwrite($socket, "\r\n");
	fwrite($socket, "mypost=$post_string\r\n");
	fwrite($socket, "\r\n");
	$header = "";
	while ($str = trim(fgets($socket, 4096))) {
		$header .= $str;
	} 
	$data = "";
	while (!feof($socket)) {
		$data .= fgets($socket, 4096);
	} 
	return $data;
} 



/**
 * Curl版本
 * 使用方法：
 * $post_string = "app=request&version=beta";
 * request_by_curl('http://facebook.cn/restServer.php',$post_string);
 */
function request_by_curl($remote_server, $post_string)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $remote_server);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'mypost=' . $post_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "Jimmy's CURL Example beta");
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
} 


/**
 * 其它版本
 * 使用方法：
 * $post_string = "app=request&version=beta";
 * request_by_other('http://facebook.cn/restServer.php',$post_string);
 */
function request_by_other($remote_server, $post_string)
{
	$context = array(
		'http' => array(
			'method' => 'POST',
			'header' => 'Content-type: application/x-www-form-urlencoded' .
						'\r\n'.'User-Agent : Jimmy\'s POST Example beta' .
						'\r\n'.'Content-length:' . strlen($post_string) + 8,
			'content' => 'mypost=' . $post_string)
		);
	$stream_context = stream_context_create($context);
	$data = file_get_contents($remote_server, false, $stream_context);
	return $data;
} 

//以程序登陆一个论坛登录为例
function bbslogin($user_login, $password, $host, $port = "80") {
	//需要提交的post数据
	$argv = array('cookie' => array('user_login' => $user_login, 'password' => $password, '_wp_http_referer' => '/bbpress/', 're' => '', 'remember' => true));
	foreach ($argv['cookie'] as $key => $value) {
		$params[] = $key . '=' . $value;
	}
	$params = implode('&', $params);
	$header = "POST /bbpress/bb-login.php HTTP/1.1\r\n";
	$header .= "Host:$host:$port\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($params) . "\r\n";
	$header .= "Connection: Close\r\n\r\n";
	$header .= $params;
	$fp = fsockopen($host, $port);
	fputs($fp, $header);
	while (!feof($fp)) {
		$str = fgets($fp);
		//以下是自己的逻辑代码,这里主要是模拟cookie,可用来同步登陆
		if (!(strpos($str, "Set-Cookie:") === false)) {
			$tmparray = explode(" ", $str);
			$cookiearray = explode("=", $tmparray[1]);
			$cookiepaths = explode("=", $tmparray[6]);
			$cookiename = urldecode($cookiearray[0]);
			$cookievalue = urldecode(substr($cookiearray[1], 0, strlen($cookiearray[1]) - 1));
			$cookietime = time() + 3600 * 24 * 7;
			$cookiepath = urldecode(substr($cookiepaths[1], 0, strlen($cookiepaths[1]) - 1));
			setcookie($cookiename, $cookievalue, $cookietime, $cookiepath);
		}
	}
	fclose($fp);
}