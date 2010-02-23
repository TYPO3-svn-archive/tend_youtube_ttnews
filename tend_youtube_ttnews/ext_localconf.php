<?php

/* Dodatni HOOKI */
/* $Id$ */


if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$GLOBALS ['TYPO3_CONF_VARS']['EXTCONF']['tt_news']['extraItemMarkerHook']['additionalFields'] =
'EXT:tend_youtube_ttnews/class.tx_tendyoutubettnews_additionalMarkers.php:tx_tendyoutubettnews_additionalMarkers';
