<?php

/*
 * 
 * CleanTalk Cleantalk Antispam Helper class
 * 
 * @package Antispam Plugin by CleanTalk
 * @subpackage Helper
 * @Version 2.0
 * @author Cleantalk team (welcome@cleantalk.org)
 * @copyright (C) 2014 CleanTalk team (http://cleantalk.org)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 */

class CleantalkHelper
{
	public static $private_networks = array(
		'v4' => array(
			'10.0.0.0/8',
			'100.64.0.0/10',
			'172.16.0.0/12',
			'192.168.0.0/16',
			'127.0.0.1/32',
		),
		'v6' => array(
			'0:0:0:0:0:0:0:1/128', // localhost
			'0:0:0:0:0:0:a:1/128', // ::ffff:127.0.0.1
		),
	);
	
	/*
	*	Getting arrays of IP (REMOTE_ADDR, X-Forwarded-For, X-Real-Ip, Cf_Connecting_Ip)
	*	reutrns array('remote_addr' => 'val', ['x_forwarded_for' => 'val', ['x_real_ip' => 'val', ['cloud_flare' => 'val']]])
	*/
	static public function ip__get($ip_types = array('real', 'remote_addr', 'x_forwarded_for', 'x_real_ip', 'cloud_flare'), $v4_only = true)
	{
		$ips     = array_flip($ip_types); // Result array with IPs
		$headers = apache_request_headers();
		
		// REMOTE_ADDR
		if(isset($ips['remote_addr'])){
			$ip_type = self::ip__validate($_SERVER['REMOTE_ADDR']);
			if($ip_type){
				$ips['remote_addr'] = $ip_type == 'v6' ? self::ip__v6_normalize($_SERVER['REMOTE_ADDR']) : $_SERVER['REMOTE_ADDR'];
			}
		}
		
		// X-Forwarded-For
		if(isset($ips['x_forwarded_for'])){
			if(isset($headers['X-Forwarded-For'])){
				$tmp = explode(",", trim($headers['X-Forwarded-For']));
				$tmp = trim($tmp[0]);
				$ip_type = self::ip__validate($tmp);
				if($ip_type){
					$ips['x_forwarded_for'] = $ip_type == 'v6' ? self::ip__v6_normalize($tmp) : $tmp;
				}
			}
		}
		
		// X-Real-Ip
		if(isset($ips['x_real_ip'])){
			if(isset($headers['X-Real-Ip'])){
				$tmp = explode(",", trim($headers['X-Real-Ip']));
				$tmp = trim($tmp[0]);
				$ip_type = self::ip__validate($tmp);
				if($ip_type){
					$ips['x_forwarded_for'] = $ip_type == 'v6' ? self::ip__v6_normalize($tmp) : $tmp;
				}
			}
		}
		
		// Cloud Flare
		if(isset($ips['cloud_flare'])){
			if(isset($headers['Cf-Connecting-Ip'], $headers['Cf-Ipcountry'], $headers['Cf-Ray'])){
				$ip_type = self::ip__validate($_SERVER['REMOTE_ADDR']);
				if($ip_type){
//					if(self::ip__mask_match($ips['remote_addr'], self::$cdn_pool['cloud_flare']['ipv4'])){
						$ips['cloud_flare'] = $headers['Cf-Connecting-Ip'];
				}
			}
		}
		
		// Getting real IP from REMOTE_ADDR or Cf_Connecting_Ip if set or from (X-Forwarded-For, X-Real-Ip) if REMOTE_ADDR is local.
		if(isset($ips['real'])){
			
			// Detect IP type
			$ip_type = self::ip__validate($_SERVER['REMOTE_ADDR']);
			if($ip_type)
				$ips['real'] = $ip_type == 'v6' ? self::ip__v6_normalize($_SERVER['REMOTE_ADDR']) : $_SERVER['REMOTE_ADDR'];
			
			// Cloud Flare
			if(isset($headers['Cf-Connecting-Ip'], $headers['Cf-Ipcountry'], $headers['Cf-Ray'])){
				$ip_type = self::ip__validate($headers['Cf-Connecting-Ip']);
				if($ip_type)
					$ips['real'] = $ip_type == 'v6' ? self::ip__v6_normalize($headers['Cf-Connecting-Ip']) : $headers['Cf-Connecting-Ip'];
				
			// Sucury
			}elseif(isset($headers['X-Sucuri-Clientip'], $headers['X-Sucuri-Country'])){
				$ip_type = self::ip__validate($headers['X-Sucuri-Clientip']);
				if($ip_type)
					$ips['real'] = $ip_type == 'v6' ? self::ip__v6_normalize($headers['X-Sucuri-Clientip']) : $headers['X-Sucuri-Clientip'];
				
			// OVH
			}elseif(isset($headers['X-Cdn-Any-Ip'], $headers['Remote-Ip'])){
				$ip_type = self::ip__validate($headers['X-Cdn-Any-Ip']);
				if($ip_type)
					$ips['real'] = $ip_type == 'v6' ? self::ip__v6_normalize($headers['X-Cdn-Any-Ip']) : $headers['X-Cdn-Any-Ip'];
			
			// Incapsula proxy
			}elseif(isset($headers['Incap-Client-Ip'])){
				$ip_type = self::ip__validate($headers['Incap-Client-Ip']);
				if($ip_type)
					$ips['real'] = $ip_type == 'v6' ? self::ip__v6_normalize($headers['Incap-Client-Ip']) : $headers['Incap-Client-Ip'];
			}
			
			// Is private network
			if($ip_type === false || ($ip_type && (self::ip__is_private_network($ips['real'], $ip_type)) || (self::ip__mask_match($ips['real'], filter_input(INPUT_SERVER, 'SERVER_ADDR').'/24', $ip_type)))){
				
				// X-Forwarded-For
				if(isset($headers['X-Forwarded-For'])){
					$tmp = explode(",", trim($headers['X-Forwarded-For']));
					$tmp = trim($tmp[0]);
					$ip_type = self::ip__validate($tmp);
					if($ip_type)
						$ips['real'] = $ip_type == 'v6' ? self::ip__v6_normalize($tmp) : $tmp;
				
				// X-Real-Ip
				}elseif(isset($headers['X-Real-Ip'])){
					$tmp = explode(",", trim($headers['X-Real-Ip']));
					$tmp = trim($tmp[0]);
					$ip_type = self::ip__validate($tmp);
					if($ip_type)
						$ips['real'] = $ip_type == 'v6' ? self::ip__v6_normalize($tmp) : $tmp;
				}
			}
		}
		
		// Validating IPs
		$result = array();
		foreach($ips as $key => $ip){
			$ip_version = self::ip__validate($ip);
			if($ip && (($v4_only && $ip_version == 'v4') || !$v4_only)){
				$result[$key] = $ip;
			}
		}
		
		$result = array_unique($result);
		return count($result) > 1 
			? $result
			: (reset($result) !== false
				? reset($result)
				: null);
	}
	
	static function ip__is_private_network($ip, $ip_type = 'v4'){
		return self::ip__mask_match($ip, self::$private_networks[$ip_type]);
	}
	
	/*
	 * Check if the IP belong to mask.  Recursive.
	 * Octet by octet for IPv4
	 * Hextet by hextet for IPv6
	 * @param ip string  
	 * @param cird mixed (string|array of strings)
	 * @param ip_type string
	 * @param cird mixed (string|array of strings)
	*/
	static public function ip__mask_match($ip, $cidr, $ip_type = 'v4', $xtet_count = 0)
	{		
		if(is_array($cidr)){
			foreach($cidr as $curr_mask){
				if(self::ip__mask_match($ip, $curr_mask, $ip_type)){
					return true;
				}
			} unset($curr_mask);
			return false;
		}
		
		if($ip_type == 'v4') $xtet_base = 8;
		if($ip_type == 'v6') $xtet_base = 16;
		
		// Calculate mask
		$exploded = explode('/', $cidr);
		$net_ip = $exploded[0];
		$mask   = $exploded[1];
		
		// Exit condition
		$xtet_end = ceil($mask / $xtet_base);
		if($xtet_count == $xtet_end)
			return true;
		
		// Lenght of bits for comparsion
		$mask = $mask - $xtet_base * $xtet_count >= $xtet_base ? $xtet_base : $mask - $xtet_base * $xtet_count;
		
		// Explode by octets/hextets from IP and Net
		$net_ip_xtets = explode($ip_type == 'v4' ? '.' : ':', $net_ip);
		$ip_xtets     = explode($ip_type == 'v4' ? '.' : ':', $ip);
		
		// Standartizing. Getting current octets/hextets. Adding leading zeros. 
		$net_xtet = str_pad(decbin($ip_type == 'v4' ? $net_ip_xtets[$xtet_count]  : hexdec($net_ip_xtets[$xtet_count])), $xtet_base, 0, STR_PAD_LEFT);
		$ip_xtet  = str_pad(decbin($ip_type == 'v4' ? $ip_xtets[$xtet_count]      : hexdec($ip_xtets[$xtet_count])),     $xtet_base, 0, STR_PAD_LEFT);
		
		// Comparing bit by bit
		for($i = 0, $result = true; $mask != 0; $mask--, $i++ ){
			if($ip_xtet[$i] != $net_xtet[$i]){
				$result = false;
				break;
			}
		}
		
		// Recursing. Moving to next octet/hextet.
		if($result)
			$result = self::ip__mask_match($ip, $cidr, $ip_type, $xtet_count + 1);
		
		return $result;
		
	}
	
	/*
	*	Validating IPv4, IPv6
	*	param (string) $ip
	*	returns (string) 'v4' || (string) 'v6' || (bool) false
	*/
	static public function ip__validate($ip)
	{
		if(!$ip)                                                                                        return false; // NULL || FALSE || '' || so on...
		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && $ip != '0.0.0.0')                   return 'v4';  // IPv4
		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) && self::ip__v6_reduce($ip) != '0::0') return 'v6';  // IPv6
		                                                                                                return false; // Unknown
	}
	
	/**
	 * Expand IPv6
	 * param (string) $ip
	 * returns (string) IPv6
	 */
	static public function ip__v6_normalize($ip)
	{
		$ip = trim($ip);
		// Searching for ::ffff:xx.xx.xx.xx patterns and turn it to IPv6
		if(preg_match('/^::ffff:([0-9]{1,3}\.?){4}$/', $ip)){
			$ip = dechex(sprintf("%u", ip2long(substr($ip, 7))));
			$ip = '0:0:0:0:0:0:'.(strlen($ip) > 4 ? substr('abcde', 0, -4) : '0').':'.substr($ip, -4, 4);
		// Normalizing hextets number
		}elseif(strpos($ip, '::') !== false){
			$ip = str_replace('::', str_repeat(':0', 8 - substr_count($ip, ':')).':', $ip);
			$ip = strpos($ip, ':') === 0 ? '0'.$ip : $ip;
			$ip = strpos(strrev($ip), ':') === 0 ? $ip.'0' : $ip;
		}
		// Simplifyng hextets
		if(preg_match('/:0(?=[a-z0-9]+)/', $ip)){
			$ip = preg_replace('/:0(?=[a-z0-9]+)/', ':', strtolower($ip));
			$ip = self::ip__v6_normalize($ip);
		}
		return $ip;
	}
	
	/**
	 * Reduce IPv6
	 * param (string) $ip
	 * returns (string) IPv6
	 */
	static public function ip__v6_reduce($ip){
		if(strpos($ip, ':') !== false){
			$ip = preg_replace('/:0{1,4}/', ':',  $ip);
			$ip = preg_replace('/:{2,}/',   '::', $ip);
			$ip = strpos($ip, '0') === 0 ? substr($ip, 1) : $ip;
		}
		return $ip;
	}
	
	/**
	 * Function sends raw http request
	 *
	 * May use 4 presets(combining possible):
	 * get_code             - getting only HTTP response code
	 * dont_wait_for_answer - async requests
	 * get                  - GET-request
	 * ssl                  - use SSL
	 * 
	 * @param string $url URL
	 * @param array $data POST|GET indexed array with data to send
	 * @param string|array $presets String or Array with presets: get_code, dont_wait_for_answer, get, ssl, dont_split_to_array
	 * @param array $opts Optional option for CURL connection
	 * 
	 * @return array (array || array('error' => true))
	 */
	static public function http__request($url, $data = array(), $presets = null, $opts = array())
	{
		if(function_exists('curl_init')){
		
			$ch = curl_init();
			
			// Merging OBLIGATORY options with GIVEN options
			$opts = self::array_merge__save_numeric_keys(
				array(
					CURLOPT_URL               => $url,
					CURLOPT_RETURNTRANSFER    => true,
					CURLOPT_CONNECTTIMEOUT_MS => 3000,
					CURLOPT_FORBID_REUSE      => true,
					CURLOPT_USERAGENT         => 'APBCT('.(defined('CLEANTALK_AGENT') ? CLEANTALK_AGENT : 'UNKNOWN_AGENT').')',
					CURLOPT_POST              => true,
					CURLOPT_POSTFIELDS        => str_replace("&amp;", "&", http_build_query($data)),
					CURLOPT_SSL_VERIFYPEER    => false,
					CURLOPT_SSL_VERIFYHOST    => 0,
					CURLOPT_HTTPHEADER        => array('Expect:'), // Fix for large data and old servers http://php.net/manual/ru/function.curl-setopt.php#82418
				),
				$opts
			);
			
			// Use presets
			$presets = is_array($presets) ? $presets : explode(' ', $presets);
			foreach($presets as $preset){
				
				switch($preset){
					
					// Get headers only
					case 'get_code':
						$opts[CURLOPT_HEADER] = true;
						$opts[CURLOPT_NOBODY] = true;
						break;
						
					// Make a request, don't wait for an answer
					case 'dont_wait_for_answer':
						$opts[CURLOPT_CONNECTTIMEOUT_MS] = 1000;
						$opts[CURLOPT_TIMEOUT_MS] = 500;
						break;
					
					case 'get':
						$opts[CURLOPT_URL] .= '?'.str_replace("&amp;", "&", http_build_query($data));
						$opts[CURLOPT_POST] = false;
						$opts[CURLOPT_POSTFIELDS] = null;
						break;
					
					case 'ssl':
						$opts[CURLOPT_SSL_VERIFYPEER] = true;
						$opts[CURLOPT_SSL_VERIFYHOST] = 2;
						$opts[CURLOPT_CAINFO] = APBCT_CASERT_PATH;
						break;
					
					default:
						
						break;
				}
		
			} unset($preset);
		
			curl_setopt_array($ch, $opts);
			$result = curl_exec($ch);
			
			// RETURN if async request
			if(in_array('dont_wait_for_answer', $presets))
				return true;
		
			if($result){
				
				if(strpos($result, PHP_EOL) !== false && !in_array('dont_split_to_array', $presets))
					$result = explode(PHP_EOL, $result);
				
				// Get code crossPHP method
				if(in_array('get_code', $presets)){
					$curl_info = curl_getinfo($ch);
					$result = $curl_info['http_code'];
				}
				curl_close($ch);
				$out = $result;
			}else
				$out = array('error' => true, 'error_string' => curl_error($ch));
		}else
			$out = array('error' => true, 'error_string' => 'CURL_NOT_INSTALLED');
		
		/** Fix for get_code preset */
		if($presets && ($presets == 'get_code' || (is_array($presets) && in_array('get_code', $presets) ) )
			&& isset($out['error_string']) && $out['error_string'] == 'CURL_NOT_INSTALLED'
		){
			$headers = get_headers($url);
			$out = (int)preg_replace('/.*(\d{3}).*/', '$1', $headers[0]);
		}
		
		return $out;
	}
	
	/**
	 * Merging arrays without reseting numeric keys
	 * 
	 * @param array $arr1 One-dimentional array
	 * @param array $arr2 One-dimentional array
	 * @return array Merged array
	 */
	public static function array_merge__save_numeric_keys($arr1, $arr2){
		foreach ($arr2 as $key => $val){
			$arr1[$key] = $val;
		}
		return $arr1;
	}
	
	/**
	 * Merging arrays without reseting numeric keys recursive
	 * 
	 * @param array $arr1 One-dimentional array
	 * @param array $arr2 One-dimentional array
	 * @return array Merged array
	 */
	public static function array_merge__save_numeric_keys__recursive($arr1, $arr2){
		foreach ($arr2 as $key => $val){
			// Array | array => array
			if(isset($arr1[$key]) && is_array($arr1[$key]) && is_array($val)){
				$arr1[$key] = self::array_merge__save_numeric_keys__recursive($arr1[$key], $val);
			// Scalar | array => array
			}elseif(isset($arr1[$key]) && !is_array($arr1[$key]) && is_array($val)){
				$tmp = $arr1[$key] = 
				$arr1[$key] = $val;
				$arr1[$key][] = $tmp;
			// array  | scalar => array
			}elseif(isset($arr1[$key]) && is_array($arr1[$key]) && !is_array($val)){
				$arr1[$key][] = $val;
			// scalar | scalar => scalar
			}else{
				$arr1[$key] = $val;
			}
		}
		return $arr1;
	}
	
	/**
	 * Function removing non UTF8 characters from array|string|object
	 * 
	 * @param mixed(array|object|string) $data
	 * @param type $data_codepage
	 * @return mixed(array|object|string)
	 */
	public static function removeNonUTF8($data, $data_codepage = null)
	{
		// Array || object
		if(is_array($data) || is_object($data)){
			foreach ($data as $key => &$val) {
				$val = self::removeNonUTF8($val, $data_codepage);
			}unset($key, $val);
			
		//String
		}else{
			if(!preg_match('//u', $data))
				$data =  'Nulled. Not UTF8 encoded or malformed.';
		}
		return $data;
	}
		
	/**
	 * Function convert anything to UTF8 and removes non UTF8 characters 
	 * 
	 * @param mixed(array|object|string) $obj
	 * @param type $data_codepage
	 * @return mixed(array|object|string)
	 */
	public static function toUTF8($obj, $data_codepage = null)
	{
		// Array || object
		if(is_array($obj) || is_object($obj)){
			foreach ($obj as $key => &$val) {
				$val = self::toUTF8($val, $data_codepage);
			}unset($key, $val);
			
		//String
		}else{
			if (!preg_match('//u', $obj) && function_exists('mb_detect_encoding') && function_exists('mb_convert_encoding')){
				$encoding = mb_detect_encoding($obj);
				$encoding = $encoding ? $encoding : $data_codepage;
				if ($encoding)
					$obj = mb_convert_encoding($str, 'UTF-8', $encoding);
			}
		}
		return $obj;
	}
	    
    /**
     * Function convert from UTF8
	 * 
     * @param mixed (array|object|string)
     * @param string
     * @return mixed (array|object|string)
     */
    public static function fromUTF8($obj, $data_codepage = null)
	{
		// Array || object
		if(is_array($obj) || is_object($obj)){
			foreach ($obj as $key => &$val) {
				$val = self::fromUTF8($val, $data_codepage);
			}unset($key, $val);
			
		//String
		}else{
			if(preg_match('u', $obj) && function_exists('mb_convert_encoding') && $data_codepage !== null)
				$obj = mb_convert_encoding($obj, $data_codepage, 'UTF-8');
		}
		return $obj;
	}
	
	/**
	* Checks if the string is JSON type
	* @param string
	* @return bool
	*/
	static public function is_json($string)
	{
		return is_string($string) && is_array(json_decode($string, true)) ? true : false;
	}
}
