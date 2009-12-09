<?php

########################################################################
# Extension Manager/Repository config file for ext "ttnewscache_cleartag".
#
# Auto generated 09-12-2009 19:51
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'ttnews Cache Clear by Tag',
	'description' => '',
	'category' => 'be',
	'author' => 'Dan Osipov',
	'author_email' => 'dosipov@phillyburbs.com',
	'shy' => '',
	'dependencies' => 'tt_news',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.1',
	'constraints' => array(
		'depends' => array(
			'tt_news' => '',
			'typo3' => '4.3.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:10:{s:9:"ChangeLog";s:4:"6059";s:10:"README.txt";s:4:"ee2d";s:32:"class.tx_ttnewscachecleartag.php";s:4:"b4c8";s:36:"class.tx_ttnewscachecleartag_tce.php";s:4:"f3ed";s:16:"ext_autoload.php";s:4:"d797";s:12:"ext_icon.gif";s:4:"ba74";s:17:"ext_localconf.php";s:4:"ce1b";s:14:"doc/manual.sxw";s:4:"34a6";s:19:"doc/wizard_form.dat";s:4:"df4c";s:20:"doc/wizard_form.html";s:4:"a031";}',
	'suggests' => array(
	),
);

?>