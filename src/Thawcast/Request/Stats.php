<?php
/**
 * Thawcast
 * @author Ross Masters <ross@rossmasters.com>
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
