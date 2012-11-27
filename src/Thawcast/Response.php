<?php
/**
 * Thawcast - Icecast analytics
 *
 * @author Ross Masters <ross@rossmasters.com>
 * @copyright Copyright (C) 2012 Ross Masters
 * @license MIT http://github.com/rmasters/thawcast/blob/master/LICENSE
 * @link http://github.com/rmasters/thawcast
 * @package Thawcast
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
