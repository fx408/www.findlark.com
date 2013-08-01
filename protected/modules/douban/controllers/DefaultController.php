<?php

class DefaultController extends DoubanController {
	
	public function actionIndex() {
		$this->title = '书籍列表';
		
		$this->render('index');
	}
	
	public function getBook($bookid, $titleSuffix = '') {
		$data = Book::model()->findByPk($bookid);
		if(empty($data)) throw new Exception('书籍已被删除或不存在!');
		
		$data = CJSON::decode($data->content, false);
		$this->title = $data->title.$titleSuffix;
		$this->bookid = $data->bookid;
		
		return $data;
	}
	
	// 详细
	public function actionBook($id) {
		$data = $this->getBook($id);
		
		$this->render('book', array('data'=> $data));
	}
	
	// 列表
	public function actionList() {
		list($page, $data) = Book::model()->list;
		
		$error =  0;
		if(empty($data)) {
			$error = 1;
			$data = '没有更多了...';
		}
		
		$this->_end($error, $data, array('page'=> $page));
	}
	
	// 读书笔记
	public function actionNoteList($bookid) {
		$book = $this->getBook($bookid, ' - 笔记');
		
		$url = sprintf("http://api.douban.com/v2/book/%d/annotations", $book->bookid);
		$note = Curl::model()->request($url);
		
		if(!$note) $this->_end(1, '获取数据失败!');
		
		$note = CJSON::decode($note);
		
		$this->render('note_list', array('data'=> $note['annotations'], 'book'=> $book));
	}
	
	// 笔记详细
	public function actionNote($bookid, $noteid) {
		$book = $this->getBook($bookid, ' - 笔记');
		
		$url = sprintf("http://api.douban.com/v2/book/annotation/%s", $noteid);
		$note = Curl::model()->request($url);
		
		if($note) $note = CJSON::decode($note, false);
		
		$this->render('note', array('data'=> $note, 'book'=> $book));
	}
	
	// 试读详细
	public function actionReading($bookid, $id) {
		$book = $this->getBook($bookid, ' - 试读');
		
		$url = sprintf('http://book.douban.com/reading/%d/', intval($id));
		$content = Curl::model()->request($url);
		$match = preg_match("#\<div\s+class=\"book\-content\">(.*?)<div\s+class=\"rel-info\">#is", $content, $data);
		if($match && isset($data[1])) {
			$data = $data[1];
			$data = preg_replace("#<(?!img|p).*?>#", "", $data);
		}
		
		$match = preg_match("#<div\s*id=\"content\">\s*<h1>(.*?)</h1>#is", $content, $title);
		
		$title = $match && isset($title[1]) ? $title[1] : $book->title;
		
		$this->render('reading', array('data'=> $data, 'book'=> $book, 'title'=> $title));
	}
}