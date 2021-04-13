<?php

namespace Essence\Provider\OEmbed;

use Essence\Provider\OEmbed;

class JWPlayer extends OEmbed
{
	protected $_endpoint = 'https://cdn.jwplayer.com/v2/media/:mediaId';
	private $pattern = '~\.jwplayer.com/.+/(?<mediaId>[a-z0-9]+)-(?<playerId>[a-z0-9]+)\.~i';
	private $mediaId;
	private $playerId;

	/**
	 * {@inheritDoc}
	 *
	 * Sets the endpoint based on the incoming mediaId
	 */
	protected function _buildConfig($url) {
		preg_match($this->pattern, $url, $matches);
		$this->playerId = $matches['playerId'] ? $matches['playerId'] : 'default';
		$this->mediaId = $matches['mediaId'];
		$endpoint = str_replace(':mediaId', $this->mediaId, $this->_endpoint);
		return new Config($endpoint, $this->_format);
	}

	/**
	 * {@inheritDoc}
	 *
	 * Reformats the JWP Api data to match oEmbed spec
	 */
	protected function _parse($response, $format) {
		$parsed = parent::_parse($response, $format);
		$playlist = $parsed['playlist'][0];
		$url = sprintf('https://cdn.jwplayer.com/players/%s-%s.html', $this->mediaId, $this->playerId);
		return [
			'type' => 'video',
			'version' => '1.0',
			'provider_name' => 'JWPlayer',
			'provider_url' => 'https://www.jwplayer.com/',
			'title' => $parsed['title'],
			'description' => $parsed['description'],
			'html' => sprintf('<iframe src="%s"></iframe>', $url),
			'thumbnailUrl' => $playlist['image'],
			'url' => sprintf('%s.html', $url),
		];
	}

}
