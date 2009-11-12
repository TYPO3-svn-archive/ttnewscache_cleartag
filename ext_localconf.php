<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) Krystian Szymukowicz (typo3@prolabium.com)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
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
 ***************************************************************/
/**
 * localconf for the extension 'ttnewscache_clearlike'
 *
 * @author Krystian Szymukowicz <typo3@prolabium.com>
 */

if (!defined ('TYPO3_MODE')) die ('Access denied.');

require_once(t3lib_extMgm::extPath('ttnewscache_cleartag').'class.tx_ttnewscachecleartag.php');
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tt_news']['extraItemMarkerHook'][] = 'tx_ttnewscachecleartag';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tt_news']['extraGlobalMarkerHook'][] = 'tx_ttnewscachecleartag';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:ttnewscache_cleartag/class.tx_ttnewscachecleartag_tce.php:tx_ttnewscacheClearTag_tcemain';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:ttnewscache_cleartag/class.tx_ttnewscachecleartag_tce.php:tx_ttnewscacheClearTag_tcemain'; 

?>