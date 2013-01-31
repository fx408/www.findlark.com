var canvas = document.getElementById('love');
var ctx = canvas.getContext('2d');
ctx.globalCompositeOperation = 'lighter';


var img = new Image;
img.src = './images/mg.gif';

var bgImg = new Image;
bgImg.src = './images/bg.jpg';

$(function() {
	canvas.height = $(window).height();
	canvas.width = $(window).width();
	
	
	var love = new loveObject();
	
	bgImg.onload = function() {
		love.init();
	};
	
	
});


var loveObject = function() {
	this.start = {x:0, y:0};
	this.proportion = 1/10;
	this.point = [];
	this.eleList = [];
	
	this.offsets = [];
	
	this.speed = 70;
	this.moveParam = 70;
	
	
	this.init = function() {
		this.start.x = Math.max(0, (canvas.width-1000)/2 );
		this.start.y = Math.max(0, (canvas.height-580)/2 );
		
		this.drawFont();
		this.point = this.getCanvasData();
		
		var _this = this;
		
		for(var i = 0, l = this.point.length; i < l; i++) {
			//this.eleList.push(new ele(this.point.length));
			this.offsets.push(new ofs());
		}
		
		setInterval(function() {
			_this.update();
			_this.drawElement();
		}, this.speed);
		
	}
	
}

loveObject.prototype.drawFont = function() {
	ctx.fillStyle = "#333";
	ctx.font='italic 400px Lucida Handwriting,Microsoft Yahei,sans-serif ';
  ctx.textBaseline='top';
  
  //ctx.fillText('Love', this.start.x, this.start.y);
  
  
  ctx.drawImage(bgImg, this.start.x, this.start.y);
  //ctx.fillText('Love You Forever.', this.start.x, this.start.y);
};

loveObject.prototype.getCanvasData = function() {
	var imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
	
	var newData = [];
	
	for(var i = 0, l = imgData.data.length; i < l; i += 4/this.proportion) {
		if(imgData.data[i] > 10 && imgData.data[i] < 200) {
			newData.push(new point(i/4));
		}
	}
	
	// ctx.fillRect(newData[0].x, newData[0].y, 100, 100);
	console.log(newData);
	
	return newData;
};

loveObject.prototype.drawElement = function() {
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	
	for(var i = 0, l = this.point.length; i < l; i++) {
		ctx.fillStyle = 'rgba(255,81,168,'+this.offsets[i].alpha+')';
		
		//ctx.fillStyle = 'rgba(255,81,168,1)';
		
		//ctx.fillRect(this.point[i].x+this.offsets[i].x, this.point[i].y+this.offsets[i].y, this.point[i].w, this.point[i].h);
		
		/*
		this.point[i].r = 1;
		this.offsets[i].x = 0;
		this.offsets[i].y = 0;
		*/
		
		ctx.drawImage(img, this.point[i].x+this.offsets[i].x, this.point[i].y+this.offsets[i].y, 24*this.point[i].r, 24*this.point[i].r);
		//if(this.offsets[i].x > 30) ctx.rotate(Math.PI * 1.2)
		
		var tx = this.point[i].x+this.offsets[i].x+24*this.point[i].r/2;
		var ty = this.point[i].y+this.offsets[i].y+24*this.point[i].r/2;
		
		
		if(this.offsets[i].x > 30) {
			//ctx.translate(tx, ty);
			//ctx.rotate(Math.PI*0.01);
			//ctx.translate(-tx, -ty);
		} else {
			//ctx.translate(0, 0);
		}
		
		//break;
	}
};

loveObject.prototype.update = function() {
	
	for(var i = 0, l = this.offsets.length; i < l; i++) {
		
		
		
		if(this.offsets[i].alpha < 1) {
			this.offsets[i].alpha *= Math.floor( Math.random() * 1.299 ) + 1.01;
			
			if(this.offsets[i].alpha > 1) this.offsets[i].alpha = 1;
			
			this.offsets[i].x = Math.random()*5+2;
			this.offsets[i].y = Math.random()*5+2;
			
		} else {
			if(i%this.moveParam == 0) {
				this.offsets[i].x += this.offsets[i].xSpeed * ( Math.random()*3 + 1 );
				this.offsets[i].y += this.offsets[i].ySpeed * ( Math.random()*3 + 1 );
				
				if( 
					(this.offsets[i].x + this.point[i].x) > canvas.width || (this.offsets[i].x + this.point[i].x) < 0 ||
					(this.offsets[i].y + this.point[i].y) > canvas.height || (this.offsets[i].y + this.point[i].y) < 0 
				) {
					this.offsets[i].x = 0;
					this.offsets[i].y = 0;
					this.offsets[i].alpha = 0.01;
				}
				
			} else {
				this.offsets[i].x = 0;
				this.offsets[i].y = 0;
			}
		}
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

loveObject.prototype.update2 = function() {
	
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
	
	this.w = Math.random()*6 + 4;
	this.h = this.w;
	
	this.r = this.w / 10;
}

var ele = function(len) {
	this.x = Math.floor( Math.random()*canvas.width );
	this.y = Math.floor( Math.random()*canvas.height );
	this.speed = Math.floor( Math.random()*4 ) + 4;
	
	this.n = Math.floor( Math.random()*len ); // 移动目标
	
	this.alpha = 1; // 透明度
}

var m = 2, n = 2;

var ofs = function() {
	var rx = Math.random() - 0.5, ry = Math.random() - 0.5;
	
	
	this.xSpeed = Math.abs(rx)/rx;
	this.ySpeed = Math.abs(ry)/ry;
	
	this.x = 0;
	this.y = 0;
	
	this.alpha = 0.01;
	this.alphaSpeed = Math.floor( Math.random() * 1.599 ) + 1.01;
}