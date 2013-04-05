<?php
/**
*
* @package Wowhead Tooltips
* @version wowhead_spell.php 4.0
* @copyright (c) 2010 Adam Koch <http://wowhead-tooltips.com>
* @license http://www.wowhead-tooltips.com/downloads/terms-of-use/
*
*/

class wowhead_spell extends wowhead
{
	public $lang;
	public $language;
	public $patterns;
	public $config;

	/**
	* Constructor
	* @access public
	**/
	public function __construct()
	{
		$this->config = new wowhead_config();
		$this->config->loadConfig();
		$this->patterns = new wowhead_patterns();
		$this->language = new wowhead_language();
	}
	
	public function close()
	{
		unset($this->lang, $this->language, $this->patterns, $this->config);	
	}

	/**
	* Parses information
	* @access public
	**/
	public function parse($name, $args = array())
	{
		if (trim($name) == '')
			return false;

		$this->lang = (!array_key_exists('lang', $args)) ? $this->config->lang : $args['lang'];
		$this->language->loadLanguage($this->lang);
		$rank = (!array_key_exists('rank', $args)) ? '' : (int)$args['rank'];
		$cache = new wowhead_cache();

		if (!$result = $cache->getSpell($name, $this->lang, $rank))
		{
			$result = (is_numeric($name)) ? $this->getSpellByID($name) : $this->getSpellByName($name, $rank);
			if (!$result)
			{
				$cache->close();
				return $this->notFound($this->language->words['spell'], $name);
			}
			else
			{
				$cache->saveSpell($result);
				$cache->close();
				return $this->generateHTML($result, 'spell', '', $rank);
			}
		}
		else
		{
			$cache->close();
			return $this->generateHTML($result, 'spell', '', $rank);
		}
	}

	/**
	* Queries Wowhead for Spell by Name
	* @access private
	**/
	private function getSpellByName($name, $rank = '')
	{
		if (trim($name) == '')
			return false;

		$data = $this->readURL($name, 'spell', false);
		
		if (!$data)
			return false;

		// for searches with only one result (aka redirect header)
		if (preg_match('#Location: \/spell=([0-9]{1,10})#s', $data, $match))
		{
			return array(
				'name'			=>	ucwords(strtolower($name)),
				'search_name'	=>	$name,
				'itemid'		=>	$match[1],
				'rank'			=>	0,
				'lang'			=>	$this->lang
			);
		}
		
		// get the line we need to pull the data
		$line = $this->spellLine($data, $name);
		//var_dump($line); die;
		if (!$line)
			return false;
		else
		{
			// decode the JSON result
			if (!$json = json_decode($line, true))
				return false;
				
			// loop through the resulting array and pull out the ones that match the name
			$json_array = array();
			foreach ($json as $spell)
			{
				$spell['name'] = substr($spell['name'], 1);
				if (stripslashes(strtolower($spell['name'])) == stripslashes(strtolower($name)))
					$json_array[] = $spell;	// add it to the array
			}
			
			if (sizeof($json_array) == 0)
				return false;
			
			// which one we grab depends on the $rank variable
			$result = ($rank != '') ? $json_array[$rank - 1] : $json_array[sizeof($json_array) - 1];

			return array(	// finally return what we found
				'name'			=>	stripslashes($result['name']),
				'search_name'	=>	$name,
				'itemid'		=>	$result['id'],
				'rank'			=>	($rank != '') ? $rank : 0,
				'lang'			=>	$this->lang
			);
		}
	}

	/**
	* Queries Wowhead for Spell info by ID
	* @access private
	**/
	private function getSpellByID($id)
	{
		if (!is_numeric($id))
			return false;

		$data = $this->readURL($id, 'spell', false);

		if ($data == '$WowheadPower.registerSpell')
		{
			return false;
		}
		else
		{
			switch ($this->lang)
			{
				case 'en':
					$str = 'enus';
					break;
				case 'de':
					$str = 'dede';
					break;
				case 'fr':
					$str = 'frfr';
					break;
				case 'es':
					$str = 'eses';
					break;
				case 'ru':
				default:
					$str = 'ruru';
					break;
			}
			if (preg_match('#name_' . $str . ': \'(.+?)\',#s', $data, $match))
			{
				if (!preg_match('#icon: \'(.+?)\',#s', $data, $icon_match))
					return false;
			
				// fixes an obscure bug found by Destroyer via http://support.wowhead-tooltips.com/threads/187-scr-ipt-script
				return array(
					'name'			=>	(strpos($match[1], "scr'+'ipt") !== false) ? stripslashes(str_replace("'+'", '', $match[1])) : stripslashes($match[1]),
					'itemid'		=>	$id,
					'search_name'	=>	$id,
					'icon'			=>	$this->getIcon($id, $data),
					'rank'			=>	0,
					'lang'			=>	$this->lang
				);
			}
			else
			{
				return false;
			}
		}
	}
	
	private function getIcon($id, $data = '')
	{
		if (trim($id) == '')
			return false;
		if (trim($data) == '')
			$data = $this->readURL($id, 'spell', false);
		
		if (!$data)
			return false;
		elseif (!preg_match('#icon: \'(.+?)\',#s', $data, $match))
			return false;
		else
			return 'http://static.wowhead.com/images/wow/icons/tiny/' . strtolower($match[1]) . '.gif';
	}
	
	private function spellLine($data, $name)
	{
		$found = false;		// assume failure
		$parts = explode(chr(10), $data);
		$name = strtolower($name);
		foreach ($parts as $line)
		{
			if (strpos($line, "new Listview({template: 'spell', id: 'abilities',") !== false && strpos(strtolower($line), '@' . $name) !== false)
			{
				$found = true;
				break;
			}
			elseif (strpos($line, "new Listview({template: 'spell', id: 'talents',") !== false && strpos(strtolower($line), '@' . $name) !== false)
			{
				$found = true;
				break;
			}
			elseif (strpos($line, "new Listview({template: 'spell', id: 'professions',") !== false && strpos(strtolower($line), '@' . $name) !== false)
			{
				$found = true;
				break;
			}
			elseif (strpos($line, "new Listview({template: 'spell', id: 'uncategorized-spells',") !== false && strpos(strtolower($line), '@' . $name) !== false)
			{
				$found = true;
				break;
			}
			elseif (strpos($line, "new Listview({template: 'spell', id: 'companions',") !== false && strpos(strtolower($line), '@' . $name) !== false)
			{
				$found = true;
				break;	
			}
		}
		
		if ($found && sizeof($line) > 0)
		{
			// clean the line up to make it valid JSON
			$line = substr($line, strpos($line, 'data: [{') + 6);
			$line = str_replace('});', '', $line);
			return $line;				
		}
		else
			return false;
	}
}
?>