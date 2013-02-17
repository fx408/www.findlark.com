var larkSnake = function() {
	this.enterDirection = 39; // 37:left, 39:right, 38:up , 40:down
	this.direction = 39;
	this.defaultX = 0;
	this.defaultY = 0;
	
	this.space = 1;
	this.eleWidth = 10;
	this.eleHeight = 10;
	this.row = 30;
	this.column = 50;
	
	this.eleList = [];
	this.eleLen = 0;
	this.spaceEle = [];
	this.spaceLen = 0
	this.snake = [];
	this.snakeLen = 0;
	this.food = null;
	
	this.minSpeed = 256;
	this.maxSpeed = 10;
	
	this.animateTime = 0;
	
	this.score = 0;
	this.level = 1;
	
	this.running = false;
	this.boundary = 0;
	
	// init
	this.init = function() {
		this.running = false;
		this.boundary = $("#boundary").val();
		
		this.score = 0;
		this.level = 1;
		this.enterDirection = 39;
		this.direction = 39;
		this.eleList = [];
		this.eleLen = 0;
		this.spaceEle = [];
		this.spaceLen = 0
		this.snake = [];
		this.snakeLen = 0;
		this.food = null;
		
		this.canvas = document.getElementById('snake');
		this.ctx = this.canvas.getContext('2d');
		
		var ofs = {};
		ofs.x = this.eleWidth+this.space;
		ofs.y = this.eleHeight+this.space;
		
		this.ctx.globalCompositeOperation = 'lighter';
		this.canvas.height = this.row * ofs.y;
		this.canvas.width = this.column * ofs.x;
		
		this.defaultX = this.canvas.width / 2;
		this.defaultY = this.canvas.height / 2;
		
		for(var i = 0; i < this.row; i++) {
			for(var j = 0; j < this.column; j++) {
				var tempEle = new ele();
				tempEle.x = j * ofs.x;
				tempEle.y = i * ofs.y;
				tempEle.i = i*this.column + j;
				this.eleList.push(tempEle);
			}
		}
		this.eleLen = this.eleList.length;
		
		for(var i = 0; i < 10; i++) {
			this.snakeLen = this.snake.push(this.eleList[i]);
		}
		
		this.food = this.createFood();
	};
}

larkSnake.prototype = {
	
	// 运行
	run: function() {
		var _this = this;
		
		this.update();
		if(!this.check()) {
			this.over();
			return false;
		}
		this.draw();
		
		var speed = Math.max( Math.ceil(this.minSpeed/(this.level/10+0.9)), this.maxSpeed );
		
		this.animateTime = setTimeout(function() {
			_this.direction = _this.enterDirection;
			_this.run();
		}, speed);
	},
	
	start: function() {
		$("#start").html("暂停");
		this.running = true;
		this.run();
	},
	
	// 暂停
	pause: function() {
		$("#start").html("开始");
		this.running = false;
		clearTimeout(this.animateTime);
	},
	
	// 结束
	over: function() {
		this.pause();
		alert('Game Over! Your score:'+this.score);
		this.init();
	}
	
	
};

// 更新位置，让 snake 移动
larkSnake.prototype.update = function() {
	var last = this.snake[this.snakeLen-1], i = 0, poor = this.direction-38;
	if(Math.abs(poor) == 1) {
		i = last.i + poor;
		
		if(last.i % this.column == 0 && poor < 0) { // 左边界
			i += this.column;
		} else if(i % this.column == 0 && poor > 0) { // 右边界
			i -= this.column;
		}
	} else {
		i = last.i + (poor-1) * this.column;
		i = (i+this.eleLen) % this.eleLen;
	}
	
	this.snake.shift();
	this.snake.push(this.eleList[i]);
};

// 遇到食物，吃之
larkSnake.prototype.eat = function() {
	var tempEle = new ele(), i = this.snake[0].i;
	
	this.snakeLen = this.snake.unshift(this.eleList[i]);
	
	this.score++;
	this.level = Math.ceil( this.score / 10 );
	
	$("#score").html(this.score);
	
	this.food = this.createFood();
};

// 检测 相撞
larkSnake.prototype.check = function() {
	var last = this.snake[this.snakeLen-1];
	
	if(last.i == this.food.i) this.eat();
	
	for(var i = 0; i < (this.snakeLen-1); i++) {
		if(last.i == this.snake[i].i) return false;
	}
	
	return true;
};

// 绘制
larkSnake.prototype.draw = function() {
	this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
	
	var t = 0, ofs = {};
	ofs.x = this.eleWidth + this.space;
	ofs.y = this.eleHeight + this.space;
	// 判断转向
	for(var i = 0; i < this.snakeLen; i++) {
		t = 0;
		if(i > 0 && i < (this.snakeLen-1)) {
			if(this.snake[i-1].x == this.snake[i].x && this.snake[i].x == this.snake[i+1].x) t = 0;
			else if(this.snake[i-1].y == this.snake[i].y && this.snake[i].y == this.snake[i+1].y) t = 0;
			
			else if(this.snake[i+1].x == this.snake[i].x) {
				if(this.snake[i].y > this.snake[i+1].y) {
					t = this.snake[i-1].x < this.snake[i].x ? 1 : 2;
				} else {
					t = this.snake[i-1].x > this.snake[i].x ? 3 : 4;
				}
			} else if(this.snake[i-1].x == this.snake[i].x) {
				if(this.snake[i].y > this.snake[i-1].y) {
					t = this.snake[i+1].x < this.snake[i].x ? 1 : 2;
				} else {
					t = this.snake[i+1].x > this.snake[i].x ? 3 : 4;
				}
			}
			
			// 边界交换
			if(t != 0) {
				if( Math.abs(this.snake[i+1].x - this.snake[i].x) > ofs.x || Math.abs(this.snake[i-1].x - this.snake[i].x) > ofs.x ) {
					if(t == 1) t = 2;
					else if(t == 2) t = 1;
					else if(t == 3) t = 4;
					else if(t == 4) t = 3;
				}
				
				if( Math.abs(this.snake[i+1].y - this.snake[i].y) > ofs.y || Math.abs(this.snake[i-1].y - this.snake[i].y) > ofs.y ) {
					if(t == 1) t = 4;
					else if(t == 2) t = 3;
					else if(t == 3) t = 2;
					else if(t == 4) t = 1;
				}
			}
		}

		this.drawEle(this.snake[i], i, t);
	}
	
	this.drawEle(this.food);
};

// 绘制元素
larkSnake.prototype.drawEle = function(ele, i, t) {
	var ctx = this.ctx;
	ctx.fillStyle = ele.color;
	
	if(i == (this.snakeLen-1)) ctx.fillStyle = '#09f';
	
	var point = {x:0, y:0};
	point.x = ele.x + this.eleWidth;
	point.y = ele.y + this.eleHeight;
	
	ctx.beginPath();
	switch(t) {
		case 1:
			ctx.moveTo(ele.x, ele.y);
			ctx.arc(ele.x, ele.y, this.eleWidth, 0, Math.PI/2);
			ctx.lineTo(ele.x, point.y);
			ctx.lineTo(ele.x, ele.y);
			break;
		case 2:
			ctx.moveTo(point.x, ele.y);
			ctx.arc(point.x, ele.y, this.eleWidth, Math.PI*0.5, Math.PI);
			ctx.lineTo(point.x, ele.y);
			ctx.lineTo(point.x, point.y);
			break;
		case 3:
			ctx.moveTo(point.x, point.y);
			ctx.arc(point.x, point.y, this.eleWidth, Math.PI*1.5, Math.PI*1, true);
			ctx.lineTo(point.x, point.y);
			ctx.lineTo(point.x, ele.y);
			break;
		case 4:
			ctx.moveTo(ele.x, point.y);
			ctx.arc(ele.x, point.y, this.eleWidth, 0, Math.PI*1.5, true);
			ctx.lineTo(ele.x, point.y);
			ctx.lineTo(point.x, point.y);
			break;
		default:
			ctx.fillRect(ele.x, ele.y, this.eleWidth, this.eleHeight);
			break;
	}
	ctx.fill();
};

// 创建食物
larkSnake.prototype.createFood = function() {
	var spaceEle = [], has = false, tempList = {};
	
	for(var j = 0, l = this.snake.length; j < l; j++) {
		tempList[this.snake[j].i] = 1;
	}
	
	for(var i = 0; i < this.eleLen; i++) {
		if(!tempList[i]) spaceEle.push(this.eleList[i]);
	}
	
	var n = Math.floor( Math.random() * spaceEle.length );
	
	var food = new ele();
	
	for(var k in spaceEle[n]) {
		food[k] = spaceEle[n][k];
	}
	food.color = "#ff0000";
	
	return food;
};


var ele = function() {
	this.x = 0;
	this.y = 0;
	this.i = 0;
	this.color = "#222";
}