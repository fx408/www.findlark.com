var bookTemplate = ''
+ '<div class="book">'
+ '	<div class="book-image">'
+ '		<img src="{$img}" class="img-rounded">'
+ '	</div>'
+ '	<div class="book-info">'
+ '		<div>'
+ '			<a href="/douban/default/book/id/{$bookid}" class="ui-link" data-transition="none">{$title}</a>'
+ '		</div>'
+ '		<div>'
+ '			作者: {$author}'
+ '		</div>'
+ '		<div>'
+ '			评分:'
+ '			<span class="text-red">{$score}</span>'
+ '			<small class="muted">({$numRaters}评)</small>'
+ '		</div>'
+ '  <div><a href="/douban/default/noteList/bookid/{$bookid}" class="ui-link" data-transition="none">读书笔记</a></div>'
+ '	</div>'
+ '	<div class="clear"></div>'
+ '	<div>{$summary}</div>'
+ '</div>';

var noteTemplate = ''
+ '<div class="note">'
+ '	<div class="user_avatar">'
+ '		<img src="{$user_avatar}" uid="{$user_uid}" class="img-rounded">'
+ '	</div>'
+ ' <div class="note-info">'
+ '	<div><a href="http://book.douban.com/people/{$user_uid}/" target="_blank">{$user_name}</a></div>'
+ '	<div>'
+ '		<a href="javascript:;" class="note_detail" noteid="{$id}">第{$page_no}页</a>'
+ '		<small class="muted">{$time}</small>'
+ '	</div>'
+ ' </div>'
+ '	<div class="clear"></div>'
+ '	<pre>{$summary}</pre>'
+ '</div>';

function _AppBook() {
	this.keyPrefix = 'book_';
	this.page = 1;
	this.listBusy = false;

	this.loadInfo = function(msg) {
		$("#load-more").children().html(msg);
	}
	
	// 显示列表
	this.showList = function(data) {
		var html = '';
		
		for(var i in data) {
			var tmp = bookTemplate;
			for(var k in data[i]) {
				var reg = new RegExp("\\{\\$"+k+"\\}", 'ig');
				tmp = tmp.replace(reg, data[i][k]);
			}
			html += tmp;
		}
		
		$("#book-list").append(html);
	}
	
	// 获取列表
	this.getBookList = function() {
		if(this.listBusy) return;
		
		var _this = this;
		this.listBusy = true;
		
		_this.loadInfo('加载中...');
		this.ajaxRequest(
			'/douban/default/list/',
			{page: this.page},
			function(data) {
				_this.listBusy = false;
				if(data.error == 0) {
					_this.page = ++data.params.page;
					_this.showList(data.msg);
					_this.loadInfo('点击加载更多...');
				} else {
					_this.loadInfo(data.msg);
				}
			},
			function() {
				_this.listBusy = false;
				_this.loadInfo('读取数据失败!');
			}
		);
	}
	
	// ajax request
	this.ajaxRequest = function(url, params, success, error) {
		var _this = this;
		
		$.ajax({
			url: url,
			data: params,
			dataType: "json",
			type: "post",
			success: (typeof success == "function" ? success : function(data) {
				if(data.error == 0) {
					_this[success](data.msg);
				} else {
					_this.showMessage(data.msg, true);
				}
			}),
			error: (typeof error == "function" ? error : function() {
				_this.showMessage('读取数据失败!', true);
			})
		});
	}
}

var AppBook = new _AppBook;

$(function() {
	AppBook.getBookList();
	$("#load-more").click(function() {
		AppBook.getBookList();
	});
});