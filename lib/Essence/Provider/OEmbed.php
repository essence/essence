<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use Essence\Exception;
use Essence\Media;
use Essence\Provider;
use Essence\Provider\OEmbed\Format;
use Essence\Provider\OEmbed\Config;
use Essence\Dom\Document\Factory\Native as Dom;
use Essence\Dom\Tag;
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
	 *	Configuration:
	 *		- 'endpoint' string The OEmbed endpoint.
	 *		- 'format' string The expected response format.
	 *
	 *	@var array
	 */
	protected $_properties = [
		'endpoint' => '',
		'format' => Format::json
	];



	/**
	 *	Constructor.
	 *
	 *	@param Http $Http HTTP client.
	 *	@param Dom $Dom DOM parser.
	 */
	public function __construct(Http $Http, Dom $Dom) {
		parent::__construct();

		$this->_Http = $Http;
		$this->_Dom = $Dom;
	}



	/**
	 *	{@inheritDoc}
	 *
	 *	@note If no endpoint was specified in the configuration, the page at
	 *		the given URL will be parsed to find one.
	 *	@throws Essence\Exception If the parsed page doesn't provide any endpoint.
	 */
	protected function _embed($url, array $options) {
		$Config = $this->_config($url, $options);
		$response = $this->_Http->get($Config->endpoint());

		return new Media(
			$this->_parse($response, $Config->format())
		);
	}



	/**
	 *	Parses the given response depending on its format.
	 *
	 *	@param string $response Response.
	 *	@param string $format Format.
	 *	@return array Data.
	 */
	protected function _parse($response, $format) {
		switch ($format) {
			case Format::json:
				return Json::parse($response);

			case Format::xml:
				return Xml::parse($response);

			default:
				throw new Exception('Unsupported response format.');
		}
	}



	/**
	 *	Builds or extracts an oEmbed config.
	 *
	 *	@param string $url URL.
	 *	@param array $options Options.
	 *	@return Config Configuration.
	 */
	protected function _config($url, array $options) {
		$Config = $this->has('endpoint')
			? $this->_buildConfig($url)
			: $this->_extractConfig($this->_Http->get($url));

		if ($options) {
			$Config->completeEndpoint($options);
		}

		return $Config;
	}



	/**
	 *	Builds an oEmbed configuration from settings.
	 *
	 *	@param string $url URL to embed.
	 *	@return Config Configuration.
	 */
	protected function _buildConfig($url) {
		$endpoint = Template::compile(
			$this->get('endpoint'),
			compact('url'),
			'urlencode'
		);

		return new Config($endpoint, $this->get('format'));
	}



	/**
	 *	Extracts an oEmbed configuration from the given page.
	 *
	 *	@param string $html HTML page.
	 *	@return array Configuration.
	 */
	protected function _extractConfig($html) {
		$Document = $this->_Dom->document($html);
		$links = $Document->tags('link');

		foreach ($links as $Link) {
			if ($format = $this->_extractFormat($Link)) {
				return new Config($Link->get('href'), $format);
			}
		}

		throw new Exception('Unable to extract any OEmbed endpoint');
	}



	/**
	 *	Extracts an oEmbed response format from a link tag.
	 *
	 *	@param $Link Link tag.
	 *	@return Tag string|null Format.
	 */
	protected function _extractFormat(Tag $Link) {
		$isAlternate = $Link->matches('rel', '~alternate~i');
		$hasFormat = $Link->matches('type', '~(?<format>json|xml)~i', $matches);

		if ($isAlternate && $hasFormat) {
			return $matches['format'];
		}

		return null;
	}
}
