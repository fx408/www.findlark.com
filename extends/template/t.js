;(function() {
	var t = function(dataList, params) {
		this.dataList = dataList;
		this.tagList = {};
		
		if(params) {
			for(var k in params) {
				this.params[k] = params[k];
			}
		}
		
		if(this.params.auto == true) this.render();
	};
	
	var trimSpace = /^\s+(.*?)\s+$/g,
		varMatch = /\{\$(\w+)\}/ig;
	
	/**
	 * ÃÊªª±Í«©±‰¡ø
	 */
	function renderTag(dataList, tag) {
		var tagList = document.getElementsByTagName(tag),
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
			
			tagList[0].outerHTML = (renderKey != false && typeof dataList[renderKey] != "undefined") ? dataList[renderKey] : '';
		}
	}
	
	/**
	 * ÃÊªª—≠ª∑”Ôæ‰
	 */
	function renderFor(dataList, tag) {
		var tagList = document.getElementsByTagName(tag),
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
			
			if(typeof dataList[renderKey] == "undefined") {
				tagList[0].outerHTML = "";
				continue;
			}
			
			returnHtml = "";
			for(var k in dataList[renderKey]) {
				temp = dataList[renderKey][k];
				
				returnHtml += html.replace(varMatch, function(_, varName) {
					if(varName == "i") return k;
					if(varName == "length") return dataList[renderKey].length ? dataList[renderKey].length : 0;
					
					return temp[varName] ? temp[varName] : '';
				});
			}
			
			tagList[0].outerHTML = returnHtml;
		}
	}
	
	t.prototype.params = {tag:"t", auto:true, for:"for"};
	t.prototype.dataList = {};
	t.prototype.render = function() {
		if(!this.params.tag) this.params.tag = "t";
		
		renderTag(this.dataList, this.params.tag);
		renderFor(this.dataList, this.params.for);
	}
	
	window.t = t;
})();