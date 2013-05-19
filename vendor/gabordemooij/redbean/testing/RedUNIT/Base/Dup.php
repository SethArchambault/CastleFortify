<?php
/**
 * RedUNIT_Base_Copy 
 * 
 * @file 			RedUNIT/Base/Copy.php
 * @description		Intensive test for dup()
 * 					This class is part of the RedUNIT test suite for RedBeanPHP.
 * @author			Gabor de Mooij
 * @license			BSD
 *
 *
 * (c) G.J.G.T. (Gabor) de Mooij
 * This source file is subject to the BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */
class RedUNIT_Base_Dup extends RedUNIT_Base {

	

	/**
	 * Begin testing.
	 * This method runs the actual test pack.
	 * 
	 * @return void
	 */
	public function run() {
		
		testpack('exportAll() and Cache');
		
		$can = R::dispense('can')->setAttr('size', 3);
		$can->ownCoffee[] = R::dispense('coffee')->setAttr('color', 'black');
		$can->sharedTag[] = R::dispense('tag')->setAttr('name', 'cool');
		$id = R::store($can);
		R::debug(true);
		ob_start();
		$can = R::load('can', $id);
		$cache = array('coffee'=>array('color'=>'color','id'=>'id','can_id'=>'can_id'),'can'=>array('size'=>'size','id'=>'id'),
			'can_tag'=>array('id'=>'id','can_id'=>'can_id','tag_id'=>'tag_id'),'tag'=>array('id'=>'id','name'=>'name'));
		$data = R::exportAll(array($can),true);
		$queries = ob_get_contents();
		R::debug(false);
		ob_end_clean();
		$len1 = strlen($queries);
		
		$can = R::dispense('can')->setAttr('size', 3);
		$can->ownCoffee[] = R::dispense('coffee')->setAttr('color', 'black');
		$can->sharedTag[] = R::dispense('tag')->setAttr('name', 'cool');
		$id = R::store($can);
		R::debug(true);
		ob_start();
		$can = R::load('can', $id);
		$cache = array('coffee'=>array('color'=>'color','id'=>'id','can_id'=>'can_id'),'can'=>array('size'=>'size','id'=>'id'),
			'can_tag'=>array('id'=>'id','can_id'=>'can_id','tag_id'=>'tag_id'),'tag'=>array('id'=>'id','name'=>'name'));
		$data = R::exportAll(array($can),true);
		$queries = ob_get_contents();
		R::debug(false);
		ob_end_clean();
		$len2 = strlen($queries);
		asrt(($len1),($len2));
		
		$can = R::dispense('can')->setAttr('size', 3);
		$can->ownCoffee[] = R::dispense('coffee')->setAttr('color', 'black');
		$can->sharedTag[] = R::dispense('tag')->setAttr('name', 'cool');
		$id = R::store($can);
		R::debug(true);
		ob_start();
		$can = R::load('can', $id);
		$cache = array('coffee'=>array('color'=>'color','id'=>'id','can_id'=>'can_id'),'can'=>array('size'=>'size','id'=>'id'),
			'can_tag'=>array('id'=>'id','can_id'=>'can_id','tag_id'=>'tag_id'),'tag'=>array('id'=>'id','name'=>'name'));
		R::$duplicationManager->setTables($cache);
		$data = R::exportAll(array($can),true);
		
		$queries = ob_get_contents();
		R::debug(false);
		ob_end_clean();
		$len3 = strlen($queries);
		asrt((($len3)<($len2)),true);
		asrt(count($data),1);
		asrt($data[0]['ownCoffee'][0]['color'],'black');
		R::$duplicationManager->setCacheTables(false);
	
		
		testpack('Dup() and Cache');
		R::nuke();
		$can = R::dispense('can')->setAttr('size', 3);
		$can->ownCoffee[] = R::dispense('coffee')->setAttr('color', 'black');
		$can->sharedTag[] = R::dispense('tag')->setAttr('name', 'cool');
		$can = R::load('can', R::store($can));
		$d = new RedBean_DuplicationManager(R::$toolbox);
		$d->setCacheTables(true);
		ob_start();
		R::debug(1);
		$x = $d->dup($can);
		$queries = ob_get_contents();
		R::debug(0);
		ob_end_clean();
		$len1 = strlen($queries);
		asrt(($len1>40),true);
		asrt(isset($x->ownCoffee),true);
		asrt(count($x->ownCoffee),1);
		asrt(isset($x->sharedTag),true);
		asrt(count($x->sharedTag),1);
		$cache = $d->getSchema();
		
		R::nuke();
		$can = R::dispense('can')->setAttr('size', 3);
		$can->ownCoffee[] = R::dispense('coffee')->setAttr('color', 'black');
		$can->sharedTag[] = R::dispense('tag')->setAttr('name', 'cool');
		$can = R::load('can', R::store($can));
		$d = new RedBean_DuplicationManager(R::$toolbox);
		//$cache = '{"book":{"id":"INTEGER","title":"TEXT"},"bean":{"id":"INTEGER","prop":"INTEGER"},"pessoa":{"id":"INTEGER","nome":"TEXT","nome_meio":"TEXT","sobrenome":"TEXT","nascimento":"NUMERIC","reg_owner":"TEXT"},"documento":{"id":"INTEGER","nome_documento":"TEXT","numero_documento":"TEXT","reg_owner":"TEXT","ownPessoa_id":"INTEGER"},"can":{"id":"INTEGER","size":"INTEGER"},"coffee":{"id":"INTEGER","color":"TEXT","can_id":"INTEGER"},"tag":{"id":"INTEGER","name":"TEXT"},"can_tag":{"id":"INTEGER","tag_id":"INTEGER","can_id":"INTEGER"}}';
		$d->setTables($cache, 1);
		ob_start();
		R::debug(1);
		$x = $d->dup($can);
		$queries = ob_get_contents();
		ob_end_clean();
		R::debug(0);
		$len2 = strlen($queries);
		asrt(isset($x->ownCoffee),true);
		asrt(count($x->ownCoffee),1);
		asrt(isset($x->sharedTag),true);
		asrt(count($x->sharedTag),1);
		asrt(json_encode($cache),json_encode($d->getSchema()));
		asrt(($len1 > $len2),true);
		
		testpack('Dup() and Export() should not taint beans');
		R::nuke();
		
		$p = R::dispense('page');
		$b = R::dispense('book');
		$b->ownPage[] = $p;
		$b->title = 'a';
		$id = R::store($b);
		$b = R::load('book',$id);
		asrt((!$b->getMeta('tainted')),true);
		R::exportAll($b);
		asrt((!$b->getMeta('tainted')),true);
		R::dup($b);
		asrt((!$b->getMeta('tainted')),true);
		
		
		testpack('Test issue with ownItems and stealing Ids.');
		R::nuke();
		$bill = R::dispense('bill');
		$item = R::dispense('item');
		$element = R::dispense('element');
		$bill->ownItem[] = $item;
		$bill->sharedElement[] = $element;
		R::store($bill);
		$bill = R::load('bill',1);
		$bill->ownItem;
		$bill->sharedElement;
		$copy = R::dup($bill);
		R::store($copy);
		
		$rows=(R::getAll('select * from bill_element'));
		asrt(count($rows),2);
		
		$rows=(R::getAll('select * from item'));
		
		foreach($rows as $row) {
			asrt(($row['bill_id']>0),true);
		}
		
		R::nuke();
		$this->runOnce();
		R::freeze(true);
		$this->runOnce(false);
		R::freeze(false);
		
		testpack('Export with filters');
		R::nuke();
		$book = R::dispense('book');
		$pages = R::dispense('page',2);
		$texts = R::dispense('text',2);
		$images = R::dispense('image',2);
		$author = R::dispense('author');
		$pub = R::dispense('publisher');
		$bookmarks = R::dispense('bookmark',2);
		$pages[0]->ownText = array( $texts[0] );
		$pages[0]->ownImage = array( $images[0] );
		$pages[1]->ownText = array( $texts[1] );
		$pages[1]->ownImage = array( $images[1] );
		$pages[0]->sharedBookmark[] = $bookmarks[0];
		$pages[1]->sharedBookmark[] = $bookmarks[1];
		$bookmarks[0]->ownNote[] = R::dispense('note')->setAttr('text','a note');
		$bookmarks[1]->ownNote[] = R::dispense('note')->setAttr('text','a note');
		$book->ownPage = $pages;
		$book->author = $author;
		
		$author->publisher = $pub;
		$bookID = R::store($book);
		R::$duplicationManager->setTables( R::$writer->getTables() );
		$objects = (R::exportAll(array($book),true,array()));
		asrt(isset($objects[0]['ownPage']),true);
		asrt(count($objects[0]['ownPage']),2);
		asrt(isset($objects[0]['author']),true);
		asrt(isset($objects[0]['ownPage'][0]['ownText']),true);
		asrt(count($objects[0]['ownPage'][0]['ownText']),1);
		asrt(isset($objects[0]['ownPage'][0]['ownImage']),true);
		asrt(count($objects[0]['ownPage'][0]['ownImage']),1);
		$objects = (R::exportAll(array($book),true,array('page','author','text','image')));
		asrt(isset($objects[0]['ownPage']),true);
		asrt(count($objects[0]['ownPage']),2);
		asrt(isset($objects[0]['author']),true);
		asrt(isset($objects[0]['ownPage'][0]['ownText']),true);
		asrt(count($objects[0]['ownPage'][0]['ownText']),1);
		asrt(isset($objects[0]['ownPage'][0]['ownImage']),true);
		asrt(count($objects[0]['ownPage'][0]['ownImage']),1);
		$objects = (R::exportAll(array($book),true,array('author')));
		asrt(isset($objects[0]['ownPage']),false);
		asrt(isset($objects[0]['ownPage'][0]['ownText']),false);
		$objects = (R::exportAll(array($book),true,array('page')));
		asrt(isset($objects[0]['author']),false);
		asrt(isset($objects[0]['ownPage'][0]['ownText']),false);
		$objects = (R::exportAll(array($book),true,array('page','text')));
		asrt(isset($objects[0]['author']),false);
		asrt(isset($objects[0]['ownPage']),true);
		asrt(isset($objects[0]['ownPage'][0]['ownText']),true);
		asrt(count($objects[0]['ownPage'][0]['ownText']),1);
		asrt(isset($objects[0]['ownPage'][0]['ownImage']),false);
		$objects = (R::exportAll(array($book),true,array('none')));
		asrt(isset($objects[0]['author']),false);
		asrt(isset($objects[0]['ownPage']),false);
		$texts = R::find('text');
		R::preload($texts,'page,*.book,*.author');
		$objects = (R::exportAll($texts,true));
		asrt(isset($objects[0]['page']['book']['author']['publisher']),true);
		asrt(isset($objects[0]['page']['sharedBookmark']),false);
		$texts = R::find('text');
		R::preload($texts,array('page','page.sharedBookmark'=>'bookmark'));
		$objects = (R::exportAll($texts,true));
		asrt(isset($objects[0]['page']['book']['author']['publisher']),true);
		asrt(isset($objects[0]['page']['sharedBookmark']),true);
		asrt(isset($objects[0]['page']['sharedBookmark'][0]['ownNote']),false);
		$texts = R::find('text');
		R::preload($texts,array('page','page.sharedBookmark'=>'bookmark','page.sharedBookmark.ownNote'=>'note'));
		$objects = (R::exportAll($texts,true));
		asrt(isset($objects[0]['page']['book']['author']['publisher']),true);
		asrt(isset($objects[0]['page']['sharedBookmark']),true);
		asrt(isset($objects[0]['page']['sharedBookmark'][0]['ownNote']),true);
		R::$duplicationManager->setCacheTables(false);
		testpack('Keyless export');
		$book = R::load('book',$bookID);
		$book->ownPage;
		$export = $book->export();
		asrt(isset($export['ownPage'][0]),true);
		RedBean_OODBBean::setFlagKeyedExport(true);
		$export = $book->export();
		asrt(isset($export['ownPage'][1]),true);
		RedBean_OODBBean::setFlagKeyedExport(false);
		$export = $book->export();
		asrt(isset($export['ownPage'][0]),true);
	}

	/**
	 * Compares object with export
	 * @param type $object
	 * @param type $array 
	 */
	public function compare($object,$array) {
		foreach($object as $property=>$value) {
			if (is_array($value)) {
					foreach($value as $index=>$nestedObject) {
						if ($nestedObject->id) {
							$foundMatch = false;
							//order might be different
							foreach($array[$property] as $k=>$a) {
								if ($a['id']==$nestedObject->id) {
									$foundMatch = true;
									$index = $k;
								}
							}
							if (!$foundMatch) throw new Exception('failed to find match for object '.$nestedObject->id);
						}
						$this->compare($nestedObject,$array[$property][$index]);
					}
			}
			elseif (!is_object($value)){
				asrt(strval($array[$property]),strval($value));
			}
		}
	}
	
	/**
	 * Run tests
	 */
	public function runOnce($n=true) {
	
		
		$books = R::dispense('book',10);
		$pages = R::dispense('page',10);
		$readers = R::dispense('reader',10);
		$texts = R::dispense('text',10);
		
		$i = 0;
		foreach($books as $book) $book->name = 'book-'.($i++);
		$i = 0;
		foreach($pages as $page) $page->name = 'page-'.($i++);
		$i = 0;
		foreach($readers as $reader) $reader->name = 'reader-'.($i++);
		$i = 0;
		foreach($texts as $text) $text->content = 'lorem ipsum -'.($i++);
		
		
		foreach($texts as $text) {
			$pages[array_rand($pages)]->ownText[] = $text;
		}
		foreach($pages as $page) {
			$books[array_rand($books)]->ownPage[] = $page;
		}
		foreach($readers as $reader) {
			$books[array_rand($books)]->sharedReader[] = $reader;
		}
		$i = $noOfReaders = $noOfPages = $noOfTexts = 0;
		foreach($books as $key=>$book) { 
			$i++;
			$noOfPages += count($book->ownPage);
			$noOfReaders += count($book->sharedReader);
			foreach($book->ownPage as $page) $noOfTexts += count($page->ownText); 
			$arr = R::exportAll($book);
			echo "\nIntermediate info: ".json_encode($arr).": Totals = $i,$noOfPages,$noOfReaders,$noOfTexts ";
			
			$this->compare($book,$arr[0]);
			$copiedBook = R::dup($book);
			$copiedBookArray = R::exportAll($copiedBook);
			$this->compare($book,$copiedBookArray[0]);
			$copiedBookArrayII = $copiedBook->export();
			$this->compare($book,$copiedBookArrayII);
			$copyFromCopy = R::dup($copiedBook);
			$copyFromCopyArray = R::exportAll($copyFromCopy);
			$this->compare($book,$copyFromCopyArray[0]);
			$copyFromCopyArrayII = $copyFromCopy->export();
			$this->compare($book,$copyFromCopyArrayII);
			$id = R::store($book);
			$copiedBook = R::dup($book);
			R::store($book); //should not be damaged
			$copiedBookArray = R::exportAll($copiedBook);
			$originalBookArray = R::exportAll($book);
			$this->compare($copiedBook,$copiedBookArray[0]);
			$this->compare($book,$originalBookArray[0]);
			$book = R::load('book',$id);
			$this->compare($book,$originalBookArray[0]);
			$copiedBook = R::dup($book);
			$this->compare($copiedBook,$copiedBook->export());
			R::store($copiedBook);
			$this->compare($copiedBook,$copiedBook->export());
			$copyFromCopy = R::dup($copiedBook);
			$this->compare($copyFromCopy,$copyFromCopy->export());
			R::store($copyFromCopy);
			$newPage = R::dispense('page');
			$newPage->name = 'new';
			$copyFromCopy->ownPage[] = $newPage;
			$modifiedCopy = R::dup($copyFromCopy);
			$exportMod = R::exportAll($modifiedCopy);
			$this->compare($modifiedCopy,$exportMod[0]);
			asrt(count($modifiedCopy->ownPage),count($copiedBook->ownPage)+1);
			R::store($modifiedCopy);
			
			if ($n) {
			asrt((int)R::getCell('SELECT count(*) FROM book'),$i*4);
			asrt((int)R::getCell('SELECT count(*) FROM page'),($noOfPages*4)+$i);
			asrt((int)R::getCell('SELECT count(*) FROM text'),$noOfTexts*4);
			asrt((int)R::getCell('SELECT count(*) FROM book_reader'),$noOfReaders*4);
			asrt((int)R::getCell('SELECT count(*) FROM reader'),$noOfReaders);
			}
		}
		
		if ($n) {
		asrt($noOfTexts,10);
		asrt($noOfReaders,10);
		asrt($noOfPages,10);
		asrt($i,10);
		}
	}
}
