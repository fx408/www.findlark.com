<div class="main" style="text-align:center">
	<button id="loginButton">登录爱奇艺</button>
	<div id="loginInfo">&nbsp;</div>
	
	<div><a href="http://www.iqiyi.com" id="iqiyi">爱奇艺</a></div>
</div>

<iframe id="loginIframe" style="display:none"></iframe>

<script type="text/javascript">

function info(msg, error) {
	var color = error ? 'red' : 'green';
	$("#loginInfo").css("color", color).html(msg);
}

$(function() {
	
	$("#loginButton").click(function() {
		$(this).attr("disabled", "disabled");
		
		info('正在获取登录地址...');
		
		$.get('/tool/qiyi', {}, function(data) {
			if(data.error == 0) {
				info('正在登录...');
				$("#loginScript").attr("src", data.msg);
			} else {
				info(data.msg, true);
				$("#loginInfo").css("color", "red").html(data.msg);
			}
		}, 'json');
		
	});
	
	$("#loginScript").load(function() {
		if(__pc__login && __pc__login.code == 'A00000') {
			info('登录成功!3秒后自动转到爱奇艺，若未跳转，请点击下方连接。');
			setTimeout(function() {
				window.location.href = $("#iqiyi").attr("href");
			}, 3500);
		} else {
			info('登录失败!请尝试重新登录!', true);
		}
		
		$("#loginButton").removeAttr("disabled");
	});
});

</script>
<script type="text/javascript" id="loginScript"></script>