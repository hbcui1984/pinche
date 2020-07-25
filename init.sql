CREATE TABLE `userlist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `true_name` varchar(32) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `register_time` datetime DEFAULT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

CREATE TABLE `pinche_reqlist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `car_id` int(11) NOT NULL COMMENT '拼车信息ID',
  `user_id` int(11) NOT NULL COMMENT '申请人用户ID',
  `phone` varchar(32) NOT NULL DEFAULT '' COMMENT '联系电话',
  `seat_req` int(11) NOT NULL DEFAULT '1' COMMENT '申请座位数',
  `req_date` datetime NOT NULL COMMENT '申请时间',
  `result` int(11) DEFAULT '0' COMMENT '申请状态：0-待确认；1-已确认；2-已拒绝；',
  `message` varchar(128) DEFAULT NULL COMMENT '留言',
  `confirm_date` datetime DEFAULT NULL COMMENT '车主确认时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `pinche_carlist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '发布人用户ID',
  `from_city` varchar(32) NOT NULL DEFAULT '' COMMENT '出发城市',
  `to_city` varchar(32) NOT NULL DEFAULT '' COMMENT '到达城市',
  `start_time` datetime NOT NULL COMMENT '出发时间',
  `position` varchar(64) DEFAULT NULL COMMENT '上车位置',
  `phone` varchar(32) NOT NULL COMMENT '联系电话',
  `seats_left` int(11) NOT NULL DEFAULT '0' COMMENT '剩余座位数',
  `type` int(11) DEFAULT NULL COMMENT '帖子类型，1-车找人；2-人找车',
  `create_date` datetime DEFAULT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;