<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Utility;



/**
 *
 */
class Url {

	/**
	 *	Constants identifying URL parts.
	 */
	const scheme = 'scheme';
	const host = 'host';
	const port = 'port';
	const user = 'user';
	const pass = 'pass';
	const path = 'path';
	const query = 'query';
	const fragment = 'fragment';



	/**
	 *	Resolves relative URLs.
	 *
	 *	@param string $url URL to resolve.
	 *	@param string $base URL of the page from which URLs were extracted.
	 *	@return string Resolved URL.
	 */
	public static function resolve($url, $base) {
		$urlParts = parse_url($url) ?: [];
		$baseParts = parse_url($base) ?: [];

		if (strpos($url, '//') === 0 && isset($baseParts[self::scheme])) {
			return $baseParts[self::scheme] . ':' . $url;
		}

		// the URL is fully qualified
		if (isset($urlParts[self::host])) {
			return $url;
		}

		// the URL is absolute
		$host = self::host($baseParts);

		if (strpos($url, '/') === 0) {
			return $host . $url;
		}

		// the URL is a path
		$basePath = isset($baseParts[self::path])
			? $baseParts[self::path]
			: '';

		$parts = $urlParts;
		$parts[self::path] = self::resolvePath($url, $basePath);

		return $host . self::path($parts);
	}



	/**
	 *	Resolves a set of relative URLs.
	 *
	 *	@see resolve()
	 *	@param array $urls URLs to resolve.
	 *	@param string $base URL of the page from which URLs were extracted.
	 *	@return array Resolved URLs.
	 */
	public static function resolveAll(array $urls, $base) {
		$resolved = array_map(function($url) use ($base) {
			return self::resolve($url, $base);
		}, $urls);

		return array_unique($resolved);
	}



	/**
	 *	Resolves relative paths.
	 *
	 *	@param string $urlPath URLs to resolve.
	 *	@param string $basePath URL of the page from which URLs were extracted.
	 *	@return array Resolved path.
	 */
	public static function resolvePath($urlPath, $basePath) {
		$urlParts = self::splitPath($urlPath);
		$baseParts = self::splitPath($basePath);
		$resolved = array_slice($baseParts, 0, -1);

		foreach ($urlParts as $part) {
			if ($part === '..') {
				array_pop($resolved);
			} else {
				$resolved[] = $part;
			}
		}

		return '/' . implode('/', $resolved);
	}



	/**
	 *	Splits a path in parts.
	 *
	 *	@param string $path Path.
	 *	@return array Parts.
	 */
	public static function splitPath($path) {
		$parts = explode('/', $path);

		return array_filter($parts, function($parts) {
			return !empty($parts) && ($parts !== '.');
		});
	}



	/**
	 *	Builds the full host part of an URL.
	 *
	 *	@param array $parts URL parts.
	 *	@return string URL.
	 */
	public static function host(array $parts) {
		if (!isset($parts[self::host])) {
			return '';
		}

		$url = isset($parts[self::scheme])
			? $parts[self::scheme]
			: 'http';

		$url .= '://';

		if (isset($parts[self::user]) && isset($parts[self::pass])) {
			$url .= $parts[self::user] . ':' . $parts[self::pass] . '@';
		}

		$url .= $parts[self::host];

		if (isset($parts[self::port])) {
			$url .= ':' . $parts[self::port];
		}

		return $url;
	}



	/**
	 *	Builds the full path part of an URL.
	 *
	 *	@param array $parts URL parts.
	 *	@return string URL.
	 */
	public static function path($parts) {
		$url = '/';

		if (isset($parts[self::path])) {
			$url .= ltrim($parts[self::path], '/');
		}

		if (isset($parts[self::query])) {
			$url .= '?' . $parts[self::query];
		}

		if (isset($parts[self::fragment])) {
			$url .= '#' . $parts[self::fragment];
		}

		return $url;
	}
}
