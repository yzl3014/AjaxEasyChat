# AjaxEasyChat
![当前版本](https://img.shields.io/badge/version-1.0%E9%A2%84%E8%A7%88%E7%89%88-brightgreen)
![推荐PHP版本](https://img.shields.io/badge/PHP-7.4-9cf)
![license](https://img.shields.io/github/license/yzl3014/AjaxEasyChat?color=FF5531)
[![实例页面](https://shields.io/badge/-%E6%9F%A5%E7%9C%8B%E5%AE%9E%E4%BE%8B%E7%AB%99%E7%82%B9-informational)](https://chat.yuanzj.top/)

> 本项目可能已经被搁置，正式版将在*亿*段时间后推出。

基于html, css, js, php和mysql的轻量级聊天室。甚至到`1.0预览版`为止，它的首页还是静态的。

引用的部分源码均以注释为标识，无从考证的暂不标识。

最佳搭配方式为php7.4+mysql。如果php版本有误，可能会导致网站无法运行或报错。

`v1.0预览版`可能不稳定或不尽人意，您可以在[issues](https://github.com/yzl3014/AjaxEasyChat/issues)中提出**潜在问题或功能**。

## 💻快速开始

### 🧬先下载本项目
在`v1.0预览版`(当前版本)时，您可以直接从`code`处下载zip压缩包。如下图所示。

但是，在以后的版本中，项目右侧的`Releases`部分可能会出现内容，请优先从[Releases](https://github.com/yzl3014/AjaxEasyChat/releases)处下载。

> ![从本项目Code处下载ZIP文件](https://user-images.githubusercontent.com/79385954/215659264-41507019-5f5d-4514-96dc-ffa642c1737f.png)

### 🎈上机部署
以下操作无需多言，如果有经验，完全可以自己部署。

1、将下载好的zip压缩包文件上传到服务器中，然后解压。再释放`aec_mes.sql`数据库文件，记下数据库名(非表名)、地址(通常是localhost或127.0.0.1)等关键信息。

2、打开`MesGet.php`和`MesPost.php`，将以下代码中的变量数据替换成你的数据。

```php
$servername = "localhost"; //数据库地址
$username = "root"; //数据库用户名
$password = "root"; //数据库密码
$dbname = "sql"; //数据库名
```

3、[点击此处](https://www.google.com/recaptcha/admin/create)前往Goole reCAPTCHA控制台(**需要Goole账号和代理工具**，如果你启用了Gooreplacer等插件以便访问recaptcha，请**关闭**它们以防止重定向发生)，创建一个recaptcha v3实例，配置如下图所示。高亮部分需要手动填写。

> ![创建reCAPTCHA v3实例](https://user-images.githubusercontent.com/79385954/215661456-15f61a8e-c933-4bac-adfc-c2dc52452230.png)

4、将获取到的两个Key复制保存。第一个将公开在首页（网站密钥），第二个则需要保密（API调用密钥）。
> ![复制保存得到的两个Key](https://user-images.githubusercontent.com/79385954/215661870-9db3871f-055c-43c5-ae33-232ac3b4e4d0.png)

5、在`MesPost.php`文件的第27行中，将`{{Your recaptcha key}}`替换为API密钥(非网站密钥)。*此操作可使用`Ctrl+F`快捷键查询关键词。*
```php
/*人机安全验证*/
$post_data = array(
  'secret' => '{{Your recaptcha key}}',
  'response' => $_POST['g-recaptcha-response']
);
$rtn=send_post('https://recaptcha.net/recaptcha/api/siteverify', $post_data);
$rtn=Json_decode($rtn);
```
再将`index.php`文件的第60行中的`data-sitekey="keykeykeykeykey"`属性值替换为你的网站密钥(site key)即可。
```html
			<button value="发送" class="sendBtn g-recaptcha" data-sitekey="{{此处替换为site key}}"
				data-callback='onSubmit' data-action='submit'>发送</button>
		</form>
```

6、上线测试即可。实例网站测试截图如下：
> ![部署完成后线上测试](https://user-images.githubusercontent.com/79385954/215665402-327ae090-ba5c-488c-9019-3f990d078663.png)


## 🤔了解部分原理和功能

> 既然开源了，自己写的这堆破烂代码不说说，谁知道有啥功能。

1、无刷新：通过jquery执行ajax操作，再使用`setInterval`函数进行轮询，默认每2秒一次，**可根据服务器速度修改**；如果服务器速度慢，可能会导致请求堆积，从而无法正常访问。

2、动态消息获取：通过传递回来的json消息，根据后端给出的格式，取得最后一个消息ID，然后配合后端，从此ID开始获取消息。

3、前端速率限制：将form提交的操作先过一遍`hasSubmitted`函数，只要执行就计时，计时期间提交直接清空文本框。可以在[main.js](https://github.com/yzl3014/AjaxEasyChat/blob/main/main.js#L64)处修改。

4、Google reCAPTCHA v3 前后端对接：直接调用官方js文件，将提交按钮配置好（无解BUG：会在按钮上方加一行高度为0的盒子，导致按钮错位）后，再在后端接收POST数据，头为`g-recaptcha-response`，再调用官方API即可。

5、自动消息保存：基于Cookies，每10秒保存一次。下次访问检查是否存在。若存在，直接填充。
