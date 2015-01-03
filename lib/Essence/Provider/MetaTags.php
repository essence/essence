<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@author Laughingwithu <laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use Essence\Exception;
use Essence\Media;
use Essence\Provider;
use Essence\Dom\Document\Factory\Native as Dom;
use Essence\Http\Client as Http;
use Essence\Utility\Hash;



/**
 *	Extracts embed informations from meta tags.
 */
class MetaTags extends Provider {

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
	 *	A regex to filter meta tags.
	 *
	 *	@var string
	 */
	protected $_metaPattern = '~.+~';



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
	 *	Sets the filter pattern.
	 *
	 *	@param string $pattern Pattern.
	 */
	public function setMetaPattern($pattern) {
		$this->_metaPattern = $pattern;
	}



	/**
	 *	{@inheritDoc}
	 */
	protected function _embed($url, array $options) {
		$html = $this->_Http->get($url);
		$metas = $this->_extractMetas($html);

		if (!$metas) {
			throw new Exception(
				"Unable to extract meta tags from '$url'."
			);
		}

		return $this->_media($metas);
	}



	/**
	 *	Extracts meta tags from the given HTML source.
	 *
	 *	@param string $html HTML.
	 *	@return array Meta tags.
	 */
	protected function _extractMetas($html) {
		$Document = $this->_Dom->document($html);

		return $Document->tags('meta', function($Tag) {
			return $Tag->matches('property', $this->_metaPattern);
		});
	}



	/**
	 *	Builds a media from the given meta tags.
	 *
	 *	@param array $metas Meta tags.
	 *	@return Media Media.
	 */
	protected function _media(array $metas) {
		$metas = Hash::combine($metas, function($Meta) {
			yield $Meta->get('property') => $Meta->get('content');
		});

		return new Media($metas);
	}
}
