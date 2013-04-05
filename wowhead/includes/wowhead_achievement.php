<?php
/**
*
* @package Wowhead Tooltips
* @version wowhead_achievement.php 4.0
* @copyright (c) 2010 Adam Koch <http://wowhead-tooltips.com>
* @license http://www.wowhead-tooltips.com/downloads/terms-of-use/
*
*/

/**
 * Wowhead Achievement Module
 * @package Wowhead Tooltips
 * @extends wowhead
 */
class wowhead_achievement extends wowhead
{
	/**
	 * Default Language
	 * @var string $lang
	 */
	public $lang;
	
	/**
	 * Patterns Module
	 * @var object $patterns
	 */
	public $patterns;
	
	/**
	 * Language Pack
	 * @var object $language
	 */
	public $language;
	
	/**
	 * Config Module
	 * @var object $config
	 */
	public $config;

	/**
	 * Constructor
	 * @access public
	 * @param object $config
	 * @return null
	 */
	public function __construct()
	{
		$this->patterns = new wowhead_patterns();
		$this->language = new wowhead_language();
		$this->config = new wowhead_config();
		$this->config->loadConfig();
	}
	
	/**
	 * Destructor
	 * @access public
	 * @return null
	 */
	public function close()
	{
		unset($this->lang, $this->language, $this->patterns, $this->config);	
	}

	/**
	 * Parse the Data
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

		$this->lang = (!array_key_exists('lang', $args)) ? $this->config->lang : $args['lang'];
		$this->language->loadLanguage($this->lang);
		if (!$result = $cache->getAchievement($name, $this->lang))
		{
			$result = (is_numeric($name)) ? $this->getAchievementByID($name) : $this->getAchievementByName($name);
			if (!$result)
			{
				// not found
				$cache->close();
				return $this->notFound($this->language->words['achievement'], $name);
			}
			else
			{
				$cache->saveAchievement($result);
				$cache->close();
				return $this->generateHTML($result, 'achievement');
			}
		}
		else
		{
			$cache->close();
			return $this->generateHTML($result, 'achievement');
		}
	}

	/**
	 * Get Achievement By ID
	 * @access public
	 * @param int $id
	 * @return array
	 */
	private function getAchievementByID($id)
	{
		if (!is_numeric($id))
			return false;

		$data = $this->readURL($id, 'achievement', false);

		if ($data == '$WowheadPower.registerAchievement(1337, 25, {});')
		{
			return false;
		}
		else
		{
			if (preg_match('#<b class="q">(.+?)</b>#s', $data, $match))
			{
				return array(
						'name'			=>	stripslashes($match[1]),
						'itemid'		=>	$id,
						'search_name'	=>	$id,
						'lang'			=>	$this->lang

				);
			}
			else
			{
				return false;
			}
		}
	}

	/**
	 * Get Achievement By Name
	 * @access public
	 * @param string $name
	 * @return array
	 */
	private function getAchievementByName($name)
	{
		if (trim($name) == '')
			return false;

		$data = $this->readURL($name, 'achievement', false);
		if (preg_match('#Location: \/achievement=(.+?)\n#s', $data, $match))
		{
			// result returns a redirection header (aka only one result)
			// so we can get the information we need from there
			return array(
				'name'			=>	stripslashes(ucwords(strtolower($name))),
				'search_name'	=>	$name,
				'itemid'		=>	$match[1],
				'lang'			=>	$this->lang
			);
		}

		$line = $this->achievementLine($data);
		
		if (!$line)
			return false;
		else
		{
			if (!$json = json_decode($line, true))
				return false;
				
			foreach ($json as $achievements)
			{
				if (stripslashes(strtolower($achievements['name'])) == stripslashes(strtolower($name)))
				{
					return array(
						'name'			=>	stripslashes($achievements['name']),
						'search_name'	=>	$name,
						'itemid'		=>	$achievements['id'],
						'lang'			=>	$this->lang
					);	
				}
			}
			return false;
		}
	}
	
	private function achievementLine($data)
	{
		$parts = explode(chr(10), $data);
		foreach ($parts as $line)
		{
			if (strpos($line, "new Listview({template: 'achievement', id: 'achievements',") !== false)
			{
				$line = substr($line, strpos($line, 'data: [{') + 6);
				$line = str_replace('});', '', $line);
				return $line;	
			}
		}
		return false;
	}
}
?>