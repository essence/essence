<?php
namespace Essence\Provider\OpenGraph;

use Essence\Media;
use Essence\Provider\OpenGraph;

/**
 * Embed provider for Bandcamp URLs.
 * @package Essence.Provider.OpenGraph
 */

class Bandcamp extends OpenGraph {

    /** @Override */
    protected $_properties = [
        'prepare' => 'static::prepareUrl',
        'complete' => 'static::completeMedia'
    ];

    /**
     * Builds an HTML code from the given media's properties to fill its
     * 'html' property.
     *
     * @param Essence\Media $Media A reference to the Media.
     * @param array $options Embed options.
     */
    public static function completeMedia(Media $Media, array $options = []) {
        if ($Media->get('og:video:type') == 'application/x-shockwave-flash'
                && $Media->has('og:video')
                && $Media->has('og:video:height')
                && $Media->has('og:video:width')
                && preg_match('/((album|track)=\d+)/', $Media->get('og:video'), $matches)) {
            $Media->set('html:small', '<iframe style="border: 0; width: 100%; height: 42px;" src="http://bandcamp.com/EmbeddedPlayer/' . $matches[1] . '/size=small/bgcol=ffffff/linkcol=0687f5/transparent=true/" seamless><a href="' . htmlspecialchars($Media->get('url')) . '">' . htmlspecialchars($Media->get('title')) . '</a></iframe>');
            $Media->set('html:medium', '<iframe style="border: 0; width: 100%; height: 120px;" src="http://bandcamp.com/EmbeddedPlayer/' . $matches[1] . '/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=false/artwork=small/transparent=true/" seamless><a href="' . htmlspecialchars($Media->get('url')) . '">' . htmlspecialchars($Media->get('title')) . '</a></iframe>');
            $largeHeight = $matches[2] == 'album' ? 470 : 442;
            $Media->set('html:large', '<iframe style="border: 0; width: 350px; height: ' . $largeHeight . 'px;" src="http://bandcamp.com/EmbeddedPlayer/' . $matches[1] . '/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=false/transparent=true/" seamless><a href="' . htmlspecialchars($Media->get('url')) . '">' . htmlspecialchars($Media->get('title')) . '</a></iframe>');
            $Media->set('html', $Media->get('html:small')); // Most responsive size.
        }
        return parent::completeMedia( $Media, $options );
    }

}
