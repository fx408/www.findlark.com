;(function() {
	var template = function() {
		this.dataList = {};
		this.tagList = {};
	};
	
	var trimSpace = /^\s+(.*?)\s+$/g,
		blockMatch = /\{([\?]?)\$(\w+)\}(.*?)\{\:\}(.*?)\{\/\}/g,
		varMatch = /\{\$(\w+)\}/ig;
	
	/**
	 * 解析标签变量
	 */
	function renderTag() {
		var tagList = document.getElementsByTagName(t.params.tag),
			tagLen = tagList.length,
			html = '',
			renderKey = '';

		for(var i=0; i < tagLen; i++) {
			html = tagList[0].innerHTML.replace(trimSpace, "$1");
			
			if(html != "") {
				renderKey = html;
			} else if(tagList[0].attributes["name"]) {
				renderKey = tagList[0].attributes["name"].value.replace(trimSpace, "$1");
			} else {
				renderKey = false;
			}
			
			tagList[0].outerHTML = (renderKey != false && typeof t.dataList[renderKey] != "undefined") ? t.dataList[renderKey] : '';
		}
	}
	
	/**
	 * 解析循环
	 */
	function renderFor() {
		var tagList = document.getElementsByTagName(t.params.for),
			tagLen = tagList.length,
			html = '',
			renderKey = '',
			returnHtml = '',
			temp = {},
			vars = [];
			
		for(var i=0; i < tagLen; i++) {
			html = tagList[0].innerHTML;
			
			renderKey = tagList[0].attributes["name"];
			if(!renderKey) continue;
			renderKey = renderKey.value;
			
			if(typeof t.dataList[renderKey] == "undefined") {
				tagList[0].outerHTML = "";
				continue;
			}
			
			returnHtml = "";
			for(var k in t.dataList[renderKey]) {
				temp = t.dataList[renderKey][k];
				
				returnHtml += html.replace(varMatch, function(_, varName) {
					if(varName == "i") return k;
					if(varName == "length") return t.dataList[renderKey].length ? t.dataList[renderKey].length : 0;
					
					return temp[varName] ? temp[varName] : '';
				});
			}
			
			tagList[0].outerHTML = renderCondition(returnHtml);
		}
	}
	
	/**
	 * 解析三元模板
	 */
	function renderCondition(string) {
		return string.replace(blockMatch, function(_, varName, trueString, falseString) {
			return t.dataList[varName] ? trueString : falseString;
		});
	}
	
	template.prototype.params = {tag:"t", for:"for", code:"code", auto:true};
	template.prototype.dataList = {};
	template.prototype.render = function() {
		if(!this.params.tag) this.params.tag = "t";
		
		renderTag();
		renderFor();
	};
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