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
class tx_ttnewscachecleartag_tcemain {

	var $extKey = 'ttnewscache_cleartag';
	
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
	public function processCmdmap_preProcess ($command, $table, $id, $value, &$thisRef) {
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
	public function processDatamap_afterDatabaseOperations($status, $table, $id, &$fieldArray, &$thisRef) {
		if ($table == 'tt_news') {
			if (!empty($thisRef->datamap['tt_news'][$id]['category'])) {
				$cats = explode(',', t3lib_div::rm_endcomma($thisRef->datamap['tt_news'][$id]['category']));
				$this->flushListCache($cats);
			}
			else if (!isset($thisRef->datamap['tt_news'][$id]['category'])) {
				// Find category from tt_news record
				$cats = $this->findCategories($id);
				if ($status == 'delete' || ($status == 'update' && isset($thisRef->datamap['tt_news'][$id]['hidden']))) {
					// record visibility changed, need to clear category cache
					$this->flushListCache($cats);
				}
			}
			if ($status == 'update' || $status == 'delete') {
				$this->flushSingleCache($id);
			}
			
			if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ttnewscache_cleartag']['clearCache'])) {
				$params = array(
					'status'		=> $status,
					'table'			=> $table,
					'id'			=> $id,
					'fieldArray'	=> $fieldArray,
					'categories'	=> $cats,
					'refObj'		=> $thisRef
				);
								
				foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ttnewscache_cleartag']['clearCache'] as $_classRef) {
					$extObj = & t3lib_div::getUserObj($_classRef);
					if (method_exists($extObj, 'processDatamap_afterDatabaseOperations')) {
						$extObj->processDatamap_afterDatabaseOperations($this, $params);
					}
				}
			}			
		}
	}
	
	/**
	* Find categories, given tt_news UID
	* 
	* @param	integer		UID of tt_news record
	* @return	array		Array of categories
	*/
	public function findCategories($id) {
		$categories = array();
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid_foreign', 'tt_news_cat_mm', 'uid_local=' . intval($id));
		foreach ($rows as $row) {
			$categories[] = $row['uid_foreign'];
		}
		return $categories;
	}

	/**
	 * Flush tt_news SINGLE cache
	 * Deletes cache with ttnewscache_detail_* tag
	 *
	 * @param	int		$id: tt_news id for which to clear cache
	 * @return	void
	 */		
	public function flushSingleCache($id) {
		$this->flushByTag('ttnewscache_detail_' . $id);
		
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ttnewscache_cleartag']['clearCache'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ttnewscache_cleartag']['clearCache'] as $_classRef) {
				$extObj = & t3lib_div::getUserObj($_classRef);
				if (method_exists($extObj, 'flushSingleCache')) {
					$extObj->flushSingleCache($this, $id);
				}
			}
		}			
	}
	
	/**
	 * Flush tt_news LIST cache
	 * Deletes cache with ttnewscache_cat_* tag
	 *
	 * @param	array		$categories: categories for which to clear the cache
	 * @return	void
	 */	
	public function flushListCache($categories = array()) {
		foreach ($categories as $cat) {
			if (intval($cat)) {
				$this->flushByTag('ttnewscache_cat_' . $cat);
			}
		}
		
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ttnewscache_cleartag']['clearCache'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ttnewscache_cleartag']['clearCache'] as $_classRef) {
				$extObj = & t3lib_div::getUserObj($_classRef);
				if (method_exists($extObj, 'flushListCache')) {
					$extObj->flushListCache($this, $categories);
				}
			}
		}
	}
	
	/**
	* Flush page cache by tag.
	* 
	* @param	string		Tag to clear cache by
	*/
	public function flushByTag($tag) {
		if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['useCachingFramework']) {
			$this->getCache();
			$this->pageCache->flushByTag($tag);
		}
	}
	
	/**
	 * Initialize Page Cache framework
	 *
	 * @return	void
	 */	
	private function getCache() {
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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ttnewscache_cleartag/class.tx_ttnewscachecleartag_tce.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ttnewscache_cleartag/class.tx_ttnewscachecleartag_tce.php']);
}
?>