<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use Essence\Media;



/**
 *	Updates Medias after extraction.
 */
abstract class Presenter {

	/**
	 *	@see present()
	 */
	public function __invoke(Media $Media) {
		return $this->present($Media);
	}



	/**
	 *	Updates the given Media.
	 *
	 *	@param Media $Media Media.
	 *	@return Media Updated Media.
	 */
	abstract public function present(Media $Media);

}
