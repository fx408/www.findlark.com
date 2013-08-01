<?php
class BookCommand extends CConsoleCommand {
	
	public function actionIndex() {
		
		$this->searchBook();
	}
	
	public function searchBook() {
		$url = 'http://book.douban.com';
		$data = Curl::model()->request($url);
		
		if($data){
			preg_match_all("#href=\"http://book.douban.com/subject/(\d+)/\"#", $data, $matchs);
			
			$matchs[1] = array_unique($matchs[1]);
			foreach($matchs[1] as $bookid) {
				Book::model()->addBook($bookid);
				
				echo $bookid." \n";
				sleep(1);
			}
			
		}
	}
	
	public function actionBuy() {
		
		
		
	}
	
}