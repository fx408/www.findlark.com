var canvas = document.getElementById('love');
var ctx = canvas.getContext('2d');
ctx.globalCompositeOperation = 'lighter';
canvas.height = 600;
canvas.width = 1000;

$(function() {
	var love = new loveObject();
	love.init();
	
});


var loveObject = function() {
	this.start = {x:10, y:50};
	this.proportion = 1/3;
	this.point = [];
	this.eleList = [];
	
	this.init = function() {
		this.drawFont();
		this.point = this.getCanvasData();
		
		var _this = this;
		
		for(var i = 0, l = this.point.length; i < l; i++) {
			this.eleList.push(new ele(this.point.length));
		}
		
		setInterval(function() {
			_this.update();
			_this.drawElement2();
		}, 50);
		
	}
	
}

loveObject.prototype.drawFont = function() {
	ctx.fillStyle = "#333";
	ctx.font='italic 400px Courier New,sans-serif ';
  ctx.textBaseline='top';
  
  ctx.fillText('Love', this.start.x, this.start.y);
  
  //ctx.fillText('Love You Forever.', this.start.x, this.start.y);
};

loveObject.prototype.getCanvasData = function() {
	var imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
	
	var newData = [];
	
	for(var i = 0, l = imgData.data.length; i < l; i += 4/this.proportion) {
		if(imgData.data[i] != 0) {
			newData.push(new point(i/4));
		}
	}
	
	// ctx.fillRect(newData[0].x, newData[0].y, 100, 100);
	console.log(newData);
	
	return newData;
};

loveObject.prototype.drawElement = function() {
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	ctx.fillStyle = '#FF51A8';
	
	var ofs = {};
	
	for(var i = 0, l = this.point.length; i < l; i++) {
		ofs.x = Math.random()*5;
		ofs.y = Math.random()*5;
		
		ctx.fillRect(this.point[i].x+ofs.x, this.point[i].y+ofs.y, 4, 4);
		
		//if(i > 100) break;
	}
};

loveObject.prototype.drawElement2 = function() {
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	
	var ofs = {};
	
	for(var i = 0, l = this.eleList.length; i < l; i++) {
		ctx.fillStyle = 'rgba(255,81,168,'+this.eleList[i].alpha+')';
		//ctx.strokeStyle = 'rgba(255,81,168,'+this.eleList[i].alpha+')';
		ctx.fillRect(this.eleList[i].x, this.eleList[i].y, 6, 6);
		//ctx.arc(this.eleList[i].x, this.eleList[i].y, 3, 0, Math.PI*2, true);
		ctx.fill();
		//if(i > 100) break;
	}
};

loveObject.prototype.update = function() {
	
	var poor = {}, n = 0;
	
	for(var i = 0, l = this.eleList.length; i < l; i++) {
		n = this.eleList[i].n;
		
		if(!this.point[n]) continue;
		
		
		poor.x = this.eleList[i].x - this.point[n].x;
		poor.y = this.eleList[i].y - this.point[n].y;
		poor.absX = Math.abs(poor.x);
		poor.absY = Math.abs(poor.y);
		
		if(poor.absX <= this.eleList[i].speed && poor.absY <= this.eleList[i].speed) {
			
			this.eleList[i].alpha = this.eleList[i].alpha * 0.98;
			if(this.eleList[i].alpha < 0.1) {
				this.eleList[i] = new ele(this.point.length);
			} else {
				//this.eleList[i].x += this.eleList[i].speed/2 * poor.x/poor.absX;
				//this.eleList[i].y += this.eleList[i].speed/2 * poor.y/poor.absY;
			}
		} else {
			if(poor.absX > this.eleList[i].speed) {
				this.eleList[i].x -= this.eleList[i].speed * poor.x/poor.absX;
			} 
			if(poor.absY > this.eleList[i].speed) {
				this.eleList[i].y -= this.eleList[i].speed * poor.y/poor.absY;
			}
		}
	}
}

var point = function(n) {
	this.x = n % canvas.width;
	this.y = Math.floor(n / canvas.width);
}

var ele = function(len) {
	this.x = Math.floor( Math.random()*canvas.width );
	this.y = Math.floor( Math.random()*canvas.height );
	this.speed = Math.floor( Math.random()*4 ) + 4;
	
	this.n = Math.floor( Math.random()*len ); // 移动目标
	
	this.alpha = 1; // 透明度
}