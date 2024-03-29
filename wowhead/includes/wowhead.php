<?php
/**
*
* @package Wowhead Tooltips
* @version wowhead.php 4.0
* @copyright (c) 2010 Adam Koch <http://wowhead-tooltips.com>
* @license http://www.wowhead-tooltips.com/downloads/terms-of-use/
*
*/

require_once(dirname(__FILE__) . '/wowhead_log.php');

/**
 * Wowhead Base Class
 * @package Wowhead Tooltips
 */
class wowhead
{
	
	/**
	 * Attempts to read URL and return content
	 * @access public
	 * @param string $url The string used to determine what to search for.
	 * @param string $type The type of object being looked for.
	 * @param bool $headers Include the headers in the returned data.
	 * @return string Returned HTML data.
	 */
	public function readURL($url, $type = 'item', $headers = true, $heroic = false)
	{
		// build the url
		switch ($type)
		{
			case 'profile':
				$built_url = 'http://profiler.wowhead.com/profile=' . $url;
				break;
			case 'npc':
				$built_url = (is_numeric($url)) ? $this->getDomain() . '/npc=' . $url . '&power' : $this->getDomain() . '/search?q=' . $this->convertString($url);
				break;
			case 'itemset':
				$built_url = (is_numeric($url)) ? $this->getDomain() . '/itemset=' . $url : $this->getDomain() . '/search?q=' . $this->convertString($url);
				break;
			case 'event':
				$built_url = $this->getDomain() . '/events';
				break;
			case 'class':
				$built_url = $this->getDomain() . '/class=' . $url;
				break;
			case 'race':
				$built_url = $this->getDomain() . '/races';
				break;
			case 'race_query':
				$built_url = $this->getDomain() . '/race=' . $url;
				break;
			case 'title':
				$built_url = $this->getDomain() . '/titles';
				break;
			case 'pet':
				$built_url = $this->getDomain() . '/pets';
				break;
			case 'pet_query':
				$built_url = $this->getDomain() . '/pet=' . $url;
				break;
			case 'enchant':
			case 'quest':
			case 'spell':
			case 'achievement':
			case 'object':
			case 'stats':
				$type = ($type == 'enchant') ? 'spell' : $type;
				if ($type == 'stats') { $type = 'statistic'; }
				if (!$power)
					$built_url = (is_numeric($url)) ? $this->getDomain() . '/' . $type . '=' . $url . '&power' : $this->getDomain() . '/search?q=' . $this->convertString($url);
				else
					$built_url = (is_numeric($url)) ? $this->getDomain() . '/' . $type . '=' . $url : $this->getDomain() . '/search?q=' . $this->convertString($url);
				break;
			case 'faction':
				$built_url = (is_numeric($url)) ? $this->getDomain() . '/faction=' . $url : $this->getDomain() . '/search?q=' . $this->convertString($url);
				break;
			case 'zone':
				$built_url = (is_numeric($url)) ? $this->getDomain() . '/zone=' . $url : $this->getDomain() . '/search?q=' . $this->convertString($url);
				break;
			case 'item':
			case 'itemico':
				$built_url = (is_numeric($url)) ? $this->getDomain() . '/item=' . $url . '&xml' : $this->getDomain() . '/search?q=' . $this->convertString($url);
				break;
			case 'craftable':
			default:
				$built_url = $this->getDomain() . '/item=' . $this->convertString($url) . '&xml';
				break;
		}

		if (WOWHEAD_DEBUG)
		{
			echo $built_url . '<br/>';
		}
	 	// Try cURL first. If that isn't available, check if we're allowed to
		// use fopen on URLs.  If that doesn't work, just die.
		if (function_exists('curl_init'))
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_HEADER, 1);
			curl_setopt($curl, CURLOPT_URL, $built_url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$html_data = curl_exec($curl);
			if (!$html_data)
			{ 
				return false; 
			}
			curl_close($curl);
		}
		elseif (ini_get('allow_url_fopen') == 1)
		{
			$html_data = @file_get_contents($built_url);
		}
		else
		{
	        // Thanks to Aki Uusitalo
			$url_array = parse_url($built_url);

			$fp = fsockopen($url_array['host'], 80, $errno, $errstr, 5);

			if (!$fp)
	        {
				return false;
			}
	        else
	        {
				$out = "GET " . $url_array['path'] . "?" . $url_array['query'] ." HTTP/1.0\r\n";
				$out .= "Host: " . $url_array['host'] . " \r\n";
				$out .= "Connection: Close\r\n\r\n";

				fwrite($fp, $out);

				$html_data = '';
				// Read the raw data from the socket in 1kb chunks
				// Hopefully, it's just HTML.

				while (!feof($fp))
	            {
					$html_data .= fgets($fp, 1024);
				}
				fclose($fp);
			}
	    }
	    if ($headers == true && strpos($html_data, 'Location:') === false)
	    	$html_data = $this->stripHeaders($html_data);

		return $html_data;
	}

	/**
	 * Strips headers from HTML data
	 * @access public
	 * @param string $data Data with headers
	 * @return string HTML data without headers
	 */
	public function stripHeaders($data)
	{
		// split the string
		$chunks = explode(chr(10), $data);

		// return the last index in the array, aka our xml
		return $chunks[sizeof($chunks) - 1];
	}

	/**

	 * Cleans HTML for passing to Wowhead
	 * @access public
	 * @param string $string
	 * @return string Cleaned HTML string
	 */
	public function cleanHTML($string)
	{
//	    if (function_exists("mb_convert_encoding"))
//	        $string = mb_convert_encoding($string, "UTF-8", "HTML-ENTITIES");
//	    else
//	    {
	       $conv_table = get_html_translation_table(HTML_ENTITIES);
	       $conv_table = array_flip($conv_table);
	       $string = strtr ($string, $conv_table);
	       $string = preg_replace('/&#(\d+);/me', "chr('\\1')", $string);
//	    }
	    return ($string);
	}
	
	/**
	 * Formats incoming string for use with the Zone module
	 * @access public
	 * @param string $in
	 * @return string
	 */
	public function wowhead_map($in)
	{
		$split = str_split($in, 3);	// split the string into 3's
		$i = 1; $str = '';
		// now loop through the array and format them into the pins format the script recognizes
		foreach ($split as $coord)
		{
			switch ($i)
			{
				case 1:
					$str .= ((float)$coord / 10) . ',';
					$i = 2;
					break;
				case 2:
					$str .= ((float)$coord / 10) . '|';
					$i = 1;
					break;
				default: break;	
			}
		}
		return substr($str, 0, strlen($str) - 2);
	}
	
	/**
	 * Properly formats string for passing to Wowhead
	 * @param string $str
	 * @return string
	 */
	public function convertString($str)
	{
		// convert to utf8, if necessary
		if ($this->lang != 'de')
		{
			if (!$this->isUTF8($str))
			{
				$str = utf8_encode($str);
			}
		}
		else
		{
			if ($this->isUTF8($str))
			{
				$str = utf8_decode($str);
			}
		}
		// clean up the html
//		$str = $this->cleanHTML($str);
		// return the url encoded string
		return urlencode($str);
	}

	/**
	 * Determines if $string is UTF-8
	 * @access public
	 * @param string $string
	 * @return bool
	 */
	public function isUTF8($string) {
		// From http://w3.org/International/questions/qa-forms-utf-8.html
		return (preg_match('%^(?:
			[\x09\x0A\x0D\x20-\x7E]            # ASCII
			| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
			|  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
			| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
			|  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
			|  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
			| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
			|  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
		)*$%xs', $string)) ? true : false;
	}

	/**
	 * Gets domain based on language
	 * @access public
	 * @return string
	 */
		public function getDomain()
    {
        if (isset($this->lang) && $this->lang != 'en') {
            return 'http://' . strtolower($this->lang) . '.wowhead.com';
       }
       return 'http://www.wowhead.com';
    }

	/**
	 * Generates link based on language and type
	 * @access public
	 * @param string $id
	 * @param string $type
	 * @return string
	 */
	public function generateLink($id, $type)
	{
		if ($type == 'itemico' || $type == 'item' || $type == 'item_icon')
		{
			return $this->getDomain() . '/item=' . $id;
		}
		elseif ($type == 'stats')
		{
			return $this->getDomain() . '/statistic=' . $id;
		}
		else
		{
			return $this->getDomain() . '/' . $type . '=' . $id;
		}
	}

	/**
	 * See if LIBXML_NOCDATA is allowed
	 * @access public
	 * @return bool
	 */
	public function allowSimpleXMLOptions()
	{
		$parts = explode('.', phpversion());
		return ($parts[0] == 5 && $parts[1] >= 1) ? true : false;
	}

	/**
	 * Returns HTML formatted error
	 * @access public
	 * @param string $error
	 * @return string
	 */
	public function generateError($error)
	{
		return '<span class="notfound">' . $error . '</span>';	
	}
	
	/**
	 * Returns HTML not found error
	 * @access public
	 * @param string $type
	 * @param string $name
	 * @return string
	 */
	public function notFound($type, $name)
	{
		$error = $this->language->words['notfound'];
		$error = str_replace('{type}', ucwords($type), $error);
		$error = str_replace('{name}', ucwords($name), $error);
		
		// log the error
		if ($this->config->log_errors == 'true')
		{
			$log = new wowhead_log();
			$log->logError($error, $type);
			$log->close(); unset($log);
		}
		return '<span class="notfound">' . $error . '</span>';
	}

	/**
	 * Replaces wildcards from patterns
	 * @access public
	 * @param string $in
	 * @param array $info
	 * @return string
	 */
	public function replaceWildcards($in, $info)
	{
		$wildcards = array();

		// build our wildcard array
		if (array_key_exists('link', $info))
			$wildcards['{link}'] = $info['link'];

		if (array_key_exists('realm', $info))
			$wildcards['{realm}'] = $info['realm'];

		if (array_key_exists('region', $info))
			$wildcards['{region}'] = $info['region'];

		if (array_key_exists('icons', $info))
			$wildcards['{icons}'] = $info['icons'];

		if (array_key_exists('name', $info))
			$wildcards['{name}'] = stripslashes($info['name']);

		if (array_key_exists('quality', $info))
			$wildcards['{qid}'] = $info['quality'];

		if (array_key_exists('rank', $info))
			$wildcards['{rank}'] = $info['rank'];

		if (array_key_exists('icon', $info))
			$wildcards['{icon}'] = $info['icon'];

		if (array_key_exists('class', $info))
			$wildcards['{class}'] = $info['class'];

		if (array_key_exists('gems', $info))
			$wildcards['{gems}'] = $info['gems'];

		if (array_key_exists('tooltip', $info))
			$wildcards['{tooltip}'] = stripslashes($info['tooltip']);

		if (array_key_exists('npcid', $info))
			$wildcards['{npcid}'] = $info['npcid'];
			
		if (array_key_exists('image', $info))
			$wildcards['{image}'] = $info['image'];
		
		if (array_key_exists('id', $info))
			$wildcards['{id}'] = $info['id'];
		
		if (array_key_exists('lang', $info))
			$wildcards['{lang}'] = $info['lang'];
		
		if (array_key_exists('faction', $info))
			$wildcards['{faction}'] = $info['faction_html'];
		
		if (array_key_exists('pattern', $info))
			$wildcards['{pattern}'] = $info['sprintf_pattern'];
		
		if (array_key_exists('expansion', $info))
			$wildcards['{expansion}'] = $info['expansion_html'];

		foreach ($wildcards as $key => $value)
		{
			$in = str_replace($key, $value, $in);
		}
		return $in;
	}

	/**
	 * Builds item enhancement string
	 * @access public
	 * @param array $args
	 * @return string
	 */
	public function buildEnhancement($args)
	{
		if (!is_array($args) || sizeof($args) == 0)
			return false;

		if (array_key_exists('gems', $args))
		{
			$gem_args = '&amp;gems=' . str_replace(',', ':', $args['gems']);
		}

		if (array_key_exists('enchant', $args))
		{
			$enchant_args = '&amp;ench=' . $args['enchant'];
		}

		if (!empty($gem_args) && !empty($enchant_args))
		{
			return $enchant_args . $gem_args;
		}
		elseif (!empty($enchant_args))
		{
			return $enchant_args;
		}
		elseif (!empty($gem_args))
		{
			return $gem_args;
		}

		return false;
	}

	/**
	 * Generates HTML for output to browser
	 * @access public
	 * @param array $info
	 * @param string $type
	 * @param string $size [optional]
	 * @param string $rank [optional]
	 * @param string $gems [optional]
	 * @return string
	 */
	public function generateHTML($info, $type, $size = '', $rank = '', $gems = '')
	{
		if ($type == 'event' || $type == 'stats' || $type == 'race')
			$info['link'] = $this->generateLink($info['id'], $type);
		elseif ($type != 'profile' && $type != 'armory' && $type != 'guild' && $type != 'armory_gearlist' && $type != 'armory_recruit' && $type != 'armory_rss' && $type != 'title')
			$info['link'] = ($type == 'npc') ? $this->generateLink($info['npcid'], 'npc') : $this->generateLink($info['itemid'], $type);
		
		// which pattern we use depends on the type, a switch will work nicely
		switch ($type)
		{
			case 'item':		// items
				if ($this->config->external_css == true || (array_key_exists('quality', $info) && sizeof($this->config->qualities) > 0 && in_array($info['quality'], $this->config->qualities)))
				{
					if (trim($gems) != '')
					{
						$info['gems'] = $gems;
						return $this->replaceWildcards($this->patterns->pattern('item_css_gems'), $info);
					}
					else
					{
						return $this->replaceWildcards($this->patterns->pattern('item_css'), $info);
					}
				}
				elseif (trim($gems) != '')
				{
					$info['gems'] = $gems;
					return $this->replaceWildcards($this->patterns->pattern('item_gems'), $info);
				}
				else
				{
					return $this->replaceWildcards($this->patterns->pattern('item'), $info);
				}				
				break;
				
			case 'item_icon':	// item icons
				if ($this->config->external_css == true || (array_key_exists('quality', $info) && sizeof($this->config->qualities) > 0 && in_array($info['quality'], $this->config->qualities)))
				{
					if (trim($gems) != '')
					{
						$info['gems'] = $gems;
						return $this->replaceWildcards($this->patterns->pattern('item_icon_css_gems'), $info);
					}
					else
					{
						return $this->replaceWildcards($this->patterns->pattern('item_icon_css'), $info);	
					}
				}
				else
				{
					if (trim($gems) != '')
					{
						$info['gems'] = $gems;
						return $this->replaceWildcards($this->patterns->pattern('item_icon_gems'), $info);
					}
					else
					{
						return $this->replaceWildcards($this->patterns->pattern('item_icon'), $info);	
					}
				}		
				break;
			
			case 'itemico':		// item icons
				if (trim($gems) != '')
				{
					$info['gems'] = $gems;
					return $this->replaceWildcards($this->patterns->pattern('icon_' . $size . '_gems'), $info);
				}
				else
				{
					return $this->replaceWildcards($this->patterns->pattern('icon_' . $size), $info);
				}
				break;
				
			case 'spell':		// spells
				if (trim($rank) != '')
					return $this->replaceWildcards($this->patterns->pattern('spell_rank'), $info);
				else
					return $this->replaceWildcards($this->patterns->pattern('spell'), $info);
				break;
				
			default: 			// all of the others
				return $this->replaceWildcards($this->patterns->pattern($type), $info);
				break;	
		}
	}
	

	/**
	 * Strips apostrophes from a string
	 * @access public
	 * @param string $in
	 * @return string
	 */
	public function stripApos($in)
	{
		return str_replace("'", "", $in);
	}
}
?>