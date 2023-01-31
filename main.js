	var mesList, lastid = 0;//mesList：消息数组。lastid：最后一个消息ID
	var countDown = 0;//速率限制倒计时
	const music = new Audio('normal.mp3');//定义音乐：normal.mp3是新消息音乐

	/*消息获取事件循环*/
	setInterval(() => {
		var element = document.getElementById("chatbox");//获取元素ID
		$.get("/MesGet.php?lastID=" + lastid,
			function (data, status) {//AJAX请求
				//console.log(data);
				mesList = JSON.parse(data);//拆分成json格式
				//console.log(mesList);
				if (JSON.stringify(mesList) != '{}') {
					lastid = mesList[mesList.length - 2]['id'];
					//获取到最后一个消息的id，下一次就从最后一个id读取，减轻服务器负担
				}
			}
		);
		for (var i = 0; i < (mesList.length - 1); i++) {
			element.innerHTML += "<p><b class=\"senderName\">"
				+ mesList[i]['name'] + "</b>: "
				+ mesList[i]['content']
				+ "<a  class=\"senderTime\">"
				+ mesList[i]['time']
				+ "</a></p>";//组成一条消息
			element.scrollTop = element.scrollHeight;//将滚动条滚动到底部
			if (first_get == false) {
				music.play();//播放收到消息铃声
			}
		}
		if (first_get) {
			first_get = false;
		}
	}, 2000);//定时执行

	/*内容自动保存*/
	setInterval(() => {
		if (getCookie("lastInput") != "") delCookie("lastInput");//如果cookie为空，就删除
		var d = new Date();
		var expiryDate = new Date(d.getTime() + (1 * 3600000));//3600000毫秒=1小时
		setCookie("lastInput", $("#contentInput").val(), expiryDate, "/");//保存cookie
	}, 10000);//10秒自动保存一次

	/*配合recaptcha使用*/
	function onSubmit(token) {
		if (hasSubmitted()) {//调用速率限制器，因为recaptcha的技术，导致只能调用，不能从form里定义。
			document.getElementById("submitContent").submit();
		}
	}

	/*速率限制器*/
	function hasSubmitted() {
		if (countDown == 0) {//发送消息后，马上开始计时。
			setInterval(() => {
				countDown = countDown + 1;
			}, 100);//按0.1秒就加1计时
			//隐藏昵称框并保存昵称置cookie
			document.getElementById("nameInput").style.display = "none";
			var d = new Date();
			var expiryDate = new Date(d.getTime() + (1 * 3600000));//3600000毫秒=1小时
			setCookie("name", $("#nameInput").val(), expiryDate, "/");
			return true;//这里表示允许发送
		}
		if (countDown < 30) {//如果发送时计时器小于30×100=3000毫秒，则速率限制。
			layer.msg("发送频率过快(time:" + (countDown - 30) + ")");
			document.getElementById('contentInput').value = "";//直接清空输入框
			return false;
		} else {
			countDown = 0;
		}
		return true;
	}
	$("#myiframe").on("load", function () {
		var text = $(this).contents().find("body").text(); //获取到的是json的字符串
		var j = $.parseJSON(text);  //json字符串转换成json对象
		if (j['status'] != "200") {
			layer.open({
				title: 'ERROR'
				, content: '状态码：' + j['status'] + "<br>错误描述：" + j['describe']
				, icon: 2
			});
		} else {
			//layer.msg("已发送文本");
			document.getElementById('contentInput').value = "";//直接清空输入框
		}
	})