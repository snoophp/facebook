<?php

namespace SnooPHP\Facebook;

use SnooPHP\Cache\I_Cache;

/**
 * Fake cache
 * 
 * @author Sneppy
 */
class NullCache implements I_Cache
{
	/**
	 * Fetch a record from cache
	 * 
	 * @param string	$uri	uri + used token
	 * 
	 * @return object|null
	 */
	public static function fetch($uri)
	{
		return null;
	}

	/**
	 * Store a record in cache
	 * 
	 * @param string	$uri		uri + used token
	 * @param string	$content	query response content
	 * 
	 * @return object|null
	 */
	public static function store($uri, $content)
	{
		return json_decode($content);
	}
}