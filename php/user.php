<?php

session_start();

$name = $_POST['username'];
$password = $_POST['password'];

$action = $_POST['action'];

$r = array('ret' => 0, 'desc' => '');

if (empty($name) || empty($password)) {
	$r['ret'] = 1001;
	$r['desc'] = '用户名、密码不允许为空';
	exit(json_encode($r));
}

if(empty($action) || !in_array($action, array("login","register"))){
	$r = array('ret' => 1100, 'desc' => '非法请求');
	exit(json_encode($r));
}

//TODO 修改用户名、密码
$mysqli = new mysqli("localhost", "root", "password", "pinche");

if ($mysqli->connect_errno) {
    $r = array('ret' => 1200, 'desc' => '数据库链接失败');
   exit(json_encode($r));
}

if ($action == "login") {
	$result = $mysqli->query("select * FROM `userlist` where `username` = '$name' and password = '$password'");
	$row = $result->fetch_assoc();

	if ($row) {
		$r['desc'] = "鉴权成功";
		
		$_SESSION['username'] = $name;
		$_SESSION['userid'] = $row['id'];
		setcookie("pinche_user",$name."_".$row['id'],time()+604800);//cookie有效期为一周
	} else {
		$r['ret'] = 1002;
		$r['desc'] = '用户名、密码错误';
	}

	$mysqli->close();

	exit(json_encode($r));
} elseif ($action == "register") {
	$truename = $_POST['truename'];
	if(empty($truename)){
		$r['ret'] = 1002;
		$r['desc'] = '真实姓名不允许为空';
	}else{
		$result = $mysqli->query("select * FROM `userlist` where `username` = '$name'");
		$row = $result->fetch_assoc();
		if($row){
			$r['ret'] = 1003;
			$r['desc'] = '用户名已存在，再换一个试试';
		}else{
			//开始插入数据
			if($mysqli->query("insert into userlist (`username`,`password`,`true_name`,`register_time`) values ('$name','$password','$truename',NOW())")==true){
				$_SESSION['username'] = $name;
				$_SESSION['userid'] = $mysqli->insert_id;
				$r["desc"] = '注册成功';
			}else{
				$r["desc"] = $mysqli->error;
			}
		}
		$mysqli->close();
	}
	exit(json_encode($r));
}
?>