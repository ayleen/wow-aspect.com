<?php
/**
*
* @package Wowhead Tooltips
* @version wowhead_armory.php 4.3
* @copyright (c) 2010 Adam Koch <http://wowhead-tooltips.com>
* @license http://www.wowhead-tooltips.com/downloads/terms-of-use/
*
*/

/**
 * Armory Module
 * @package Wowhead Tooltips
 * @extends wowhead
 */
class wowhead_armory extends wowhead
{
	/**
	 * Armory Region
	 * @var string $region
	 */
	private $region;
	
	/**
	 * Armory Realm
	 * @var string $realm
	 */
	private $realm;
	
	/**
	 * Config Module
	 * @var object $config
	 */
	public $config;
	
	/**
	 * Patterns Module
	 * @var object $patterns
	 */
	public $patterns;
	
	/**
	 * Default Language (Dummy Var)
	 * @var string $lang
	 */
	public $lang = 'en';
	
	/**
	 * Language Packs
	 * @var object $language
	 */
	public $language;
	
	/**
	 * Show Armory Icons
	 * @var bool $icons
	 */
	private $icons;
	
	/**
	 * Show Class Icon
	 * @var bool $class_icon
	 */
	private $class_icon;
	
	/**
	 * Show Race Icons
	 * @var bool $race_icon
	 */
	private $race_icon;
	
	/**
	 * Icon URL
	 * var string $icon_url
	 */
	private $icon_url;
	
	/**
	 * Armory Character Cache Time
	 * @var int $char_cache
	 */
	private $char_cache;
	
	/**
	 * Armory URL
	 * @var string $url
	 */
	private $url;
	
	/**
	 * Armory Character URL
	 * @var string $char_url
	 */
	private $char_url;
	
	/**
	 * Character Data From Armory
	 * @var array $char_data
	 */
	private $char_data = array();
	
	/**
	 * Character Stats
	 * @var array $stats
	 */
	private $stats = array();
	
	/**
	 * Base URL for Images
	 * @var string $images_base_url
	 */
	private $images_base_url;
	
	/**
	 * Base URL for Avatars
	 * @var string $avatars_base_url
	 */
	private $avatars_base_url;
	
	/**
	 * Main Spec Tree for Talents
	 * @var int $main_spec
	 */
	private $main_spec;
	
	/**
	 * Unix Timestamp
	 * @var int $now
	 */
	private $now;
	
	/**
	 * Average Item Level
	 * @var int $item_level
	 */
	private $item_level;
	
	/**
	 * Show Guild Rank
	 * @var bool $show_rank
	 */
	private $show_rank;
	
	/**
	 * Determines What Output is Produced
	 * @var string $type
	 */
	private $type = 'armory';
	
	/**
	 * Date Format For date();
	 * @var string $date_format
	 */
	private $date_format;
	
	/**
	 * Time Format for date();
	 * @var string $time_format
	 */
	private $time_format;
	
	/**
	 * Stats Config to Show
	 * @var array $stats_conf
	 */
	protected $stats_conf = array (
		/* --base stats-- */
		'stamina' => false,
		'strength' => false,
		'intellect' => false,
		'agility' => false,
		'spirit' => false,
		'armor' => false,
		/*   -- spell damage--   */
		'shadow_power' => false,
		'shadow_crit' => false,
		'fire_power' => false,
		'fire_crit' => false,
		'frost_power' => false,
		'frost_crit' => false,
		'arcane_power' => false,
		'arcane_crit' => false,
		'nature_power' => false,
		'nature_crit' => false,
		'holy_power' => false,
		'holy_crit' => false,
		'healing' => false,
		'mana_regen' => false,
		'mana_regen_cast' => false,
		'spell_hit' => false,
		'penetration' => false,
		/*   -- melee damage--   */
		'melee_main_dmg' => false,
		'melee_main_speed' => false,
		'melee_main_dps' => false,
		'melee_off_dmg' => false,
		'melee_off_speed' => false,
		'melee_off_dps' => false,
		'melee_power' => false,
		'melee_hit' => false,
		'melee_crit' => false,
		'melee_expertise' => false,
		/*   -- ranged damage--   */
		'ranged_power' => false,
		'ranged_dmg' => false,
		'ranged_speed' => false,
		'ranged_dps' => false,
		'ranged_crit' => false,
		'ranged_hit' => false,
                /*  --Haste--  */
                'haste_rating' => false,
		/*  --defenses--  */
		'defense' => false,
		'dodge' => false,
		'parry' => false,
		'block' => false,
		'resilience' => false,
		/*  --resistances--  */
		'arcane_resist' => false,
		'fire_resist' => false,
		'frost_resist' => false,
		'shadow_resist' => false,
		'nature_resist' => false,
		'holy_resist' => false,
	);
	
	/**
	 * Determine Class from ID
	 * @var array $id_to_name
	 */
	private $id_to_name = array (
		'unknown',
		'warrior',
		'paladin',
		'hunter',
		'rogue',
		'priest',
		'deathknight',
		'shaman',
		'mage',
		'warlock',
		'(10)',
		'druid',
	);

	/**
	 * Determine Race from ID
	 * @var array $race_ids
	 */
	private $race_ids = array(
	    'unknown',
	    'human',
	    'orc',
	    'dwarf',
	    'nightelf',
	    'undead',
	    'tauren',
	    'gnome',
	    'troll',
	    '(9)',
	    'bloodelf',
	    'draenei'
	);

	/**
	 * Determine Gender from ID
	 * @var array $gender_ids
	 */
	private $gender_ids = array(
	    'male',
	    'female'
	);	
	
	/**
	 * Character Tab SimpleXML
	 * @var object $ctab
	 */
	private $ctab;
	
	/**
	 * Character SimpleXML
	 * @var object $char
	 */
	private $char;
	
	/**
	 * Character Info SimpleXML
	 * @var object $cinfo
	 */
	private $cinfo;
	
	/**
	 * Constructor
	 * @access public
	 * @param object $config
	 * return null
	 */
	public function __construct()
	{
		// we'll need these later
		$this->config = new wowhead_config();
		$this->config->loadConfig();
		$this->region = $this->config->armory_region;
		$this->realm = $this->config->armory_realm;
		$this->patterns = new wowhead_patterns;
		$this->icons = $this->config->armory_icons;
		$this->class_icon = $this->config->armory_class_icon;
		$this->race_icon = $this->config->armory_race_icon;
		$this->icon_url = $this->config->armory_image_url;
		$this->char_cache = (int)$this->config->armory_char_cache;
		$this->item_level = $this->config->armory_item_level;
		$this->show_rank = $this->config->armory_show_rank;
		$this->url = $this->armoryURL();
		$this->images_base_url = $this->url . '_images/icons/';
		$this->avatars_base_url = $this->url . '_images/portraits/';
		$this->date_format = $this->config->armory_date_format;
		$this->time_format = $this->config->armory_time_format;
		$this->lang = $this->config->lang;
		$this->language = new wowhead_language();
	}
	
	/**
	 * Destructor
	 * @return null
	 */
	public function close()
	{
		unset($this->lang, $this->language, $this->patterns, $this->config);	
	}
	
	/**
	 * Parse Text
	 * @access public
	 * @param string $name
	 * @param array $args [optional]
	 * @return string
	 */
	public function parse($name, $args = array())
	{

		if (trim($name) == '')
			return false;
		
		$cache = new wowhead_cache();
		// they specified a realm/region
		if (array_key_exists('loc', $args))
		{
			$aLoc = explode(',', $args['loc']);
			$this->region = $aLoc[0];
			$this->realm = $aLoc[1];
		}
		
		// set the various options
		if (array_key_exists('lang', $args))		// set the language
			$this->lang = $args['lang'];	
		if (array_key_exists('noicons', $args))		// disable all icons
			$this->icons = false;
		if (array_key_exists('noclass', $args))		// disable class icon
			$this->class_icon = false;
		if (array_key_exists('norace', $args))		// disable race icon
			$this->race_icon = false;
		if (array_key_exists('gearlist', $args))	// include gearlist
			$this->type = 'armory_gearlist';
		if (array_key_exists('recruit', $args))		// include recruit info
			$this->type = 'armory_recruit';
		if (array_key_exists('rss', $args))			// include rss info
			$this->type = 'armory_rss';
		
		// load the language pack
		$this->language->loadLanguage($this->lang);	
		$this->char_url = $this->characterURL($name);
		
		if (WOWHEAD_DEBUG == true)
			print $this->char_url;
		
		$this->now = mktime();
		$uniquekey = $cache->generateKey($name, $this->realm, $this->region);
		$result = $cache->getArmory($uniquekey, $this->char_cache);
		if (!$result)
		{
			$xml_data = $this->getXML($this->characterURL($name));
			if (!$xml = @simplexml_load_string($xml_data, 'SimpleXMLElement'))
			{
				// failed to get the xml, most likely blocked by the armory or character not found
				$cache->close();
				return $this->generateError($this->language->words['armory_blocked']);
			}
			
			// make sure the character does exist
			if (array_key_exists('errcode', $xml->characterInfo))
			{
				$cache->close();
				return $this->generateError($this->language->words['char_not_found']);	
			}
			elseif (!$xml->characterInfo->characterTab)
			{
				$cache->close();
				return $this->generateError($this->language->words['char_no_data']);
			};
			
			$this->ctab = $xml->characterInfo->characterTab;
			$this->char = $xml->characterInfo->character;
			$this->cinfo = $xml->characterInfo;
			
			// get character stats
			$this->stats = $this->generateStats();
			
			// get character talents
			$this->char_data['talents'] = $this->generateTalents();
			
			// get professions
			$this->char_data['prof'] = $this->generateProfessions();
			
			// get avatar
			$this->char_data['avatar'] = $this->generateAvatar();
			
			// generate achievements
			$this->char_data['achieve'] = $this->generateAchievements();
			
			// generate average item level
			if ($this->item_level == true)
				$this->char_data['itemlevel'] = $this->generateItemLevel();
			
			// finalize the character and add other randomness
			$this->finalizeChar();
			
			// save to cache
			$cache->saveArmory(array(
				'uniquekey'	=>	$uniquekey,
				'name'		=>	$this->char_data['name'],
				'class'		=>	$this->char_data['class'],
				'genderid'	=>	$this->char_data['genderid'],
				'raceid'	=>	$this->char_data['raceid'],
				'classid'	=>	$this->char_data['classid'],
				'realm'		=>	$this->realm,
				'region'	=>	$this->region,
				'tooltip'	=>	$this->generateTooltip()
			));
			
			unset($xml); $cache->close();
			
			// generate html
			return $this->generateHTML(array(
				'realm'		=>	$this->realm,
				'region'	=>	$this->region,
				'name'		=>	$this->char_data['name'],
				'icons'		=>	$this->getIcons($this->char_data['raceid'], $this->char_data['genderid'], $this->char_data['classid']),
				'link'		=>	$this->characterURL($this->char_data['name']),
				'class'		=>	'armory_tt_class_' . strtolower(str_replace(' ', '', $this->char_data['class'])),
				'image'		=>	$this->icon_url . 'images/wait.gif'
			), $this->type);
		}
		else
		{
			$cache->close();
			return $this->generateHTML(array(
				'realm'		=>	$this->realm,
				'region'	=>	$this->region,
				'name'		=>	$result['name'],
				'icons'		=>	$this->getIcons($result['raceid'], $result['genderid'], $result['classid']),
				'link'		=>	$this->characterURL($result['name']),
				'class'		=>	'armory_tt_class_' . strtolower(str_replace(' ', '', $result['class'])),
				'image'		=>	$this->icon_url . 'images/wait.gif'
			), $this->type);
		}
	}
	
	/**
	 * Generates Tooltip
	 * @access public
	 * @return string
	 */
	private function generateTooltip()
	{
		// now we'll actually build the tooltip
		
		// get the template from the patterns class
		$html = $this->patterns->pattern('armory_tooltip');
		
		// now time to replace everything, lol
		$html = str_replace('{avatar}', $this->char_data['avatar'], $html);
		$html = str_replace('{name}', $this->char_data['prefix'] . $this->char_data['name'] . $this->char_data['suffix'], $html);
		$html = str_replace('{guild}', $this->char_data['guild'], $html);
		$html = str_replace('{rank}', $this->char_data['rank'], $html);
		$html = str_replace('{level}', $this->char_data['level'], $html);
		$html = str_replace('{race}', $this->char_data['race'], $html);
		$html = str_replace('{class}', $this->char_data['class'], $html);
		$html = str_replace('{health}', $this->char_data['health']['value'], $html);
		$html = str_replace('{secondbar_class}', $this->char_data['secondbar']['class'], $html);
		$html = str_replace('{secondbar}', $this->char_data['secondbar']['value'], $html);
		$html = str_replace('{date}', date($this->date_format, $this->now), $html);
		$html = str_replace('{time}', date($this->time_format, $this->now), $html);
		
		// wildcards we had to write functions for
		$html = str_replace('{talents}', $this->generateTalentsHTML(), $html);
		$html = str_replace('{prof}', $this->generateProfessionsHTML(), $html);
		$html = str_replace('{misc}', $this->generateMiscHTML(), $html);
		$html = str_replace('{stats}', $this->generateStatsHTML(), $html);
		return $html;
	}
	
	/**
	 * Generates Misc Section of Tooltip
	 * @access public
	 * @return string
	 */
	private function generateMiscHTML()
	{
		$html = '';
		
		// achievements
		$html .= '
		<tr>
			<td class="armory_tt_misc_name">' . $this->language->words['achievements'] . ':</td>
			<td class="armory_tt_misc_value">&nbsp; ' . $this->char_data['achieve']['earned'] . '/' . $this->char_data['achieve']['total'] . '</td>
		</tr>
		<tr>
			<td class="armory_tt_misc_name">' . $this->language->words['achievement_pts'] . ':</td>
			<td class="armory_tt_misc_value">&nbsp; ' . $this->char_data['achieve']['points'] . '/' . $this->char_data['achieve']['totalpoints'] . '</td>
		</tr>
		<tr>
			<td class="armory_tt_misc_name">' . $this->language->words['lifetime_hk'] . ':</td>
			<td class="armory_tt_misc_value">&nbsp; ' . (string)$this->ctab->pvp->lifetimehonorablekills['value'] . '</td>
		</tr>
';
		if ($this->item_level == true)
		{
			$html .= '
		<tr>	
			<td class="armory_tt_misc_name">' . $this->language->words['avg_ilevel'] . ':</td>
			<td class="armory_tt_misc_value">&nbsp; ' . $this->char_data['itemlevel'] . '</td>
		</tr>
	';	
		}
		return $html;
	}
	
	/**
	 * Generate Professions Section of Tooltip
	 * @access public
	 * @return string
	 */
	private function generateProfessionsHTML()
	{
		$html = '';
		
		foreach ($this->char_data['prof'] as $p)
		{
			$html .= '
		<tr>
			<td class="armory_tt_profession_name">
				<img src="' . $p['icon_url'] . '">
				' . $p['name'] . '
			</td>
		   	<td class="armory_tt_profession_skill">
				&nbsp; ' . $p['value'] . '/' . $p['max'] . '
		   	</td>
		</tr>		
';
		}
		
		return $html;
	}
	
	/**
	 * Generate Stats Section of Tooltip
	 * @access public
	 * @return string
	 */
	private function generateStatsHTML()
	{
		$html = '';
		
		foreach ($this->char_data['stats'] as $stat => $v)
		{
			$html .= '
                 <tr>
                   <td class="armory_tt_stat_' . $v['class'] . '">' . $v['field'] . ':</td>
                   <td class="armory_tt_stat_value">&nbsp; ' . $v['value'] . '</td>
                 </tr>';	
		}
		
		return $html;
	}
	
	/**
	 * Generate Talents Section of Tooltips
	 * @access public
	 * @return string
	 */
	private function generateTalentsHTML()
	{
		$html = '';
		
		foreach ($this->char_data['talents'] as $talent)
		{
			$strong = ($talent['active'] == true) ? '<strong>' : '';
			$slash_strong = ($talent['active'] == true) ? '</strong>' : '';
			$html .= '
<nobr>
<img src="' . $talent['icon_url'] . '">
<span class="armory_tt_talent_trees">' . $strong . $talent['tree'][1] . '/' . $talent['tree'][2] . '/' . $talent['tree'][3] . $slash_strong . '</span>
</nobr>';	
		}
		
		return $html;
	}

    /**
     * Generate Item Level Section of Tooltip
     * Rewrite by Subxero <http://support.wowhead-tooltips.com/members/87-Subxero>
     * @access public
     * @return string
     */
    private function generateItemLevel()
    {
        $level = 0; $items = 0;
        foreach($this->ctab->items->item as $item)
        {
            // skip without itemlevel and shirts (slot 3) and tabards (slot 18)
            if (((string)$item['level'] != '') && ($item['slot'] != 3) && ($item['slot'] != 18))
            {
                // add to total item level to get the average (mean) later
                $level += (int)$item['level'];
                $items++;
                //}
            }
        }
        
        if ($items != 0)
            return bcdiv($level, $items, 0);
        else
            return '0';
    }  
	
	/**
	 * Generate Item Level Section of Tooltip
	 * @access public
	 * @return string
	private function generateItemLevel()
	{
		$level = 0; $items = 0;
		foreach($this->ctab->items->item as $item)
		{
			// we'll use wowhead to get the item level
			$xml_data = $this->getXML('http://www.wowhead.com/item=' . (string)$item['id'] . '&xml');
			$xml = simplexml_load_string($xml_data, 'SimpleXMLElement');
			if ((string)$xml->item->level != '')
			{
				// add to total item level to get the average (mean) later
				$level += (int)$xml->item->level;
				$items++;
			}
			unset($xml);
		}
		
		if ($items != 0)
			return bcdiv($level, $items, 0);
		else
			return '0';
	}
	*/
	
	/**
	 * Generate Achievements Section of Tooltip
	 * @access public
	 * @return array
	 */
	private function generateAchievements()
	{
		return array(
			'earned'		=>	(string)$this->cinfo->summary->c['earned'],
			'total'			=>	(string)$this->cinfo->summary->c['total'],
			'points'		=>	(string)$this->cinfo->summary->c['points'],
			'totalpoints'	=>	(string)$this->cinfo->summary->c['totalPoints']
		);	
	}
	
	/**
	 * Finalize the Character for Display
	 * @access public
	 * @return null
	 */
	private function finalizeChar()
	{
		// get the required stats for the character's class
		require(dirname(__FILE__) . '/class_conf/' . str_replace(' ', '', strtolower((string)$this->char['class'])) . '.php');
		
		foreach ($this->stats_conf as $stat => $value)
		{
			if ($value)
				$this->char_data['stats'][$stat] = $this->stats[$stat];	
		}
		$this->char_data['health'] = $this->stats['health'];
		$this->char_data['secondbar'] = $this->stats['secondbar'];
		// class specific rage/energy for warriors and rogues
		if ((string)$this->char['class'] == 'warrior')
			$this->char_data['secondbar']['class'] = 'power_rage';
		elseif ((string)$this->char['class'] == 'rogue')
			$this->char_data['secondbar']['class'] = 'power_energy';

		// can't forget the characters name, lol
		$this->char_data['name'] = (string)$this->char['name'];
			
		// add the guild name
		$this->char_data['guild'] = ((string)$this->char['guildName'] == '') ? '&nbsp;' : '&lt;' . (string)$this->char['guildName'] . '&gt;';
		
		// generate guild rank
		if ($this->show_rank == true && $this->char_data['guild'] != '&nbsp;')
			$this->char_data['rank'] = $this->generateGuildRank();
		else
			$this->char_data['rank'] = '';
		
		// add prefix and suffix
		$this->char_data['prefix'] = (string)$this->char['prefix'];
		$this->char_data['suffix'] = (string)$this->char['suffix'];
		
		// level
		$this->char_data['level'] = (string)$this->char['level'];
		
		// class
		$this->char_data['class'] = (string)$this->char['class'];
		
		// race
		$this->char_data['race'] = (string)$this->char['race'];
		
		// gender, race, and class id
		$this->char_data['genderid'] = (string)$this->char['genderId'];
		$this->char_data['raceid'] = (string)$this->char['raceId'];
		$this->char_data['classid'] = (string)$this->char['classId'];
	}
	
	/**
	 * Generates Guild Rank
	 * @access public
	 * @return string
	 */
	private function generateGuildRank()
	{
		$guild_data = $this->getXML($this->guildURL());
		if (!$guild_data || !$gxml = @simplexml_load_string($guild_data, 'SimpleXMLElement'))
			return '';
		
		// now we need to find the info for the person in question
		// depending on the size of the guild this could add a bit of execution time
		$found = false;
		foreach ($gxml->guildInfo->guild->members->character as $member)
		{
			if (strtolower($member['name']) == strtolower($this->char_data['name']))
			{
				$found = true;
				break;
			}
		}
		
		if (!$found)
			return '';
		else
		{
			// we now have the character info, so now we need to get the rank title, according to the script's config
			$which = 'armory_rank_' . (string)$member['rank'];
			return $this->config->$which;	
		}
	}
	
	/**
	 * Enable Stats for a Character
	 * @access public
	 * @param array $stats
	 * @return null
	 */
	private function enable_stats($stats)
	{
		foreach ($stats as $stat) {
			$this->stats_conf[$stat] = true;
		}
	}
	
	/**
	 * Generate Avatar Based on Character Level
	 * @access public
	 * @return string
	 */
	private function generateAvatar()
	{
		// determines the avatar based on the character's level
		
		$char = $this->char;
		$avatar = $this->avatars_base_url;	// base url
		
		// add the specific directory based on level
		if ((int)$char['level'] == 80)
		{
			$avatar .= 'wow-80/';	
		} 
		elseif ((int)$char['level'] == 70)
		{
			$avatar .= 'wow-70/';	
		}
		elseif ((int)$char['level'] < 70 && (int)$char['level'] > 59)
		{
			$avatar .= 'wow/';	
		}
		else
		{
			$avatar .= 'wow-default/';
		}
		
		// add the image name
		$avatar .= (string)$char['genderId'] . '-' . (string)$char['raceId'] . '-' . (string)$char['classId'] . '.gif';
		
		return $avatar;
	}
	
	/**
	 * Generate Professions
	 * @access public
	 * @return array
	 */
	private function generateProfessions()
	{
		$ctab = $this->ctab;
		$prof = array(); $i = 0;
		
		foreach ($ctab->professions->skill as $skill)
		{
			$prof[$i] = array(
				'icon_url'	=>	$this->images_base_url . 'professions/' . (string)$skill['key'] . '-sm.gif',
				'value'		=>	(string)$skill['value'],
				'max'		=>	(string)$skill['max'],
				'name'		=>	(string)$skill['name']
			);
			$i++;
		}
		
		return $prof;
	}
	
	/**
	 * Generate Talents
	 * @access public
	 * @return array
	 */
	private function generateTalents()
	{
		$ctab = $this->ctab;
		$talents = array(); $i = 0;
		foreach ($ctab->talentSpecs->talentSpec as $spec)
		{
			// determine the main tree
			switch (max((int)$spec['treeOne'], (int)$spec['treeTwo'], (int)$spec['treeThree']))
			{
				case (int)$spec['treeOne']:
					$main_tree = 1;
					break;
				case (int)$spec['treeTwo']:
					$main_tree = 2;
					break;
				case (int)$spec['treeThree']:
					$main_tree = 3;
					break;
				default: break;	
			}
			
			if ((string)$spec['active'] == '1')
			{
				// set this spec to active
				$this->main_spec = $main_tree;	
			}
		
			
			$talents[$i] = array(
				//'main_spec'	=>	$this->talent_trees[strtolower($this->char['class'])][$main_tree - 1],
				'icon_url'	=>	$this->images_base_url . 'class/' . $this->char['classId'] . '/talents/' . $main_tree . '.gif',
				'prim'		=>	(string)$spec['prim'],
				'active'	=>	(int)$spec['active'],
				'tree'		=>	array(
					'main'	=>	$main_tree,
					'1'		=>	(string)$spec['treeOne'],
					'2'		=>	(string)$spec['treeTwo'],
					'3'		=>	(string)$spec['treeThree']
				)
			);
			$i++;
		}
		
		return $talents;
	}
	
	/**
	 * Generate stats
	 * @access public
	 * @return array
	 */
	private function generateStats()
	{
		$ctab = $this->ctab;
		$stats = array();
		
		// base stats first
		$stamina = (int)$ctab->baseStats->stamina['effective'] . " (" . (int)$ctab->baseStats->stamina['base'] . " + " . ((int)$ctab->baseStats->stamina['effective'] - (int)$ctab->baseStats->stamina['base']) . ")";
		$intellect = (int)$ctab->baseStats->intellect['effective'] . " (" . (int)$ctab->baseStats->intellect['base'] . " + " . ((int)$ctab->baseStats->intellect['effective'] - (int)$ctab->baseStats->intellect['base']) . ")";
		$strength = (int)$ctab->baseStats->strength['effective'] . " (" . (int)$ctab->baseStats->strength['base'] . " + " . ((int)$ctab->baseStats->strength['effective'] - (int)$ctab->baseStats->strength['base']) . ")";
		$agility = (int)$ctab->baseStats->agility['effective'] . " (" . (int)$ctab->baseStats->agility['base'] . " + " . ((int)$ctab->baseStats->agility['effective'] - (int)$ctab->baseStats->agility['base']) . ")";
		$spirit = (int)$ctab->baseStats->spirit['effective'] . " (" . (int)$ctab->baseStats->spirit['base'] . " + " . ((int)$ctab->baseStats->spirit['effective'] - (int)$ctab->baseStats->spirit['base']) . ")";
		$armor = (int)$ctab->baseStats->armor['effective'] . " (" . (int)$ctab->baseStats->armor['base'] . " + " . ((int)$ctab->baseStats->armor['effective'] - (int)$ctab->baseStats->armor['base']) . ")";
		$this->addtostats($stats, 'stamina', $this->language->words['stamina'], $stamina, 'primary');
		$this->addtostats($stats, 'intellect', $this->language->words['intellect'], $intellect, 'primary');
		$this->addtostats($stats, 'strength', $this->language->words['strength'], $strength, 'primary');
		$this->addtostats($stats, 'agility', $this->language->words['agility'], $agility, 'primary');
		$this->addtostats($stats, 'spirit', $this->language->words['spirit'], $spirit, 'primary');
		$this->addtostats($stats, 'armor', $this->language->words['armor'], $armor, 'primary');
		
		// health and mana, power, or rage
		$this->addtostats($stats, 'health', 'Health', (string)$ctab->characterBars->health['effective'], 'health');
		$this->addtostats($stats, 'secondbar', 'Power', (string)$ctab->characterBars->secondBar['effective'], 'power_mana');
		
		// spell power and crit
		$this->addtostats($stats, 'arcane_power', $this->language->words['arcane_dmg'], (string)$ctab->spell->bonusDamage->arcane['value'], 'arcane_spell');
		$this->addtostats($stats, 'arcane_crit', $this->language->words['arcane_crit'], (string)$ctab->spell->critChance->arcane['percent'], 'arcane_spell');
		$this->addtostats($stats, 'fire_power', $this->language->words['fire_dmg'], (string)$ctab->spell->bonusDamage->fire['value'], 'fire_spell');
		$this->addtostats($stats, 'fire_crit', $this->language->words['fire_crit'], (string)$ctab->spell->critChance->fire['percent'], 'fire_spell');
		$this->addtostats($stats, 'frost_power', $this->language->words['frost_dmg'], (string)$ctab->spell->bonusDamage->frost['value'], 'frost_spell');
		$this->addtostats($stats, 'frost_crit', $this->language->words['frost_crit'], (string)$ctab->spell->critChance->frost['percent'], 'frost_spell');
		$this->addtostats($stats, 'holy_power', $this->language->words['holy_dmg'], (string)$ctab->spell->bonusDamage->holy['value'], 'holy_spell');
		$this->addtostats($stats, 'holy_crit', $this->language->words['holy_crit'], (string)$ctab->spell->critChance->holy['percent'], 'holy_spell');
		$this->addtostats($stats, 'nature_power', $this->language->words['nature_dmg'], (string)$ctab->spell->bonusDamage->nature['value'], 'nature_spell');
		$this->addtostats($stats, 'nature_crit', $this->language->words['nature_crit'], (string)$ctab->spell->critChance->nature['percent'], 'nature_spell');
		$this->addtostats($stats, 'shadow_power', $this->language->words['shadow_dmg'], (string)$ctab->spell->bonusDamage->shadow['value'], 'shadow_spell');
		$this->addtostats($stats, 'shadow_crit', $this->language->words['shadow_crit'], (string)$ctab->spell->critChance->shadow['percent'], 'shadow_spell');
	
		// spell hit, hase, and pen
		$this->addtostats($stats, 'spell_hit', $this->language->words['spell_hit'], (string)$ctab->spell->hitRating['increasedHitPercent'] . '%', 'generic');
		$this->addtostats($stats, 'haste_rating', $this->language->words['haste'], (string)$ctab->spell->hasteRating['hasteRating'] . ' / ' . (string)$ctab->spell->hasteRating['hastePercent'] . '%', 'generic');
		$this->addtostats($stats, 'penetration', $this->language->words['spell_pen'], (string)$ctab->spell->hitRating['penetration'], 'generic');
		
		// main hand
		$this->addtostats($stats, 'melee_main_dmg', $this->language->words['melee_main_dmg'], (string)$ctab->melee->mainHandDamage['max'], 'melee_main_hand');
		$this->addtostats($stats, 'melee_main_speed', $this->language->words['melee_main_speed'], (string)$ctab->melee->mainHandDamage['speed'], 'melee_main_hand');
		$this->addtostats($stats, 'melee_main_dps', $this->language->words['melee_main_dps'], (string)$ctab->melee->mainHandDamage['dps'], 'melee_main_hand');
		
		// off hand
		$this->addtostats($stats, 'melee_off_dmg', $this->language->words['melee_off_dmg'], (string)$ctab->melee->offHandDamage['max'], 'melee_off_hand');
		$this->addtostats($stats, 'melee_off_speed', $this->language->words['melee_off_speed'], (string)$ctab->melee->offHandDamage['speed'], 'melee_off_hand');
		$this->addtostats($stats, 'melee_off_dps', $this->language->words['melee_off_dps'], (string)$ctab->melee->offHandDamage['dps'], 'melee_off_hand');
		
		// melee stats
		$this->addtostats($stats, 'melee_power', $this->language->words['melee_power'], (string)$ctab->melee->power['effective'], 'generic');
		$this->addtostats($stats, 'melee_hit', $this->language->words['melee_hit'], (string)$ctab->melee->hitRating['increasedHitPercent'] . '%', 'generic');
		$this->addtostats($stats, 'melee_crit', $this->language->words['melee_crit'], (string)$ctab->melee->critChance['percent'] . '%', 'generic');
		$this->addtostats($stats, 'melee_expertise', $this->language->words['melee_expertise'], (string)$ctab->melee->expertise['value'], 'generic');
		
		// defensive stats
		$this->addtostats($stats, 'defense', $this->language->words['defense'], (float)$ctab->defenses->defense['value'] + (float)$ctab->defenses->defense['plusDefense'], 'defensive');
		$this->addtostats($stats, 'dodge', $this->language->words['dodge_chance'], (string)$ctab->defenses->dodge['percent'] . '%', 'defensive');
		$this->addtostats($stats, 'block', $this->language->words['block_chance'], (string)$ctab->defenses->block['percent'] . '%', 'defensive');
		$this->addtostats($stats, 'parry', $this->language->words['parry_chance'], (string)$ctab->defenses->parry['percent'] . '%', 'defensive');
		$this->addtostats($stats, 'resilience', $this->language->words['resilience'], (string)$ctab->defenses->resilience['value'], 'defensive');
		
		// healing and associated stats
		$this->addtostats($stats, 'healing', $this->language->words['healing'], (string)$ctab->spell->bonusHealing['value'], 'healing');
		$this->addtostats($stats, 'mana_regen', $this->language->words['mana_regen'], (string)$ctab->spell->manaRegen['notCasting'], 'mana_regen');
		$this->addtostats($stats, 'mana_regen_cast', $this->language->words['mana_regen_cast'], (string)$ctab->spell->manaRegen['casting'], 'mana_regen');
		
		// ranged stats
		$this->addtostats($stats, 'ranged_dmg', $this->language->words['ranged_dmg'], (string)$ctab->ranged->damage['min'] . '-' . (string)$ctab->ranged->damage['max'], 'ranged');
		$this->addtostats($stats, 'ranged_dps', $this->language->words['ranged_dps'], (string)$ctab->ranged->damage['dps'], 'ranged');
		$this->addtostats($stats, 'ranged_crit', $this->language->words['ranged_crit'], (string)$ctab->ranged->critChance['percent'] . '%', 'ranged');
		$this->addtostats($stats, 'ranged_hit', $this->language->words['ranged_hit'], (string)$ctab->ranged->hitRating['increasedHitPercent'] . '%', 'ranged'); 
		$this->addtostats($stats, 'ranged_speed', $this->language->words['ranged_speed'], (string)$ctab->ranged->speed['value'], 'ranged');
		$this->addtostats($stats, 'ranged_power', $this->language->words['ranged_power'], (string)$ctab->ranged->power['effective'], 'ranged'); 
		
		return $stats;
	}
	
	/**
	 * Add to Stats Array
	 * @access public
	 * @param array $stats
	 * @param string $index
	 * @param string $field
	 * @param string $value
	 * @param string $class
	 * @return null
	 */
	private function addtostats(&$stats, $index, $field, $value, $class)
	{
		$stats[$index] = array(
			'field'	=>	$field,
			'value'	=>	$value,
			'class'	=>	$class
		);
	}
	
	/**
	 * Gets Class and Race Icons
	 * @access public
	 * @param int $raceid
	 * @param int $genderid
	 * @param int $classid
	 * @return string
	 */
	private function getIcons($raceid, $genderid, $classid)
	{
		$icons = '';
		// build the icon html
		if ($this->icons == true)
		{
			if ($this->race_icon)
				$icons .= '<img src="' . $this->icon_url . 'images/race/' . $raceid . '-' . $genderid . '.gif" alt="' . ucwords($this->gender_ids[$genderid]) . ' ' . ucwords($this->race_ids[$raceid]) . '" title="' . ucwords($this->gender_ids[$genderid]) . ' ' . ucwords($this->race_ids[$raceid]) . '" />&nbsp;';

			if ($this->class_icon)
				$icons .= '<img src="' . $this->icon_url . 'images/class/' . $classid . '.gif" title="' . ucwords($this->id_to_name[$classid]) . '" alt="' . ucwords($this->id_to_name[$classid]) . '" />&nbsp;';
		}
		return $icons;
	}
	
	/**
	 * Get XML From Armory
	 * @access public
	 * @param string $url
	 * @param string $language [optional]
	 * @return string
	 */
	private function getXML($url, $language = NULL) {
		$useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/2.0.0.2";
		if (array_search ('curl', get_loaded_extensions ()) !== false) {
			$ch = curl_init();
			
			$cookie_file = dirname(__FILE__) . '/cookiejar.txt';
			curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie_file);
			curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie_file);
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt ($ch, CURLOPT_USERAGENT, $useragent);
			curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Accept-language: '.$language));
			curl_setopt ($ch, CURLOPT_HEADER, 0);

			$f = curl_exec($ch);
			curl_close($ch);
			//var_dump($f); die;
			return $f;
		} elseif (ini_get ('allow_url_fopen')) {
			if ($language) {
				$user_agent = $useragent . "\r\nAccept-Language: " . $language;
			} else {
				$user_agent = $useragent;
			}

			$opts = array (
				'http' => array (
					'method' => "GET",
					'header'=> "User-Agent: " . $user_agent
				)
			);

			$context = stream_context_create ($opts);

			$f = '';
			$handle = fopen ($url, 'r', false, $context);
			while (!feof ($handle)) {
				$f .= fgets ($handle);
			}
			fclose ($handle);
			return $f;
		}

		trigger_error ($this->language->words['curl_fail'], E_USER_ERROR);
		return false;
	}
	
	/**
	 * Get Character URL
	 * @access public
	 * @param string $name
	 * @return string
	 */
	private function characterURL($name)
	{
		return $this->armoryURL() . 'character-sheet.xml?r=' . str_replace(' ', '+', $this->realm) . '&cn=' . $name;
	}

	/**
	 * Get Armory URL Based on Region
	 * @access public
	 * @return string
	 */
	private function armoryURL()
	{
		$prefix = ($this->region == 'us') ? 'www' : $this->region;
		return "http://{$prefix}.wowarmory.com/";
	}
	
	/**
	 * Generates Guild URL
	 * @access public
	 * @return string
	 */
	private function guildURL()
	{
		$guild = str_replace(' ', '+', $this->char_data['guild']);
		$guild = str_replace(array('&gt;', '&lt;'), '', $guild);
		$prefix = ($this->region == 'us') ? 'www' : $this->region;
		return "http://{$prefix}.wowarmory.com/guild-info.xml?r=" . str_replace(' ', '+', $this->realm) . "&gn={$guild}&cn={$this->char_data['name']}";
	}
}
?>
