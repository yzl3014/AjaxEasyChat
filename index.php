<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>AjaxEasyChat</title>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<!-- 基本meta属性，有利于SEO -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="renderer" content="webkit" />
	<meta name="force-rendering" content="webkit" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
	<meta name="description" content="AjaxEasyChat，一个超轻量级的聊天室！不需要Node.js或docker，虚拟主机也能轻松搭建！快来试试测试版本吧！" />
	<meta name="keywords" content="聊天室,ajax,html5,h5,php" />
	<meta name="author" content="yuanzj, y@yuanzj.top" />
	<meta name="format-detection" content="telphone=no, email=no" />
	<!-- 引入必要的文件 -->
	<link rel="stylesheet" href="index.css" />
	<script src="https://cdn.staticfile.org/jquery/3.6.3/jquery.min.js"></script>
	<script src="https://cdn.staticfile.org/layui/2.7.6/layui.min.js"></script>
	<script src="cookie.js"></script>
	<!-- <script src="ban.js"></script> -->
	<script>
		$(document).ready(function () {
			var cookie_lastInput = getCookie("lastInput");
			var cookie_name = getCookie("name");
			if (cookie_lastInput != "" && cookie_lastInput != undefined) $("#contentInput").val(cookie_lastInput);
			if (cookie_name != "" && cookie_name != undefined) {
				var nameInput = document.getElementById("nameInput");
				$("#nameInput").val(cookie_name);
				nameInput.style.display = "none";
				document.getElementById("contentInput").setAttribute("placeholder", "畅所欲言...按下回车后发送");
			}
		});
		var first_get = true;//首次获取不发出铃声
	</script>
	<!-- 引入recaptcha v3验证模块 -->
	<script src="https://recaptcha.net/recaptcha/api.js"></script>
</head>

<body>
	<div class="mainBox">
		<!-- 聊天消息显示框，消息以P标签的形式显示 -->
		<div class="chatBox" id="chatbox">
			<p style="color:red;">公告：本聊天室因JavaScript原因不支持Internet Explorer(IE)系列浏览器。</p>
			<p style="color:#EE9A00;">欢迎进入公共聊天室！数据正在载入，Sit back and relax...</p>
		</div>
		<!-- 空行 -->
		<div style="height:3px;"></div>
		<!-- 表单，提交消息信息-->
		<form action="MesPost.php" method="post" target="myiframe" onsubmit="return hasSubmitted()" id="submitContent">
			<!--名称框-->
			<input class="nameInputBox" type="text" autocomplete="off" placeholder="昵称(自动保存,不填则为IP)" name="name"
				id="nameInput"></input>
			<!--消息编辑框-->
			<input class="textInputBox" type="text" name="content" id="contentInput" required="required"
				placeholder="畅所欲言...按下回车后发送" autocomplete="off" maxlength="100" autofocus></input>
			<!--“发送”按钮
			<input  value="发送" class="sendBtn"></input>-->
			<button value="发送" class="sendBtn g-recaptcha" data-sitekey="6LdcKDkkAAAAAPkKwyB0EYeWQD0IBNdBJYqDnuok"
				data-callback='onSubmit' data-action='submit'>发送</button>
		</form>
		<!-- 下面这是一个不显示的iframe，可以实现表单提交后不刷新 -->
		<iframe frameborder=0 id="myiframe" name="myiframe" style="display:none;"></iframe>
	</div>
	<!-- footer相关信息 -->
	<footer><a href="https://github.com/yzl3014/AjaxEasyChat">AjaxEasyChat</a> | By <a href="https://yuanzj.top">Yuanzj</a> | v1.0</footer>
</body>
<script src="/main.js"></script>
</script>

</html>