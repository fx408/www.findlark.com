<?php
class BookHelper{
	
	public static function getBookReading($bookid) {
		$url = sprintf('http://book.douban.com/subject/%d/reading/', intval($bookid));
		$content = Curl::model()->request($url);

		$match = preg_match_all("#href=\"http://book.douban.com/reading/(\d+)/\"#", $content, $data);
		return $match ? $data[1] : false;
	}
	
	public static function getReadingDetail($id) {
		
		
	}
}
