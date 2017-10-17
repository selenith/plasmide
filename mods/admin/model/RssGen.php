<?php
class RssGen{

	private $artList;
	
	private $content ='';

	public function setArtList($artList){
		$this->artList = $artList;
	}	
	
	public function generer(){

		
		$this->createContent();
		file_put_contents('../../../feed/index.xml', $this->content);
	}
	
	private function createItem($article, $link){
		

		$desc =  str_replace('src="files/', 'src="'.$link.'/files/', $article->get('code'));
		
		$item ='
			<item>
				<title>'.$article->get('nom').'</title>
				<link>'.$link.'/site/art/'.$article->get('id').' </link>
				<description><![CDATA['.$desc.']]></description>
				<pubDate>'.date('r',$article->get('date')).'</pubDate>
			</item>'.PHP_EOL;
		return $item;
	}

	private function createContent(){

		date_default_timezone_set('Europe/Paris');
		$link = 'http://'.Config::getVal('adresse').'/';
		
		$this->content = '<?xml version="1.0" encoding="UTF-8" ?>'.PHP_EOL.
		'<rss version="2.0">'.PHP_EOL.
      		'	<channel>'.PHP_EOL.
		'		<title>'.Config::getVal('nom').'</title>'.PHP_EOL.
		'		<description>'.Config::getVal('description').'</description>'.PHP_EOL.
		'		<lastBuildDate>'.date(DATE_RFC2822).'</lastBuildDate>'.PHP_EOL.
		'		<link>'.$link.'</link>'.PHP_EOL;

     		foreach($this->artList as $art){
			 $this->content .= $this->createItem($art, $link);
		}
		$this->content .='	</channel>'.PHP_EOL.
		'</rss>';
	}
}

?>
