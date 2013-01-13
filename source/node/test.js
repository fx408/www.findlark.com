var io = require('socket.io').listen(80),
		redis = require("redis"),
		client = redis.createClient(6379, '127.0.0.1'),
		socketList = {}; // ���ӵ��û��б�
		connectCount = 0, // ��ǰ������
		maxConnect = 100; // �������������

io.sockets.on('connection', function (socket) {
	if(connectCount > maxConnect) return false;
	++connectCount;
	
	socketList[socket.id] = socket;
	console.log("connect:"+socket.id);

	socket.on('disconnect', function () {
		delete socketList[socket.id];
		--connectCount;
		console.log("disconnect:"+socket.id);
	});
	
	firstConnectMsg();
});

// ����һ������ ���û�������Ϣ
function firstConnectMsg() {
	var msg = '{"title":"Hello. Click me!", "content":"Hello, Do you want to say something?"}';

	send(msg);
}

// �㲥����ÿ�������ϵĿͻ��� ������Ϣ
function send(msg) {
	msg = formatMsg(msg);
	if(!msg) return run();

	for(var k in socketList) {
		socketList[k].emit("news", msg);
	}
	
	run();
}

/*
 * ���ַ�����Ϣ ��ʽ�� Ϊ JSON ��Ϣ��ʧ��ʱ���� false
 * @param String msg JSON ��ʽ�ַ���
 * @return JSON Object OR false
 */
function formatMsg(msg) {
	try{
		msg = JSON.parse(msg);
	} catch(e) {
		console.log(e);
		return false;
	}
	var date = new Date();
	msg.time = date.toLocaleTimeString();

	var defaultMsg = {
		title: '',
		content: '',
		icon:'say',
		author: '',
		latitude: 0,
		longitude: 0,
		time: 0
	};
	
	for(var k in msg) {
		defaultMsg[k] = msg[k];
	}
	return defaultMsg;
}

/*
 * �� redis ������ȡ��Ϣ���з��ͣ�
 * ���� redis ����������
 */
var maxBlockTime = 15; // �����ʱ�� (s)
function run() {
	console.log("run");
	client.brpop("findlark_msg", maxBlockTime, function(err, msg) {
		console.log(err);
		console.log(msg);
		if(msg && msg[1]) {
			send(msg[1]);
		} else {
			run();
		}
	});
}

run();