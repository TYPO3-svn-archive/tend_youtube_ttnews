<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_tendyoutubettnews_youtube_video' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:tend_youtube_ttnews/locallang_db.xml:tt_news.tx_tendyoutubettnews_youtube_video',		
		'config' => array (
			'type' => 'group',	
			'internal_type' => 'db',	
			'allowed' => 'tx_tendyoutube_video',	
			'size' => 5,	
			'minitems' => 0,
			'maxitems' => 100,
		)
	),
);


t3lib_div::loadTCA('tt_news');
t3lib_extMgm::addTCAcolumns('tt_news',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tt_news','tx_tendyoutubettnews_youtube_video;;;;1-1-1');
?>