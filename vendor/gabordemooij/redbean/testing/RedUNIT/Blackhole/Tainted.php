<?php
/**
 * RedUNIT_Blackhole_Tainted 
 * 
 * @file 			RedUNIT/Blackhole/Tainted.php
 * @description		Tests tainted flag for OODBBean objects.
 * 					This class is part of the RedUNIT test suite for RedBeanPHP.
 * @author			Gabor de Mooij
 * @license			BSD
 *
 *
 * (c) G.J.G.T. (Gabor) de Mooij
 * This source file is subject to the BSD/GPLv2 License that is bundled
 * with this source code in the file license.txt.
 */
class RedUNIT_Blackhole_Tainted extends RedUNIT_Blackhole {
	/**
	 * Begin testing.
	 * This method runs the actual test pack.
	 * 
	 * @return void
	 */
	public function run() {
		
		testpack('Original Tainted Tests');
		$redbean = R::$redbean;
		$spoon = $redbean->dispense("spoon");
		asrt($spoon->getMeta("tainted"),true);
		$spoon->dirty = "yes";
		asrt($spoon->getMeta("tainted"),true);
		
		testpack('Tainted List test');
		$note = R::dispense('note');
		$note->text = 'abc';
		$note->ownNote[] = R::dispense('note')->setAttr('text','def');
		$id = R::store($note);
		$note = R::load('note',$id);
		asrt($note->isTainted(),false);
		$note->text; //shouldnt affect tainted
		asrt($note->isTainted(),false);
		$note->ownNote;
		asrt($note->isTainted(),true);
		
		testpack('Tainted Test Old Value');
		$text = $note->old('text');
		asrt($text,'abc');
		asrt($note->hasChanged('text'),false);
		$note->text = 'xxx';
		asrt($note->hasChanged('text'),true);
		$text = $note->old('text');
		asrt($text,'abc');
		
		testpack('Tainted Non-exist');
		asrt($note->hasChanged('text2'),false);
		
		testpack('Misc Tainted Tests');
		$bean = R::dispense('bean');
		$bean->hasChanged('prop');
		$bean->old('prop');
	}
}