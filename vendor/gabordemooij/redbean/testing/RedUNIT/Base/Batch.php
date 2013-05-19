<?php
/**
 * RedUNIT_Base_Batch 
 * 
 * @file 			RedUNIT/Base/Batch.php
 * @description		Tests batch loading of beans, i.e. loading large collections of beans
 * 					in optimized way.
 * 					This class is part of the RedUNIT test suite for RedBeanPHP.
 * @author			Gabor de Mooij
 * @license			BSD
 *
 *
 * (c) G.J.G.T. (Gabor) de Mooij
 * This source file is subject to the BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */
class RedUNIT_Base_Batch extends RedUNIT_Base {

	/**
	 * Begin testing.
	 * This method runs the actual test pack.
	 * 
	 * @return void
	 */
	public function run() {
	
		$toolbox = R::$toolbox;
		$adapter = $toolbox->getDatabaseAdapter();
		$writer  = $toolbox->getWriter();
		$redbean = $toolbox->getRedBean();
		$pdo = $adapter->getDatabase();
			
		$page = $redbean->dispense("page");
		$page->name = "page no. 1";
		$page->rating = 1;
		$id1 = $redbean->store($page);
		$page = $redbean->dispense("page");
		$page->name = "page no. 2";
		$id2 = $redbean->store($page);
		$batch = $redbean->batch( "page", array($id1, $id2) );
		asrt(count($batch),2);
		asrt($batch[$id1]->getMeta("type"),"page");
		asrt($batch[$id2]->getMeta("type"),"page");
		asrt((int)$batch[$id1]->id,$id1);
		asrt((int)$batch[$id2]->id,$id2);
		$book = $redbean->dispense("book");
		$book->name="book 1";
		$redbean->store($book);
		$book = $redbean->dispense("book");
		$book->name="book 2";
		$redbean->store($book);
		$book = $redbean->dispense("book");
		$book->name="book 3";
		$redbean->store($book);
		$books = $redbean->batch("book", $adapter->getCol("SELECT id FROM book"));
		asrt(count($books),3);
		
		$a = $redbean->batch('book',9919);
		asrt(is_array($a),true);
		asrt(count($a),0);
		$a = $redbean->batch('triangle',1);
		asrt(is_array($a),true);
		asrt(count($a),0);
		
		R::freeze(true);
		
		$a = $redbean->batch('book',9919);
		asrt(is_array($a),true);
		asrt(count($a),0);
		$a = $redbean->batch('triangle',1);
		asrt(is_array($a),true);
		asrt(count($a),0);
		
		R::freeze(false);
		
		asrt(R::wipe('spaghettimonster'),false);
		
		testpack('deal with missing beans');
		R::nuke();
		$id = R::store(R::dispense('beer'));
		$bottles = R::batch('beer',array($id,$id+1,$id+2));
		asrt(count($bottles),3);
		asrt((int)$bottles[$id]->id,(int)$id);
		asrt((int)$bottles[$id+1]->id,0);
		asrt((int)$bottles[$id+2]->id,0);
		
		
	}	
}