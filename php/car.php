<?php

session_start();
date_default_timezone_set("PRC");

$method = $_SERVER['REQUEST_METHOD'];
$r = array('ret' => 0, 'desc' => '');

if (empty($method)) {
	$r = array('ret' => 1100, 'desc' => '非法请求');
	exit(json_encode($r));
}

//TODO 修改用户名、密码
$mysqli = new mysqli("localhost", "root", "password", "pinche");

if ($method == "GET") {
	$action = $_GET['action'];
	if ($action == "getCarList") {
		//获取拼车列表信息
		$query = "select * from pinche_carlist order by id desc";
		if ($result = $mysqli -> query($query)) {
			$r['data'] = array();
			/* fetch associative array */
			while ($row = $result -> fetch_assoc()) {
				$owner_id = $row['user_id'];
				if ($_SESSION['userid'] == $owner_id) {
					$row['is_owner'] = 1;
				}
				

				//联网获取发布者真实姓名
				$query = "select true_name from userlist where `id` = '$owner_id'";
				if ($result_inner = $mysqli -> query($query)) {
					if ($row_inner = $result_inner -> fetch_assoc()) {
						$row['true_name'] = $row_inner['true_name'];
					}
				}
				$result_inner -> free();
				
				array_push($r['data'], $row);

			}

			/* free result set */
			$result -> free();
		}
		/* close connection */
		$mysqli -> close();

		exit(json_encode($r));
	} elseif ($action == "getCarById") {
		$id = $_GET['id'];
		if (empty($id)) {
			$r = array('ret' => 1100, 'desc' => '非法请求');
			exit(json_encode($r));
		}
		$query = "select * from pinche_carlist where id='$id'";

		if ($result = $mysqli -> query($query)) {
			$uid = '';
			if ($row = $result -> fetch_assoc()) {
				$r['data'] = $row;
				$uid = $row['user_id'];
			}
			/* free result set */
			$result -> free();
			if (!empty($uid)) {
				$query = "select true_name from userlist where `id` = '$uid'";
				if ($result = $mysqli -> query($query)) {
					if ($row = $result -> fetch_assoc()) {
						$r['data']['true_name'] = $row['true_name'];
					}
				}
				$result -> free();
			} else {
				$r = array('ret' => 1100, 'desc' => '数据查询错误');
			}

		}
		/* close connection */
		$mysqli -> close();
		exit(json_encode($r));
	}
} elseif ($method == "POST") {
	$action = $_POST['action'];
	$user_id = $_SESSION['userid'];
	if (!isset($user_id) || empty($user_id)) {
		$r = array('ret' => 1101, 'desc' => '需要登录才能操作');
		exit(json_encode($r));
	}
	if ($action == "addCarInfo") {//车主发布信息

		$from_city = $_POST['from_city'];
		$to_city = $_POST['to_city'];

		$timestamp = strtotime($_POST['start_time']);
		$start_time = date("Y-m-d H:i:s", $timestamp);
		$position = $_POST['position'];
		$phone = $_POST['phone'];
		$seats_left = $_POST['seats_left'];
		
		$type = $_POST['type'];

		$query = "insert into pinche_carlist (`user_id`,`from_city`,`to_city`,`start_time`,`position`,`phone`,`seats_left`,`type`,`create_date`) values ('$user_id','$from_city','$to_city','$start_time','$position','$phone','$seats_left','$type',NOW())";

		if ($mysqli -> query($query) == true) {
			$r["desc"] = '发布成功';
		} else {
			$r["desc"] = $mysqli -> error;
		}

		exit(json_encode($r));
	} elseif ($action == "updateCarInfo") {
		$id = $_POST['id'];
		$query = "select * from pinche_carlist where id='$id'";

		if ($result = $mysqli -> query($query)) {

			if ($row = $result -> fetch_assoc()) {
				if ($row['user_id'] == $user_id) {
					$from_city = $_POST['from_city'];
					$to_city = $_POST['to_city'];

					$timestamp = strtotime($_POST['start_time']);
					$start_time = date("Y-m-d H:i:s", $timestamp);
					$position = $_POST['position'];
					$phone = $_POST['phone'];
					$seats_left = $_POST['seats_left'];
					$query = "update pinche_carlist set `from_city` = '$from_city',`to_city`='$to_city',`start_time` = '$start_time',`position` = '$position',`phone` = '$phone',`seats_left` = '$seats_left' where id = '$id'";

					if ($mysqli -> query($query) == true) {
						$r["desc"] = '修改成功';
					} else {
						$r["desc"] = $mysqli -> error;
					}

				} else {
					$r = array('ret' => 1102, 'desc' => $row['user_id'] . "," . $id);
				}
			}

			/* free result set */
			$result -> free();
			exit(json_encode($r));
		}
	}
}
?>