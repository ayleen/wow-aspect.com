<?php
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.0 Patch Level 3
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2012 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/
if (!VB_API) die;

loadCommonWhiteList();

$VB_API_WHITELIST = array(
	'response' => array(
		'HTML' => array(
			'anywaychecked', 'checked', 'disablesmiliesoption',
			'pm' => array(
				'pmid', 'recipients', 'bccrecipients', 'title', 'forward'
			),
			'posticons',
			'postpreview', 'selectedicon'
		)
	),
	'show' => array(
		'receivepmbuddies', 'bcclink', 'posticons', 'smiliebox', 'sendmax',
		'sendmultiple', 'parseurl'
	)
);

/*======================================================================*\
|| ####################################################################
|| # CVS: $RCSfile$ - $Revision: 35584 $
|| ####################################################################
\*======================================================================*/