<?php

namespace SnooPHP\Facebook;

use SnooPHP\Model\Model;

/**
 * A ready-to-use MySql-based cache implementation
 * 
 * @author Sneppy
 */
class CacheModel extends Model implements I_Cache
{
	/**
	 * Return associated table name
	 * 
	 * @return string
	 */
	public static function tableName()
	{
		return "facebook_cache";
	}

	/**
	 * Fetch a record from database
	 * 
	 * @param string	$query			query string
	 * @param string	$token	access token used in the request
	 * 
	 * @return CacheModel|null
	 */
	public static function fetch($query, $token)
	{
		static::select("where query = :query and access_token = :access_token and expires_at > now()", [
			"query"			=> $query,
			"access_token"	=> $token
		])->first();
	}

	/**
	 * Store a record in cache
	 * 
	 * @param string	$query			query string
	 * @param string	$token	access token used in the request
	 * @param string	$content		query response content
	 * 
	 * @return object|false stored object or false if fails
	 */
	public static function store($query, $token, $content)
	{
		// Escape unicode
		//$content = preg_replace("/\\\\u/", "\\\\\\\\u", $content);

		// If already exists update
		if ($record = static::fetch($query, $token))
		{
			$record->content	= $content;
			$record->expires_at	= date("Y-m-d H:i:s", time() + 3600);
			return $record->save();
		}

		// Else insert new
		$record = new static([
			"query"			=> $query,
			"access_token"	=> $token,
			"content"		=> $content,
			"expires_at"	=> date("Y-m-d H:i:s", time() + 3600)
		]);
		return $record->save();
	}
}