<?php
namespace Essence\Provider\OpenGraph;

use Essence\Media;
use Essence\Provider\OpenGraph;

/**
 * Embed provider for Vine URLs.
 * @package Essence.Provider.OpenGraph
 */

class Vine extends OpenGraph {

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
        if ($Media->get('type') == 'vine-app:video'
                && preg_match('#https?://vine.co/v/[a-zA-Z0-9]+#', $Media->get('url'), $matches)) {
            $Media->set('html:small', '<iframe class="vine-embed" src="' . $matches[0] . '/embed/postcard" width="320" height="320" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>');
            $Media->set('html:medium', '<iframe class="vine-embed" src="' . $matches[0] . '/embed/postcard" width="480" height="480" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>');
            $Media->set('html:large', '<iframe class="vine-embed" src="' . $matches[0] . '/embed/postcard" width="600" height="600" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>');
            $Media->set('html', $Media->get('html:small'));
        }
        return parent::completeMedia( $Media, $options );
    }

}
