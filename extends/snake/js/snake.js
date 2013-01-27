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
	this.snake = [];
	
	this.minSpeed = 500;
	this.maxSpeed = 10;
	this.speed = this.minSpeed;
	
	this.animateTime = 0;
	
	this.init = function() {
		this.canvas = document.getElementById('snake');
		this.ctx = this.canvas.getContext('2d');
		
		var ofs = {};
		ofs.x = this.eleWidth+this.space;
		ofs.y = this.eleHeight+this.space;
		
		this.ctx.globalCompositeOperation = 'lighter';
		this.canvas.height = this.row * ofs.y; //$(window).height() - 20;
		this.canvas.width = this.column * ofs.x; //$(window).width() - 70;
		
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
		
		for(var i = 0; i < 5; i++) {
			this.snake.push(this.eleList[i]);
		}
		this.run();
	};
	
	this.run = function() {
		var _this = this;
		
		this.drawFood();
		this.update();
		this.check();
		this.draw();
		
		this.animateTime = setTimeout(function() {
			_this.direction = _this.enterDirection;
			_this.run();
		}, this.speed);
		
	};
	
}

larkSnake.prototype = {
	
	
};

larkSnake.prototype.update = function() {
	var last = this.snake.pop(), i = 0, poor = this.direction-38;
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
	this.snake.push(last);
	this.snake.push(this.eleList[i]);
};

larkSnake.prototype.eat = function() {
	
	
};

larkSnake.prototype.check = function() {
	
	
	
	
};

larkSnake.prototype.draw = function() {
	this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
	
	for(var i = 0, l = this.snake.length; i < l; i++) {
		this.drawEle(this.snake[i]);
		
	}
	
};

larkSnake.prototype.drawEle = function(ele) {
	var ctx = this.ctx;
	ctx.fillStyle = ele.color;
	ctx.fillRect(ele.x, ele.y, this.eleWidth, this.eleHeight);
	
};

larkSnake.prototype.drawFood = function() {
	var food = {};
	
	while(true) {
		food.x = Math.floor( Math.random()*this.column ) * this.eleWidth;
		food.y = Math.floor( Math.random()*this.row ) * this.eleHeight;
		
		//for()
		
		break;
	}
	
	food.color = "#ff0000";
	
	this.drawEle(food);
};


var ele = function() {
	this.x = 0;
	this.y = 0;
	this.i = 0;
	this.color = "#222";
}