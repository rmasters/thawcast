<?php
/**
 * Thawcast
 * @author Ross Masters <ross@rossmasters.com>
 */

namespace Thawcast\Response;

use Thawcast\Response;
use SimpleXmlElement;

/**
 * Available sources, found in /admin/listmounts
 *
 * @package Responses
 */
class Mounts extends Response
{
    protected $mounts;

    public static function fromXml($xml) {
        $instance = new self;

        $icestats = new SimpleXmlElement($xml);

        $sources = array();
        foreach ($icestats->source as $source) {
            $sources[] = array(
                'mount' => (string) $source['mount'],
                'listeners' => (int) $source->listeners,
                'connected' => (int) $source->Connected,
                'content-type' => (string) $source->{'content-type'},
                'fallback' => null, // @todo Find a fallback example
            );
        }
        $instance->mounts = $sources;

        return $instance;
    }
}
