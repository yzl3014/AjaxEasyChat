<?php
//header("Content-Type: application/json; charset=utf-8");
//上面那一行写了，前端的JS就报错。乱动即崩。

if (isset($_GET['lastID'])) {
    if (!is_numeric($_GET['lastID']))
        die('{"content":"_ERR_ID_NOT_A_NUM_"}');//如果lastID不是整数，则报错
}
if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
    die("Forbidden.");//如果REFERER为空或不存在，则报错。
}

$get_last = $_GET['lastID'];

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
    $conn->query("set character set utf8mb4"); //设置读取时的编码为utf8mb4，防止emoji变问号
    $conn->query("SET NAMES 'utf8mb4';"); //双保险。至于为什么需要，三个字：不知道
}

//读取数据
if ($get_last > 0) { //如果要求只读取最后一行（减轻负担）
    $last = $conn->query("SELECT * FROM `". $dbname."`.`aec_mes` ORDER BY ID DESC LIMIT 1;");
    //先获取最后一个消息的ID，假如说2秒内有3个消息，只取最后一个是不行的。
    $row = $last->fetch_assoc();
    //肯定只有一行（说了最后一条消息），直接转换。
    $last_id = $row['id']; //获取到最后一个ID
    $sql = "SELECT * FROM `". $dbname."`.`aec_mes` ORDER BY ID DESC LIMIT " . bcsub($last_id, intval($get_last)) . " ;";
    /*假如说，用户端发送请求表示“我这最后的ID是10”，服务器这发现最后一条消息的ID是13，
     *所以，在上一次获取到本次获取中，多了3条消息。所以只需要取3条消息（减法操作），而不是全部取一遍。
     *这样能很好的减轻SQL的负担，防止操作次数过多，导致崩溃。
     */
} else {
    $sql = "SELECT * FROM `". $dbname."`.`aec_mes` LIMIT 1000;";
    //如果没有指定头，就说明这次获取是第一次获取，数据需要全取出来。
}
$result = $conn->query($sql); //发送请求


if ($result->num_rows > 0) {
    // 输出数据
    echo '[';
    while ($row = $result->fetch_assoc()) {
        echo '{"id":"' . $row['id'] . '","name":"' . $row['name'] . '","user_id":"' . $row['user_id'] . '","content":"' . $row['content'] . '","time":"'.$row['time'].'","type":"' . $row['type'] . '","recipient":"' . $row['recipient'] . '"},';
        //输出示例：{"id":"1","name":"admin","user_id":"1","content":"test123测试","type":"mes","recipient":"#"}

    }
    echo '{}]';
    /*最后为什么返回个空？
    1、如果数据库里暂时没有新消息（新消息怎么获取？看上面），返回内容将会为空，前端一直报错，影响性能。
    2、上面的while返回数据时，为了符合json格式（数组示例：[{123},{123},{456}]），只能带个逗号。
    但是，最后一个项目如果还带着逗号，前端就解析不了。为了保证写最少的后端，这里加个空，防止出故障。
    */
} else {
    echo "{}";
}
$conn->close();