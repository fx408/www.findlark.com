;(function() {
	var template = function() {
		this.dataList = {};
		this.tagList = {};
	};
	
	var trimSpace = /^\s+(.*?)\s+$/g,
		blockMatch = /\{([\?\@]*)\$([\w\.]+)\}(?:(.*?)\{\:\}(.*?)\{\/\})?/g;
	
	/**
	 * 解析标签变量
	 */
	function renderTag() {
		each(t.params.tag, function(html, tag) {
			var temp = html.replace(trimSpace, "$1");
			if(temp == "") temp = getRenderKey(tag);
			
			return getValueOfVar(temp);
		});
	}
	
	/**
	 * 解析循环
	 */
	function renderFor() {
		each(t.params.for, function(html, tag) {
			var renderKey = getRenderKey(tag), 
				returnHtml = "",
				temp = getValueOfVar(renderKey);
			
			if(!temp) return returnHtml;
			
			len = getDataLength(temp);
			for(var k in temp) {
				temp[k]["i"] = k;
				temp[k]["length"] = len;
				
				returnHtml += renderCodeBlock(html, temp[k]);
			}
			
			return returnHtml;
		});

		if(document.getElementsByTagName(t.params.for).length) renderFor();
	}
	
	/**
	 * 解析条件标签
	 */
	function renderIf() {
		each(t.params.if, function(html, tag) {
			var renderKey = getRenderKey(tag);

			return getValueOfVar(renderKey) == false ? "" : renderCodeBlock(html);
		});
	}
	
	/**
	 * 解析 code 标签
	 */
	function renderCode() {
		each(t.params.code, function(html) {
			
			return renderCodeBlock(html);
		});
	}
	
	/**
	 * 解析 模板代码块
	 */
	function renderCodeBlock(string, dataList) {
		if(!string) return "";
		
		return string.replace(blockMatch, function(_, sign, varName, trueString, falseString) {
			var returnString = "";
			
			switch(sign) {
				case "": 
					returnString = getValueOfVar(varName, dataList);
					break;
				case "@":
					returnString = getValueOfVar(varName);
					break;
				case "@?":
					dataList = t.dataList;
				case "?":
				console.log(dataList)
					returnString = getValueOfVar(varName, dataList) ? trueString : falseString;
					returnString = renderCodeBlock(returnString);
					break;
				default:
					returnString = "";
			}
			return returnString;
		});
	}
	
	/**
	 * 标签遍历，按回调函数处理
	 */
	function each(tag, callback) {
		var tagList = document.getElementsByTagName(tag),
			html = "";
		
		while(tagList.length) {
			html = tagList[0].innerHTML;
			tagList[0].outerHTML = callback(html, tagList[0]);
		}
	}
	
	/**
	 * 获取变量值
	 */
	function getValueOfVar(varName, dataList) {
		if(!dataList) dataList = t.dataList;
		
		var temp = varName.split('.');
		while(temp.length) {
			if( !(temp[0] in dataList) ) return "";
			
			dataList = dataList[temp.shift()];
		}
		
		return dataList;
	}
	
	// 获取 标签 name 字段
	function getRenderKey(tag) {
		renderKey = tag.attributes["name"];
		return renderKey ? renderKey.value.replace(trimSpace, "$1") : false;
	}
	
	// 计算数据长度
	function getDataLength(data) {
		if(data.length) return data.length;
		
		var i = 0;
		for(var k in data) {
			i++;
		}
		return i;
	}
	
	// 可设置参数列表
	template.prototype.params = {
		tag: "t",
		for: "for",
		if: "if",
		code: "code",
		auto: true
	};
	// 模板数据 列表
	template.prototype.dataList = {};
	// 解析
	template.prototype.render = function() {
		if(!this.params.tag) this.params.tag = "t";
		
		renderCode();
		renderTag();
		renderFor();
		renderIf();
		
		console.log(t.dataList)
	};
	// 参数设置
	template.prototype.siteParams = function(params) {
		if(!params) return false;
		
		for(var k in params) {
			this.params[k] = params[k];
		}
		
		return this.params;
	};
	
	var t = null;
	var newTemplate = function(dataList, params) {
		if(!t) t = new template();
		t.siteParams(params);
		t.dataList = dataList;
		if(t.params.auto == true) t.render();
		return t;
	}
	window.newTemplate = newTemplate;
})();