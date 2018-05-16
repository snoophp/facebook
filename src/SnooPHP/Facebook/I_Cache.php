<?php

namespace SnooPHP\Facebook;

/**
 * Interface to store and retrieve api requests
 * 
 * @author Sneppy
 */
interface I_Cache
{
	/**
	 * Fetch a record from cache
	 * 
	 * @param string	$query	query string
	 * @param string	$token	access token used
	 * 
	 * @return object|null
	 */
	public static function fetch($query, $token);

	/**
	 * Store a record in cache
	 * 
	 * @param string	$query		query string
	 * @param string	$token		access token used
	 * @param string	$content	query response content
	 * 
	 * @return object|null
	 */
	public static function store($query, $token, $content);
}