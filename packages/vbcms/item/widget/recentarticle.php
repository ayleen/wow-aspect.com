<?php if (!defined('VB_ENTRY')) die('Access denied.');
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

/**
 * Test Widget Item
 *
 * @package vBulletin
 * @author Edwin Brown, vBulletin Development Team
 * @version $Revision: 35390 $
 * @since $Date: 2010-02-11 08:45:25 -0800 (Thu, 11 Feb 2010) $
 * @copyright vBulletin Solutions Inc.
 */
class vBCms_Item_Widget_RecentArticle extends vBCms_Item_Widget
{
	/*Properties====================================================================*/

	/**
	 * A package identifier.
	 *
	 * @var string
	 */
	protected $package = 'vBCms';

	/**
	 * A class identifier.
	 *
	 * @var string
	 */
	protected $class = 'RecentArticle';

	/** The default configuration **/
	protected $config = array(
		'categories'    => '',
		'sections'    => '',
		'template_name' => 'vbcms_widget_recentarticle_page',
		'days' => 7,
		'count' => 6,
		'cache_ttl' => 5
	);

}

/*======================================================================*\
|| ####################################################################
|| # SVN: $Revision: 35390 $
|| ####################################################################
\*======================================================================*/