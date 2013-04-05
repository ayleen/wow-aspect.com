<?php
/**
*
* @package Wowhead Tooltips
* @version wowhead_guild.php 4.0
* @copyright (c) 2010 Adam Koch <http://wowhead-tooltips.com>
* @license http://www.wowhead-tooltips.com/downloads/terms-of-use/
*
*/

class wowhead_guild extends wowhead
{
	/*
	 * Variables
	 */
	public $patterns;
	public $lang = 'en';	// dummy to prevent any errors
	public $language;
	public $config;
	private $realm;
	private $region;
	private $guild_cache;
	private $guild = array();
	private $ginfo = array();
	private $now;
	private $time_format;
	private $date_format;
	
	private $faction = array (
		0 => 'Alliance',
		1 => 'Horde',
	);	
	private $class_ids = array (
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

	private $gender_ids = array(
	    'male',
	    'female'
	);
	
	public function __construct()
	{
		$this->config = new wowhead_config();
		$this->config->loadConfig();
		$this->patterns = new wowhead_patterns();
		$this->realm = $this->config->armory_realm;
		$this->region = $this->config->armory_region;
		$this->guild_cache = $this->config->armory_guild_cache;
		$this->date_format = $this->config->armory_date_format;
		$this->time_format = $this->config->armory_time_format;
		$this->lang = $this->config->lang;
		$this->language = new wowhead_language();
	}
	
	public function close()
	{
		unset($this->lang, $this->language, $this->patterns, $this->config);	
	}
	
	public function parse($name, $args = array())
	{
		if (trim($name) == '')
			return false;

		// see if they specified a realm/region
		if (array_key_exists('loc', $args))
		{
			$aLoc = explode(',', $args['loc']);
			$this->region = $aLoc[0];
			$this->realm = $aLoc[1];
		}
		
		// set the language
		if (array_key_exists('lang', $args))
			$this->lang = $args['lang'];
		
		// load the language pack
		$this->language->loadLanguage($this->lang);
		
		$cache = new wowhead_cache();
		$this->now = mktime();
		if (!$result = $cache->getGuild($cache->generateKey($name, $this->realm, $this->region), $this->guild_cache))
		{
			if (WOWHEAD_DEBUG == true)
				print $this->guildURL($name);
			
			// query the armory for guild info
			$xml_data = $this->getXML($this->guildURL($name));
			if (!$xml = @simplexml_load_string($xml_data, 'SimpleXMLElement'))
			{
				// invalid XML
				$cache->close();
				return $this->generateError($this->language->words['armory_blocked'], 'guild');	
			}
			
			if (array_key_exists('errorhtml', $xml) || !array_key_exists('guildInfo', $xml))
			{
				// most likely guild not found
				$cache->close();
				return $this->generateError($this->language->words['guild_not_found'], 'guild');	
			}
			$this->ginfo = $xml->guildInfo;
			
			// get general guild info
			$this->guild = $this->generateInfo();
			$this->guild['icon'] = $this->armoryURL() . '_images/icons/faction/icon-' . $this->guild['faction_id'] . '.gif';
			
			// generate the guild stats
			$this->guild['stats'] = $this->generateStats();
			
			// now build the tooltip
			$tooltip = $this->generateTooltip();
			
			// save to the cache
			$cache->saveGuild(array(
				'uniquekey'		=>	$cache->generateKey($name, $this->realm, $this->region),
				'name'			=>	$this->guild['name'],
				'realm'			=>	$this->realm,
				'region'		=>	$this->region,
				'tooltip'		=>	$tooltip
			));
			

			$cache->close();

			return $this->generateHTML(array(
				'name'		=>	ucwords($name),
				'region'	=>	$this->region,
				'realm'		=>	$this->realm,
				'link'		=>	$this->guildURL($name),
			), 'guild');
		}
		else
		{
			$cache->close();

			return $this->generateHTML(array(
				'name'		=>	ucwords($name),
				'region'	=>	$this->region,
				'realm'		=>	$this->realm,
				'link'		=>	$this->guildURL($name),
			), 'guild');
		}
	}
	
	private function generateTooltip()
	{
		$html = $this->patterns->pattern('armory_guild');
		
		$html = str_replace('{icon}', $this->guild['icon'], $html);
		$html = str_replace('{name}', $this->guild['name'], $html);
		$html = str_replace('{realm}', $this->guild['realm'], $html);
		$html = str_replace('{battlegroup}', $this->guild['battlegroup'], $html);
		$html = str_replace('{count}', $this->guild['member_count'], $html);
		
		// stats
		$html = str_replace('{gender_stats}', $this->generateGenderHTML(), $html);
		$html = str_replace('{race_stats}', $this->generateRaceHTML(), $html);
		$html = str_replace('{class_stats}', $this->generateClassHTML(), $html);
		
		// date/time
		$html = str_replace('{date}', date($this->date_format, $this->now), $html);
		$html = str_replace('{time}', date($this->time_format, $this->now), $html);
		
		return $html;
	}
	
	private function generateGenderHTML()
	{
		$html = '';
		
		foreach ($this->guild['stats']['gender'] as $g => $v)
		{
			if ((int)$v != 0 && (int)$this->guild['member_count'] != 0)
			{
				$html .= '
             <tr>
               <td class="armory_tt_stat_primary">
                  ' . ucwords($g) . ':
               </td>
               <td class="armory_tt_stat_value">
                    &nbsp; ' . $v . '
					&nbsp; (' . (string)$this->percent($v, $this->guild['member_count']) . '%)
               </td>
             </tr>	
';
			}
		}
		
		return $html;
	}
	
	private function generateRaceHTML()
	{
		$html = '';
		
		foreach ($this->guild['stats']['race'] as $g => $v)
		{
			if ((int)$v != 0 && (int)$this->guild['member_count'] != 0)
			{
				$html .= '
             <tr>
               <td class="armory_tt_stat_primary">
                  ' . ucwords($g) . ':
               </td>
               <td class="armory_tt_stat_value">
                    &nbsp; ' . $v . '
					&nbsp; (' . (string)$this->percent($v, $this->guild['member_count']) . '%)
               </td>
             </tr>	
';
			}
		}
		
		return $html;		
	}
	
	private function generateClassHTML()
	{
		$html = '';
		
		foreach ($this->guild['stats']['class'] as $g => $v)
		{
			if ((int)$v != 0 && (int)$this->guild['member_count'] != 0)
			{
				$cname = ($g == 'deathknight') ? 'Death Knight' : ucwords($g);
				$html .= '
             <tr>
               <td class="armory_tt_stat_primary">
                  ' . $cname . ':
               </td>
               <td class="armory_tt_stat_value">
                    &nbsp; ' . $v . '
					&nbsp; (' . (string)$this->percent($v, $this->guild['member_count']) . '%)
               </td>
             </tr>	
';
			}
		}
		
		return $html;		
	}
	
	private function generateStats()
	{
		$gender = array();
		$race = array();
		$class = array();
		$members = $this->ginfo->guild->members;
		
		// fill each array
		foreach ($this->class_ids as $c)
			$class[$c] = 0;	
		foreach ($this->race_ids as $r)
			$race[$r] = 0;
		foreach ($this->gender_ids as $g)
			$gender[$g] = 0;

		foreach ($members->character as $member)
		{
			if (array_key_exists((int)$member['genderId'], $this->gender_ids))
				$gender[$this->gender_ids[(int)$member['genderId']]]++;
			if (array_key_exists((int)$member['raceId'], $this->race_ids))
				$race[$this->race_ids[(int)$member['raceId']]]++;
			if (array_key_exists((int)$member['classId'], $this->class_ids))
				$class[$this->class_ids[(int)$member['classId']]]++;
		}
		
		return array(
			'gender'	=>	$gender,
			'race'		=>	$race,
			'class'		=>	$class
		);
	}
	
	private function generateInfo()
	{
		return array(
			'faction'		=>	$this->faction[(int)$this->ginfo->guildHeader['faction']],
			'faction_id'	=>	(string)$this->ginfo->guildHeader['faction'],
			'name'			=>	(string)$this->ginfo->guildHeader['name'],
			'member_count'	=>	(string)$this->ginfo->guildHeader['count'],
			'battlegroup'	=>	(string)$this->ginfo->guildHeader['battleGroup'],
			'realm'			=>	(string)$this->ginfo->guildHeader['realm']
		);
	}
	
	/**
	* Generate the full armory url
	* @access private
	**/
	private function guildURL($name)
	{
		return $this->armoryURL() . 'guild-info.xml?r=' . str_replace(' ', '+', $this->realm) . '&gn=' . $name;
	}

	/**
	* Generates the domain of the armory
	* @access private
	**/
	private function armoryURL()
	{
		$prefix = ($this->region == 'us') ? 'www' : $this->region;
		return "http://{$prefix}.wowarmory.com/";
	}

	private function percent($num_amount, $num_total) {
	    $count1 = $num_amount / $num_total;
	    $count2 = $count1 * 100;
	    $count = number_format($count2, 0);
	    return $count;
	}
	
	// From phpArmory.class.php
	private function getXML($url, $language = null) {
		$useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/2.0.0.2";
		
		// replace spaces with + sign
		$url = str_replace(' ', '+', $url);
		
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
			//curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Accept-language: '.$language));
			curl_setopt ($ch, CURLOPT_HEADER, 0);

			$f = curl_exec($ch);

			curl_close($ch);

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
}
?>