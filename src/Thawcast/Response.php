<?php
/**
 * Thawcast
 * @author Ross Masters <ross@rossmasters.com>
 */

namespace Thawcast;

/**
 * A server response
 *
 * @package Responses
 */
abstract class Response
{
    /**
     * Loader function for an XML document
     * @param string $xml
     * @return Thawcast\Response
     */
    abstract public static function fromXml($xml);
}
