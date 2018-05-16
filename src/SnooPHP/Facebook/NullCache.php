<?php

namespace SnooPHP\Facebook;

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
	 * @param string	$query			query string
	 * @param string	$token	access token used
	 * 
	 * @return object|null
	 */
	public static function fetch($query, $token)
	{
		return null;
	}

	/**
	 * Store a record in cache
	 * 
	 * @param string	$query			query string
	 * @param string	$token	access token used
	 * @param string	$content		query response content
	 * 
	 * @return object|null
	 */
	public static function store($query, $token, $content)
	{
		return json_decode($content);
	}
}