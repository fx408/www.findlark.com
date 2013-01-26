var larkSnake = function() {
	this.snake = [];
	this.enterDirection = 39; // 37:left, 39:right, 38:up , 40:down
	this.direction = 39;
	this.defaultX = 0;
	this.defaultY = 0;
	
	this.minSpeed = 500;
	this.maxSpeed = 10;
	this.speed = this.minSpeed;
	
	this.space = 1;
	
	this.animateTime = 0;
	
	this.init = function() {
		this.canvas = document.getElementById('snake');
		this.ctx = this.canvas.getContext('2d');
		
		this.ctx.globalCompositeOperation = 'lighter';
		this.canvas.height = $(window).height() - 20;
		this.canvas.width =  $(window).width() - 20;
		
		this.defaultX = this.canvas.width / 2;
		this.defaultY = this.canvas.height / 2;
		
		for(var i = 0; i < 5; i++) {
			this.snake.push(new ele());
		}
		
		this.run();
	};
	
	this.run = function() {
		var _this = this;
		
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
	var tempEle = new ele(), ofs = {}, poor = this.direction-38;
	
	ofs.x = this.snake[0].w+this.space;
	ofs.y = this.snake[0].h+this.space;
	
	if(Math.abs(poor) == 1) {
		tempEle.x = this.snake[0].x + poor*ofs.x;
		tempEle.y = this.snake[0].y;
	} else {
		tempEle.y = this.snake[0].y + (poor-1)*ofs.y;
		tempEle.x = this.snake[0].x
	}
	
	var mX = this.canvas.width - ofs.x, mY = this.canvas.height - ofs.y;
	
	tempEle.x = (tempEle.x+mX) % mX;
	tempEle.y = (tempEle.y+mY) % mY;
	
	this.snake.unshift(tempEle);
	this.snake.pop();
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
	ctx.fillStyle = "#222";
	ctx.fillRect(ele.x, ele.y, ele.w, ele.h);
	
};


var ele = function() {
	this.x = 0;
	this.y = 0;
	this.h = 5;
	this.w = 5;
}