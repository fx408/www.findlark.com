<?php

class MeiziCommand extends CConsoleCommand {
	
	public function actionIndex() {
		$url = "http://sejie.wanxun.org/post/2012-09-25/40039413449";
		
		for($i = 1; $i < 100; $i++) {
			$url = 'http://sejie.wanxun.org/';
			if($i > 1) $url = $url.'page/'.$i;
			
			$pages = Curl::model()->matchContent($url, "#http\:\/\/sejie\.wanxun\.org\/post\/(?:\d+)-(?:\d+)-(?:\d+)\/(?:\d+)#");
			
			if(!$pages) continue;
			
			foreach($pages[0] as $page) {
				$images = Curl::model()->matchContent($page, "#\<img.*?src\=(?:\"|\')(.*?)(?:\"|\')#");
				foreach($images[1] as $img) {
					ImageCrawl::model()->saveImg($img);
				}
			}
			break;
		}
	}
	
	public function actionHuaban() {
		$url = 'http://huaban.com/favorite/beauty/';
		
		$curlModel = Curl::model();
		// $curlModel->setDefault('referer', 'http://huaban.com');
		
		$links = $curlModel->matchContent($url, "#\<a\s+href=\"\/pins\/(\d+)\"#");
		foreach($links[0] as $link) {
			
			
		}
		
	}
}