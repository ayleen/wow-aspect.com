<?php
/**
*
* @package Wowhead Tooltips
* @version functions.php 4.0
* @copyright (c) 2010 Adam Koch <http://wowhead-tooltips.com>
* @license http://www.wowhead-tooltips.com/downloads/terms-of-use/
*
*/

/**
 * Cleans HTML for output to Wowhead
 * @access public
 * @param string $string
 * @return string
 */
function cleanHTML($string)
{
    if (function_exists("mb_convert_encoding"))
        $string = mb_convert_encoding($string, "UTF-8", "HTML-ENTITIES");
    else
    {
       $conv_table = get_html_translation_table(HTML_ENTITIES);
       $conv_table = array_flip($conv_table);
       $string = strtr ($string, $conv_table);
       $string = preg_replace('/&#(\d+);/me', "chr('\\1')", $string);
    }
    return ($string);
}

/**
 * Converts string to UTF-8
 * @access public
 * @param string $str
 * @return string
 */
function convertString($str)
{
	// convert to utf8, if necessary
	if (!is_utf8($str))
	{
		$str = utf8_encode($str);
	}

	// clean up the html
	$str = cleanHTML($str);

	// return the url encoded string
	return urlencode($str);
}

/**
 * Determines if string is UTF-8
 * @access public
 * @param string $string
 * @return bool
 */
function is_utf8($string) {
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
 * Generates Unique Key for MySQL
 * @access public
 * @param string $name
 * @param string $realm
 * @param string $region
 * @return string
 */
function generateKey($name, $realm, $region)
{
	$name = strtolower(str_replace(' ', '', $name));
	$realm = strtolower(str_replace(' ', '', $realm));
	$region = strtolower($region);
	return md5($name . $realm . $region);
}

/**
 * Attempts to Pull XML Data From Armory
 * @access public
 * @param string $uri
 * @return string
 */
function readURL($uri)
{
	// Try cURL first. If that isn't available, check if we're allowed to
	// use fopen on URLs.  If that doesn't work, just die.
	if (function_exists('curl_init'))
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_URL, $uri);
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
		$html_data = @file_get_contents($uri);
	}
	else
	{
        // Thanks to Aki Uusitalo
		$url_array = parse_url($uri);

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
	return $html_data;	
}

/**
 * Get XML From Armory
 * @access public
 * @param string $url
 * @param string $language [optional]
 * @return string
 */
function getXML($url, $language = NULL) {
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

	trigger_error ('Could not fetch URL, neither with cURL, nor fopen', E_USER_ERROR);
	return false;
}

/**
 * Generates Armory Character URL
 * @access public
 * @param string $name
 * @param string $region
 * @param string $realm
 * @return string
 */
function characterURL($name, $region, $realm)
{
	return armoryURL($region) . 'character-sheet.xml?r=' . str_replace(' ', '+', $realm) . '&cn=' . $name;
}

/**
 * Generates Armory URL
 * @access public
 * @param string $region
 * @return string
 */
function armoryURL($region)
{
	$prefix = ($region == 'us') ? 'www' : $region;
	return "http://{$prefix}.wowarmory.com/";
}

/**
 * Generates Armory Reputation URL
 * @access public
 * @param string $name
 * @param string $region
 * @param string $realm
 * @return string
 */
function reputationURL($name, $region, $realm)
{
	return armoryURL($region) . 'character-reputation.xml?r=' . str_replace(' ', '+', $realm) . '&cn=' . $name;	
}

/**
 * Generates Armory Achievement URL
 * @access public
 * @param string $name
 * @param string $region
 * @param string $realm
 * @return string
 */
function achievementURL($name, $region, $realm)
{
	return armoryURL($region) . 'character-achievements.xml?r=' . str_replace(' ', '+', $realm) . '&cn=' . $name . '&c=168';	
}

/**
 * Generates Armory Talents URL
 * @access public
 * @param string $name
 * @param string $region
 * @param string $realm
 * @return string
 */
function talentsURL($name, $region, $realm)
{
	return armoryURL($region) . 'character-talents.xml?r=' . str_replace(' ', '+', $realm) . '&cn=' . $name;	
}

/**
 * Generates Armory RSS URL
 * @access public
 * @param string $name
 * @param string $region
 * @param string $realm
 * @return string
 */
function rssURL($name, $region, $realm)
{
	$realm = (strpos($realm, ' ')) ? str_replace(' ', '+', $realm) : $realm;	// replace spaces with (+)
	return armoryURL($region) . "character-feed.atom?r={$realm}&cn={$name}&locale=" . getLocale($region);	
}

/**
 * Generates Locale Based on Language
 * @access public
 * @param string $region
 * @return string
 */
function getLocale($region)
{
	return ($region == 'en') ? 'en_US' : strtolower($region) . '_' . strtoupper($region);
}

/**
 * Build Factions Array
 * @access public
 * @param array $wrath
 * @return array
 */
function buildFactions($wrath)
{
	$factions = array();
	foreach ($wrath as $rep)
	{
		if (array_key_exists('faction', $rep))
		{
			foreach ($rep as $fact)
			{
				$factions[(string)$rep['name']][] = array(
					'name'	=>	(string)$fact['name'],
					'rep'	=>	(string)$fact['reputation']
				);
			}
			sort($factions[(string)$rep['name']]);
		}
		else
		{
			$factions['Wrath of the Lich King'][] = array(
				'name'	=>	(string)$rep['name'],
				'rep'	=>	(string)$rep['reputation']
			);
		}
	}
	sort($factions['Wrath of the Lich King']);
	return $factions;
}

/**
 * Calculates Reputation Standing
 * @access public
 * @param int $val
 * @return array
 */
function calculateStanding($val)
{
	global $language;
	
	// make sure it's an integer
	$val = (int)$val;
	
	// hated (42000 + value)
	if ($val >= -42000 && $val <= -6001)
	{
		$value = 42000 + $val;
		return array(
			'word'		=>	$language->words['hated'],
			'max'		=>	'36000',
			'value'		=>	$value,
			'class'		=>	'hated'
		);
	}
	// hostile (6000 + value)
	elseif ($val >= -6000 && $val <= -3001)
	{
		$value = 6000 + $val;
		return array(
			'word'		=>	$language->words['hostile'],
			'max'		=>	'3000',
			'value'		=>	$value,
			'class'		=>	'hostile'
		);		
	}
	// unfriendly (3000 + value)
	elseif ($val >= -3000 && $val <= -1)
	{
		$value = 3000 + $val;
		return array(
			'word'		=>	$language->words['unfriendly'],
			'max'		=>	'3000',
			'value'		=>	$value,
			'class'		=>	'unfriendly'
		);
	}
	// neutral (value)
	elseif ($val >= 0 && $val <= 2999)
	{
		return array(
			'word'		=>	$language->words['neutral'],
			'max'		=>	'3000',
			'value'		=>	$val,
			'class'		=>	'neutral'
		);
	}
	// friendly (value - 3000)
	elseif ($val >= 3000 && $val <= 8999)
	{
		$value = $val - 3000;
		return array(
			'word'		=>	$language->words['friendly'],
			'max'		=>	'6000',
			'value'		=>	$value,
			'class'		=>	'friendly'
		);
	}
	// honored (value - 9000)
	elseif ($val >= 9000 && $val <= 20999)
	{
		$value = $val - 9000;
		return array(
			'word'		=>	$language->words['honored'],
			'max'		=>	'12000',
			'value'		=>	$value,
			'class'		=>	'honored'
		);
	}
	// revered (value - 21000)
	elseif ($val >= 21000 && $val <= 41999)
	{
		$value = $val - 21000;
		return array(
			'word'		=>	$language->words['revered'],
			'max'		=>	'21000',
			'value'		=>	$value,
			'class'		=>	'revered'
		);
	}
	// exalted (value - 42000)
	elseif ($val >= 42000 && $val <= 42999)
	{
		$value = $val - 42000;
		return array(
			'word'		=>	$language->words['exalted'],
			'max'		=>	'1000',
			'value'		=>	$value,
			'class'		=>	'exalted'
		);
	}
}

/**
 * Calculates Percentage
 * @access public
 * @param int $first
 * @param int $second
 * @return int
 */
function percentage($first, $second)
{
	return number_format((($first / $second) * 100), 0);	
}

/**
 * Gets Achievement Icon from Wowhead
 * @access public
 * @param int $id
 * @return icon
 */
function getAchievementIcon($id)
{
	global $wowhead_url;
	
	$data = getXML($wowhead_url . 'achievement=' . $id . '&power');
	
	if (preg_match('#icon: \'(.+?)\'#', $data, $match))
	{
		// icon found
		return 'http://static.wowhead.com/images/wow/icons/tiny/' . strtolower($match[1]) . '.gif';	
	}
	else
	{
		return false;	
	}
}

/**
 * Get Talent Images Based on Class
 * @access private
 * @param string $class
 * @param int $one
 * @param int $two
 * @param int $three
 * @return string
 */
function getTalentImage($class, $one, $two, $three)
{
	global $config;
	
	$val = max($one, $two, $three);
	
	if ($val == $one)
		return $config->armory_image_url . 'images/talents/' . $class . '/1.gif';
	elseif ($val == $two)
		return $config->armory_image_url . 'images/talents/' . $class . '/2.gif';
	else
		return $config->armory_image_url . 'images/talents/' . $class . '/3.gif';
}

function stripHeaders($data)
{
	// split the string
	$chunks = explode(chr(10), $data);

	// return the last index in the array, aka our xml
	return $chunks[sizeof($chunks) - 1];
}

function getDomain($lang)
{
	return ($lang == 'en') ? 'http://www.wowhead.com/' : 'http://' . $lang . '.wowhead.com/';
}

function getRewardLine($data)
{
	$lines = explode(chr(10), $data);
	
	foreach ($lines as $line)
	{
		if (strpos($line, "new Listview({template: 'item', id: 'items',") !== false)
		{
			return $line;
			break;
		}
	}
	
	return false;
}

function rewardsFound($items)
{
	$found = false;
	foreach ($items as $standing)
	{
		if (sizeof($standing) > 0)
			$found = true;	
	}
	return $found;
}

if (!function_exists('bcdiv'))
{
   function bcdiv($first, $second, $scale = 0)
   {
       $res = $first / $second;
       return round($res, $scale);
   }
}


?>
