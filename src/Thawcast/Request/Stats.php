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

namespace Thawcast\Request;

use Thawcast\Request;
use Thawcast\Response\Stats as Response;

/**
 * Retrieve general server information
 *
 * @package Requests
 */
class Stats extends Request
{
    /**
     * Send a request and build the response
     * @param array $args
     * @return Thawcast\Response\Stats
     */
    protected function buildResponse(array $args) {
        $response = $this->get('/admin/stats', true);
        return Response::fromXml($response->body);
    }
}
