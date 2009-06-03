<?php
/**
 * ************************************************************
 *  Copyright notice
 *
 *  (c) Krystian Szymukowicz (typo3@prolabium.com)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/o*r modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 *
 */

/**
 * Clear cache function for ttnewscache exetension. Uses sql LIKE to search for markers in HTML field of cache_pages table.
 *
 * @author    Krystian Szymukowicz <typo3@prolabium.com>
 */
class tx_ttnewscacheClearTag_tcemain {

	var $extKey = 'ttnewscache_cleartag';

	
	/**
	 * TCEmain hook used to get old values of 'categories' and 'related' in case of 'update' of the record.
	 *
	 * @param	array		Form fields.
	 * @param	array		Table the record belongs to.
	 * @param	integer		Id of the record.
	 * @param	object		Parent object.
	 * @return	void
	 */
	function processDatamap_preProcessFieldArray($incomingArray, $table, $id, &$thisRef) {
		//DebugBreak();
	}
	
	/**
	 * TCEmain hook used to take care of 'delete' record.
	 *
	 * @param	string		Command from tcemain.
	 * @param	string		Table the comman process on.
	 * @param	integer		Id of the record.
	 * @param	string		Value for the command.
	 * @param	object		Parent object.
	 * @return	void
	 */
	function processCmdmap_preProcess ($command, $table, $id, $value, &$thisRef) {
		if ($command == 'delete' && $table == 'tt_news') {
			$fieldArray = array();
			$this->processDatamap_afterDatabaseOperations($command, $table, $id, $fieldArray, $thisRef);
		}
	}
	
	/**
	 * TCEmain hook used to take care of 'new', 'update' and 'delete' record. 'delete' is kind "fake" - is called from processCmdmap_preProcess.
	 *
	 * @param	string		Operation status. Can be 'new','update'. Call from processCmdmap_preProcess add third status 'delete'.
	 * @param	string		Table the operation was processed on.
	 * @param	integer		Id of the record.
	 * @param	array		Fields that have been changed.
	 * @param	object		Parent object.
	 * @return	void
	 */
	function processDatamap_afterDatabaseOperations($status, $table, $id, &$fieldArray, &$thisRef) {
		if ($table == 'tt_news') {
			if (intval($thisRef->datamap['tt_news'][$id]['category']) != 1) {
				$cats = split(',', t3lib_div::rm_endcomma($thisRef->datamap['tt_news'][$id]['category']));
				$this->flushListCache($cats);
			}
			if ($status == 'update') {
				$this->flushSingleCache($id);
			}
		}
	}

	/**
	 * Flush tt_news SINGLE cache
	 * Deletes cache with ttnewscache_detail_* tag
	 *
	 * @param	int		$id: tt_news id for which to clear cache
	 * @return	void
	 */		
	function flushSingleCache($id) {
		$this->getCache(); 
		$this->pageCache->flushByTag('ttnewscache_detail_' . $id);
	}
	
	/**
	 * Flush tt_news LIST cache
	 * Deletes cache with ttnewscache_cat_* tag
	 *
	 * @param	array		$categories: categories for which to clear the cache
	 * @return	void
	 */	
	function flushListCache($categories = array()) {
		$this->getCache(); 
		foreach ($categories as $cat) {
			if (intval($cat)) {
				$this->pageCache->flushByTag('ttnewscache_cat_' . $cat);
			}
		}		
	}
	
	/**
	 * Initialize Page Cache framework
	 *
	 * @return	void
	 */	
	function getCache() {
		if (!isset($this->pageCache)) {
			try {
				$this->pageCache = $GLOBALS['typo3CacheManager']->getCache(
					'cache_pages'
				);
			} catch(t3lib_cache_exception_NoSuchCache $e) {
				t3lib_cache::initPageCache();

				$this->pageCache = $GLOBALS['typo3CacheManager']->getCache(
					'cache_pages'
				);
			}
		}
	}
	
}
?>
