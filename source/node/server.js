require('./config');

var io = require('socket.io').listen(config.socketPort),
		mysql = require("mysql"),
		redis = require("redis"),
		user = require('./user');

global.cookie = require("cookie");

global.redisClient = redis.createClient(config.redis.port, config.redis.host);
global.redisClient.on("error", function (err) {
	console.log("Error " + err);
});

global.mysqlClient = mysql.createConnection(config.mysql);
mysqlClient.on('error', function(err) {
  console.log(err);
});

setInterval(function(){
	myClient.ping();
},60000);


io.of("/user")
.authorization(user.auth)
.on('connection', function(socket) {
	//console.log(socket.handshake.cookies);
	var uid = socket.handshake.cookies.uid;
	socket.user_id = uid;

	user.list[uid] = socket;
	console.log(uid);
	
	socket.emit("loginResult", {error:0, msg:'success!'}); // 返回用户登录结果
	user.listenEvent(socket);
});

var broadcast = require("./broadcast");
io.of('/broadcast').on('connection', function (socket) {
	broadcast.listenEvent(socket);
});
