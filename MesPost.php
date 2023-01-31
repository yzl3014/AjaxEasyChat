<?php
if(!isset($_POST['g-recaptcha-response'])) {
	echo '{"status":"403","describe":"recaptcha相关参数出错"}';
	exit();
}
function getRealIp() {
	//https://m.xp.cn/b.php/71754.html
	return $_SERVER['HTTP_CF_CONNECTING_IP'];
}
function send_post($url, $post_data) {
	$postdata = http_build_query($post_data);
	$options = array(
		    'http' => array(
		      'method' => 'POST',
		     'header' => 'Content-type:application/x-www-form-urlencoded',
		     'content' => $postdata,
		      'timeout' => 15 * 60 // 超时时间（单位:s）
	)
		  );
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	return $result;
}

/*人机安全验证*/
$post_data = array(
  'secret' => '{{Your recaptcha key}}',
  'response' => $_POST['g-recaptcha-response']
);
$rtn=send_post('https://recaptcha.net/recaptcha/api/siteverify', $post_data);
$rtn=Json_decode($rtn);
//print_r($rtn);
if(empty($rtn->score)) {
	echo '{"status":"403","describe":"recaptcha相关参数出错"}';
	exit();
}
if($rtn->score < 0.5) {
	echo '{"status":"1000","describe":"您的真人率过低('.$rtn->score.')，请刷新后重试。"}';
	exit();
}
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sql";
// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
	die("连接失败: " . $conn->connect_error);
} else {
	$conn->query("set character set utf8mb4");
	//设置读取时的编码为utf8mb4，防止emoji变问号
	$conn->query("SET NAMES 'utf8mb4';");
	//双保险。至于为什么需要，三个字：不知道
}
$MesContent = htmlspecialchars($_POST['content']);
//防止xss攻击
if ($_POST['name'] != "") {
	$name = htmlspecialchars($_POST['name']);
} else {
	$name = "匿名[" . getRealIp() . "]";
	if(stripos($_POST['name'])!==0 && getRealIp()!="0.0.0.0")$name = $_POST['name']."[" . getRealIp() . "]";
}

//去除不良词语
$MesContent = str_replace("傻逼", "**", $MesContent);
$MesContent = str_replace("二逼", "**", $MesContent);
$MesContent = str_replace("sb", "**", $MesContent);
$MesContent = str_replace("操你妈", "***", $MesContent);
$MesContent = str_replace("法轮", "**", $MesContent);

//发送消息
$sql = "INSERT INTO `". $dbname."`.`aec_mes` (`name`, `user_id`, `content`, `time`, `type`, `recipient`, `ip`) VALUES ('" . $name . "', '1', '" . $MesContent . "', '" . date("Y-m-d H:i:s") . "', 'mes', '#', '".$_SERVER["HTTP_CF_CONNECTING_IP"]."');";
$rtn = $conn->query($sql);
$conn->close();
echo '{"status":"200","describe":"发送操作完成"}';