<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use Essence\Exception;
use Essence\Media;
use Essence\Provider;
use Essence\Dom\Parser as Dom;
use Essence\Http\Client as Http;
use Essence\Utility\Template;
use Essence\Utility\Json;
use Essence\Utility\Xml;



/**
 *	Base class for an OEmbed provider.
 *	This kind of provider extracts embed informations through the OEmbed protocol.
 */
class OEmbed extends Provider {

	/**
	 *	JSON response format.
	 *
	 *	@var string
	 */
	const json = 'json';



	/**
	 *	XML response format.
	 *
	 *	@var string
	 */
	const xml = 'xml';



	/**
	 *	HTTP client.
	 *
	 *	@var Http
	 */
	protected $_Http = null;



	/**
	 *	DOM parser.
	 *
	 *	@var Dom
	 */
	protected $_Dom = null;



	/**
	 *	Configuration.
	 *
	 *	@property string $endpoint The OEmbed endpoint.
	 *	@property string $format The expected response format.
	 *	@var array
	 */
	protected $_properties = [
		'endpoint' => '',
		'format' => self::json
	];



	/**
	 *	Constructor.
	 *
	 *	@param Http $Http HTTP client.
	 *	@param Dom $Dom DOM parser.
	 *	@param array $preparators Preparator.
	 *	@param array $presenters Presenters.
	 */
	public function __construct(
		Http $Http,
		Dom $Dom,
		array $preparators = [],
		array $presenters = []
	) {
		$this->_Http = $Http;
		$this->_Dom = $Dom;

		parent::__construct($preparators, $presenters);
	}



	/**
	 *	{@inheritDoc}
	 *
	 *	@note If no endpoint was specified in the configuration, the page at
	 *		the given URL will be parsed to find one.
	 *	@throws Essence\Exception If the parsed page doesn't provide any endpoint.
	 */
	protected function _embed($url, array $options) {
		$config = $this->_config($url, $options);
		$response = $this->_Http->get($config['endpoint']);

		switch ($config['format']) {
			case self::json:
				$data = Json::parse($response);
				break;

			case self::xml:
				$data = Xml::parse($response);
				break;

			default:
				throw new Exception('Unsupported response format.');
		}

		return new Media($data);
	}



	/**
	 *	Builds or extracts an oEmbed config.
	 *
	 *	@param string $url URL.
	 *	@param array $options Options.
	 *	@return array Configuration.
	 */
	protected function _config($url, array $options) {
		$config = $this->endpoint
			? $this->_buildConfig($url)
			: $this->_extractConfig($this->_Http->get($url));

		if ($options) {
			$config['endpoint'] = $this->_completeEndpoint(
				$config['endpoint'],
				$options
			);
		}

		return $config;
	}



	/**
	 *	Builds an oEmbed configuration from settings.
	 *
	 *	@param string $url URL to embed.
	 *	@return array Configuration.
	 */
	protected function _buildConfig($url) {
		return [
			'format' => $this->format,
			'endpoint' => Template::compile(
				$this->endpoint,
				compact('url'),
				'urlencode'
			)
		];
	}



	/**
	 *	Extracts an oEmbed configuration from the given page.
	 *
	 *	@param string $html HTML page.
	 *	@return array Configuration.
	 */
	protected function _extractConfig($html) {
		$attributes = $this->_Dom->extractAttributes($html, [
			'link' => [
				'rel' => '#alternate#i',
				'type',
				'href'
			]
		]);

		foreach ($attributes['link'] as $link) {
			if (preg_match('#(?<format>json|xml)#i', $link['type'], $matches)) {
				return [
					'endpoint' => $link['href'],
					'format' => $matches['format']
				];
			}
		}

		throw new Exception('Unable to extract any OEmbed endpoint');
	}



	/**
	 *	Appends a set of options as parameters to the given endpoint URL.
	 *
	 *	@param string $endpoint Endpoint URL.
	 *	@param array $options Options to append.
	 *	@param string Completed endpoint.
	 */
	protected function _completeEndpoint($endpoint, $options) {
		$params = array_intersect_key($options, [
			'maxwidth' => '',
			'maxheight' => ''
		]);

		if ($params) {
			$endpoint .= (strrpos($endpoint, '?') === false) ? '?' : '&';
			$endpoint .= http_build_query($params);
		}

		return $endpoint;
	}
}
