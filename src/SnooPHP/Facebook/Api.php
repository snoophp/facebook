<?php

namespace SnooPHP\Facebook;

use SnooPHP\Curl\Get;

/**
 * Perform raw api requests or use dedicated methods
 * 
 * Requests can be saved in a dedicated cache
 * 
 * @author Sneppy
 */
class Api
{
	/**
	 * @var string $clientId application client id
	 */
	protected $clientId;

	/**
	 * @var string $clientSecret application secret id
	 */
	protected $clientSecret;

	/**
	 * @var object $token user access token, used for api requests
	 */
	protected $token;

	/**
	 * @var string $lastResult store last request result (raw)
	 */
	protected $lastResult;

	/**
	 * @var string $version api version (default: v1)
	 */
	protected $version = "v3.0";

	/**
	 * @var string $cacheClass cache class
	 */
	protected $cacheClass;

	/**
	 * @var string $defaultCacheClass
	 */
	protected static $defaultCacheClass = "SnooPHP\Cache\NullCache";

	/**
	 * @const ENDPOINT facebook api endpoint
	 */
	const ENDPOINT = "https://graph.facebook.com";

	/**
	 * Create a new instance
	 */
	public function __construct()
	{
		// Set cache class
		$this->cacheClass = static::$defaultCacheClass;
	}

	/**
	 * Perform a generic query
	 * 
	 * @param string	$query	query string (with parameters)
	 * 
	 * @return object|bool false if fails
	 */
	public function query($query)
	{
		// If no access token, try to use client token
		if (!$this->token || empty($this->token->access_token))
			$token = $this->clientToken();
		else
			$token = $this->token;
		
		// Build uri
		$uri	= preg_match("/^https?:\/\//", $query) ? $query : static::ENDPOINT."/{$this->version}/{$query}";
		$uri	.= (strpos($query, '?') !== false ? '&' : '?')."access_token=$token";

		// Check if cached result exists
		if ($record = $this->cacheClass::fetch("$uri|$token")) return $record;

		// Make api request
		$curl = new Get($uri);
		if ($curl && $curl->success())
		{
			// Save record in cache and return it
			$this->lastResult = $curl->content();
			return $this->cacheClass::store("$uri|$token", $this->lastResult);
		}
		else
		{
			$this->lastResult = false;
			return false;
		}
	}

	/**
	 * Get client token from client id and secret
	 * 
	 * A client token cannot be use to access a user private data
	 * But it is suitable to query public content (e.g. page public data)
	 * It is obtained by simply concatenating the app client id and client secret
	 * 
	 * @return string
	 */
	protected function clientToken()
	{
		return $this->clientId."|".$this->clientSecret;
	}

	/**
	 * Create a new instance from client id and client secret
	 * 
	 * @param string	$clientId		client id
	 * @param string	$clientSecret	client secret
	 * 
	 * @return Api
	 */
	public static function withClient($clientId, $clientSecret)
	{
		$api = new static();
		$api->clientId		= $clientId;
		$api->clientSecret	= $clientSecret;
		return $api;
	}
	
	/**
	 * Create a new instance from existing access token
	 * 
	 * @param string	$token	provided access token
	 * 
	 * @return Api
	 */
	public static function withToken($token)
	{
		$api = new static();
		$api->token = $token;
		return $api;
	}

	/**
	 * Set or get default cache class for this session
	 * 
	 * @param string|null	$defaultCacheClass	cache full classname
	 * 
	 * @return string
	 */
	public static function defaultCacheClass($defaultCacheClass = null)
	{
		if ($defaultCacheClass) static::$defaultCacheClass = $defaultCacheClass;
		return static::$defaultCacheClass;
	}
}