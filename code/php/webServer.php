<?php
/*
注意需安装 PHP pthreads 扩展 方可运行

PHP-CLI/1.0

PHP “phpWebServer.php” 8080
*/
class pthread extends thread {
	
	private $socket;
	
	public function __construct($socket) {
	
		$this->socket = $socket;
		
	}
	
	public function run() {
		
		date_default_timezone_set('UTC');
		
		$timeout = 5;
		$connfd = socket_accept($this->socket);
		if(!$connfd) {
			echo socket_strerror();
			exit;
		}
		socket_set_option($connfd, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $timeout, 'usec' => 0));
		
		$buffer = '';
		while (( $buffer .= socket_read($connfd, 1024, PHP_BINARY_READ) ) != NULL)
		if(strpos($buffer, "\r\n\r\n") !== false) break;
		
		$headers = array();
		$headers[] = 'HTTP/1.1 200 OK';
		$headers[] = 'Date: '. date('D, d M Y H:i:s'). ' GMT';
		$headers[] = 'Server: PHP-CLI/1.0';
		$headers[] = 'Content-Type: text/html; charset=utf-8';
		$headers[] = 'Connection: close';
		
		$response = array(
		'header'=> implode("\r\n", $headers) . "\r\n",
		'html'=> '<pre /><div>I\'m a PHP-CLI/1.0</div>');
		
		socket_write($connfd, implode("\r\n", $response));
		socket_close($connfd);
		socket_close($this->socket);
		
	}
	
}

error_reporting(0);
set_time_limit(0);
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket, '0.0.0.0', $argv[1]);
socket_listen($socket);

$i = 0;

while(1) {
	$pthread[$i] = new pthread($socket);
	$pthread[$i]->start();
	$pthread[$i]->join();
}