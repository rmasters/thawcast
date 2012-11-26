<?php
/**
 * Thawcast
 * @author Ross Masters <ross@rossmasters.com>
 */

namespace Thawcast\Response;

use Thawcast\Response;
use SimpleXmlElement;

/**
 * Clients information found in /admin/listclients
 * 
 * @package Responses
 */
class Clients extends Response
{
    public $sources;

    private function __construct() {
        $this->sources = array();
    }

    public static function fromXml($xml) {
        $instance = new self;

        $icestats = new SimpleXmlElement($xml);

        foreach ($icestats->source as $s) {
            $mount = (string) $s['mount'];
            $instance->sources[$mount] = array(
                'listeners' => array(),
            );

            foreach ($s->listener as $l) {
                $instance->sources[$mount]['listeners'][] = array(
                    'ip' => $l->IP,
                    'user_agent' => $l->UserAgent,
                    'connection' => $l->Connected,
                    'id' => $l->ID,
                );
            }
        }

        return $instance;
    }
}
