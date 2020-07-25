function getCookie(name) {
	var value = "; " + document.cookie;
	var parts = value.split("; " + name + "=");
	if(parts.length == 2) return parts.pop().split(";").shift();
}

(function($, owner) {
	var server_url = "/php/";
	/**
	 * 用户登录
	 **/
	owner.login = function(loginInfo, callback) {
		callback = callback || $.noop;
		loginInfo = loginInfo || {};
		loginInfo.account = loginInfo.account || '';
		loginInfo.password = loginInfo.password || '';
		if(loginInfo.account.length < 3) {
			return callback('账号最短为 3 个字符');
		}
		if(loginInfo.password.length < 6) {
			return callback('密码最短为 6 个字符');
		}

		$.post(server_url + "user.php", {
			username: loginInfo.account,
			password: loginInfo.password,
			action: "login"
		}, function(data) {
			if(data.ret == 0) {
				callback();
			} else {
				callback(data.desc);
			}
		}, 'json');

	};

	/**
	 * 新用户注册
	 **/
	owner.reg = function(regInfo, callback) {
		callback = callback || $.noop;
		regInfo = regInfo || {};
		regInfo.account = regInfo.account || '';
		regInfo.password = regInfo.password || '';
		if(regInfo.account.length < 3) {
			return callback('用户名最短需要 3 个字符');
		}
		if(regInfo.password.length < 6) {
			return callback('密码最短需要 6 个字符');
		}

		$.post(server_url + "user.php", {
			username: regInfo.account,
			password: regInfo.password,
			truename: regInfo.truename,
			action: "register"
		}, function(data) {
			if(data.ret == 0) {
				callback();
			} else {
				callback(data.desc);
			}
		}, 'json');
	};

	/**
	 * 获取当前状态
	 **/
	owner.getState = function() {
		var stateText = localStorage.getItem('$state') || "{}";
		return JSON.parse(stateText);
	};

	var checkEmail = function(email) {
		email = email || '';
		return(email.length > 3 && email.indexOf('@') > -1);
	};

	/**
	 * 找回密码
	 **/
	owner.forgetPassword = function(email, callback) {
		callback = callback || $.noop;
		if(!checkEmail(email)) {
			return callback('邮箱地址不合法');
		}
		return callback(null, '新的随机密码已经发送到您的邮箱，请查收邮件。');
	};

	owner.getCarList = function(callback) {
		$.getJSON(server_url + "car.php", {
			action: "getCarList"
		}, function(data) {
			callback(data);
		});
	}

	owner.addCar = function(info, callback) {
		callback = callback || $.noop;
		info = info || {};
		info.action = "addCarInfo";

		$.post(server_url + "car.php", info, function(data) {
			if(data.ret == 0) {
				callback();
			} else {
				callback(data.desc);
			}
		}, 'json');
	};
	owner.updateCar = function(info, callback) {
		callback = callback || $.noop;
		info = info || {};
		info.action = "updateCarInfo";

		$.post(server_url + "car.php", info, function(data) {
			if(data.ret == 0) {
				callback();
			} else {
				callback(data.desc);
			}
		}, 'json');
	};
	owner.getCarById = function(info, callback) {
		$.getJSON(server_url + "car.php", {
			id: info.id,
			action: "getCarById"
		}, function(rsp) {
			callback(rsp);
		});
	}

}(mui, window.app = {}));