<?php
/*
 * Register necessary class names with autoloader
 *
 * $Id: ext_autoload.php 6045 2009-09-24 11:25:43Z steffenk $
 */
$extensionPath = t3lib_extMgm::extPath('ttnewscache_cleartag');
return array(
	'tx_ttnewscachecleartag_tcemain' => $extensionPath . 'class.tx_ttnewscachecleartag_tce.php',
	'tx_ttnewscachecleartag' => $extensionPath . 'class.tx_ttnewscachecleartag.php',
);
?>
