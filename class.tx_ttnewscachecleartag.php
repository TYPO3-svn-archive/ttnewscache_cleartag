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
class tx_ttnewscacheClearTag {

	var $extKey = 'ttnewscache_cleartag';
	
	/**
	 * this function is called by the Hook in the function getItemMarkerArray() from class.tx_ttnews.php
	 *
	 * @param	array		$markerArray: the markerArray from the tt_news class
	 * @param	array		$row: the database row for the current news-record
	 * @param	array		$lConf: the TS setup array from tt_news (holds the TS vars from the current tt_news view)
	 * @param	object		$pObj: reference to the parent object
	 * @return	array		$markerArray: the processed markerArray
	 */
	function extraItemMarkerProcessor($markerArray, $row, $lConf, &$pObj) {
		//DebugBreak();
		$tag = 'ttnewscache_detail_' . $row['uid'];
        $this->addTag($tag);
		return $markerArray;
	}
	
	/**
	 * this function is called by the Hook in the function extraGlobalMarkerProcessor() from class.tx_ttnews.php
	 *
	 * @param	object		$pObj: reference to the parent object
	 * @param	array		$markerArray: the markerArray from the tt_news class
	 * @return	array		$markerArray: the processed markerArray
	 */
	function extraGlobalMarkerProcessor(&$pObj, $markerArray) {
		//DebugBreak();
		if ($pObj->theCode != 'SINGLE') {
			$cats = split(',', $pObj->catExclusive);
			foreach ($cats as $cat) {
				$this->addTag('ttnewscache_cat_' . $cat);
			}
		}
		return $markerArray;
	}
	
	/**
	 * Add tag to page that is going to be cached.
	 * Checks for duplicates
	 *
	 * @param	string		$tag: Tag that will be added to the page tag list
	 * @return	void
	 */		
	function addTag($tag) {
		static $addedTags = array();
		if (!in_array($tag, $addedTags)) {
			$addedTags[] = $tag;
			$GLOBALS['TSFE']->addCacheTags(array($tag));
		}	
	}
	
}
?>